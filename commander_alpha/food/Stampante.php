<?php
require_once 'PropertyObject.php';

class Stampante extends PropertyObject {
    
    public function __construct($stampanteID) {
        
        $arData = DataManager2::getStampanteData($stampanteID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['nome'] = 'nome';     
    }
      
      
    public function __toString() {
        return 'id: ' . $this->id .
               ', nome: '. $this->nome;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
