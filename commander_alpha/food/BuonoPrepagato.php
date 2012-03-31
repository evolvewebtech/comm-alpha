<?php
/**
 * Description of BuonoPrepagato
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/Entity.php';

class BuonoPrepagato extends Entity {
    
    public function __construct($seriale) {
      
      $arData = DataManager2::getBuonoPrepagatoData($seriale);
      parent::__construct($arData);

      $this->propertyTable['id'] = 'id';
      $this->propertyTable['seriale'] = 'seriale';
      $this->propertyTable['credito'] = 'credito';
      $this->propertyTable['nominativo'] = 'nominativo';
      $this->propertyTable['gestore_id'] = 'gestore_id';
    }

    
    /**
     *
     * @return <string>
     */
    public function __toString() {
        return 'seriale: ' . $this->seriale .
               ', credito: '. $this->credito;
    }


    public function validate() {
      //Add common validation routines
    }
}
?>
