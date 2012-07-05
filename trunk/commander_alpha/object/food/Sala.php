<?php
/**
 * Description of Sala
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Sala extends Entity {
    
    private $_tavoli;
    
    public function __construct($salaID) {
        
        $arData = DataManager2::getSalaData($salaID);
        parent::__construct($arData);
        
        $this->propertyTable['id'] = 'id';
        $this->propertyTable['nome'] = 'nome';     
        $this->propertyTable['posizione'] = 'posizione';
        $this->_tavoli = DataManager2::getTavoloObjectsForEntity($salaID);
    }
      
    
    /**
     *
     * @param <int> $num
     * @return <Tavolo object>
     */
    public function getTavolo($num) {
        if (!isset($this->_tavoli[$num])) {
            //throw new Exception('Stampante non presente');
        }
        else  {
            return $this->_tavoli[$num];
        }
    }
    
    
    /**
     *
     * @return <int>
     */
    public function getNumberOfTavoli() {
        return count($this->_tavoli);
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
