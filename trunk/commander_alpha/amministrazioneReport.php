<?php
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();

    $lang = 'ita';
?>
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>

<!-- jqPlot -->
<!--[if lt IE 9]>
    <script language="javascript" type="text/javascript" src="media/js/excanvas.js"></script>
<![endif]-->
<script language="javascript" type="text/javascript" src="media/js/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="media/css/jquery.jqplot.css" />

<!-- tooltip -->
<script type="text/javascript" src="media/js/jquery.betterTooltip.js"></script>

<!-- CSS -->
<style type="text/css">
    /*
     * foglio di stile per la pagina corrente
     *
     */
    .clearfix{ display: block; height: 0; clear: both; visibility: hidden; }
    h4{ font:300 16px 'Helvetica Neue', Helvetica, Arial, sans-serif;
        line-height:160%; letter-spacing:0.15em; color:#fff;
        text-shadow:1px 1px 0 rgb(0,0,0); }
    p{ font:300 12px 'Lucida Grande', Tahoma, Verdana, sans-serif;
       color:#000;}
    a{ text-decoration:none; }


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

.tipMid {background: transparent url(img/tipMid.png) repeat-y; padding: 0 25px 20px 25px;}
.tipBtm {background: transparent url(img/tipBtm.png) no-repeat bottom; height: 32px;}

#cloud1 {
    color: white;
    font-size: 20px;
    float: left;
    margin-right: 30px;
}
#cloud2{
    color: white;
    font-size: 20px;
    float: left;
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

    //creo un array formattando la data: gg Mese yyyy
    $new_day = array();
    foreach ($day as $key=>$value) {
        $new_day[]=Utility::displayDate($value,$lang);
    }

?>
<script type="text/javascript">
    $(document).ready(function(){

           var giorni = new Array();
           <? foreach ($new_day as $key=>$value) { ?>
                    giorni.push("<?=$value?>");
           <? } ?>

            //tooltip
            $('.tTip').betterTooltip({speed: 150, delay: 300});

            //display days
            $('#cloud2').click(function () {
                $("#cloud2").fadeToggle("fast", function () {
                    $.each(giorni, function(key, value){
                        $("#day").append('<a class="giorno" herf="#">'+value+'</a> - ');
                    });
                });
            });

    });
</script>


<div class="tTip" id="cloud1" title="In questa sezione sono disponibili tutte le
     statistiche che riguardano l'intero periodo di attivit&agrave; fino ad oggi">STATISTICHE COMPLESSIVE</div>
<div class="tTip" id="cloud2" title="Qui &egrave; possibile selezionare un singolo
     giorno di attivit&agrave; e valutarne i risultati.">STATISTICHE GIORNALIERE</div>
<div id="day"></div>
<div style="clear:both;"></div>

<div style="padding:50px;color:white;">
        <?php

        ?>
</div>


        <h4 style="margin-left: 10px; float:left; width: 920px;">
            <a style="color:#fff;" href="logout.php">esci</a> |
            <a style="color:#fff;" href="support.php">supporto</a> |
            <a style="color:#fff;" href="license.php">credit</a>
        </h4>
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