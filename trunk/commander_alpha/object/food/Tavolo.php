<?php
/**
 * Description of Tavolo
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Tavolo extends Entity {
    
    public function __construct($tavoloID) {
        
        $arData = DataManager2::getTavoloData($tavoloID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['nome'] = 'nome';     
        $this->propertyTable['numero'] = 'numero';
        $this->propertyTable['nmax_coperti'] = 'nome'; 
        $this->propertyTable['posizione'] = 'posizione';
        $this->propertyTable['sala_id'] = 'sala_id'; 
    }
      
    
    /**
     *
     * @return <string>
     */
    public function __toString() {
        return 'id: ' . $this->id .
               ', nome: '. $this->nome;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
