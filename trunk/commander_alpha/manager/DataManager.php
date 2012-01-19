<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
require_once dirname(__FILE__).'/../user/User.php';
require_once dirname(__FILE__).'/../user/Gestore.php';
require_once dirname(__FILE__).'/../user/Cassiere.php';

class DataManager {

    private static function _getConnection() {
      static $hDB;
      static $database = "commander";

      if(isset($hDB)) {
         return $hDB;
      }

      $hDB = mysql_connect("localhost", "root", "")
         or die("Failure connecting to the database!");

      if (!$hDB){
          die('Could not connect: ' . mysql_error());
      }
      mysql_select_db($database);

      return $hDB;
    }


    /**
     *
     * inserisce un cassiere al'interno del db.
     *
     * utilizzo: DataManager::inserisciCassiere('NULL','NULL','NULL',...)
     *
     *
     * @param <int> $cassiere_id
     * @param <int> $utente_registrato_id
     * @param <int> $utente_registrato_id2
     * @param <int> $gestore_id
     * @param <string> $username
     * @param <string> $password
     * @param <string> $email
     * @param <string> $nome
     * @param <string> $cognome
     * @param <int> $livello_cassiere
     * @return <bool>
     */
    public static function inserisciCassiere($cassiere_id, $utente_registrato_id, $utente_registrato_id2, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * inserisco un profilo utente (tabella_ cmd_utente registrato)
         */
        $ret = $db->insert('cmd_utente_registrato', array($utente_registrato_id, $username, $password, $email, $nome, $cognome));

        /*
         * prelevo l'id appena assegnato (AI) dalla tabella cmd_utente_registrato
         */
        $db->select('cmd_utente_registrato', 'id', "username='$username'");
        $utente_registrato_id2 = $db->getResult();
        $utente_registrato_id2 = $utente_registrato_id2['id'];

        /*
         * inserisco il nuovo cassiere e lo associo al gestore e al suo profilo
         */
        $ret2 = $db->insert('cmd_cassiere', array($cassiere_id, $livello_cassiere, $utente_registrato_id2, $gestore_id));

        if ($ret && $ret2)
            return true;
        else {
            if ($ret)
                $db->delete('cmd_utente_registrato', "username='$username'");
            return false;
        }

    }//end inserisciCassiere

    /**
     *
     * aggiorna un cassiere all'interno del db.
     *
     * utilizzo: DataManager::aggiornaCassiere($cassiere_id, $gestore_id, $username, ...);
     *
     * @param <int> $cassiere_id
     * @param <int> $gestore_id
     * @param <string> $username
     * @param <string> $password
     * @param <string> $email
     * @param <string> $nome
     * @param <string> $cognome
     * @param <int> $livello_cassiere
     * @return <bool>
     */
    public static function aggiornaCassiere($cassiere_id, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * aggiorno il livello del cassiere
         */
        $ret = $db->update('cmd_cassiere',
                array('livello_cassiere' => $livello_cassiere),
                            array('id',$cassiere_id));

        /*
         * prelevo l'id dalla tabella cmd_cassiere appena aggiornata
         */
        $db->select('cmd_cassiere', 'utente_registrato_id', "id='$cassiere_id'");
        $utente_registrato_id = $db->getResult();
        /*
         * trasformo per sicurezza utente_registrato in un numero intero
         */
        $utente_registrato_id = intval($utente_registrato_id['utente_registrato_id']);

        if ($ret) {
            /*
             * aggiorno il profilo del cassiere
             */
            $ret2 = $db->update('cmd_utente_registrato',
                                    array(
                                        'username' => $username,
                                        'password' => $password,
                                        'email'    => $email,
                                        'nome'     => $nome,
                                        'cognome'  => $cognome),
                                    array(
                                        'id',$utente_registrato_id)
                                );
            if ($ret2==false){
                   $ret=false;
                }
            }
        return $ret;
    }//end aggiornaCassiere


    /**
     * ritorno l'oggetto Gestore prendendo in ingresso l'id dell'utente
     *
     * @param <int> $cassiereID
     * @return Gestore
     */
    public static function getGestore($cassiereID){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $res = $db->select('cmd_cassiere', 'gestore_id', "id='$cassiereID'");
        $gestore_id = $db->getResult();
//        var_dump($gestore_id);
        if(!$res) {
            die("Failed getting gestore info for cassiere number $cassiereID");
        }
        $row = $gestore_id;//mysql_fetch_assoc($gestore_id);
        if($row) {
          return new Gestore(intval($row['gestore_id']));
        } else {
          return null;
        }
    }


    /**
     *
     * prendo tutti i cassieri associati al gestore avente, li restituisco
     * come oggetti
     *
     * @param <id> $gestoreID
     * @return Cassiere
     */
    public static function getTuttiCassieri($gestoreID){
        $sql = "SELECT * FROM cmd_cassiere WHERE gestore_id='$gestoreID'";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting cassieri data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = new Cassiere(intval($rec['id']));
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }


    /**
     * ritorno tutti i dati di un utente registrato:
     * - id
     * - nome
     * - cognome
     * - username
     * - md5(password)
     *
     * @param <int> $userID
     * @return <array string>
     */
    public static function getUserData($userID){
        $sql = "SELECT * FROM cmd_utente_registrato WHERE id=$userID";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Failed getting entity User2");
            }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     * - id
     * - utente_registrato_id
     * -
     *
     * @param <int> $userID
     * @return <array string>
     */
    public static function getGestoreData($userID){
        $sql = "SELECT * FROM cmd_gestore WHERE utente_registrato_id=$userID";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Failed getting entity Gestore");
            }
            return mysql_fetch_assoc($res);
        }       
    }

    /**
     * - id
     * - utente_registrato_id
     * - gestore_id
     * - livello_cassiere
     *
     * @param <int> $userID
     * @return <array cassiere>
     */
    public static function getCassiereData($userID){
        $sql = "SELECT * FROM cmd_cassiere WHERE utente_registrato_id=$userID";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Cassiere");
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <Utente registrato object
     */
    public static function getAllEntitiesAsObjects() {

        $sql = "SELECT id,type FROM cmd_utente_registrato";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore");
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                if($row['type'] == 'G') {
                    $id = intval($row['id']);
                    $objs[] = new Gestore($id);
                } elseif ($row['type'] == 'C') {
                    $id = intval($row['id']);
                    $objs[] = new Cassiere($id);
                } else {
                    die("Unknown entity type {$row['type']} encountered!");
                }
              }
              return $objs;
            } else {
              return array();
            }
    }


    /**
     *
     * ritorno l'oggetto Cassiere o Gestore in base all'id dell'utente_registrato
     *
     * @param <int> $id
     * @return Utente_registrato object
     */
    public static function getUserAsObject($id){
        $sql = "SELECT * FROM cmd_utente_registrato WHERE id=$id";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore");
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                if($row['type'] == 'G') {
                    $id = intval($row['id']);
                    $objs[] = new Gestore($id);
                } elseif ($row['type'] == 'C') {
                    $id = intval($row['id']);
                    $objs[] = new Cassiere($id);
                } else {
                    die("Unknown entity type {$row['type']} encountered!");
                }
              }
              return $objs;
            } else {
              return array();
            }

    }

}
?>