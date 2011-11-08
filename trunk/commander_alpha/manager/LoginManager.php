<?php
  class LoginManager {

      public static function getUserIdbyUsername($username){


       }

       public function getUserLevel($username, $password, $db){
           $query = 'SELECT admin_level FROM site_user WHERE ' .
             'username = "' . mysql_real_escape_string($username, $db) . '" AND ' .
             'password = PASSWORD("' . mysql_real_escape_string($password, $db) . '")';
            $result = mysql_query($query, $db) or die(mysql_error($db));

            return $result;
       }
  }
?>
