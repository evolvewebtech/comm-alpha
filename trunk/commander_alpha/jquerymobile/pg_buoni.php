
<!-- PAGINA BUONI PREPAGATI -->

<div class="scelta_buono">
    <label for="search-basic" style="font-size: 14px">Cerca codice buono prepagato:</label>
    <div style="margin-top: 8px"></div>
    <input type="search" name="search" id="searc-basic" value="" />
    <div style="margin-top: 15px"></div>
    <a id="cerca-buono" data-role="button" data-icon="search" class="ui-btn-right">Cerca</a>
    <div style="margin-top: 15px"></div>

    <div id="buono-non-trovato" style="text-align: center"></div>

    <div id="buono-trovato">
        <section class="ui-body ui-body-b">
            <div id="dati-buono" style="text-align: center"></div> 
            <div id="dati-buono-01">
            <a href="#chiusura" id="paga-con-buono" data-role="button" class="ui-btn-right" data-transition="fade">Paga con questo buono</a>
            </div>
            <div id="dati-buono-02">
            <a href="#chiusura" id="paga-con-buono-ann" data-role="button" class="ui-btn-right" data-transition="fade">Non pagare con questo buono</a>
            </div>
            <a href="#chiusura" id="buono-annulla" data-role="button" class="ui-btn-right" data-transition="fade">Annulla</a>
        </section>
    </div>
</div>


<script type="text/javascript">
    
    /**
     * Funzione evento pageshow pagina "Buoni-pre"
     *
     */
    function buoniprePageShow() {
        if (!usa_buono) {
            document.getElementById('buono-non-trovato').innerHTML = '';
            document.getElementById('dati-buono').innerHTML = '';
            document.getElementById('searc-basic').value = '';
            $('#buono-trovato').hide('fast');
            $('#dati-buono-01').show('fast');
            $('#dati-buono-02').hide('fast');
        }
    }
    
    
    /**
     * Evento click pulsante cerca buono prepagato
     * 
     */
    $('#cerca-buono').live("click", function() {
        
        var data = 'buonoSer=' + document.getElementById('searc-basic').value;
        
        $.ajax({
            type : "POST",
            data: data,
            url: "cerca_buono.php",
            dataType: 'json',
            cache: false,
            success: onCercaBuonoSuccess,
            error: onCercaBuonoError
        });
        
    });
    
    
    /**
     * Richiesta Ajax completata con successo
     *
     */
    function onCercaBuonoSuccess(data, status) { 
        //alert("Credito buono: " + data[0] + " €, Nominativo: " + data[1]);
        
        //Verifica se utente loggato
        if ( !logged(data['err']) ) return;
        
        buono_ser = document.getElementById('searc-basic').value;
        buono_cred = data['buono'][0];
        buono_nom = data['buono'][1];
        
        var str = "";
        str = str + '<ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-inset="true" data-role="listview">';
        str = str + '<li class="ui-li ui-li-static ui-body-c ui-corner-top ui-corner-bottom">';
        str = str + '<p class="ui-li-aside ui-li-desc" style="font-size: 14px">Credito residuo: ' + formatMoney(buono_cred,2,true) + '\u20ac</p>';
        str = str + '<h3 class="ui-li-heading">Buono n°: ' + buono_ser + '</h3>';
        str = str + '<p class="ui-li-desc">Nominativo: ' + buono_nom + '</p>';
        str = str + '</li>';
        str = str + '</ul>';
        
        document.getElementById('buono-non-trovato').innerHTML = '';
        document.getElementById('dati-buono').innerHTML = str;
        $('#buono-trovato').show('fast');
    }
    
    
    /**
     * Errore richiesta Ajax
     *
     */
    function onCercaBuonoError(data, status) { 
        //alert("Errore Ajax");
        
        var str = "Buono prepagato non trovato o esaurito";
        document.getElementById('buono-non-trovato').innerHTML = str;
        document.getElementById('dati-buono').innerHTML = '';
        $('#buono-trovato').hide('fast');
    }
    
    
    /**
     * Evento click pulsante "paga con buono"
     * 
     */
    $('#paga-con-buono').live("click", function() {        
        usa_buono = true;

        if (buono_cred <= totale) {
            buono_cred_us = parseFloat(buono_cred);
        }
        else buono_cred_us = totale;
        
        buono_cred_us = Math.round(buono_cred_us*100) / 100;

        $('#dati-buono-01').hide('fast');
        $('#dati-buono-02').show('fast');                
    });
    
    
    /**
     * Evento click pulsante "annulla pagamento con buono"
     * 
     */
    $('#paga-con-buono-ann').live("click", function() {
        usa_buono = false;
        buono_ser = "";
        buono_cred_us = 0;
        
        $('#dati-buono-01').show('fast');
        $('#dati-buono-02').hide('fast'); 
    });

</script>