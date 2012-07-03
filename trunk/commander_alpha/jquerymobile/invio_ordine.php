<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        
        $objSession = new HTTPSession();
        $objSession->Impress();        
   
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        //Array da passare con JSON  
        $arr = array(   "next_id"   => 0,
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
                
                //Inizio Transaction
                DataManager2::startTransaction();

                //ID del nuovo "ordine"
                $next_id = DataManager2::nextIDOrdine();
                $seriale = $next_id;
                $n_coperti = $data['n_coperti'];
                $tavolo_id = $data['tavolo_id'];
                $buono_ser = $data['buono_ser'];
                $buono_cred_us = $data['buono_cred_us'];
                $sconto_perc = $data['sconto'];
                $asporto = '';
                if ($data['asporto'] == 'true') $asporto = 'ASPORTO';
                $saldo = 0;

                //Verifica se credito buono sufficiente
                $buono_ok = false;
                if ($buono_ser != "") {
                    //Recupero dati "Buono prepagato"
                    $buono = DataManager2::getBuonoPrepagatoAsObject($buono_ser, $user->getGestoreID() );
                    if ($buono->credito >= $buono_cred_us) {
                        //Inserimento relazione "BuonoOrdine"
                        $ret2 = DataManager2::inserisciBuonoOrdine((int)$buono->id, $next_id, (double)$buono_cred_us);               
                        if ($ret2) {
                            $nuovo_credito = $buono->credito - $buono_cred_us;
                            //Aggiornamento del credito del buono utilizzato
                            $ret2 = DataManager::aggiornaBuonoPrepagato((int)$buono->id,
                                                                    $buono->seriale,
                                                                    $nuovo_credito,
                                                                    $buono->nominativo,
                                                                    (int)$buono->gestore_id,
                                                                    0);
                        }
                        if ($ret2) { $buono_ok = true; }
                    }
                }
                else $buono_ok = true;

                //Inserimento del nuovo ordine nel database
                if ($buono_ok) {
                    $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);
                }
                else {
                    DataManager2::rollbackTransaction();
                    //die();
                    $arr['err'] = 'E104';
                    echo json_encode($arr);
                    return;
                }

                if(($ret) && ($buono_ok)){
                    for ($i=0; $i<count($data['alimenti']); $i++) {

                        //ID del nuovo "ordine"
                        $next_id_rigaordine = DataManager2::nextIDRigaOrdine();
                        $ordine_id = $next_id;
                        $alimento_id = $data['alimenti'][$i][0];
                        $menu_fisso_id = $data['alimenti'][$i][5];
                        $numero = $data['alimenti'][$i][1];
                        $prezzo = $data['alimenti'][$i][2];
                        $iva = $data['alimenti'][$i][3];
                        $cassiere_id = $user->id;
                        
                        //Calcolo sconto
                        if ($sconto_perc > 0) {
                            $prezzo = $prezzo - ( (floatval($prezzo) * floatval($sconto_perc)) / 100 );
                            $prezzo = round($prezzo, 2);
                        }
                        $saldo = floatval($saldo) + (floatval($prezzo)*floatval($numero));

                        //Inserimento "RigaOrdine nel database"
                        $ret = DataManager2::inserisciRigaOrdine('null', $ordine_id, $alimento_id, $menu_fisso_id, $numero, $prezzo, $iva, $cassiere_id);

                        if ($ret) {
                            //Inserimento relazioni "VarianteRigaOrdine" nel database
                            for ($j=0; $j<count($data['alimenti'][$i][4]); $j++) {
                                $variante_id = $data['alimenti'][$i][4][$j];
                                DataManager2::inserisciVarianteRigaOrdine($variante_id, $next_id_rigaordine);
                            }
                        } 

                        if (!$ret) {
                            DataManager2::rollbackTransaction();
                            //die();
                            $arr['err'] = 'E103';
                            echo json_encode($arr);
                            return;
                        }
                    }
                    //Query database
                    $ret = DataManager2::inserisciOrdineChiuso('null', $next_id);
                    if (!$ret) {
                        DataManager2::rollbackTransaction();
                        //die();
                        $arr['err'] = 'E102';
                        echo json_encode($arr);
                        return;
                    }
                    //Aggiornamento saldo incassato
                    $saldo = floatval($saldo) - floatval($buono_cred_us);
                    $ret = $user->aggiornaCassa($saldo);
                    if (!$ret) {
                        DataManager2::rollbackTransaction();
                        //die();
                        $arr['err'] = 'E105';
                        echo json_encode($arr);
                        return;
                    }
                }
                else {
                    DataManager2::rollbackTransaction();
                    //die();
                    $arr['err'] = 'E101';
                    echo json_encode($arr);
                    return;
                }
                DataManager2::commitTransaction();
                $arr['next_id'] = $next_id;
                //Fine Transaction
            
                
                /**
                 * Stampa ordine
                 *  
                 */
                include dirname(__FILE__).'/stampa_ordine.php';
                //stampaOrdine(ordine_id, testo_tipo_stampa, stampa_prezzi, stamp_alernativa, id_stamp_alernativa)
                $ret = stampaOrdine($next_id, $asporto, true, false, 0);
                
                //Ritorno codice errore in presenza di errori di stampa
                //(solo se l'ordine è da stampare)
                if (!$ret) {
                    $arr['err'] = 'E200';
                }
                
                
            echo json_encode($arr);    
                
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
        DataManager2::rollbackTransaction();
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
