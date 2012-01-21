<?php
/**
 * Esempio gestore evento
 *
 * @author alessandro
 */

    require_once dirname(__FILE__).'/Event_Handler.php';

    class Handler_Edit extends Event_Handler {
        private $handle;
        
        function __construct($event_handle){
            $this->handle = $event_handle;
        }
        
        function handled_event(){
            echo "This is event".$this->handle.", which is now handled - no kidding! <BR /n>";
        }
    }
?>
