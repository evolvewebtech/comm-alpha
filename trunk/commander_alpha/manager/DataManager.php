<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
class DataManager {

    static function _getConnection() {
        static $hDB;
        static $database = "finetor";

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
}
?>