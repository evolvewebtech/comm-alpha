<?php
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();

    $lang = 'ita';
?>
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>

<link rel="stylesheet" type="text/css" href="media/css/jquery.jqplot.css" />
<!-- jqPlot -->
<!--[if lt IE 9]>
    <script language="javascript" type="text/javascript" src="media/js/excanvas.js"></script>
<![endif]-->
<script language="javascript" type="text/javascript" src="media/js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.barRenderer.min.js"></script>

<!-- tooltip -->
<script type="text/javascript" src="media/js/jquery.betterTooltip.js"></script>

<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

<!-- CSS -->
<style type="text/css">
/*-----------------------------------------------------------------------------------------------*/
/*                                         TOOLTIP STYLES                                        */
/*-----------------------------------------------------------------------------------------------*/

.tTip {
    width: 200px;
    cursor: pointer;
    color: #666;
    font-weight: bold;
    margin-top: 100px;
}
.tip {
    color: #333;
}

#day{
    margin-top: 100px;
    font-weight: bold;
    float: left;
    color:white;
}

.tip {
    width: 212px;
    padding-top: 37px;
    overflow: hidden;
    display: none;
    position: absolute;
    z-index: 500;
    background: transparent url(img/tipTop.png) no-repeat top;}

.tipMid {background: transparent url(img/tipMid.png) repeat-y;
         padding: 0 25px 20px 25px;}
.tipBtm {background: transparent url(img/tipBtm.png) no-repeat bottom;
         height: 32px;}

