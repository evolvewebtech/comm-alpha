<?php
    
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        
        $seriale = mysql_real_escape_string($_POST['buonoSer']);
        $buono = DataManager2::getBuonoPrepagatoAsObject($seriale);        
        
        $var = array();
        $var[0] = $buono->credito;
        $var[1] = $buono->nominativo;

        echo json_encode($var);
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }

?>
