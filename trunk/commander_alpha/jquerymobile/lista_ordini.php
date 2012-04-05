<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        
        //$dataQuery = mysql_real_escape_string($_POST['data']);             
        $arOrdini = DataManager2::getAllOrdiniDateAsObjects('curdate()');
        
        //Array da passare con JSON  
        $arr = array(); 
        
        if ($arOrdini) {
            for($i=0; $i<count($arOrdini); $i++) {
                
                $tot = 0;
                for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                    $tot = $tot + $arOrdini[$i]->getRigaOrdine($j)->prezzo;
                }
                
                $arrTemp = array(   "timestamp"     => $arOrdini[$i]->timestamp,
                                    "n_coperti"     => $arOrdini[$i]->n_coperti,
                                    "tavolo_id"     => $arOrdini[$i]->tavolo_id,
                                    "totale"        => $tot);
                
                $arr[$i] = $arrTemp;
            }
        }
        

        //Invio array con Ajax
        if ($arr){
            echo json_encode($arr);
        }else {
            echo json_encode("an error occurred");
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
    
?>
