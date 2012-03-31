
$("#chiusura").live('pageshow', function() {
            
    var numTavolo = document.getElementById('basic').value;
    var numCoperti = document.getElementById('slider-0').value;   
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
    
    var resto = 0;
    if (contanti > 0) resto = contanti - totale;
     
    str = "";
    str = str + '<h2 class="name">Resto</h2>';
    str = str + '<h2 class="prezzo">' + resto + ' \u20ac</h2>';
    document.getElementById('chius-resto').innerHTML = str;
});


$("#diag-ins-cont").live('pageshow', function() {
    document.getElementById('cont-ric').value = '';
});