<?php
/**
 * Description of Stampante
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Stampante extends Entity {
    
    public function __construct($stampanteID) {
        
        $arData = DataManager2::getStampanteData($stampanteID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['nome'] = 'nome';     
        $this->propertyTable['posizione'] = 'posizione';
        $this->propertyTable['indirizzo'] = 'indirizzo';
        $this->propertyTable['gestore_id'] = 'gestore_id';
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
