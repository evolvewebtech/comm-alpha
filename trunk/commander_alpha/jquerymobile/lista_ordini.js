var dataSel = "";
/*
 * Evento "pageshow" pagina "info_ordini"
 *
 */
//$("#info-ordini").live('pageshow', function() {
$('#mydate').live('datebox', function(event, payload) {    
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
    //alert("Successo lettura da database con Ajax!")  
    str = '';
    
    if (data.length > 0) {
    
    str = str + '<ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-icon="star" data-inset="true" data-role="listview">';
    str = str + '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top ui-btn-up-undefined" data-role="list-divider" role="heading">Ordini del '+dataSel+'</li>';
    
    for (i=0; i<data.length; i++) {    
    str = str + '<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-count ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-inline="false" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c">';
    str = str + '<div class="ui-btn-inner ui-li"><div class="ui-btn-text">';
    str = str + '<a class="ui-link-inherit" href="index.html">';
    str = str + data[i].timestamp + ' - Tavolo n° ' + data[i].tavolo_id + ' - Coperti ' + data[i].n_coperti;
    str = str + '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -15px">Totale '+data[i].totale+' €</span>';
    str = str + '</a>';
    str = str + '</div></div>';
    str = str + '</li>';
    }
    
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
    str = str + '<div style="margin:auto">';
    str = str + 'Nessun ordine trovato</div>';
    document.getElementById('lista-vecchi-ordini').innerHTML = str;
}
