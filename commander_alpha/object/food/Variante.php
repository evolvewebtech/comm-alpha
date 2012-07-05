<?php
/**
 * Description of Variante
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Variante extends Entity {
    
    public function __construct($varianteID) {
        
        $arData = DataManager2::getVarianteData($varianteID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['descrizione'] = 'descrizione';
        $this->propertyTable['prezzo'] = 'prezzo';
        $this->propertyTable['iva'] = 'iva';
        $this->propertyTable['gestore_id'] = 'gestore_id';
    }
      
    
    /**
     *
     * @return <string>
     */
    public function __toString() {
        return 'id: ' . $this->id .
               ', descrizione: '. $this->descrizione;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
