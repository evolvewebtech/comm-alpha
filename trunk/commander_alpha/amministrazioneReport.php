<?php
    require_once dirname(__FILE__).'/manager/Utility.php';
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $lang = 'ita';
?>
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="media/js/jquery-ui.min.js"></script>

<link rel="stylesheet" type="text/css" href="media/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="jquerymobile/css/jquery.mobile-1.0.1.min.css"/>

<!-- main -->
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

<!-- CSS -->
<style type="text/css">
.cloud {
    background-color: #fff;
    border-radius: 5px 5px 5px 5px;
    color: black;
    padding: 10px;
    text-decoration: none;
    border: 1px solid #FFF;
    margin: 10px;
}
.cloud:hover{
    cursor: pointer;
}
a {
   color: white;
   text-decoration:none;
}
a:hover{
   cursor: pointer;
}
.ui-datepicker-trigger{
    height: 30px;
    margin: 0px 0px -10px 10px;
    padding: 0px;
}

li.ordini:hover{
    cursor: pointer;
}
/* -----------
 * -----------
 *
 * form
 */
#ricerca-ordine-form{
    border-radius: 5px 5px 5px 5px;
    color: black;
    padding: 10px;
    text-decoration: none;
    border: 1px solid #FFF;
    margin: 0px 10px 10px 10px;
}
#ricerca-ordine-form fieldset{
    border: none;
}
form{
      margin:15px;
      padding:5px;
      border-bottom:1px solid #ddd;
}
form input[type=submit]{
    display:none;
}
div#results{
    margin-bottom: 10px;
}
div#results div.result{
    padding:10px 0px;
    margin:10px 0px 10px;
}
.ui-listview-inset .ui-li {
    border-right-width: 0px;
    border-left-width: 0px;
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
<h1 style="margin-bottom: 20px;">Statistiche<small style="color:#fff;text-align: right; font-size: 12px; float: right;">Sei qui:
    <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
    <a style="color:#fff; font-size: 14px;" href="amministrazioneReport.php">
    <b>Statistiche</b></a></small>
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
<script type="text/javascript">
    $(document).ready(function(){

        /*
         * toggle per la visualizzazione del form di ricerca
         */
         $("#ricerca-ordine").click(function () {
            $("#ricerca-ordine-form").slideToggle("slow");
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
                showResults(data,$q.val());
                runningRequest=false;
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
                showResults(data,$q2.val());
                runningRequest=false;
            });

            $('form').submit(function(e){
                e.preventDefault();
            });

        });


        //datepicker
        $.datepicker.setDefaults($.datepicker.regional['it']);
        $( "#cerca_ordine" ).datepicker({
                showOn: "button",
                buttonImage: "media/css/images/datepicker.jpeg",
                dateFormat: 'd MM, y',
                buttonImageOnly: true,
                onSelect: function(){
                    var gg = $("#cerca_ordine").datepicker('getDate').getDate();
                    var mm = ($("#cerca_ordine").datepicker('getDate').getMonth()) + 1;
                    var aaaa = $("#cerca_ordine").datepicker('getDate').getFullYear();
                    $('#debug').append('<br />data: '+gg+' - '+mm+' - '+aaaa);

                    var data = aaaa+"-"+zeroPad(mm,2)+"-"+zeroPad(gg,2);
                    data = JSON.stringify(data);
                    dataSel = data;
                    $('#debug').append('<br />data2: '+data);

                    $.ajax({
                        type : "POST",
                        data: data,
//                        url: "jquerymobile/reportListaOrdini.php",
                        url: "jquerymobile/lista_ordini.php",
                        dataType: 'json',
                        cache: false,
                        success: onListaOrdiniSuccess,
                        error: onError
                    });
                }
             });

/*
 * creo l'HTML per la visualizzazione dei risultati
 */
function showResults(data, highlight){

    var resultHtml = '';
    var totOrdini = 0;

    resultHtml+='<ul style="width: 880px;margin:auto;" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-icon="star" data-inset="true" data-role="listview">';
    resultHtml+='<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top ui-btn-up-undefined" data-role="list-divider" role="heading">Ordini trovati</li>';

    $.each(data, function(i,item){

        var new_id = 'ord-ser-';
        new_id = new_id + item.seriale + '&' + item.timestamp + '&' + item.tavolo_id;
        new_id = new_id + '&' + item.n_coperti + '&' + item.totale;

        resultHtml+='<li class="ordini ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-inline="false" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c">';
        resultHtml+='<div class="ui-btn-inner ui-li"><div class="ui-btn-text">';
        resultHtml+='<a class="ui-link-inherit ristampa-ordine" id="'+new_id+'" href="amministrazioneOrdine.php?id='+item.id+'">';
        resultHtml+='<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-s">' + item.seriale + '</div>';
        resultHtml+='<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-d">' + item.timestamp + '</div>';
        resultHtml+='<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-t">Tavolo ' + item.tavolo_id + '</div>';
        resultHtml+='<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-c">Coperti ' + item.n_coperti + '</div>';
        resultHtml+='<span style="border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px">Totale '+item.totale+' &#8364;</span>';
        resultHtml+='</a>';
        resultHtml+='</div></div>';
        resultHtml+='</li>';

        totOrdini = totOrdini + item.totale;

    });

    resultHtml+='<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-corner-bottom ui-btn-up-a" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="a">';
    resultHtml+='<div class="ui-btn-inner ui-li"><div class="ui-btn-text" style="height: 36px">';
    resultHtml+='<span style="margin-right: 205px; border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; margin-right: 180px; font-size: 14px">Totale contanti incassati: '+totOrdini+' &#8364;</span>';
    resultHtml+='<span style="border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; font-size: 14px">Totale ordini: '+totOrdini+' &#8364;</span>';
    resultHtml+='</div></div>';
    resultHtml+='</li>';

    resultHtml+="</ul>";


    $('div#results').html(resultHtml);
}

/*
 * aggiungo uno zero davanti se il numero ha una solo cifra
 */
function zeroPad(num,count) {
    var numZeropad = num + '';
    while(numZeropad.length < count) {
    numZeropad = "0" + numZeropad;
    }
    return numZeropad;
}
/*
 * Richiesta Ajax completata con successo
 *
 */
function onListaOrdiniSuccess(data, status) {
    //alert("Successo lettura da database con Ajax!")
    var totOrdini = 0;
    str = '';

    //eliminazione carattere '"'
    dataSel = dataSel.replace('"','');
    dataSel = dataSel.replace('"','');

    if (data.length > 0) {

    str += '<ul style="width: 880px;margin:auto;" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-icon="star" data-inset="true" data-role="listview">';
    str += '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top ui-btn-up-undefined" data-role="list-divider" role="heading">Ordini del '+dataSel+'</li>';

    for (i=0; i<data.length; i++) {
    var new_id = 'ord-ser-';
    new_id = new_id + data[i].seriale + '&' + data[i].timestamp + '&' + data[i].tavolo_id;
    new_id = new_id + '&' + data[i].n_coperti + '&' + data[i].totale;

    str += '<li class="ordini ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-inline="false" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c">';
    str += '<div class="ui-btn-inner ui-li"><div class="ui-btn-text">';
    str += '<a class="ui-link-inherit ristampa-ordine" id="'+new_id+'" href="amministrazioneOrdine.php?id='+data[i].id+'">';
    str += '<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-d">' + data[i].timestamp + '</div>';
    str += '<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-t">Tavolo ' + data[i].tavolo_id + '</div>';
    str += '<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-c">Coperti ' + data[i].n_coperti + '</div>';
    str += '<span style="border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px">Totale '+data[i].totale+' &#8364;</span>';
    str += '</a>';
    str += '</div></div>';
    str += '</li>';

    totOrdini = totOrdini + data[i].totale;
    }

    str += '<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-corner-bottom ui-btn-up-a" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="a">';
    str += '<div class="ui-btn-inner ui-li"><div class="ui-btn-text" style="height: 36px">';
    str += '<span style="margin-right: 205px; border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; margin-right: 180px; font-size: 14px">Totale contanti incassati: '+totOrdini+' &#8364;</span>';
    str += '<span style="border-radius:2px; font-size: 18px;" class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; font-size: 14px">Totale ordini: '+totOrdini+' &#8364;</span>';
    str += '</div></div>';
    str += '</li>';

    str += "</ul>";
    }
    else {
        str += '<div style="margin:auto">';
        str += 'Nessun ordine trovato</div>';
    }
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
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

    <div class="cloud">STORICO ORDINI <input style="display:none;" type="text" id="cerca_ordine"></div>
    <div id="lista-vecchi-ordini" class="lista_ordini"></div>

    <div class="cloud" title="Statistiche per l'intero periodo di attivit&agrave;.">STATISTICHE COMPLESSIVE</div>
    <div class="cloud" title="Statistiche per un singolo giorno di attivit&agrave;.">STATISTICHE GIORNALIERE</div>
    <div class="cloud" id="ricerca-ordine" title="Ricerca un ordine tra quelli disponibili.">RICERCA UN ORDINE</div>
    <form id="ricerca-ordine-form" method="get" action="" style="display:none;background-color:white;">
        <fieldset>
            <p>Inserisci nei campi sottostanti il numero del tavolo o il numero dell'ordine per visualizzarne la comanda.</p>
            <label style="margin-right: 85px;" class="" for="ricerca_ordine">numero ordine</label>
            <input type="text" id="q" name="q" autocomplete="off" />
            <input type="submit" value="Search" />
            <br />
            <label style="margin-right: 43px;" class="" for="ricerca_ordine">numero/nome tavolo</label>
            <input type="text" id="q2" name="q2" autocomplete="off" />
            <input type="submit" value="Search" />
        </fieldset>
    </form>
    <div id="results"></div>
</div><!-- end container -->
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
