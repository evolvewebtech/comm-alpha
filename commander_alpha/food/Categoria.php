<?php
require_once 'Entity.php';

class Categoria extends Entity {
    
    private $_alimenti;

    public function __construct($categoriaID) {
      
      $arData = DataManager2::getCategoriaData($categoriaID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->_alimenti = DataManager2::getAlimentoObjectsForEntity($categoriaID);
    }

    
    public function getAlimento($num) {
        if (!isset($this->_alimenti[$num])) {
            throw new Exception('Alimento non presente');
        }
        else  {
            return $this->_alimenti[$num];
        }
    }
    
    
    public function getNumberOfAlimenti() {
        return count($this->_alimenti);
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
