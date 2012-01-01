<?php
require_once 'PropertyObject.php';

abstract class User extends PropertyObject {

    public function __construct($arData) {

        parent::__construct($arData);

        $this->propertyTable['id'] = 'id';

        $utente_registrato = DataManager::getUserData($this->id);

        $this->propertyTable['username'] = 'username';
        $this->propertyTable['md5_pw'] = 'md5_pw';
        $this->propertyTable['first_name'] = 'first_name';
        $this->propertyTable['last_name'] = 'last_name';

        $this->username = $utente_registrato['username'];
        $this->md5_pw = $utente_registrato['md5_pw'];
        $this->first_name = $utente_registrato['first_name'];
        $this->last_name = $utente_registrato['last_name'];

        //      $this->_emails = DataManager::getEmailObjectsForEntity($entityID);
        //      $this->_addresses = DataManager::getAddressObjectsForEntity($entityID);
        //      $this->_phonenumbers = DataManager::getPhoneNumberObjectsForEntity($entityID);
    }

    function setID($val) {
        throw new Exception('You may not alter the value of the ID field!');
    }

/*
    function setEntityID($val) {
      $this->setID($val);
    }
*/
/*
    function phonenumbers($index) {
      if(!isset($this->_phonenumbers[$index])) {
        throw new Exception('Invalid phone number specified!');
      } else {
         return $this->_phonenumbers[$index];
      }
    }

    function getNumberOfPhoneNumbers() {
      return sizeof($this->_phonenumbers);
    }

    function addPhoneNumber(PhoneNumber $phone) {
      $this->_phonenumbers[] = $phone;
    }

*/

    public function validate() {
        //Add common validation routines
    }

}