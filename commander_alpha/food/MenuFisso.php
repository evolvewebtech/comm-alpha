<?php
require_once 'Entity.php';

class MenuFisso extends Entity {
    
    private $_categorie;
    private $_alimenti;

    public function __construct($menuID) {
      
      $arData = DataManager2::getMenuFissoData($menuID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->_categorie = DataManager2::getCategoriaObjectsForEntity($menuID);
      $this->_alimenti = DataManager2::getAlimentoObjectsForEntity($menuID);
    }

    
    public function getCategoria($num) {
        if (!isset($this->_categorie[$num])) {
            throw new Exception('Categoria non presente');
        }
        else  {
            return $this->_categorie[$num];
        }
    }
    
    
    public function getAlimento($num) {
        if (!isset($this->_alimenti[$num])) {
            throw new Exception('Alimento non presente');
        }
        else  {
            return $this->_alimenti[$num];
        }
    }
    
    
    public function getNumberOfCategorie() {
        return count($this->_categorie);
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
