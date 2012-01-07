<?php
/**
 * Description of MenuFisso
 *
 * @author alessandro
 */
require_once 'Entity.php';

class MenuFisso extends Entity {
    
    private $_categorie;
    private $_alimenti;

    public function __construct($menuID) {
      
      $arData = DataManager2::getMenuFissoData($menuID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->propertyTable['prezzo'] = 'prezzo';
      $this->propertyTable['iva'] = 'iva';
      $this->propertyTable['descrizione'] = 'descrizione';
      $this->propertyTable['gestore_id'] = 'gestore_id';
      $this->_categorie = DataManager2::getCategoriaObjectsForEntity($menuID);
      $this->_alimenti = DataManager2::getAlimentoObjectsForEntity($menuID);
    }

    
    /**
     *
     * @param <Alimento> $alimento 
     */
    public function addAlimento($alimento) {
        
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
     * @param <Categoria> $categoria 
     */
    public function addCategoria($categoria) {
        
    }
    
    
    /**
     *
     * @param <int> $num
     * @return <Categoria>
     */
    public function getCategoria($num) {
        if (!isset($this->_categorie[$num])) {
            throw new Exception('Categoria non presente');
        }
        else  {
            return $this->_categorie[$num];
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
     * @return <int>
     */
    public function getNumberOfCategorie() {
        return count($this->_categorie);
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
