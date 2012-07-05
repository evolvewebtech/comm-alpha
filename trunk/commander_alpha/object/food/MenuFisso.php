<?php
/**
 * Description of MenuFisso
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class MenuFisso extends Entity {
    
    private $_catMenu;

    public function __construct($menuID) {
      
      $arData = DataManager2::getMenuFissoData($menuID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->propertyTable['prezzo'] = 'prezzo';
      $this->propertyTable['iva'] = 'iva';
      $this->propertyTable['descrizione'] = 'descrizione';
      $this->propertyTable['gestore_id'] = 'gestore_id';
      $this->_catMenu = DataManager2::getCatMenuObjectsForEntity($menuID);
    }

     
    /**
     *
     * @param <int> $num
     * @return <Categoria>
     */
    public function getCategoria($num) {
        if (!isset($this->_catMenu[$num])) {
            throw new Exception('Categoria non presente');
        }
        else  {
            return $this->_catMenu[$num];
        }
    }
    
    
    /**
     *
     * @return <int>
     */
    public function getNumberOfCategorie() {
        return count($this->_catMenu);
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
