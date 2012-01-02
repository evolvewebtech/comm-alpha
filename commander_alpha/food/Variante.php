<?php
require_once 'PropertyObject.php';

class Variante extends PropertyObject {
    
    public function __construct($varianteID) {
        
        $arData = DataManager2::getVarianteData($varianteID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['descrizione'] = 'descrizione';     
    }
      
      
    public function __toString() {
        return 'id: ' . $this->id .
               ', descrizione: '. $this->descrizione;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
