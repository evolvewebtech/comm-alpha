<?php
/**
 * Classe per l'invio di una stampa
 *
 *@author Sarzina - Falanga
 *
 */
class Comm_print {

    /**
     *
     * @param <type> $ip
     * @param <type> $out
     * @return <type> 
     */
    public static function comm_print($ip_address, $out) {

        $fp = fsockopen($ip_address, 9100, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)";
        } else {
            fwrite($fp, $out);
            fclose($fp);
            return true;
        }

    }


}

?>

