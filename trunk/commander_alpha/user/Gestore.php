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
     * GESTIONE CASSIERI    ----------------------------------------------------
     *
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

    public function getCassiere($cassiere_id){
        $cassiere =  DataManager::getCassiereData($cassiere_id);
        return $cassiere;
    }

    public function logOutCassiere($cassiere_id) {
        $cassiere =  DataManager::getCassiereDataByCassiereID($cassiere_id);
        $ret = DataManager::logoutCassiere($cassiere);
        return $ret;

    }


    /**
     *
     * @param <type> $cassiere_id
     * @param <type> $utente_registrato_id
     * @param <type> $username
     * @param <type> $password
     * @param <type> $nome
     * @param <type> $cognome
     * @param <type> $tipo
     * @return <type>
     */
    public function addCassiere($cassiere_id, $utente_registrato_id, $username, $password, $nome, $cognome, $tipo){
        return DataManager::inserisciCassiere($cassiere_id, $utente_registrato_id, $this->id, $username, $password, $nome, $cognome, $tipo);
    }

    /**
     *
     * @param <type> $cassiere_id
     * @param <type> $username
     * @param <type> $password
     * @param <type> $nome
     * @param <type> $cognome
     * @param <type> $tipo
     * @return <type>
     */
    public function editCassiere($cassiere_id, $username, $password, $nome, $cognome, $tipo){
        return DataManager::aggiornaCassiere($cassiere_id, $this->id, $username, $password, $nome, $cognome, $tipo);
    }

    
    public function delCassiere($utente_registrato_id){
        return DataManager::delCassiere($utente_registrato_id);
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE SALA    --------------------------------------------------------
     * 
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
     * @return <bool>
     */
    public function editSala($id, $nome, $posizione){
        return DataManager::editSala($id, $nome, $posizione);
    }

    /**
     *
     * @param <type> $id
     * @return <array>
     */
    public function getSala($id){
        $sala =  DataManager::getSala($id);
        return $sala;
    }

    /**
     *
     * @param <type> $id
     * @return <bool>
     */
    public function delSala($id){
        return DataManager::delSala($id);
    }

    /**
     * inserisco in un array tutte le sale presenti nel db
     *
     * @return <array>
     */
    public function getAllSala(){
        $allSala = DataManager::getAllSala();
        return $allSala;
    }

    /*
     * -------------------------------------------------------------------------
     * GESTIONE TAVOLO   -------------------------------------------------------
     * 
     * - addTavolo()
     * - editTavolo()
     * - getTavolo()
     * - delTavolo()
     *
     * - getAllTavolo()
     */

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $numero
     * @param <type> $nmax_coperti
     * @param <type> $posizione
     * @return <bool>
     */
    public function addTavolo($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id){
        return DataManager::addTavolo($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $numero
     * @param <type> $nmax_coperti
     * @param <type> $posizione
     * @return <bool>
     */
    public function editTavolo($id, $nome, $numero, $nmax_coperti, $posizione,$sala_id){
        return DataManager::addTavolo($id, $nome, $numero, $nmax_coperti, $posizione,$sala_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delTavolo($id){
        return DataManager::delTavolo($id);
    }

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public function getTavolo($id){
        return DataManager::getTavolo($id);
    }

    /**
     *
     * @return <bool>
     */
    public function getAllTavolo(){
        return DataManager::getAllTavolo();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE ALIMENTO   -----------------------------------------------------
     * 
     * - addAlimento()
     * - editAlimento()
     * - getAlimento()
     * - delAlimento()
     *
     * - getAllAlimento()
     */

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $prezzo
     * @param <type> $iva
     * @param <type> $colore_bottone
     * @param <type> $descrizione
     * @param <type> $apeso
     * @param <type> $path_image
     * @param <type> $codice_prodotto
     * @param <type> $quantita
     * @param <type> $gestore_id
     * @param <type> $categoria_id
     * @param <type> $alimento_id
     * @return <bool>
     */
    public function addAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                $descrizione, $apeso, $path_image, $codice_prodotto,
                                $quantita, $gestore_id, $categoria_id, $alimento_id){
        return DataManager::inserisciAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                              $descrizione, $apeso, $path_image, $codice_prodotto,
                                              $quantita, $gestore_id, $categoria_id, $alimento_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $prezzo
     * @param <type> $iva
     * @param <type> $colore_bottone
     * @param <type> $descrizione
     * @param <type> $apeso
     * @param <type> $path_image
     * @param <type> $codice_prodotto
     * @param <type> $quantita
     * @param <type> $gestore_id
     * @param <type> $categoria_id
     * @param <type> $alimento_id
     * @return <bool>
     */
    public function editAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                 $descrizione, $apeso, $path_image, $codice_prodotto,
                                 $quantita, $gestore_id, $categoria_id, $alimento_id){
        return DataManager::aggiornaAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                             $descrizione, $apeso, $path_image, $codice_prodotto,
                                             $quantita, $gestore_id, $categoria_id, $alimento_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getAlimento($id){
        return DataManager::getAlimento($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delAlimento($id){
        return DataManager::cancellaAlimento($id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllAlimento(){
        return DataManager::getAllAlimento();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE ALIMENTO_ESAURITO   --------------------------------------------
     * 
     * - addAlimentoEsaurito()
     * - editAlimentoEsaurito()
     * - getAlimentoEsaurito()
     * - delAlimentoEsaurito()
     *
     * - getAllAlimentoEsaurito()
     */

    /**
     *
     * @param <type> $id
     * @param <type> $alimento_id
     * @param <type> $data_esaurito
     * @return <type>
     */
    public function addAlimentoEsaurito($id, $alimento_id, $data_esaurito){
        return DataManager::inserisciAlimentoEsaurito($id, $alimento_id, $data_esaurito);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $alimento_id
     * @param <type> $data_esaurito
     * @return <type> 
     */
    public function editAlimentoEsaurito($id, $alimento_id, $data_esaurito){
        return DataManager::aggiornaAlimentoEsaurito($id, $alimento_id, $data_esaurito);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getAlimentoEsaurito($id){
        return DataManager::getAlimentoEsaurito($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delAlimentoEsaurito($alimento_id){
        return DataManager::cancellaAlimentoEsaurito($alimento_id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllAlimentoEsaurito(){
        return DataManager::getAllAlimentoEsaurito();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE VARIANTE  ------------------------------------------------------
     *
     * - addVariante()
     * - editVariante()
     * - getVariante()
     * - delVariante()
     *
     * - getAllVariante()
     */

  /**
    *
    * @param <type> $id
    * @param <type> $descrizione
    * @param <type> $prezzo
    * @param <type> $iva
    * @param <type> $gestore_id
    * @return <type>
    */
    public function addVariante($id, $descrizione, $prezzo, $iva, $gestore_id){
        return DataManager::inserisciVariante($id, $descrizione, $prezzo, $iva, $gestore_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $descrizione
     * @param <type> $prezzo
     * @param <type> $iva
     * @param <type> $gestore_id
     * @return <bool>
     */
    public function editVariante($id, $descrizione, $prezzo, $iva, $gestore_id){
        return DataManager::aggiornaVariante($id, $descrizione, $prezzo, $iva, $gestore_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getVariante($id){
        return DataManager::getVariante($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delVariante($id){
        return DataManager::cancellaVariante($id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllAlimentoVariante(){
        return DataManager::getAllAlimentoVariante();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE CATEGORIA  ------------------------------------------------------
     *
     * - addCategoria()
     * - editCategoria()
     * - getCategoria()
     * - delCategoria()
     *
     * - getAllCategoria()
     */

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $colore_bottone_predef
     * @param <int> $gestore_id
     * @return <bool>
     */
    public function addCategoria($id, $nome, $colore_bottone_predef, $gestore_id){
        return DataManager::inserisciCategoria($id, $nome, $colore_bottone_predef, $gestore_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $colore_bottone_predef
     * @param <type> $gestore_id
     * @return <bool>
     */
    public function editCategoria($id, $nome, $colore_bottone_predef, $gestore_id){
        return DataManager::aggiornaCategoria($id, $nome, $colore_bottone_predef, $gestore_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getCategoria($id){
        return DataManager::getCategoria($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delCategoria($id){
        return DataManager::cancellaCategoria($id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllCategoria(){
        return DataManager::getAllCategoria();
    }

    /*
     * -------------------------------------------------------------------------
     * GESTIONE MENUFISSO  ------------------------------------------------------
     *
     * - addMenufisso()
     * - editMenufisso()
     * - getMenufisso()
     * - delMenufisso()
     *
     * - getAllMenufisso()
     */


    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $prezzo
     * @param <string> $iva
     * @param <string> $descrizione
     * @param <int> $gestore_id
     * @return <bool>
     */
    public function addMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){
        return DataManager::inserisciMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id);
    }

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $prezzo
     * @param <string> $iva
     * @param <string> $descrizione
     * @param <int> $gestore_id
     * @return <bool>
     */
    public function editMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){
        return DataManager::aggiornaMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getMenufisso($id){
        return DataManager::getMenufisso($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delMenufisso($id){
        return DataManager::cancellaMenufisso($id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllMenufisso(){
        return DataManager::getAllMenufisso();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE STAMPANTE  ------------------------------------------------------
     *
     * - addStampante()
     * - editStampante()
     * - getStampante()
     * - delStampante()
     *
     * - getAllStampante()
     */

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $colore_bottone_predef
     * @param <int> $gestore_id
     * @return <bool>
     */
    public function addStampante($id, $nome, $posizione, $indirizzo, $gestore_id){
        return DataManager::inserisciStampante($id, $nome, $posizione, $indirizzo, $gestore_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $colore_bottone_predef
     * @param <type> $gestore_id
     * @return <bool>
     */
    public function editStampante($id, $nome, $posizione, $indirizzo, $gestore_id){
        return DataManager::aggiornaStampante($id, $nome, $posizione, $indirizzo, $gestore_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getStampante($id){
        return DataManager::getStampante($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delStampante($id){
        return DataManager::cancellaStampante($id);
    }

    /**
     *
     * @return <array>
     */
    public function getAllStampante(){
        return DataManager::getAllStampante();
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE RELAZIONE ALIMENTO STAMPANTE  ------------------------------------------------------
     *
     * - addAlimentoStampante()
     * - editAlimentoStampante()
     * - getAlimentoStampante()
     * - delAlimentoStampante()
     *
     * - getAllAlimentoStampante()
     */

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $colore_bottone_predef
     * @param <int> $gestore_id
     * @return <bool>
     */
    public function addAlimentoStampante($alimento_id, $stampante_id){
        return DataManager::inserisciAlimentoStampante($alimento_id, $stampante_id);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $colore_bottone_predef
     * @param <type> $gestore_id
     * @return <bool>
     */
    public function editAlimentoStampante($alimento_id, $stampante_id, $new_alimento_id, $new_stampante_id){
        return DataManager::aggiornaAlimentoStampante($alimento_id, $stampante_id, $new_alimento_id, $new_stampante_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getAlimentoStampante($id){
        return DataManager::getAlimentoStampante($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delAlimentoStampante($alimento_id, $stampante_id){
        return DataManager::cancellaAlimentoStampante($alimento_id, $stampante_id);
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE RELAZIONE ALIMENTO VARIANTE ------------------------------------
     *
     * - addAlimentoVariante()
     * - editAlimentoVariante()
     * - getAlimentoVariante()
     * - delAlimentoVariante()
     *
     * - getAllAlimentoVariante()
     */

    /**
     *
     * @param <type> $alimento_id
     * @param <type> $stampante_id
     * @return <type>
     */
    public function addAlimentoVariante($alimento_id, $variante_id){
        return DataManager::inserisciAlimentoVariante($alimento_id, $variante_id);
    }

    /**
     *
     * @param <type> $alimento_id
     * @param <type> $variante_id
     * @param <type> $new_alimento_id
     * @param <type> $new_variante_id
     * @return <type>
     */
    public function editAlimentoVariante($alimento_id, $variante_id, $new_alimento_id, $new_variante_id){
        return DataManager::aggiornaAlimentoVariante($alimento_id, $variante_id, $new_alimento_id, $new_variante_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getAlimentoVariante($id){
        return DataManager::getAlimentoVariante($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delAlimentoVariante($alimento_id, $variante_id){
        return DataManager::cancellaAlimentoVariante($alimento_id, $variante_id);
    }


    /*
     * -------------------------------------------------------------------------
     * GESTIONE RELAZIONE ALIMENTO_MENU ALIMENTO -------------------------------
     *
     * - addAlimentoMenuAlimento()
     * - getAlimentoMenuAlimento()
     * - delAlimentoMenuAlimento()
     *
     * - getAllAlimentoMenuAlimento()
     */

    /**
     *
     * @param <type> $alimento_id
     * @param <type> $stampante_id
     * @return <type>
     */
    public function addAlimentoMenuAlimento($alimento_menu_id, $alimento_id){
        return DataManager::inserisciAlimentoMenuAlimento($alimento_menu_id, $alimento_id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getAlimentoMenuAlimento($id){
        return DataManager::getAlimentoMenuAlimento($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delAlimentoMenuAlimento($alimento_menu_id, $alimento_id){
        return DataManager::cancellaAlimentoMenuAlimento($alimento_menu_id, $alimento_id);
    }



    /*
     * -------------------------------------------------------------------------
     * GESTIONE VARIANTE  ------------------------------------------------------
     *
     * - addBuonoPrepagato()
     * - editBuonoPrepagato()
     * - getBuonoPrepagato()
     * - delBuonoPrepagato()
     *
     * - getAllBuonoPrepagato()
     */

    /**
     *
     * @param <type> $id
     * @param <type> $seriale
     * @param <type> $credito
     * @param <type> $nominativo
     * @param <type> $gestore_id
     * @return <type>
     */
    public function addBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo){
        return DataManager::inserisciBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo);
    }

    /**
     *
     * @param <type> $id
     * @param <type> $seriale
     * @param <type> $credito
     * @param <type> $nominativo
     * @param <type> $gestore_id
     * @param <int>  $record_attivo
     * @return <type> 
     */
    public function editBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo){
        return DataManager::aggiornaBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function getBuonoPrepagato($id){
        return DataManager::getBuonoPrepagato($id);
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public function delBuonoPrepagato($id){
        return DataManager::cancellaBuonoPrepagato($id);
    }


}
?>
