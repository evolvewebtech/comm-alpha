<?php
require_once 'Entity.php';

class Alimento extends Entity {

    private $_stampanti;
    private $_varianti;
    
    public function __construct($alimentoID) {
      
      $arData = DataManager2::getAlimentoData($alimentoID);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['nome'] = 'nome';
      $this->propertyTable['prezzo'] = 'prezzo';
      $this->propertyTable['iva'] = 'iva';
      $this->propertyTable['colore_bottone'] = 'colore_bottone';
      $this->propertyTable['descrizione'] = 'descrizione';
      $this->propertyTable['apeso'] = 'apeso';
      $this->propertyTable['path_image'] = 'path_image';
      $this->propertyTable['codice_prodotto'] = 'codice_prodotto';
      $this->propertyTable['quantita'] = 'quantita';
      $this->propertyTable['gestore_id'] = 'gestore_id';
      $this->propertyTable['categoria_id'] = 'categoria_id';
      $this->propertyTable['alimento_id'] = 'alimento_id';   
      $this->_stampanti = DataManager2::getStampanteObjectsForEntity($alimentoID);
      $this->_varianti = DataManager2::getVarianteObjectsForEntity($alimentoID);
    }

    
    public function getStampante($num) {
        if (!isset($this->_stampanti[$num])) {
            //throw new Exception('Stampante non presente');
        }
        else  {
            return $this->_stampanti[$num];
        }
    }
    
    
    public function getVariante($num) {
        if (!isset($this->_varianti[$num])) {
            //throw new Exception('Variante non presente');
        }
        else  {
            return $this->_varianti[$num];
        }
    }
    
    
    public function getNumberOfStampanti() {
        return count($this->_stampanti);
    }
    
    
    public function getNumberOfVarianti() {
        return count($this->_varianti);
    }
    
    
    public function __toString() {
        return 'id: ' . $this->id .
               ', nome: '. $this->nome .
               ', prezzo: ' . $this->prezzo .
               ', codice: ' . $this->codice_prodotto;
    }


    public function validate() {
      //Add common validation routines
    }
}
?>
