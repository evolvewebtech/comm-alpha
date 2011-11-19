<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
class DataManager {

    /**
     * inserisce un cassiere al'interno del db.
     * utilizzo: DataManager::inserisciCassiere('NULL','NULL','NULL',...)
     *
     *
     * @param <type> $cassiere_id
     * @param <type> $utente_registrato_id
     * @param <type> $utente_registrato_id2
     * @param <type> $gestore_id
     * @param <type> $username
     * @param <type> $password
     * @param <type> $email
     * @param <type> $nome
     * @param <type> $cognome
     * @param <type> $livello_cassiere
     * @return <type>
     */
    static function inseririCassiere($cassiere_id, $utente_registrato_id, $utente_registrato_id2, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
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
        
        
        /*
         * prova commit!!!!!!!!!!!!!!
         */
        
        
    }
}
?>