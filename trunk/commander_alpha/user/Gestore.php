<?php

require_once dirname(__FILE__).'/User.php';
require_once dirname(__FILE__).'/Cassiere.php';

class Gestore extends User {

    public function __construct($userID) {

        $arData = DataManager::getGestoreData($userID);
        parent::__construct($arData);

        $this->propertyTable['id'] = 'id';
        $this->propertyTable['utente_registrato_id'] = 'utente_registrato_id';
    }

    public function __toString() {
        return 'id: '. $this->id . ' ur_id: ' . $this->utente_registrato_id . '<p>username: ' . $this->username . '</p>';
    }


    public function validate() {
        parent::validate();
    //add individual-specific validation
    }


/*
     * -------------------------------------------------------------------------
     * GESTIONE CASSIERI    --------------------------------------------------------
     * - addCassiere()
     * - editCassiere()
     * - getCassiere()
     * - delCassiere()
     *
     * - getAllCassiere()
     */

    /**
     *
     * @return <type> 
     */
    public function getAllCassiere(){
        return DataManager::getTuttiCassieri($this->id);
    }

    /**
     *
     * @param <type> $cassiere_id
     * @param <type> $utente_registrato_id
     * @param <type> $username
     * @param <type> $password
     * @param <type> $email
     * @param <type> $nome
     * @param <type> $cognome
     * @param <type> $livello_cassiere
     */
    public function addCassiere($cassiere_id, $utente_registrato_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
        return DataManager::inserisciCassiere($cassiere_id, $utente_registrato_id, $this->id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
    }

    /**
     *
     * @param <type> $cassiere_id
     * @param <type> $username
     * @param <type> $password
     * @param <type> $email
     * @param <type> $nome
     * @param <type> $cognome
     * @param <type> $livello_cassiere
     */
    public function editCassiere($cassiere_id, $username, $password, $email, $nome, $cognome, $livello_cassiere){
        return DataManager::aggiornaCassiere($cassiere_id, $this->id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
    }

    
    public function deleteCassiere(){

    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE SALA    --------------------------------------------------------
     * - addSala()
     * - editSala()
     * - getSala()
     * - delSala()
     *
     * - getAllSala()
     */

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $posizione
     * @return <bool>
     */
    public function addSala($id, $nome,$posizione){
        return DataManager::addSala($id, $nome, $posizione);
    }


    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $posizione
     * @return <type>
     */
    public function editSala($id, $nome, $posizione){
        return DataManager::editSala($id, $nome, $posizione);
    }

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public function getSala($id){
        $sala =  DataManager::getSala($id);
        return $sala;
    }

    /**
     *
     * @param <type> $id
     * @return <type> 
     */
    public function delSala($id){
        return DataManager::delSala($id);
    }

    /**
     * inserisco in un array tutte le sale presenti nel db
     *
     * @return <type>
     */
    public function getAllSala(){
        $allSala = DataManager::getAllSala();
        return $allSala;
    }
}
?>
