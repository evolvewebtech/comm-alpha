<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        
        $data = file_get_contents('php://input');
        $dataQuery = json_decode($data, true);
        $arOrdini = DataManager2::getAllOrdiniDateAsObjects($dataQuery);
        
        //Array da passare con JSON  
        $arr = array(); 
        
        if ($arOrdini) {
            for($i=0; $i<count($arOrdini); $i++) {
                
                $tot = 0;
                for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                    $tot = $tot + ($arOrdini[$i]->getRigaOrdine($j)->prezzo * $arOrdini[$i]->getRigaOrdine($j)->numero);
                }
                
                $arrTemp = array(   "id"            => $arOrdini[$i]->id,
                                    "seriale"       => $arOrdini[$i]->seriale,
                                    "timestamp"     => $arOrdini[$i]->timestamp,
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
            //echo json_encode("an error occurred");
            echo "an error occurred";
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
    
?>
