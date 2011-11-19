<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
class DataManager {

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
    static function aggiornaCassiere($cassiere_id, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
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


}
?>