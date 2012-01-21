<?php
/**
 * Dispatcher degli eventi
 *
 * @author alessandro
 */

    require_once dirname(__FILE__).'/Event_Handler.php';
    require_once dirname(__FILE__).'/Handler_View.php';
    require_once dirname(__FILE__).'/Handler_Edit.php';

    class Dispatcher
    {
        private $handle;
        
        function __construct($event_handle) {
            $this->handle = $event_handle;
        }
        function handle_the_event(){
            $name = 'handler_'.$this->handle;
            if (class_exists($name)){
                $handler_obj = new $name($this->handle);
                $response = $handler_obj->handled_event();
                return $response;
            }
            else{
                echo "I can’t handle this!";
            }
        }
    }
?>
