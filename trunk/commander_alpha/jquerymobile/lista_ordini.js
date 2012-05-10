
var dataSel = "";

/*
 * Evento data selezionata in datepicker
 *
 */
$('#ord-datepicker').live('datebox', function(event, payload) {    
    if ( payload.method === 'set' ) {
        
        data = JSON.stringify(payload.value);
        dataSel = data;
        
        $.ajax({
            type : "POST",
            data: data,
            url: "lista_ordini.php",
            dataType: 'json',
            cache: false,
            success: onListaOrdiniSuccess,
            error: onListaOrdiniError
        });
        
    }
});


/*
 * Richiesta Ajax completata con successo
 *
 */
function onListaOrdiniSuccess(data, status) { 
    
    //Verifica se utente loggato
    if ((data['err'] == 'E001') || (data['err'] == 'E002')) {
        //utente non loggato correttamente 
        var str = '';
        if (data['err'] == 'E002') str = 'Utente non autenticato o sessione scaduta';
        else str = 'Non possiedi i permessi per visualizzare questa pagina!';
        document.getElementById('log-err-text').innerHTML = str;
        //apertura pagina avviso
        document.location.href="#diag-log-err";
        $.mobile.changePage( "#diag-log-err", 'none', false, true);
        return
    }
        
    //alert("Successo lettura da database con Ajax!")
    var totOrdini = 0;
    str = '';
    
    //eliminazione carattere '"'
    dataSel = dataSel.replace('"','');
    dataSel = dataSel.replace('"','');
    dataSel = formato_data_ora(dataSel, '-');
    
    if (data['ordini'].length > 0) {
    
    str = str + '<ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-icon="star" data-inset="true" data-role="listview">';
    str = str + '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top ui-btn-up-undefined" data-role="list-divider" role="heading">Ordini del '+dataSel+'</li>';
    
    for (i=0; i<data['ordini'].length; i++) {
    
    var timestamp = formato_data_ora(data['ordini'][i].timestamp, '-');
          
    var new_id = 'ord-ser-';
    new_id = new_id + data['ordini'][i].seriale + '&' + timestamp + '&' + data['ordini'][i].tavolo_id;
    new_id = new_id + '&' + data['ordini'][i].n_coperti + '&' + data['ordini'][i].totale;
    
    str = str + '<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-inline="false" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c">';
    str = str + '<div class="ui-btn-inner ui-li"><div class="ui-btn-text">';
    str = str + '<a class="ui-link-inherit ristampa-ordine" id="' + new_id + '" href="#ristampa-ordine">';
    str = str + '<div class="ord-num-d">' + timestamp + '</div>';
    str = str + '<div class="ord-num-t">Tavolo ' + data['ordini'][i].tavolo_id + '</div>';
    str = str + '<div class="ord-num-c">Coperti ' + data['ordini'][i].n_coperti + '</div>';
    str = str + '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px">Totale '+data['ordini'][i].totale+' €</span>';
    str = str + '</a>';
    str = str + '</div></div>';
    str = str + '</li>';
    
    totOrdini = totOrdini + data['ordini'][i].totale;
    }
    
    str = str + '<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-corner-bottom ui-btn-up-a" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="a">';
    str = str + '<div class="ui-btn-inner ui-li"><div class="ui-btn-text" style="height: 36px">';
    str = str + '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; margin-right: 180px; font-size: 14px">Totale contanti incassati: '+totOrdini+' €</span>';
    str = str + '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px; font-size: 14px">Totale ordini: '+totOrdini+' €</span>';
    str = str + '</div></div>';
    str = str + '</li>';
    
    str = str + "</ul>";
    }
    else {
        str = str + '<div style="margin:auto">';
        str = str + 'Nessun ordine trovato</div>';
    }
    
    
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
}


/*
 * Errore richiesta Ajax
 *
 */
function onListaOrdiniError(data, status) {
    //alert("Errore Ajax");
    str = '';
    str = str + '<section class="ui-body ui-body-b" style="margin-top: 40px">';
    str = str + '<div style="margin:auto">';
    str = str + 'Nessun ordine trovato per questa data</div>';
    str = str + '</section>';
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
}
