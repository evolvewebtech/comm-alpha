<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * Description of HTTPSession
 * It will be an entirely self-contained class
 * that hides all of PHP’s session_ functions from your application’s main body.
 *
 * It also provides session variable handling, which bypasses PHP’s own.
 * Rather than store multiple variables in a single serialized hash,
 * your methodology will use separate table rows for each variable.
 * This could speed up access immensely.
 * Note, however, that the previous session-handling instruction method
 * was not designed to cope with class methods, so you have to be rather cunning in your implementation.
 *
 * @author francesco
 */
class HTTPSession {

    private $php_session_id;
    private $native_session_id;
    private $dbhandle;
    private $logged_in;
    private $user_id;
    private $session_timeout = 3600;//60;//      # 60 minute inactivity timeout
    private $session_lifespan = 7200;//180;//    # 2 hour session duration

    /*
     * Edit the following variables
     */
    private $db_host = 'localhost';     // Database Host
    private $db_user = 'root';          // Username
    private $db_pass = '';              // Password
    private $db_name = 'commander';     // Database

    /*
     *
     *  Sets up the database connection. This would normally be handled by another class
     *  in a production environment.
     *
     *  Tells PHP how to handle session events
     *  in the custom class (discussed in further detail shortly)
     *
     *  Checks whether an existing session identifier is being offered
     *  by the client before PHP has a chance to get its hands on it
     *  (which would be the case if the user is in the middle of a session
     *  instead of starting a new one).
     *  It also performs various checks on age, inactivity, and the consistency
     *  of the reported HTTP user agent.
     *  If it fails, remove it altogether (and any garbage found) so that
     *  PHP can issue a new session from scratch.
     *
     *  Sets up the session lifespan parameter (which PHP will obey when issuing the cookie itself)
     *
     *  Tells PHP to go ahead and start the session in the normal way
     *
     */
    public function __construct() {

      # Connect to database
      $this->dbhandle = mysql_connect($this->db_host,$this->db_user,$this->db_pass);
      if (!$this->dbhandle) {
        die('Could not connect: ' . mysql_error());
      }
      $seldb = mysql_select_db($this->db_name, $this->dbhandle);
      if (!$seldb){
        die('Could not connect: ' . mysql_error());
      }

      /*
       * The function instructs PHP as to which custom functions to call
       * when certain session behavior takes place (such as when a session is started,
       * finished, read to, written from, or destroyed)
       *
       * Set up the handler
       *
       */
      session_set_save_handler(
          array(&$this, '_session_open_method'),
          array(&$this, '_session_close_method'),
          array(&$this, '_session_read_method'),
          array(&$this, '_session_write_method'),
          array(&$this, '_session_destroy_method'),
          array(&$this, '_session_gc_method')
      );

      # Check the cookie passed - if one is - if it looks wrong we'll
      # scrub it right away
      $strUserAgent = $_SERVER["HTTP_USER_AGENT"];

      if ($_COOKIE["PHPSESSID"]) {

        # Security and age check
        $this->php_session_id = $_COOKIE["PHPSESSID"];

        /*
        $stmt_old = "SELECT id FROM http_session WHERE ascii_session_id = '" .
                        $this->php_session_id . "' AND ((now() - created) < ' " .
                        $this->session_lifespan . " seconds') AND user_agent='" . 
                        $strUserAgent . "' AND ((now() - last_impression) <= '".
                        $this->session_timeout ." seconds' OR last_impression IS NULL)";
        */
        $stmt = "SELECT id FROM http_session WHERE ascii_session_id = '" .
                        $this->php_session_id . "' AND ((now() - created) < ' " .
                        $this->session_lifespan . " seconds') AND ((now() - last_impression) <= '".
                        $this->session_timeout ." seconds' OR last_impression IS NULL) AND user_agent='" .
                        $strUserAgent . "'";
        
        $result = mysql_query($stmt);

        if (!$result) {
            die('1 - Invalid query: ' . mysql_error());
        }

        if (mysql_num_rows($result)==0) {

            # Set failed flag
            $failed = 1;

            # Delete from database - we do garbage cleanup at the same time
            $maxlifetime = $this->session_lifespan;
            $del_query = "DELETE FROM http_session WHERE (ascii_session_id = '".
                              $this->php_session_id . "') OR (NOW() - created > '$maxlifetime seconds')";
            $result = mysql_query($del_query);
            if (!$result) {
                die('2 - Invalid query: ' . mysql_error());
            }

            # Clean up stray session variables
            $result = mysql_query("DELETE FROM session_variable WHERE session_id NOT IN (SELECT id FROM http_session)");
            if (!$result) {
                die('3 - Invalid query: ' . mysql_error());
            }

            # Get rid of this one... this will force PHP to give us another
            unset($_COOKIE["PHPSESSID"]);
            
        };
      };

      # Set the life time for the cookie
      session_set_cookie_params($this->session_lifespan);

      # Call the session_start method to get things started
      session_start();
    }

