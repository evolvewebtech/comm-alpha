<?php
/* 
 * pagina per la ricerca ajax
 */
    try
    {
        require_once dirname(__FILE__) . '/manager/DataManager.php';
        require_once dirname(__FILE__).'/manager/HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();

        /*
         * controllo se il login sia valido
         */
        /*
         * inizio login
         */
        if($objSession->IsLoggedIn()){

            $objUser = $objSession->GetUserObject();
            $gestore = $objUser[0];
            if(get_class($gestore) == 'Gestore') {

                /*
                 * inizio la ricerca
                 * controllo che qualcosa sia ricercato
                 */
                if(!empty($_GET['q']) & $_GET['search']=='ordine') {
                    $q = mysql_real_escape_string($_GET['q']);
                    $arOrdini = DataManager::search_ordine($q);

                    //Array da passare con JSON
                    $var = array();

                    if ($arOrdini) {
                        for($i=0; $i<count($arOrdini); $i++) {

                            $tot = 0;
                            for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                                $tot = $tot + ($arOrdini[$i]->getRigaOrdine($j)->prezzo * $arOrdini[$i]->getRigaOrdine($j)->numero);
                            }

                            $tavolo_id = $arOrdini[$i]->tavolo_id;
                            $tavolo = DataManager::getTavolo(intval($tavolo_id));

                            $arrTemp = array(   "id"            => $arOrdini[$i]->id,
                                                "seriale"       => $arOrdini[$i]->seriale,
                                                "timestamp"     => $arOrdini[$i]->timestamp,
                                                "n_coperti"     => $arOrdini[$i]->n_coperti,
                                                "tavolo"        => $tavolo,
                                                "tavolo_id"     => $arOrdini[$i]->tavolo_id,
                                                "totale"        => $tot);

                            $var[$i] = $arrTemp;
                        }
                    }
                }elseif((!empty($_GET['q2']) & $_GET['search']=='tavolo')){
                    $q = mysql_real_escape_string($_GET['q2']);
                    $arOrdini = DataManager::search_tavolo($q);

                    //Array da passare con JSON
                    $var = array();

                    if ($arOrdini) {
                        for($i=0; $i<count($arOrdini); $i++) {

                            $tot = 0;
                            for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                                $tot = $tot + ($arOrdini[$i]->getRigaOrdine($j)->prezzo * $arOrdini[$i]->getRigaOrdine($j)->numero);
                            }

                            $tavolo_id = $arOrdini[$i]->tavolo_id;
                            $tavolo = DataManager::getTavolo(intval($tavolo_id));

                            $arrTemp = array(   "id"            => $arOrdini[$i]->id,
                                                "seriale"       => $arOrdini[$i]->seriale,
                                                "timestamp"     => $arOrdini[$i]->timestamp,
                                                "n_coperti"     => $arOrdini[$i]->n_coperti,
                                                'tavolo'        => $tavolo,
                                                "tavolo_id"     => $arOrdini[$i]->tavolo_id,
                                                "totale"        => $tot
                                            );

                            $tavolo = DataManager::getTavolo(intval($arrTemp['tavolo_id']));
                            $var[$i] = $arrTemp;
                        }
                    }
                }
                
            /*
             * fine login
             *
             */
            } else{
                $var['err'] = 'E001'; //non è un gestore
            }
        }//isLoggedin
        else {
            $var['err'] = 'E002';  //not logged in o sessione scaduta
        }

        echo json_encode($var);

    } catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
