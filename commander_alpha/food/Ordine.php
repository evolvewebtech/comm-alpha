<?php
/**
 * Description of Ordine
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Ordine extends Entity {
    
    private $_righe_ordine;
    
    public function __construct($ordineID) {
        
        $arData = DataManager2::getOrdineData($ordineID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['seriale'] = 'seriale';     
        $this->propertyTable['timestamp'] = 'timestamp';
        $this->propertyTable['n_coperti'] = 'n_coperti';
        $this->propertyTable['tavolo_id'] = 'tavolo_id';
        $this->_righe_ordine = DataManager2::getRigaOrdineObjectsForEntity($ordineID);
    }
      
    
    /**
     *
     * @param <int> $num
     * @return <RigaOrdine>
     */
    public function getRigaOrdine($num) {
        if (!isset($this->_righe_ordine[$num])) {
            throw new Exception('RigaOrdine non presente');
        }
        else  {
            return $this->_righe_ordine[$num];
        }
    }
    
    
    /**
     *
     * @return <int>
     */
    public function getNumberOfRigheOrdine() {
        return count($this->_righe_ordine);
    }
    
    
    /**
     *
     * @return <string>
     */
    public function __toString() {
        return 'id: ' . $this->id .
               ', seriale: '. $this->seriale.
               ', timestamp: '. $this->timestamp.
               ', coperti: '. $this->n_coperti.
               ', tavolo: '. $this->tavolo_id;
    }  
      
    
    public function validate() {
      //Add common validation routines
    }
}
?>
