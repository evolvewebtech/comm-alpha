<?
require_once dirname(__FILE__).'/PosPrint.php';
require_once dirname(__FILE__).'/EscPos.php';
require_once dirname(__FILE__).'/../../manager/DataManager2.php';
require_once dirname(__FILE__).'/../../manager/DataManager.php';
require_once dirname(__FILE__).'/../../manager/Utility.php';


$nome_stampante = "cucina";
$ip_address = "192.168.1.101";
//$out = chr(27)."M".chr(48).chr(29).chr(33)."0"."Prova".chr(10);

$id = intval(6);
//Array da passare con JSON
$arr = array();
if ($id > 0) {
    $ordine = DataManager2::getOrdineAsObject($id);

//    echo "<pre>";
//    print_r($ordine);
//    echo "</pre>";

    /*
     * prelevo alucune info sull'ordine
     */
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

        $arrRighe[$i] = $riga;
    }

    $arr = $arrRighe;
}

$str = '';
$strCass = '';

/*
 * inizio preparazione biglietto per la stampa del
 * riepilogo ordine
 * 
 */
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
$esc->text("  Ordine: $seriale");

$euro = chr(213);
$totale_ordine = 0;
$righe_ordine = count($arr);

for ($i=0; $i<count($arr); $i++) {
    $numero = floatval($arr[$i]['numero']);
    $prezzo = floatval($arr[$i]['prezzo']);
    (float) $prezzoTot = ( $numero * $prezzo );

    $totale_ordine += $prezzoTot;

    $nome = $arr[$i]['nome'];

    $str.='<div class="old-ord-rig">';
    $str.='<div class="num">'.$arr[$i]['numero'].'</div>';
    $str.='<div class="name">'.$arr[$i]['nome'].'</div>';
    $str.='<div class="prezzo">'.$prezzoTot.' &#8364; </div>';
    $str.='</div>';

    $prezzoTot = sprintf("%01.2f",$prezzoTot)." ".$euro;
    $esc->text("  $numero $nome $prezzoTot");  //$esc->printEuro();

    for ($j=0; $j<count($arr[$i]['arrVar']); $j++) {

        $descrizione = $arr[$i]['arrVar'][$j]['descrizione'];
        $str.='<div class="old-ord-rig-var">';
        $str.='<div class="name">'.$arr[$i]['arrVar'][$j]['descrizione'].'</div>';
        $str.='</div>';
        $esc->text("    $descrizione");
    }

    if ($i == 0) {
        $cameriere  = $arr[$i]['cassiere_id'];
        $strCass = '  Cameriere: '.$arr[$i]['cassiere_id'];
        $cameriere_id = $arr[$i]['cassiere_id'];
        $cameriere = DataManager::getCassiereDataByCassiereID($cameriere_id);
        $nome_cameriere = $cameriere['first_name'];
    }
}
    $totale_ordine = sprintf("%01.2f",$totale_ordine)." ".$euro;
    $data = Utility::formattaData($timestamp);

    $esc->text("---------------------------------------");
    $esc->font(false,true,false,true,true);
    $esc->text("  Totale: $totale_ordine");
    $esc->font();
    $esc->text("---------------------------------------");
    $esc->text("  Voci in comanda: ".$righe_ordine);
    $esc->text("  Cameriere: $nome_cameriere");
    $esc->text("  Data: $data");


    $esc->cutCom();				// 30 spaces and paper cut
    $to_printer=$esc->out();


    //echo $totale_ordine."<br />".$to_printer;
    echo PosPrint::comm_print($ip_address, $to_printer);

?>