#cloud1 {
    color: white;
    font-size: 20px;
    float: left;
    margin-right: 30px;
    width: 290px;
}
#cloud2{
    color: white;
    font-size: 20px;
    float: left;
    width: 290px;
}
a{ color: white;
   text-decoration:none;
   cursor: pointer;
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

           $data_cassieri = DataManager::getTuttiCassieri($gestore_id);
           $numero_cassieri = count($data_cassieri);

           
//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
<h1>Business Intelligence
    <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
        Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                 <a style="color:#fff; font-size: 14px;" href="amministrazioneReport.php">
                     <b>Business Intelligence</b></a>
    </small>
</h1>

<?php
    /*
     * prelevo tutti i giorni in cui sono stati effettuati ordini
     * ritorno un array con tutti i time stamp
     */
    $giorni = DataManager::visualizzaGiorni();
    
    /*
     * creo un array con solo i giorni in cui ci sono stati ordini
     */
    $day = array();
    foreach ($giorni as $giorno) {
        $timestamp = $giorno['timestamp'];
        $dateANDtime = explode(' ',$timestamp);
        $date = $dateANDtime[0];
        $time = $dateANDtime[1];
        $giorno['day'] = $date;
        $giorno['time'] = $time;
        $day[] = $date;
    }
    //elimino i duplicati, ogni giorno è presente una sola volta
    $day = array_unique($day);


    //numero totale giorni
    $nume_giorni = count($day);


    //creo un array formattando la data: gg Mese yyyy
    $new_day = array();
    foreach ($day as $key=>$value) {
        $new_day[]=Utility::displayDate($value,$lang);
    }

?>

<?php
    /*
    //tutti gli ordini
    $ordini = DataManager::getAllOrdiniChiusi();
    //numero ordini
    $n_ordini = count($ordini);

    //nummero totale camerieri
    $numero_cassieri;

    //
    foreach ($ordini as $ordine){

        $new_ordine = DataManager::getOrdineByOrdineID($ordine['ordine_id']);
        echo '<hr />ORDINE: '.$new_ordine['id'];
        $righe_ordine = DataManager::getRigheOrdineByOrdineID($new_ordine['id']);

        //prendo il cassiere id dalla prima riga (tanto sono tutti uguali)
        //id cassiere
        $id_cassiere = $righe_ordine[0]['cassiere_id'];
        echo '<pre style="color:white;">';
        print_r($righe_ordine);
        echo "</pre>";
        print_r($id_cassiere);

        //dovrei aumentare a ogni cassiere il numero di ordini
        //devo contare il numero di ordini per ogni cassiere

        //per ogni cassiere vedo anche righe ordine compare l'id
    }

    $ordini2 = DataManager2::getAllOrdiniAsObjects();

    echo 'ordini2:<pre style="color:white;">';
    print_r($ordini2);
    echo "</pre>";
     *
     * 
     */
    /*
     * grafico ordini per cameriere
     * elenco ordini per cameriere in media
     */

    /*
     * grafico ordini per giorno
     * elenco ordini per giorno in media
     *
     */

    /*
     * grafico ordini all'ora
     * ordini per ora in media
     *
     */

    /*
     * grafico alimenti per ordine
     * alimenti in media
     *
     */

    /*
     * grafico menu fissi per ordine
     * menu fissi per ordine in media
     */

    /*
     * grafico numero totali menu fissi
     *
     */

    /*
     * menu fissi per giorno
     *
     */

    /*
     * buoni utilizzati per giorno
     * numero totale buoni
     */

    /*
     * per ogni alimento visualizzare un grafico
     */

?>

<script type="text/javascript">
    $(document).ready(function(){

           var giorni = new Array();
           <? //foreach ($new_day as $key=>$value) { ?>
              //      giorni.push("<?=$value?>");
           <? //} ?>

           //tooltip
           $('.tTip').betterTooltip({speed: 150, delay: 300});

           //display days
           $('#cloud2').click(function () {
               $("#cloud2").fadeToggle("fast", function () {
                   $.each(giorni, function(key, value){
                       var gg = value.split(" ");
                       gg = gg[0]+"-"+gg[1]+"-"+gg[2];
                       $("#day").append('<a class="giorno" href="amministrazioneSingleReport.php?gg='+gg+'">'+value+'</a><br />');
                    });
                });
            });

            var line2 = [[giorni[0],2],[giorni[1],4]];

            var line1 = [['Cup Holder Pinion Bob', 7], ['Generic Fog Lamp', 9], ['HDTV Receiver', 15],
              ['8 Track Control Module', 12], [' Sludge Pump Fourier Modulator', 3],
              ['Transcender/Spice Rack', 6], ['Hair Spray Danger Indicator', 18]];

            var plot1 = $.jqplot('chartdiv', [line2], {
                title: 'Ordini per giorno',
                series:[{renderer:$.jqplot.BarRenderer}],
                axesDefaults: {
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                    tickOptions: {
                      angle: -30,
                      fontSize: '11pt'
                    }
                },
                axes: {
                  xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                  }
                }
            });
            
/*
          var line1 = [['Cup Holder Pinion Bob', 7], ['Generic Fog Lamp', 9], ['HDTV Receiver', 15],
          ['8 Track Control Module', 12], [' Sludge Pump Fourier Modulator', 3],
          ['Transcender/Spice Rack', 6], ['Hair Spray Danger Indicator', 18]];

          var plot1 = $.jqplot('chart1', [line1], {
            title: 'Concern vs. Occurrance',
            series:[{renderer:$.jqplot.BarRenderer}],
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                tickOptions: {
                  angle: -30,
                  fontSize: '10pt'
                }
            },
            axes: {
              xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer
              }
            }
          });
*/



    });
</script>

<div class="tTip" id="cloud1" title="In questa sezione sono disponibili tutte le
     statistiche che riguardano l'intero periodo di attivit&agrave; fino ad oggi">STATISTICHE COMPLESSIVE</div>
<div class="tTip" id="cloud2" title="Qui &egrave; possibile selezionare un singolo
     giorno di attivit&agrave; e valutarne i risultati.">STATISTICHE GIORNALIERE</div>
<div id="day"></div>
<div style="clear:both;"></div>

        <!--
        <div id="chartdiv" style="height:400px;width:600px; "></div>
        -->

        <!-- footer -->
        <? include_once 'footer.php'; ?>
</div><!-- end content -->

<!-- DEBUG -->
<div id="debug" style="width: 920px;float:left; margin-top: 30px;color:white; font-size: 10px;">DEBUG:</div>
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