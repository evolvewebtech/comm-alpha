<?php
    
    require_once dirname(__FILE__).'/../manager/DataManager.php';
    require_once dirname(__FILE__).'/../manager/DataManager2.php';
    require_once dirname(__FILE__).'/../manager/Utility.php';
    require_once dirname(__FILE__).'/../php_print/test/PosPrint.php';
    require_once dirname(__FILE__).'/../php_print/test/EscPos.php';
    
    
    /**
     * Funzione per la stampa dell'ordine
     * 
     * @param int $id               (ID ordine da stampare)
     * @param bool $ristampa        (Ristampa)
     * @return bool                 ($ret=true se stampa ok o niente da stampare)
     */
    function stampaOrdine($id, $ristampa) {

        $lang = 'ita';
        $ret = false;
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
                if ($rigaOrd->alimento_id > 0) {
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
        }

        //Array biglietti da stampare
        //Per ogni stampante è inviata una stampa
        for($s=0; $s<count($arrStmp); $s++) {

            $esc = new EscPos("it",858,"àèìòù","\x7B\x7D\x7E\x7C\x60\xD5");	// initialize and select country, codepage and extra char trasformer string
            $esc->align("c");			// central align
            $esc->font(false,true,false,false,true);	// small, bold, tall and large font
            $esc->text("SS.ANNA e GIOACCHINO");
            $esc->text("--------------------");
            $esc->font(false,true,false,true,true);
            $esc->text("Tavolo: $nome_tavolo");
            $esc->font();
            if ($ristampa) $esc->text("RISTAMPA");
            else $esc->text("");
            $esc->text("  Ordine: $seriale, coperti: $n_coperti");
            $esc->align();
            $esc->font();
            $esc->text("-----------------------------------------");

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

                $esc->font(false,false,false,true,false);	// select bold, tall and large font
                if ($numero > 0) {
                    $esc->text("  $numero", false);
                    $esc->tab();
                    $esc->text("$nome   $prezzoTot");
                }
                else {
                    $esc->text(" ANNULLA ", false);
                    $esc->text("$numero ", false);
                    $esc->text("$nome  $prezzoTot");
                }

                for ($j=0; $j<count($arrStmp[$s]["alimenti"][$i][0]['arrVar']); $j++) {

                    $descrizione = $arrStmp[$s]["alimenti"][$i][0]['arrVar'][$j]['descrizione'];
                    $esc->font();
                    $esc->text("    $descrizione");
                }
                
                //Estrazione nome cassiere da cassiere_id del primo alimento della stampa
                if ($i == 0) {
                    $cameriere  = $arrStmp[$s]["alimenti"][$i][0]['cassiere_id'];
                    $cameriere_id = $arrStmp[$s]["alimenti"][$i][0]['cassiere_id'];
                    $cameriere = DataManager::getCassiereDataByCassiereID($cameriere_id);
                    $nome_cameriere = $cameriere['first_name'];
                }

                //Non conteggiato annullamento alimenti
                if ($numero > 0) $voci_comanda += $numero;
            }

            $totale_ordine_print = sprintf("%01.2f",$totale_ordine)." ".$euro;
            $data = Utility::formattaDataOra($timestamp);

            $esc->font();
            $esc->text("-----------------------------------------");
            $esc->font(false,true,false,false,true);
            $esc->text("  Totale: $totale_ordine_print");
            $esc->font();
            $esc->text("-----------------------------------------");
            $esc->text("  Voci in comanda: ".$voci_comanda."     Stampa ".($s+1)."/".count($arrStmp));
            $esc->text("  Stampante: $nome_stamp");
            $esc->text("  Cameriere: $nome_cameriere");
            $esc->text("  Data: $data");


            $esc->cutCom();
            $to_printer=$esc->out();

            $ret = PosPrint::comm_print($ip_address, $to_printer);
        }
        
        //Nessun alimento da stampare
        if (count($arrStmp) == 0) $ret = true;

        return $ret;
    }
?>
