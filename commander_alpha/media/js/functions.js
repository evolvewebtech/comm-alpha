/*
 * creo l'HTML per la visualizzazione dei risultati
 */
function showResults(data, selettore){

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
        resultHtml+='<div style="float: left; margin: 0px 20px 0px 0px;" class="ord-num-t">Tavolo numero: ' + item.tavolo.numero + '<br />Tavolo nome: '+item.tavolo.nome+'</div>';
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


    $(selettore).html(resultHtml);
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
    var totOrdini = 0;
    str = '';

    //eliminazione carattere '"'
    dataSel = dataSel.replace('"','');
    dataSel = dataSel.replace('"','');

    console.log(data);

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

        } else {
            str += '<div style="margin:auto">';
            str += 'Nessun ordine trovato</div>';
    }
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
}

function print_graph(selettore, s1, ticks){

    $('#'+selettore).empty();
    $('.jqplot-axis').empty();
    $('.jqplot-xaxis').empty();
//    $('#'+selettore).empty();

    console.log('s1:');
    console.log(s1);
    console.log('ticks:');
    console.log(ticks);

    $.jqplot.config.enablePlugins = true;

    plot1 = $.jqplot(selettore, [s1], {
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
              textColor: '#ffffff',
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
}