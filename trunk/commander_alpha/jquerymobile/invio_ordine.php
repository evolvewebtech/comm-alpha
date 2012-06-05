<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        require_once dirname(__FILE__).'/../manager/Utility.php';
    require_once dirname(__FILE__).'/../php_print/test/PosPrint.php';
    require_once dirname(__FILE__).'/../php_print/test/EscPos.php';
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

                //Inserimento del nuovo ordine nel database
                $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);

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
                /*if ($buono_ok) {
                    $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);
                }
                else {
                    DataManager2::rollbackTransaction();
                    die();
                }*/

                if(($ret) && ($buono_ok)){
                    for ($i=0; $i<count($data['alimenti']); $i++) {

                        //ID del nuovo "ordine"
                        $next_id_rigaordine = DataManager2::nextIDRigaOrdine();
                        $ordine_id = $next_id;
                        $alimento_id = $data['alimenti'][$i][0];
                        $alimento_menu_id = 0;
                        $numero = $data['alimenti'][$i][1];
                        $prezzo = $data['alimenti'][$i][2];
                        $iva = $data['alimenti'][$i][3];
                        $cassiere_id = $objSession->GetUserID();

                        //Inserimento "RigaOrdine nel database"
                        $ret = DataManager2::inserisciRigaOrdine('null', $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassiere_id);

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
//                echo json_encode($arr);
                //Fine Transaction
            
                
//******************************************************************************
//Stampa ordine
                $lang = 'ita';
    
                //deve essere in base all'ip
                $nome_stampante = "cassiere";

                $ret = false;
                $id  = $next_id;
                
                $arrStmp = array();
                
                if ($id > 0) {
                    $ordine = DataManager2::getOrdineAsObject($id);

                    $seriale     = $ordine->seriale;
                    $timestamp   = $ordine->timestamp;
                    $n_coperti   = intval($ordine->n_coperti);
                    $tavolo_id   = intval($ordine->tavolo_id);

                    $tavolo = DataManager::getTavolo($tavolo_id);
                    $numero_tavolo = intval($tavolo['numero']);
                    $nome_tavolo   = $tavolo['nome'];

                    for ($i=0; $i<$ordine->getNumberOfRigheOrdine(); $i++) {
                        $rigaOrd = $ordine->getRigaOrdine($i);
                        $riga = array();
                        $arrRigaStamp = array();

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
                                "cassiere_id"   => $rigaOrd->cassiere_id,
                                //"arrStampanti"  => $rigaOrd->stampanti,
                                "arrVar"        => $arrVar );
                        }

                        //Stampanti associate all'alimento
                        //Creato un array con un numero di elementi pari al numero
                        //di stampanti utilizzate per questo ordine.
                        //A ogni stampante sono inviati i rispettivi alimenti dell'ordine
                        $num_stampanti = $alimTemp->getNumberOfStampanti();                   
                        for($j=0; $j<$num_stampanti; $j++) {
                            $stampante = $alimTemp->getStampante($j);
                            $stampante_id = $stampante->id;
                            $ip_address = $stampante->indirizzo;
                            $nome_stamp = $stampante->nome;

                            $stmpPres = false;

                            for($t=0; $t<count($arrStmp); $t++) {
                                //Se la stampante è già aggiunta all'array, è
                                //inserito l'alimento
                                if ($arrStmp[$t]["stampante_id"] == $stampante_id) {           
                                    $stmpPres = true;
                                    $temp = array();
                                    array_push($temp, $riga);
                                    array_push($arrStmp[$t]["alimenti"], $temp);
                                }
                            }

                            //Se la stampante non è stata già aggiunta all'array
                            //è creato un nuovo elemento con id e indirizzo della
                            //stampante ed è aggiunto l'alimento
                            if (!$stmpPres) { 
                                $arrRigaStamp = array (
                                    "stampante_id" => $stampante_id,
                                    "ip_address" => $ip_address,
                                    "nome_stamp" => $nome_stamp,
                                    "alimenti" => array()
                                );
                                array_push($arrStmp, $arrRigaStamp);
                                $temp = array();
                                array_push($temp, $riga);
                                $tempID = count($arrStmp) - 1;
                                array_push($arrStmp[$tempID]["alimenti"], $temp);
                            }
                        }
                    }
                }

                $str = '';
                $strCass = '';

                //Array biglietti da stampare
                //Per ogni stampante è inviata una stampa
                for($s=0; $s<count($arrStmp); $s++) {

                    $esc = new EscPos("it",858,"àèìòù","\x7B\x7D\x7E\x7C\x60\xD5");	// initialize and select country, codepage and extra char trasformer string
                    $esc->align("c");			// central align
                    $esc->font(false,true,false,true,true);	// select bold, tall and large font
                    $esc->text("S.ANNA e GIOACCHINO");
                    $esc->text("-------------------");
                    $esc->font(false,true,false,false,true);
                    $esc->text("Tavolo: $numero_tavolo");
                    $esc->font();
                    $esc->text($nome_stampante);
                    $esc->align();
                    $esc->text("  Ordine: $seriale, coperti: $n_coperti");

                    $euro = chr(213);
                    $totale_ordine = 0;
                    $voci_comanda = 0;
                    $ip_address = $arrStmp[$s]["ip_address"];
                    $nome_stamp = $arrStmp[$s]["nome_stamp"];
                    
                    for ($i=0; $i<count($arrStmp[$s]["alimenti"]); $i++) {
                        $numero = floatval($arrStmp[$s]["alimenti"][$i][0]['numero']);
                        $prezzo = floatval($arrStmp[$s]["alimenti"][$i][0]['prezzo']);
                        (float) $prezzoTot = ( $numero * $prezzo );

                        $totale_ordine += $prezzoTot;

                        $nome = $arrStmp[$s]["alimenti"][$i][0]['nome'];
                        $prezzoTot = sprintf("%01.2f",$prezzoTot)." ".$euro;

                        $esc->text("  $numero $nome $prezzoTot");

                        for ($j=0; $j<count($arrStmp[$s]["alimenti"][$i][0]['arrVar']); $j++) {

                            $descrizione = $arrStmp[$s]["alimenti"][$i][0]['arrVar'][$j]['descrizione'];
                            $esc->text("    $descrizione");
                        }

                        if ($i == 0) {
                            $cameriere  = $arrStmp[$s]["alimenti"][$i][0]['cassiere_id'];
                            $cameriere_id = $arrStmp[$s]["alimenti"][$i][0]['cassiere_id'];
                            $cameriere = DataManager::getCassiereDataByCassiereID($cameriere_id);
                            $nome_cameriere = $cameriere['first_name'];
                            $nome_cameriere = $user->username;
                        }

                        $voci_comanda += $numero;
                    }

                    $totale_ordine_print = sprintf("%01.2f",$totale_ordine)." ".$euro;
                    $data = Utility::formattaDataOra($timestamp);

                    $esc->text("---------------------------------------");
                    $esc->font(false,true,false,true,true);
                    $esc->text("  Totale: $totale_ordine_print");
                    $esc->font();
                    $esc->text("---------------------------------------");
                    $esc->text("  Voci in comanda: ".$voci_comanda."     Stampa ".($s+1)."/".count($arrStmp));
                    $esc->text("  Stampante: $nome_stamp");
                    $esc->text("  Cameriere: $nome_cameriere");
                    $esc->text("  Data: $data");


                    $esc->cutCom();
                    $to_printer=$esc->out();
                    
                    $ret = PosPrint::comm_print($ip_address, $to_printer);
                }
                
                //Ritorno codice errore in presenza di errori di stampa
                //(solo se l'ordine è da stampare)
                if ((!$ret) && (count($arrStmp)>0)) {
                    $arr['err'] = 'E200';
                }
//******************************************************************************
                
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
