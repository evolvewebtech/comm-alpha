<?php
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $objSession->Impress();

    header('Content-Type: text/html; charset=utf-8');
    $lang = 'ita';
?>
<!-- jq -->
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="media/js/jquery-ui.min.js"></script>
<script src="media/js/jquery.validate.min.js"></script>

<!-- TODO
sisstemare metodo getTotaleAlimentoLastWeek
-->


<!-- End additional plugins -->

<!-- jq plot css -->
<link rel="stylesheet" type="text/css" href="media/css/jquery.jqplot.min.css" />

<!-- timepicker -->
<script type="text/javascript" src="media/js/timepicker.js"></script>

<!-- lib -->
<script type="text/javascript" src="media/js/functions.js"></script>

<!-- jq -->
<link rel="stylesheet" type="text/css" href="media/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="jquerymobile/css/jquery.mobile-1.0.1.min.css"/>

<!-- lib -->
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

<style type="text/css">
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default{
        margin-top:0px;
    }
</style>

<!-- jq plot-->
<script type="text/javascript" src="media/js/jquery.jqplot.min.js"></script>
<!--<script type="text/javascript" src="media/js/syntaxhighlighter/shCore.min.js"></script>
<script type="text/javascript" src="media/js/syntaxhighlighter/shBrushJScript.min.js"></script>
<script type="text/javascript" src="media/js/syntaxhighlighter/shBrushXml.min.js"></script>-->

<!-- Additional plugins go here -->
<script type="text/javascript" src="media/js/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="media/js/plugins/jqplot.pointLabels.min.js"></script>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="media/js/excanvas.min.js"></script><![endif]-->

