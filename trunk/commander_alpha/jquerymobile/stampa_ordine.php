<?php
try
    {
    require_once dirname(__FILE__).'/../manager/Utility.php';
    require_once dirname(__FILE__).'/../manager/HTTPSession.php';

    require_once dirname(__FILE__).'/../php_print/test/PosPrint.php';
    require_once dirname(__FILE__).'/../php_print/test/EscPos.php';
    require_once dirname(__FILE__).'/../manager/DataManager2.php';
    require_once dirname(__FILE__).'/../manager/DataManager.php';

    //$objSession = new HTTPSession();
    $lang = 'ita';
    
    //deve essere in base all'ip
    $nome_stampante = "cassiere";

    $ret = false;
    $id         = $_POST['id'];

    /*
     * controllo se il login sia valido
     */
    /*
     * inizio login
     */
//    if($objSession->IsLoggedIn()){

//        $objUser = $objSession->GetUserObject();
//        $gestore = $objUser[0];
//        if(get_class($gestore) == 'Gestore') {

//            $gestore_id = $gestore->id;
//            $utente_registrato_id = $gestore->utente_registrato_id;

            $arr = array();
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
                        
                        for($t=0; $t<count($arr); $t++) {
                            //Se la stampante è già aggiunta all'array, è
                            //inserito l'alimento
                            if ($arr[$t]["stampante_id"] == $stampante_id) {           
                                $stmpPres = true;
                                $temp = array();
                                array_push($temp, $riga);
                                array_push($arr[$t]["alimenti"], $temp);
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
                            array_push($arr, $arrRigaStamp);
                            $temp = array();
                            array_push($temp, $riga);
                            $tempID = count($arr) - 1;
                            array_push($arr[$tempID]["alimenti"], $temp);
                        }
                    }
                }
            }

            $str = '';
            $strCass = '';

            //Array biglietti da stampare
            //Per ogni stampante è inviata una stampa
            for($s=0; $s<count($arr); $s++) {
                
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
                $ip_address = $arr[$s]["ip_address"];
                $nome_stamp = $arr[$s]["nome_stamp"];

                for ($i=0; $i<count($arr[$s]["alimenti"]); $i++) {
                    $numero = floatval($arr[$s]["alimenti"][$i][0]['numero']);
                    $prezzo = floatval($arr[$s]["alimenti"][$i][0]['prezzo']);
                    (float) $prezzoTot = ( $numero * $prezzo );

                    $totale_ordine += $prezzoTot;

                    $nome = $arr[$s]["alimenti"][$i][0]['nome'];
                    $prezzoTot = sprintf("%01.2f",$prezzoTot)." ".$euro;

                    $esc->text("  $numero $nome $prezzoTot");

                    for ($j=0; $j<count($arr[$s]["alimenti"][$i][0]['arrVar']); $j++) {

                        $descrizione = $arr[$s]["alimenti"][$i][0]['arrVar'][$j]['descrizione'];
                        $esc->text("    $descrizione");
                    }

                    if ($i == 0) {
                        $cameriere  = $arr[$s]["alimenti"][$i][0]['cassiere_id'];
                        $cameriere_id = $arr[$s]["alimenti"][$i][0]['cassiere_id'];
                        $cameriere = DataManager::getCassiereDataByCassiereID($cameriere_id);
                        $nome_cameriere = $cameriere['first_name'];
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
                $esc->text("  Voci in comanda: ".$voci_comanda."     Stampa ".($s+1)."/".count($arr));
                $esc->text("  Stampante: $nome_stamp");
                $esc->text("  Cameriere: $nome_cameriere");
                $esc->text("  Data: $data");


                $esc->cutCom();
                $to_printer=$esc->out();
                
                $ret = PosPrint::comm_print($ip_address, $to_printer);

            }
        /*
         * fine login
         *
         */
//        } else{
//            $var['err'] = 'E001'; //non è un gestore
//        }
//    }//isLoggedin
//    else {
//        $var['err'] = 'E002';  //not logged in o sessione scaduta
//    }

    echo json_encode($ret);

} catch(Exception $e) {
    echo $e->getMessage();
    // Note: Log the error or something
}
?>
