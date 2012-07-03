
/*
 * Inizializzazione Comander
 *
 */
$(function(){
    //Aggiornamento livelli e sconti cassiere
    $.ajax({
        type : "POST",
        data: '',
        url: "livelli.php",
        dataType: 'json',
        cache: false,
        success: onLivelliSuccess,
        error: onLivelliError
    });
});


/**
 * Richiesta Ajax completata con successo
 *
 */
function onLivelliSuccess(data, status) { 

    //Verifica se utente loggato
    if ( !logged(data['err']) ) return;

    //alert("Successo lettura Livelli da database con Ajax!");  

    document.getElementById('user01').innerHTML = data['cassiere'];

    for(var i=0; i<data['livelli'].length; i++) {
        livelli.push(data['livelli'][i]);
    }

    //Verifica se cassiere abilitato per prenotazione al tavolo
    if (livelli[1] != 2) {
        cassa_fissa = true;
        resetTavolo();
        //nascosto pulsante "Indietro" in pagina "ordine"
        $('#pg-ord-back').hide();
    }

    //Creazione pulsanti selezione sconto
    var str = '';
    for(var j=0; j<data['sconti'].length; j++) {
        sconti.push(data['sconti'][j]);    
        str = str + '<a href="#'+data['sconti'][j]+'" data-role="button" class="ui-link-inherit cl-sconto">';
        str = str + '<div class="name" style="font-size: 150%">Sconto '+data['sconti'][j]+'%</div>';
        str = str + '</a>';        
    }

    str = str + '<a href="#0" data-role="button" class="ui-link-inherit cl-sconto">';
    str = str + '<div class="name" style="font-size: 150%">Nessuno sconto</div>';
    str = str + '</a>';  

    document.getElementById('diag-sconto-bt').innerHTML = str;

    if (data['sconti'].length > 0) {
        document.getElementById('diag-sconto-text').style.display = 'none';
        document.getElementById('diag-sconto-bt').style.display = 'inline';
    }
    else {
        document.getElementById('diag-sconto-text').style.display = 'inline';
        document.getElementById('diag-sconto-bt').style.display = 'none';
    }
}


/**
 * Errore richiesta Ajax
 *
 */
function onLivelliError(data, status) { 
    alert("Errore Ajax Livelli " + data['err']);
}


/*
 * PAGINA "HOME"
 */
$("#home").live('pageshow', function() {
    homePageShow();
});


/*
 * PAGINA "TAVOLI"
 */
$("#tavoli").live('pageshow', function() {
    tavoliPageShow();
});


/*
 * PAGINA "INFO ORDINI"
 */
$("#info-ordini").live('pageshow', function() {
    if (memPayload != '') setTimeout("viewResult(8)", 500);
});


/*
 * PAGINA "CHIUSURA"
 */
$("#chiusura").live('pageshow', function() {
    chiusuraPageShow();
});


/*
 * PAGINA-DIALOG "INSERIMENTO CONTANTI"
 */
$("#diag-ins-cont").live('pageshow', function() {
    document.getElementById('cont-ric').value = '';
});


/*
 * PAGINA "BUONI PREPAGATI"
 */
$("#buoni-pre").live('pageshow', function() {
    buoniprePageShow();
});
