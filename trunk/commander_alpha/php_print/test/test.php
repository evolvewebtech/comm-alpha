<?
require_once dirname(__FILE__).'/PosPrint.php';
require_once dirname(__FILE__).'/EscPos.php';
require_once dirname(__FILE__).'/../../manager/DataManager2.php';

$ip_address = "192.168.1.101";
//$out = chr(27)."M".chr(48).chr(29).chr(33)."0"."Prova".chr(10);

$id = intval(6);
$arr = array();
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
            $variante = array();
            $variante[0] = $rigaOrd->getVariante($j)->descrizione;
            //$variante[1] = $rigaOrd->getVariante($j)->prezzo;
            $arrVar[$j] = $variante;
        }

        //Riga_ordine
        if ($alimTemp) {
            $riga[0] = $alimTemp->nome;
            $riga[1] = $rigaOrd->numero;
            $riga[2] = $rigaOrd->prezzo;
            $riga[3] = $rigaOrd->iva;
            $riga[4] = $rigaOrd->cassiere_id;
            $riga[5] = $arrVar;
        }

        $arrRighe[$i] = $riga;
    }

    $arr = $arrRighe;
}
if ($arr){
    echo "<pre>";
    //print_r($arr);
    echo "</pre>";
}

foreach ($arr as $riga_ordine) {
    echo "<pre>";
    //print_r($riga_ordine);
    echo "</pre>";
    foreach ($riga_ordine as $value) {
//        echo "<pre>";
//        print_r($value);
//        echo "</pre>";
    }
}

$str = '';
$strCass = '';

for ($i=0; i<count($arr); $i++) {
    $prezzoTot = doubleval($arr[$i][1]) * doubleval($arr[i][2]);

    $str.='<div class="old-ord-rig">';
    $str.='<div class="num">' + $arr[$i][1] + '</div>';
    $str.='<div class="name">' + $arr[$i][0] + '</div>';
    $str.='<div class="prezzo">' + $prezzoTot + ' \u20ac</div>';
    $str.='</div>';

    for ($j=0; j<count($arr[$i][5]); $j++) {
        $str.='<div class="old-ord-rig-var">';
        $str.='<div class="name">' + $arr[$i][5][$j][0] + '</div>';
        $str.='</div>';
    }

    if ($i == 0) { $strCass = 'Cameriere: ' + $arr[$i][4]; }
}

        //Riepilogo ordine
        echo $str;

        //Cassiere
        echo $strCass;
    



$esc = new EscPos("it",858,"àèìòù","\x7B\x7D\x7E\x7C\x60\xD5");	// initialize and select country, codepage and extra char trasformer string
$esc->align("c");			// central align
$esc->font(false,true,false,true,true);	// select bold, tall and large font
$esc->text("S.ANNA e GIOACCHINO");
$esc->text("-------------------");
$esc->imageFromFile("grupposanna.jpeg");
$esc->font();
$esc->align();				// left align
$esc->line("Tavolo: 1");
$esc->line("Sala: 2");
$esc->line("ALIMENTO: patate");
$esc->text("-------------------");
$esc->cutCom();				// 30 spaces and paper cut
$to_printer=$esc->out();

echo "<br />".$to_printer;
//echo PosPrint::comm_print($ip_address, $to_printer);
?>