<div id="content">
<?php
if($objSession->IsLoggedIn()){
    $objUser = $objSession->GetUserObject();
    $gestore = $objUser[0];
    if(get_class($gestore) == 'Gestore') {

       $gestore_id = $gestore->id;
       $utente_registrato_id = $gestore->utente_registrato_id;

       //$totali = DataManager::getTotaleLastWeek();
       //06/03/2012 00:00
       $end_timestamp = date ("m/d/Y H:i");
       $start_timestamp = date("m/d/Y H:i", mktime(0, 0, 0,date("m"),date("d")-7,date("Y")));
//     var_dump($start_timestamp);
//     var_dump($end_timestamp);
       $totali = DataManager::getTotaliAlimentiConsumati($start_timestamp, $end_timestamp);
       if (!$totali){
           $totali = array();
       }
//       echo "<pre>";
//       var_dump($totali);
//       echo "</pre>";
       $cassieri = $gestore->getallCassiere();

?>
<h1 style="margin-bottom: 20px;">Statistiche<small style="color:#fff;text-align: right; font-size: 12px; float: right;">Sei qui:
    <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
    <a style="color:#fff; font-size: 14px;" href="amministrazioneReport.php">
    <b>Statistiche</b></a></small>
</h1>
<script type="text/javascript">
    $(document).ready(function(){

        /*
         * definisco i dialoghi
         */
        var $dialogERR = $( "#dialogERR" ).dialog({
                position: 'center',
                autoOpen: false,
                modal: true,
                buttons: {
                        Chiudi: function() {
                                $( this ).dialog( "close" );
                        }
                },
                open: function() {
                },
                close: function() {
                }
        });

        var $dialogOK = $( "#dialogOK" ).dialog({
                position: 'center',
                autoOpen: false,
                modal: true,
                buttons: {
                        Chiudi: function() {
                                $( this ).dialog( "close" );
                        }
                },
                open: function() {
                },
                close: function() {
                }
        });


        /*
         * toggle per la visualizzazione del form di ricerca
         */
         $("#ricerca-ordine").click(function () {

             $('button').css('background-color','buttonface');
             $('button').css('color','black');

            $("#ricerca-ordine-form").slideToggle("slow");
         });


        /*
         * toggle per la visualizzazione del form di ricerca per cameriere
         */
         $("#ricerca-ordine-cameriere").click(function () {
            $("#ricerca-cameriere-form").slideToggle("slow");
         });


        /*
         * ricerca istantanea delle comande
         * 
         * dato il seriale dell'ordine
         * o dato il numero/nome del tavolo
         */
        var runningRequest = false; //richieta inviata
        var request;


        //rilevo la pressione dei tasti
        $('input#q').keyup(function(e){
            e.preventDefault();
            var $q = $(this);

            if($q.val() == ''){
                $('div#results').html('');
                return false;
            }

            //Abort della richiesta aperta per velocizzare
            if(runningRequest){
                request.abort();
            }

            runningRequest=true;
            request = $.getJSON('search.php?search=ordine',{
                q:$q.val()
            },function(data){
                    if (data.err=='E002'){
                        $('#code-err').html('Sessione scaduta o login non valido.');
                        $dialogERR.dialog("open");
                    } else if (data.err=='E001'){
                        $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
                        $dialogERR.dialog("open");
                    } else {
                        showResults(data,'div#results');
                        runningRequest=false;
                    }
            });

            $('form').submit(function(e){
                e.preventDefault();
            });

        });


        //rilevo la pressione dei tasti
        $('input#q2').keyup(function(e){
            e.preventDefault();
            var $q2 = $(this);

            if($q2.val() == ''){
                $('div#results').html('');
                return false;
            }

            //Abort della richiesta aperta per velocizzare
            if(runningRequest){
                request.abort();
            }

            runningRequest=true;
            request = $.getJSON('search.php?search=tavolo',{
                q2:$q2.val()
            },function(data){
                    if (data.err=='E002'){
                        $('#code-err').html('Sessione scaduta o login non valido.');
                        $dialogERR.dialog("open");
                    } else if (data.err=='E001'){
                        $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
                        $dialogERR.dialog("open");
                    }else{
                        showResults(data,'div#results');
                        runningRequest=false;
                    } 
                });

            $('form').submit(function(e){
                e.preventDefault();
            });

        });

        $('#inizio_ordini').datetimepicker({
            onClose: function(dateText, inst) {
                var endDateTextBox = $('#fine_ordini');
                if (endDateTextBox.val() != '') {
                    var testStartDate = new Date(dateText);
                    var testEndDate = new Date(endDateTextBox.val());
                    if (testStartDate > testEndDate)
                        endDateTextBox.val(dateText);
                }
                else {
                    endDateTextBox.val(dateText);
                }
            },
            onSelect: function (selectedDateTime){
                var start = $(this).datetimepicker('getDate');
                $('#fine_ordini').datetimepicker('option', 'minDate', new Date(start.getTime()));
            }
        });
        $('#fine_ordini').datetimepicker({
            onClose: function(dateText, inst) {
                var startDateTextBox = $('#inizio_ordini');
                if (startDateTextBox.val() != '') {
                    var testStartDate = new Date(startDateTextBox.val());
                    var testEndDate = new Date(dateText);
                    if (testStartDate > testEndDate)
                        startDateTextBox.val(dateText);                        
                }
                else {
                    startDateTextBox.val(dateText);
                }
            },
            onSelect: function (selectedDateTime){
                var end = $(this).datetimepicker('getDate');
                $('#inizio_ordini').datetimepicker('option', 'maxDate', new Date(end.getTime()) );
            }

        });

        $("#data-ordini-form").validate({
                    rules: {
                        inizio_ordini: {
                            required: true
                        },
                        fine_ordini: {
                            required: true
                        }
                    },
                    messages: {
                        inizio_ordini: {
                            required: ' campo obbligatorio'
                        },
                        fine_ordini: {
                            required: ' campo obbligatorio'
                        }
                    }
        });

        $("#print-graph").live("click", function(){
            $("#report").slideToggle("slow");
        });

        $("#cerca-ordini").live("click", function() {

             $('button').css('background-color','buttonface');
             $('button').css('color','black');
            /*
             * controllo che le date non siano vuote
             */
            if($("#data-ordini-form").valid()){
                /*
                 * prelevo le date di inizio e fine, e visualizzo i risultati
                 * stringa formattata json
                 *
                 *
                 */
                var data_inizio = $('#inizio_ordini').val();
                var data_fine   = $('#fine_ordini').val();

                var data_da = $('#data-da');
                data_da.html(data_inizio);

                var data_al = $('#data-al');
                data_al.html(data_fine);

                var ricercaOrdineForm = 'start_timestamp='+data_inizio+'&'+'end_timestamp='+data_fine+'&'+'cameriere_id='+null;

                console.log(ricercaOrdineForm);
                console.log(data_inizio);
                console.log(data_fine);

                $.ajax({
                    type: "POST",
                    data: ricercaOrdineForm,
                    url: "manager/gestore/report.php",
                    dataType: 'json',
                    cache: false,
                    success: onReportSuccess,
                    error: onError
                });

                $.ajax({
                    type: "POST",
                    data: ricercaOrdineForm,
                    url: "manager/gestore/reportAlimenti.php",
                    dataType: 'json',
                    cache: false,
                    success: onReportAlimentoSuccess,
                    error: onError
                });

            }            
        });


        $(".cameriere-button").live("click", function() {

             $('button').css('background-color','buttonface');
             $('button').css('color','black');

            /*
             * controllo che le date non siano vuote
             */
            if($("#data-ordini-form").valid()){
                /*
                 * prelevo le date di inizio e fine, e visualizzo i risultati
                 * stringa formattata json
                 *
                 */
                var data_inizio = $('#inizio_ordini').val();
                var data_fine   = $('#fine_ordini').val();

                var cameriere_id = $(this).attr("id");
                $(this).css('background-color','green');
                $(this).css('color','white');

                var ricercaOrdineForm = 'start_timestamp='+data_inizio+'&'+'end_timestamp='+data_fine+'&'+'cameriere_id='+cameriere_id;

                console.log('---------------------');
                console.log(cameriere_id);
                console.log(ricercaOrdineForm);
                console.log(data_inizio);
                console.log(data_fine);

                $.ajax({
                    type: "POST",
                    data: ricercaOrdineForm,
                    url: "manager/gestore/report.php",
                    dataType: 'json',
                    cache: false,
                    success: onReportSuccess,
                    error: onError
                });

                $.ajax({
                    type: "POST",
                    data: ricercaOrdineForm,
                    url: "manager/gestore/reportAlimenti.php",
                    dataType: 'json',
                    cache: false,
                    success: onReportAlimentoSuccess,
                    error: onError
                });

            }
        });
        
function onReportSuccess(data) {
   if (data.err=='E002'){
       $('#code-err').html('Sessione scaduta o login non valido.');
       $dialogERR.dialog("open");
   } else if (data.err=='E001'){
       $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
       $dialogERR.dialog("open");
   } else if (data.err=='false'){
       $('#code-err').html('Errore durante la richiesta.');
       $dialogERR.dialog("open");
   } else if(data.err==''){
//       $('#code-ok').html('OK.');
//       $dialogOK.dialog( "open" );
   }else{
       showResults(data, 'div#lista-vecchi-ordini');
   }
}

function onReportAlimentoSuccess(data){
   console.log('++++++++++++');
   console.log(data);
   console.log('++++++++++++');
   if (data.err=='E002'){
       $('#code-err').html('Sessione scaduta o login non valido.');
       $dialogERR.dialog("open");
   } else if (data.err=='E001'){
       $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
       $dialogERR.dialog("open");
   } else if (data.err=='false'){
       $('#code-err').html('Errore durante la richiesta.');
       $dialogERR.dialog("open");
   } else if(data.err==''){
//       $('#code-ok').html('OK.');
//       $dialogOK.dialog( "open" );

        print_graph('chart1',data.s1,data.ticks);

//         if (data.s1=='' | data.ticks==''){
//            $('#chart1').empty();
//         }else{
//             print_graph('chart1',data.s1,data.ticks);
//         }
   }else{

       print_graph('chart1',data.s1,data.ticks);

//         if (data.s1=='' | data.ticks==''){
//            $('#chart1').empty();
//         }else{
//             print_graph('chart1',data.s1,data.ticks);
//         }
   }
}

/*
 * Errore richiesta Ajax
 *
 */
function onError(data, status) {
    alert("Errore Ajax");
    str = '';
    str = str + '<section class="ui-body ui-body-b" style="margin-top: 40px">';
    str = str + '<div style="margin:auto">';
    str = str + 'Nessun ordine trovato per questa data</div>';
    str = str + '</section>';
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
}

});
</script>

<div id="container" style="border:2px solid #fff; border-radius: 3px;">

    <!-- dialogs -->
    <? include_once dirname(__FILE__).'/dialogs.php'; ?>

    <div class="cloud" id="grafico">
        <form id="data-ordini-form" style="background-color:white;">
            <label style="margin-right: 25px;" class="" for="ricerca_ordine">INSERISCI LE DATE DI INIZIO E FINE</label>
            <input type="text" name="inizio_ordini" id="inizio_ordini" value="">
            <input type="text" name="fine_ordini" id="fine_ordini" value="">
        </form>
        <button type="submit" id="cerca-ordini" class="cec-button">cerca</button>
        <button type="submit" id="print-graph" class="cec-button">grafico</button>
    </div>
    <div id="report" class="lista_ordini" style="color:#999999;">

        <span>Quntit&agrave; consumate dal <span id="data-da"><?=$start_timestamp?></span> al <span id="data-al"><?=$end_timestamp?></span></span>
<!--        <div><span>Hai premuto su: </span><span id="info1">ancora niente...</span></div>-->
        <div id="chart1" style="margin-top:20px; margin-left:20px; width:800px; height:400px;"></div>     
       <!-- week graph -->
       <script type="text/javascript">$(document).ready(function(){
               $.jqplot.config.enablePlugins = true;

               var s1    = new Array();
               var ticks = new Array();
               <? if ($totali){
                    foreach ($totali as $totale) { ?>
                        ticks.push("<?=$totale['nome']?>");
                        s1.push("<?=intval($totale['quantita_consumata'])?>");
               <?   }} ?>
               console.log(s1);
               console.log(ticks);
                //var s1 = [2, 6, 7, 10];
                //var ticks = ['a', 'b', 'c', 'd'];

                plot1 = $.jqplot('chart1', [s1], {
                    // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
                    animate: !$.jqplot.use_excanvas,
                    title: 'Consumi',
                    seriesDefaults:{
                        renderer:$.jqplot.BarRenderer,
                        pointLabels: { show: true }
                    },
                    axesDefaults: {
                        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                        tickOptions: {
                          angle: -30,
                          fontSize: '10pt'
                        }
                    },
                    axes: {
                        xaxis: {
                            renderer: $.jqplot.CategoryAxisRenderer,
                            ticks: ticks                            
                        }
                    },
                    highlighter: { show: false }
                });

                /*
                 * visualizza le coordinate al click
                 */
//                $('#chart1').bind('jqplotDataClick',
//                    function (ev, seriesIndex, pointIndex, data) {
//                        $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
//                    }
//                );
            });
        </script>
    </div>

    <!--RICERCA -->
    <div class="cloud" id="filtro-cameriere">
        <p><span>Filtra i risultati per cameriere</span>
        <button type="submit" id="0" class="cameriere-button cec-button">tutti</button>
        <?foreach ($cassieri as $cassiere) {?>
                <button type="submit" id="<?=$cassiere->id?>" class="cameriere-button cec-button"><?=$cassiere->username;?></button>
        <?}?></p>
    </div>
    <div id="lista-vecchi-ordini" class="lista_ordini"></div>

    <div class="cloud" id="ricerca-ordine" title="Ricerca un ordine tra quelli disponibili.">RICERCA UN ORDINE</div>
    <form id="ricerca-ordine-form" method="get" action="" style="display:none;background-color:white;">
        <fieldset>
            <p>Inserisci nei campi sottostanti il numero del tavolo o il numero dell'ordine per visualizzarne la comanda.</p>
            <label style="margin-right: 85px;" class="" for="ricerca_ordine">numero ordine</label>
            <input type="text" id="q" name="q" autocomplete="off" />
            <!--<input type="submit" value="Search" />-->
            <br />
            <label style="margin-right: 43px;" class="" for="ricerca_ordine">numero/nome tavolo</label>
            <input type="text" id="q2" name="q2" autocomplete="off" />
            <!--<input type="submit" value="Search" />-->
        </fieldset>
    </form>
    <div id="results"></div>
</div><!-- end container -->
        <!-- footer -->
        <? include_once dirname(__FILE__).'/footer.php'; ?>
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
<script type="text/javascript" src="media/js/example.js"></script>