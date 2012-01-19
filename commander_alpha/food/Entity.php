<?php
require_once dirname(__FILE__).'/../object/PropertyObject.php';


abstract class Entity extends PropertyObject {    
    
    public function __construct($entityID) {
        
        //$arData = DataManager2::getEntityData($entityID);
        //parent::__construct($arData);
        parent::__construct($entityID);
        
        $this->propertyTable['entityid'] = 'entityid';
        $this->propertyTable['id'] = 'entityid';        
    }
    
    function setID($val) {
        throw new Exception('You may not alter the value of the ID field!');
    }
    
    function setEntityID($val) {
        $this->setID($val);
    }
    
    
    
    
    public function validate() {
      //Add common validation routines
    }
}
?>
