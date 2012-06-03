
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
    
    //Test
    var randNum = Math.random()*5000;
    randNum = parseInt(randNum);
    if (randNum < 1000) randNum = 1000;
    setTimeout("changePageHome()",randNum);       
});


//Test
function changePageHome() {
    document.location.href="#tavoli";
    $.mobile.changePage( "#tavoli", 'none', false, true);
}


$("#tavoli").live('pageshow', function() {
    document.getElementById('text-num-t').value = "10";
    document.getElementById('slider-0').value = 1;
    numTavolo = 10;
    numCoperti = 1;  
    //Test
    setTimeout("changePageTav()",1000);  
});


//Test
function changePageTav() {
    document.location.href="#ordine";
    $.mobile.changePage( "#ordine", 'none', false, true);
}


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
        livelli[i] = data['livelli'][i];
    }
    
    //Verifica se cassiere abilitato per prenotazione al tavolo
    if (livelli[1] != 2) {
        cassa_fissa = true;
        //nascosto pulsante "Indietro" in pagina "ordine"
        document.getElementById('#pg-ord-back').style.display='none';
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
 * PAGINA "CHIUSURA"
 */
$("#chiusura").live('pageshow', function() {
            
    numTavolo = 10;
    numCoperti = 1;   
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
    
    var randNum = Math.random()*3000;
    randNum = parseInt(randNum);
    if (randNum < 500) randNum = 500;
    setTimeout("changePageChius()",randNum);
});


//Test
function changePageChius() {
    //document.location.href="#ordine";
    //$.mobile.changePage( "#ordine", 'none', false, true);
    invioOrdine();
}



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
