
/*
 * PAGINA "HOME"
 */
$("#home").live('pageshow', function() {
    //Inizializzazione variabili
    numTavolo = 0;
    numCoperti = 0;
    totale = 0;
    contanti = 0;
    arrAlim = new Array();
    arrMenu = new Array();
    arrMenuSel = new Array();
    arrList = new Array();
    show_opt = false;
    mem_index = -1;
    mem_ord_type = "cat";
    usa_buono = false;
    buono_ser = "";
    buono_nom = "";
    buono_cred = 0;
    buono_cred_us = 0;
    refreshAlim = false;
    ann_voci = false;
    
    document.getElementById('text-num-t').value = "";
    document.getElementById('slider-0').value = 1;
    
    if (refreshLiv) {
        refreshLiv = false;
        
        $.ajax({
            type : "POST",
            data: '',
            url: "livelli.php",
            dataType: 'json',
            cache: false,
            success: onLivelliSuccess,
            error: onLivelliError
        });
    }
});


/*
 * Richiesta Ajax completata con successo
 *
 */
function onLivelliSuccess(data, status) { 
    
    //Verifica se utente loggato
    if ( !logged(data['err']) ) return;
    
    //alert("Successo lettura da database con Ajax!");  
    
    document.getElementById('user01').innerHTML = 'Cameriere: ' + data['cassiere'];
    
    for(var i=0; i<data['livelli'].length; i++) {
        livelli.push(data['livelli'][i]);
    }
    
    //Verifica se cassiere abilitato per prenotazione al tavolo
    if (livelli[1] != 2) {
        cassa_fissa = true;
        //nascosto pulsante "Indietro" in pagina "ordine"
        //document.getElementById('#pg-ord-back').style.display='none';
    }
    
    //Creazione pulsanti selezione sconto
    for(var j=0; j<data['sconti'].length; j++) {
        sconti.push(data['sconti'][j]);
        var str = '';
        str = str + '<a href="#'+data['sconti'][j]+'" data-role="button" class="ui-link-inherit cl-sconto">';
        str = str + '<div class="name" style="font-size: 150%">Sconto '+data['sconti'][j]+'%</div>';
        str = str + '</a>';        
        document.getElementById('diag-sconto-bt').innerHTML = str;
    }
    
    if (data['sconti'].length > 0) {
        document.getElementById('diag-sconto-text').style.display = 'none';
        document.getElementById('diag-sconto-bt').style.display = 'inline';
    }
    else {
        document.getElementById('diag-sconto-text').style.display = 'inline';
        document.getElementById('diag-sconto-bt').style.display = 'none';
    }
}



/*
 * Errore richiesta Ajax
 *
 */
function onLivelliError(data, status) { 
    alert("Errore Ajax");
}



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
            
    numTavolo = document.getElementById('text-num-t').value;
    numCoperti = document.getElementById('slider-0').value;   
    var str = "";
    str = str + '<div style="font-size: 24px">Tavolo ' + numTavolo + '</div>';
    str = str + '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">Coperti ' + numCoperti + '</span>';           
    document.getElementById('chius-head').innerHTML = str;

    str = "";
    str = str + '<h2 class="name">Totale conto</h2>';
    str = str + '<h2 class="prezzo">' + formatMoney(totale,2,true) + ' \u20ac</h2>';
    document.getElementById('chius-tot-ord').innerHTML = str;

    var totPersona = 0;
    try {
        totPersona = parseFloat(totale) / parseFloat(numCoperti);
        totPersona = Math.round(totPersona*100) / 100;
    }
    catch(err) {;}
    
    str = "";
    str = str + '<h2 class="name">Totale per persona</h2>';
    str = str + '<h2 class="prezzo">' + formatMoney(totPersona,2,true) + ' \u20ac</h2>';
    document.getElementById('chius-tot-pers').innerHTML = str;
    
    var soldi = 0;
    var strSoldi = "";
    var strColor = "";
    if (contanti + buono_cred_us < totale) {
        soldi = totale - contanti - buono_cred_us;
        strSoldi = "Da ricevere";
        strColor = "#F00";
    }
    else {
        soldi = contanti + buono_cred_us - totale;
        strSoldi = "Resto";
        strColor = "#00A700";
    }
    
    soldi = Math.round(soldi*100) / 100;
     
    str = "";
    str = str + '<h2 class="name" style="color: ' + strColor + '">' + strSoldi + '</h2>';
    str = str + '<h2 class="prezzo" style="color: ' + strColor + '">' + formatMoney(soldi,2,true) + ' \u20ac</h2>';
    document.getElementById('chius-resto').innerHTML = str;
    
    str = "";
    str = str + '<h2 class="name">Buono prepagato</h2>';
    str = str + '<h2 class="prezzo">' + formatMoney(buono_cred_us,2,true) + ' \u20ac</h2>';
    document.getElementById('chius-buoni').innerHTML = str;
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
    if (!usa_buono) {
        document.getElementById('buono-non-trovato').innerHTML = '';
        document.getElementById('dati-buono').innerHTML = '';
        document.getElementById('searc-basic').value = '';
        $('#buono-trovato').hide('fast');
        $('#dati-buono-01').show('fast');
        $('#dati-buono-02').hide('fast');
    }
});