    /*
     *
     * This method touches the session to indicate that a new page impression has taken place.
     * Generally, this method would be called on any page that uses
     * the session class directly after it has been instantiated.
     *
     */
    public function Impress() {
        if ($this->native_session_id) {
            $result = mysql_query("UPDATE http_session SET last_impression = NOW() WHERE id = " . $this->native_session_id);
            //var_dump($result);
            if (!$result) {
                die('4 - Invalid query: ' . mysql_error());
            }
        }

    }

    /**
     *
     * @return <type> 
     */
    public function IsLoggedIn() {
/*
        $stmt = "SELECT logged_in FROM http_session WHERE ascii_session_id = '" .
                        $this->php_session_id . "' AND ((now() - created) < ' " .
                        $this->session_lifespan . " seconds') AND ((now() - last_impression) <= '".
                        $this->session_timeout ." seconds' OR last_impression IS NULL) AND user_agent='" .
                        $strUserAgent . "'";
*/
        $stmt = 'SELECT logged_in'.
                ' FROM http_session'.
                ' WHERE ascii_session_id = "'.$this->php_session_id.'"'.
                ' AND ((now() - created) < \''.$this->session_lifespan.' seconds\')'.
                ' AND ((now() - last_impression) <= \''.$this->session_timeout.' seconds\')'.
                ' OR last_impression IS NULL'.
                ' AND user_agent=\''.$_SERVER["HTTP_USER_AGENT"].'\'';
                //' AND user_agent=\'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.53 Safari/536.5\'';
        $result = mysql_query($stmt);
        if(!$result){
            return false;
        }
        $row = mysql_fetch_Row($result);
         //var_dump($row);
        $this->logged_in = $row;
        
        return($this->logged_in);
    }

    /**
     *
     * @return <type>
     */
    public function GetUserID() {
        if ($this->logged_in) {
            return($this->user_id);
        } else {
            return(false);
        };
    }

    /**
     *
     * @return <type>
     */
    public function GetUserObject() {

        if ($this->logged_in) {
            require_once  dirname(__FILE__).'/DataManager.php';
            $objUser = DataManager::getUserAsObject($this->user_id);
            if ($objUser) {
                return($objUser);

            } else {
                return(false);
            };
        };
    }

    /**
     *
     * @return <type>
     */
    public function GetSessionIdentifier() {
        return($this->php_session_id);
    }

    /**
     *
     * @param <type> $strUsername
     * @param <type> $strPlainPassword
     * @return <type>
     */
    public function Login($strUsername, $strPlainPassword) {

        $strMD5Password = md5($strPlainPassword);
        $stmt = "select id FROM cmd_utente_registrato WHERE username='$strUsername' AND md5_pw='$strMD5Password'";
        $result = mysql_query($stmt);

        if (mysql_num_rows($result)>0) {
            $row = mysql_fetch_array($result);
            $this->user_id = $row["id"];
            $this->logged_in = true;
            $result = mysql_query("UPDATE http_session SET logged_in = true, user_id = " . $this->user_id . " WHERE id = " . $this->native_session_id);
            return(true);
      } else {

          return(false);
      };
    }

