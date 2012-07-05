<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession;
        $objSession->Impress();
        
        $id = intval(mysql_real_escape_string($_POST['id']));
        
        //Array da passare con JSON  
        $arr = array(   "righe"   => array(),
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
        
            if ($id > 0) {
                $ordine = DataManager2::getOrdineAsObject($id);

                $arrRighe = array();

                for ($i=0; $i<$ordine->getNumberOfRigheOrdine(); $i++) {
                    $rigaOrd = $ordine->getRigaOrdine($i);
                    $riga = array();

                    //Recupero nome alimento
                    $alimTemp = DataManager2::getAlimentoAsObject($rigaOrd->alimento_id);

                    //Recupero varianti alimento
                    $arrVar = array();
                    for ($j=0; $j<$rigaOrd->getNumberOfVarianti(); $j++) {
                        $variante = array(
                                    "descrizione" => $rigaOrd->getVariante($j)->descrizione
                                    //"prezzo" => $rigaOrd->getVariante($j)->prezzo 
                                    );
                        $arrVar[$j] = $variante;
                    }

                    //Riga_ordine
                    if ($alimTemp) {
                        $riga = array (
                            "nome"          => $alimTemp->nome,
                            "numero"        => $rigaOrd->numero,
                            "prezzo"        => $rigaOrd->prezzo,
                            "iva"           => $rigaOrd->iva,
                            //"cassiere"      => $rigaOrd->cassiere_id,
                            "cassiere"      => $user->username,
                            "arrVar"        => $arrVar );
                    }

                    $arrRighe[$i] = $riga;
                }

                $arr['righe'] = $arrRighe;
            }

            //Invio array con Ajax
            if ($arr){
                echo json_encode($arr);
            }else {
                echo json_encode("an error occurred");
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
