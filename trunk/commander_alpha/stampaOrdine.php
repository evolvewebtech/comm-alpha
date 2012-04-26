<?php
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';

    require_once dirname(__FILE__).'/php_print/test/PosPrint.php';
    require_once dirname(__FILE__).'/php_print/test/EscPos.php';
    require_once dirname(__FILE__).'/manager/DataManager2.php';
    require_once dirname(__FILE__).'/manager/DataManager.php';

    $objSession = new HTTPSession();
    $lang = 'ita';

    $nome_stampante = "amministrazione";
    $ip_address = "192.168.1.101";   //sarà la stampante del gestore

    //$id = $_GET['id'];
    $id = intval(6);
?>
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquerymobile/css/jquery.mobile-1.0.1.min.css"/>

<!-- main -->
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

<!-- CSS -->
<style type="text/css">
.cloud {
    cursor: pointer;
    background-color: #fff;
    border-radius: 5px 5px 5px 5px;
    color: black;
    padding: 10px;
    text-decoration: none;
    border: 1px solid #FFF;
    margin: 10px;
}
a {
   color: white;
   text-decoration:none;
   cursor: pointer;
}
.old-ord {
    width: 400px;
    margin: auto;
}

.old-ord-rig {
    margin: 0px;
    height: 20px;
    font-family: Helvetica,Arial,sans-serif;
    font-weight: bold;
}

.old-ord-rig .num{
  margin: 0px;
  margin-left: 20px;
  float: left;
  font-size: 150%;
  position:relative;
  height: 100%;
  vertical-align: middle;
}

.old-ord-rig .name{
  margin: 0px;
  margin-left: 20px;
  float: left;
  font-size: 150%;
  position:relative;
  height: 100%;
  vertical-align: middle;
}

.old-ord-rig .prezzo{
  margin: 0px;
  margin-right: 40px;
  float: right;
  font-size: 150%;
  position:relative;
  height: 100%;
  vertical-align: middle;
}
.old-ord-rig-var {
    margin: 0px;
    height: 20px;
}

.old-ord-rig-var .name{
  margin: 0px;
  margin-left: 60px;
  float: left;
  font-size: 1.05em;
  position:relative;
  height: 100%;
  vertical-align: middle;
  /*color: #2F3E46;*/
  color: #888;
}

.old-ord-rig-var .prezzo {
  margin: 0px;
  margin-right: 50px;
  float: right;
  font-size: 1.05em;
  position:relative;
  height: 100%;
  vertical-align: middle;
  /*color: #2F3E46;*/
  color: #888;
}
.comm-li-tot {
    margin: 0px;
    height: 20px;
    font-family: Helvetica,Arial,sans-serif;
    font-weight: bold;
}

.comm-li-tot .name{
  margin: 0px;
  margin-left: 20px;
  float: left;
  font-size: 150%;
  position:relative;
  height: 100%;
  vertical-align: middle;
}

.comm-li-tot .prezzo{
  margin: 0px;
  margin-right: 25px;
  float: right;
  font-size: 150%;
  position:relative;
  height: 100%;
  vertical-align: middle;
}
</style>
<div id="content">

<?php
if($objSession->IsLoggedIn()){
    $objUser = $objSession->GetUserObject();
    $gestore = $objUser[0];
    if(get_class($gestore) == 'Gestore') {

       $gestore_id = $gestore->id;
       $utente_registrato_id = $gestore->utente_registrato_id;
       //echo '<p style="background-color:white">'.$numero_tavolo.'</p>';

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

        $num_stampanti = $alimTemp->getNumberOfStampanti();
        echo $num_stampanti;
        for($j=0; $j<$num_stampanti; $j++) {
            $stampante = $alimTemp->getStampante($j);
            echo "<pre>";
            print_r($stampante);
            echo "</pre>";
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
                "arrStampanti"  => $rigaOrd->stampanti,
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

    $str.='<div class="old-ord-rig">';
    $str.='<div class="num" style="widht: 400px;">'.$arr[$i]['numero'].'</div>';
    $str.='<div class="name" style="widht: 400px;">'.$arr[$i]['nome'].'</div>';
    $str.='<div class="prezzo" style="widht: 400px;">'.$prezzoTot.' &#8364; </div>';
    $str.='</div>';

    $prezzoTot = sprintf("%01.2f",$prezzoTot)." ".$euro;
    $esc->text("  $numero $nome $prezzoTot");  //$esc->printEuro();

    for ($j=0; $j<count($arr[$i]['arrVar']); $j++) {

        $descrizione = $arr[$i]['arrVar'][$j]['descrizione'];
        $str.='<div class="old-ord-rig-var">';
        $str.='<div class="name" style="widht: 400px;">'.$arr[$i]['arrVar'][$j]['descrizione'].'</div>';
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
    $totale_ordine_data = sprintf("%01.2f",$totale_ordine) . " &#8364";
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

    echo $str;
    //PosPrint::comm_print($ip_address, $to_printer);
?>
        <!-- footer -->
        <? include_once 'footer.php'; ?>
</div><!-- end content -->
<?php
        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <br /><a style="color:#fff;" href="logout.php"> <-- LOGIN</a>
            </h4>';
    }
?>