    /**
     *
     * @return <type>
     */
    public function LogOut() {
        
        if ($this->logged_in == true) {
            $result = mysql_query("UPDATE http_session SET logged_in = false, user_id = 0 WHERE id = " . $this->native_session_id);
            $this->logged_in = false;
            $this->user_id = 0;
            return(true);
        } else {
            return(false);
        }
    }
    

    public function __get($nm) {

        $result = mysql_query("SELECT variable_value FROM session_variable WHERE session_id = " .
                                    $this->native_session_id . " AND variable_name = '" . $nm . "'");
        if (mysql_num_rows($result)>0) {
            $row = mysql_fetch_array($result);
            return(unserialize($row["variable_value"]));
      } else {
            return(false);
      };
    }

    public function __set($nm, $val) {

        $strSer = serialize($val);
        $stmt = "INSERT INTO session_variable(session_id, variable_name, variable_value) VALUES(" .
                                    $this->native_session_id . ", '$nm', '$strSer')";
        $result = mysql_query($stmt);
    }

    private function _session_open_method($save_path, $session_name) {
        # Do nothing
        return(true);
    }

    public function _session_close_method() {

        mysql_close($this->dbhandle);
        return(true);
    }

    /*
     * The read()function is used whenever an attempt to retrieve
     * a variable from the $_SESSION hash is made.
     * It takes the session identifier as its sole operand, and expects
     * a serialized representation of $_ SESSION in its entirety to be returned.
     *
     */
    public function _session_read_method($id) {
        
        # We use this to determine whether or not our session actually exists.
        $strUserAgent = $_SERVER["HTTP_USER_AGENT"];
        $this->php_session_id = $id;

        # Set failed flag to 1 for now
        $failed = 1;

        # See if this exists in the database or not.
        $sel = 'SELECT id, logged_in, user_id FROM http_session WHERE ascii_session_id="' . $id . '"';
        $result = mysql_query($sel);
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }
        if (mysql_num_rows($result)>0) {
            $row = mysql_fetch_array($result);
            $this->native_session_id = $row["id"];
            if ($row["logged_in"]==1) {
                $this->logged_in = true;
                $this->user_id = $row["user_id"];
            } else {
                $this->logged_in = false;
            }
        } else {
            $this->logged_in = false;
            # We need to create an entry in the database
            $result = mysql_query("INSERT INTO http_session(ascii_session_id, logged_in,user_id, created, user_agent) VALUES ('$id','f',0,now(),'$strUserAgent')");

            # Now get the true ID
            $result = mysql_query("SELECT id FROM http_session WHERE ascii_session_id='$id'");
            $row = mysql_fetch_array($result);
            $this->native_session_id = $row["id"];
        }

        # Just return empty string
        return("");
    }

    /*
     *
     * The write()function is used whenever an attempt to change
     * or add to $_SESSION is made. It takes the session identifier,
     * followed by the preserialized representation of $_SESSION, as its two parameters.
     * It expects true to be returned if the data is successfully committed.
     * This method is called even if no session variables are registered,
     * and it is the first time the generated session ID is revealed to you.
     *
     */
    public function _session_write_method($id, $sess_data) {
        return(true);
    }

    private function _session_destroy_method($id) {
        $result = mysql_query("DELETE FROM http_session WHERE ascii_session_id = '$id'");
        return($result);
    }

    /*
     *
     * The gc()(garbage cleanup) function should be able to accept the
     * “maximum lifetime of session cookies” parameter as its only operand
     * and get rid of any sessions older than that lifetime.
     * It should return true when it’s done. This function appears
     * to be called just before open()so that PHP rids itself
     * of any expired sessions before they may be used.
     *
     */
    private function _session_gc_method($maxlifetime) {
        return(true);
    }
}
?>