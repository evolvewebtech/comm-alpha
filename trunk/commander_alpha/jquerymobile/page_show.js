
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
    str = str + '<h2 class="prezzo">' + totale + ' \u20ac</h2>';
    document.getElementById('chius-tot-ord').innerHTML = str;

    var totPersona = 0;
    try {
        totPersona = parseFloat(totale) / parseFloat(numCoperti);
        totPersona = Math.round(totPersona*100) / 100;
    }
    catch(err) {;}
    
    str = "";
    str = str + '<h2 class="name">Totale per persona</h2>';
    str = str + '<h2 class="prezzo">' + totPersona + ' \u20ac</h2>';
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
    str = str + '<h2 class="prezzo" style="color: ' + strColor + '">' + soldi + ' \u20ac</h2>';
    document.getElementById('chius-resto').innerHTML = str;
});


$("#diag-ins-cont").live('pageshow', function() {
    document.getElementById('cont-ric').value = '';
});



/*
 * PAGINA "BUONI PREPAGATI"
 */
$("#chiusura").live('pageshow', function() {
    str = "";
    str = str + '<h2 class="name">Buono prepagato</h2>';
    str = str + '<h2 class="prezzo">' + buono_cred_us + ' \u20ac</h2>';
    document.getElementById('chius-buoni').innerHTML = str;
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
