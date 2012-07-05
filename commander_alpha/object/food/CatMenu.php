<?php
/**
 * Description of CatMenu
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class CatMenu extends Entity {
    
    private $_alimenti;

    public function __construct($catMenuID) {
      
      $arData = DataManager2::getCatMenuData($catMenuID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome_cat'] = 'nome_cat';
      $this->_alimenti = DataManager2::getAlimentoMenuObjectsForEntity($catMenuID);
    }

    
    /**
     *
     * @param <int> $num
     * @return <Alimento>
     */
    public function getAlimento($num) {
        if (!isset($this->_alimenti[$num])) {
            throw new Exception('Alimento non presente');
        }
        else  {
            return $this->_alimenti[$num];
        }
    }
    
    
    /**
     *
     * @return <int>
     */
    public function getNumberOfAlimenti() {
        return count($this->_alimenti);
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
