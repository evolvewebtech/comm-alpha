<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();
        
        $dataQuery = mysql_real_escape_string($_POST['dataora']);
        $numRes = mysql_real_escape_string($_POST['numres']);
        
        //Array da passare con JSON  
        $arr = array(   "ordini"   => array(),
                        "num_ord"  => 0,
                        "totale"   => 0,
                        "contanti" => 0,
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
            $totOrdini = 0;
            $totBuoni = 0;
                
            $arOrdini = DataManager2::getAllOrdiniDateAsObjects($dataQuery, $objSession->GetUserID());
        
            if ($arOrdini) {
                $numOrd = count($arOrdini);
                $arr['num_ord'] = $numOrd;
                
                for($i=0; $i<$numOrd; $i++) {

                    $tot = 0;
                    for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                        $tot = $tot + ($arOrdini[$i]->getRigaOrdine($j)->prezzo * $arOrdini[$i]->getRigaOrdine($j)->numero);
                    }
                    $totOrdini = $totOrdini + $tot;
                    $buono = DataManager2::getCreditoBuonoUsato($arOrdini[$i]->id);

                    $arrTemp = array(   "id"            => $arOrdini[$i]->id,
                                        "seriale"       => $arOrdini[$i]->seriale,
                                        "timestamp"     => $arOrdini[$i]->timestamp,
                                        "n_coperti"     => $arOrdini[$i]->n_coperti,
                                        "tavolo_id"     => $arOrdini[$i]->tavolo_id,
                                        "totale"        => $tot,
                                        "tot_buono"     => $buono );
                    
                    $totBuoni = $totBuoni + $buono;
                    //Visulizzati solo n risultati
                    if (($numRes<$numOrd) && (($numOrd-$numRes)>3) && ($i<$numRes) && ($numRes>0)) {
                        $arr['ordini'][$i] = $arrTemp;
                    }
                    if (($numRes<=0) || ($numRes>=$numOrd) || (($numOrd-$numRes)<=3)) {
                        $arr['ordini'][$i] = $arrTemp;
                    }
                    $arrTemp = null;
                }
                $arr['totale'] = $totOrdini;
                $arr['contanti'] = $totOrdini - $totBuoni;
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
