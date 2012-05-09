<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/User.php';
require_once dirname(__FILE__).'/Gestore.php';

class Cassiere extends User {

    public function __construct($userID) {

        $arData = DataManager::getCassiereData($userID);
        
//        echo "<pre>";
//        print_r($arData);
//        echo "</pre>";
        
        parent::__construct($arData);

        $this->propertyTable['id'] = 'id';
        //$this->propertyTable['livello_cassiere'] = 'livello_cassiere';
        $this->propertyTable['utente_registrato_id'] = 'utente_registrato_id';
        $this->propertyTable['gestore_id'] = 'gestore_id';
    }

    public function __toString() {
        return 'id: ' . $this->id . ' ur_id: ' . $this->utente_registrato_id . ' g_id: ' . $this->gestore_id . '<p>' . $this->username .'</p>';
    }

    /**
     *
     * restituisco l'oggetto Gestore del gestore che ha creato
     * il cassiere
     *
     * @return <User object>
     */
    public function getGestore() {
        return DataManager::getGestore($this->id);
    }

    /**
     * ritorno solo l'id del gestore
     *
     * @return <int>
     */
    public function getGestoreID(){
        return intval($this->gestore_id);
    }


    /**
     *
     * prendo il livello del cassiere
     *
     * @return <array>
     */
    public function getLivelli(){
        return DataManager::getLivelliCassiere(intval($this->id));
    }


    public function validate() {
        parent::validate();
    //add individual-specific validation
    }
}
?>