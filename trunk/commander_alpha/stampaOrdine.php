<?php
try
    {
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';

    require_once dirname(__FILE__).'/php_print/test/PosPrint.php';
    require_once dirname(__FILE__).'/php_print/test/EscPos.php';
    require_once dirname(__FILE__).'/manager/DataManager2.php';
    require_once dirname(__FILE__).'/manager/DataManager.php';

    $objSession = new HTTPSession();
    $lang = 'ita';
    
    //deve essere in base all'ip
    $nome_stampante = "amministrazione";

    $ret = false;
    $id         = $_POST['id'];
    $ip_address = $_POST['ip'];

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

            $gestore_id = $gestore->id;
            $utente_registrato_id = $gestore->utente_registrato_id;

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

                $arrRighe = array();

                for ($i=0; $i<$ordine->getNumberOfRigheOrdine(); $i++) {
                    $rigaOrd = $ordine->getRigaOrdine($i);
                    $riga = array();

                    //Recupero nome alimento
                    $alimTemp = DataManager2::getAlimentoAsObject($rigaOrd->alimento_id);

                    $num_stampanti = $alimTemp->getNumberOfStampanti();
                    for($j=0; $j<$num_stampanti; $j++) {
                        $stampante = $alimTemp->getStampante($j);
                        $stampante_id = $stampante->id;
                    }

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

                    $arrRighe[$i] = $riga;
                }

                $arr = $arrRighe;
            }

            $str = '';
            $strCass = '';


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
            $righe_ordine = count($arr);

            for ($i=0; $i<count($arr); $i++) {
                $numero = floatval($arr[$i]['numero']);
                $prezzo = floatval($arr[$i]['prezzo']);
                (float) $prezzoTot = ( $numero * $prezzo );

                $totale_ordine += $prezzoTot;

                $nome = $arr[$i]['nome'];
                $prezzoTot = sprintf("%01.2f",$prezzoTot)." ".$euro;

                $esc->text("  $numero $nome $prezzoTot");

                for ($j=0; $j<count($arr[$i]['arrVar']); $j++) {

                    $descrizione = $arr[$i]['arrVar'][$j]['descrizione'];
                    $esc->text("    $descrizione");
                }

                if ($i == 0) {
                    $cameriere  = $arr[$i]['cassiere_id'];
                    $cameriere_id = $arr[$i]['cassiere_id'];
                    $cameriere = DataManager::getCassiereDataByCassiereID($cameriere_id);
                    $nome_cameriere = $cameriere['first_name'];
                }
            }

            $totale_ordine_print = sprintf("%01.2f",$totale_ordine)." ".$euro;
            $data = Utility::formattaDataOra($timestamp);

            $esc->text("---------------------------------------");
            $esc->font(false,true,false,true,true);
            $esc->text("  Totale: $totale_ordine_print");
            $esc->font();
            $esc->text("---------------------------------------");
            $esc->text("  Voci in comanda: ".$righe_ordine);
            $esc->text("  Cameriere: $nome_cameriere");
            $esc->text("  Data: $data");


            $esc->cutCom();
            $to_printer=$esc->out();

            $ret = PosPrint::comm_print($ip_address, $to_printer);
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

    echo json_encode($ret);

} catch(Exception $e) {
    echo $e->getMessage();
    // Note: Log the error or something
}
?>
