<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        


        //Query database
        //DataManager2::inserisciOrdine('null', '001', "", '20', '101');
        
        
        
        
        //Invio array con Ajax
        /*if ($arr){
            echo json_encode($arr);
        }else {
            echo json_encode("an error occurred");
        }*/
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
