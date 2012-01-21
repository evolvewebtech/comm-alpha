<?php
/**
 * Classe astratta Event_Handler
 *
 * @author alessandro
 */

    require_once dirname(__FILE__).'/Handled.php';
    
    abstract class Event_Handler
    {
        //function dbconn(){
        //    $link_id = db_connect('sample_db');
        //    return $link_id;
        //}
        
        abstract function handled_event();
    }
?>
