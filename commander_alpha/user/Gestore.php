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

    /*
     * getCassieri
     */
    public function getCassieri() {
        return DataManager::getTuttiCassieri($this->id);
    }

    public function validate() {
        parent::validate();
    //add individual-specific validation
    }

    public function addSala($id, $nome,$posizione){
        $ret = DataManager::addSala($id, $nome, $posizione);
        return true;
    }
}
?>
