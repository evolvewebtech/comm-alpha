<?php
require_once dirname(__FILE__).'/Validator.php';

abstract class PropertyObject implements Validator {

        /*
         * registro le coppie nome/valore riferite
         * ai campi del db
         *
         */
        protected $propertyTable = array();

        /*
         * lista delle proprietà che devono essere modificate
         */
        protected $changedProperties = array();

        /*
         * dati correnti presi dal db
         */
        protected $data;

        /*
         * qualsiasi errore di validazione
         */
        protected $errors = array();


        public function __construct($arData){
            $this->data = $arData;
        }

        /*
         *
         */
        public function __get($propertyName) {

            if(!array_key_exists($propertyName, $this->propertyTable)) {
                throw new Exception("Invalid property \"$propertyName\"! ");
            }
            if(method_exists($this, 'get' . $propertyName)) {
                return call_user_func(
                       array($this, 'get' . $propertyName));
            } else {
                return $this->data[$this->propertyTable[$propertyName]];
            }
        }

        /*
         *
         */
        public function __set($propertyName, $value) {

            if(!array_key_exists($propertyName, $this->propertyTable)) {
                throw new Exception("Invalid property \"$propertyName\"!");
            }
            if(method_exists($this, 'set' . $propertyName)) {
                return call_user_func(
                                array($this, 'set' . $propertyName),
                                $value);
            } else {

                /*
                 * se il valore della proprietà è cambiato e non è ancora
                 * nella array chagedProperties lo aggiungo
                 *
                 */
                if($this->propertyTable[$propertyName] != $value &&
                    !in_array($propertyName, $this->changedProperties)) {
                        $this->changedProperties[] = $propertyName;
                }
                
                /*
                 * setto il nuovo valore
                 */
                $this->data[$this->propertyTable[$propertyName]] = $value;
            }
        }

        public function validate() {

        }
}
?>
