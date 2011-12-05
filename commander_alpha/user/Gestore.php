<?php

require_once('User.php');
require_once('Cassiere.php');

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

}
?>
