<?php
/**
 * Description of Categoria
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class Categoria extends Entity {
    
    private $_alimenti;

    public function __construct($categoriaID) {
      
      $arData = DataManager2::getCategoriaData($categoriaID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->propertyTable['colore_bottone_predef'] = 'colore_bottone_predef';
      $this->propertyTable['gestore_id'] = 'gestore_id';
      $this->_alimenti = DataManager2::getAlimentoObjectsForEntity($categoriaID);
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
