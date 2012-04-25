<?php
/**
 * Description of Comm_print
 *
 * @author alessandro
 */

class Comm_print {

    public function __construct() {;}
    
    public function comm_print($ip, $out) {
        
        $fp = fsockopen($ip, 9100, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)";
        } else {
            //$out = chr(27)."M".chr(48).chr(29).chr(33)."0"."Prova ".$i.chr(10);
            fwrite($fp, $out);
        //  while (!feof($fp)) {
        //      echo fgets($fp, 128);
        //  }
            fclose($fp);
            return true;
        }
        
    }
    
}

?>

