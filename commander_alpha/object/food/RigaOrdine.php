<?php
/**
 * Description of RigaOrdine
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class RigaOrdine extends Entity {
    
    private $_varianti;
    
    public function __construct($rigaOrdineID) {
        
        $arData = DataManager2::getRigaOrdineData($rigaOrdineID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['ordine_id'] = 'ordine_id';     
        $this->propertyTable['alimento_id'] = 'alimento_id';
        $this->propertyTable['menu_fisso_id'] = 'menu_fisso_id';
        $this->propertyTable['numero'] = 'numero';
        $this->propertyTable['prezzo'] = 'prezzo';
        $this->propertyTable['iva'] = 'iva';
        $this->propertyTable['cassiere_id'] = 'cassiere_id';
        $this->_varianti = DataManager2::getVarianteOrdineObjectsForEntity($rigaOrdineID);
    }
      
    
    /**
     *
     * @param <int> $num
     * @return <Variante>
     */
    public function getVariante($num) {
        if (!isset($this->_varianti[$num])) {
            throw new Exception('Variante non presente');
        }
        else  {
            return $this->_varianti[$num];
        }
    }
    
    
    /**
     *
     * @return <int>
     */
    public function getNumberOfVarianti() {
        return count($this->_varianti);
    }
    
    
    /**
     *
     * @return <string>
     */
    public function __toString() {
        return 'id: ' . $this->id .
               ', ordine_id: '. $this->ordine_id.
               ', alimento_id: '. $this->alimento_id.
               ', menu_fisso_id: '. $this->menu_fisso_id;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
