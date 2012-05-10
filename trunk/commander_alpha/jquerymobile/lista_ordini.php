<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession();
        
        $data = file_get_contents('php://input');
        $dataQuery = json_decode($data, true);
        
        //Array da passare con JSON  
        $arr = array(   "ordini"   => array(),
                        "cassiere" => '',
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
                
            $arr['cassiere'] = $user->username;
                
            $arOrdini = DataManager2::getAllOrdiniDateAsObjects($dataQuery, $objSession->GetUserID());
        
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

                    $arr['ordini'][$i] = $arrTemp;
                }
            }


            //Invio array con Ajax
            if ($arr){
                echo json_encode($arr);
            }else {
                //echo json_encode("an error occurred");
                echo "an error occurred";
            }

            /*
             * fine login
             *
             */
            } else{
                $arr['err'] = 'E001'; //non è un cassiere
                echo json_encode($arr);
            }
        }//isLoggedin
        else {
            $arr['err'] = 'E002';  //not logged in o sessione scaduta
            echo json_encode($arr);
        } 
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
    
?>
