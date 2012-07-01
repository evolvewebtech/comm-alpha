
<!-- PAGINA VECCHIO ORDINE -->

<div class="old-ord">
    <section class="ui-body ui-body-d" style="box-shadow: 3px 3px 10px #aaaaaa">
        <div style="padding-top: 10px; padding-bottom: 10px">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li class="ui-li ui-li-divider ui-btn ui-bar-d ui-li-has-count ui-btn-up-undefined" data-role="list-divider" role="heading" style="padding-top: 14px; padding-bottom: 14px">
                <div id="old-ord-tav" style="font-size: 22px">Tavolo</div>
                <div id="old-ord-ser">Ordine n°</div>
                <div id="old-ord-cop">
                <span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">Coperti 0</span>
                </div>
            </li>

            </ul>
        </div>
        <div id="old-ord-righe" style="padding-top: 10px; padding-bottom: 10px"></div>
        <div style="padding-top: 10px; padding-bottom: 10px">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="old-ord-tot">
                <h2 class="name">Totale</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
        <div id="old-ord-ts" style="padding-top: 10px; margin-left: 20px"></div>
        <div id="old-ord-cs" style="padding-bottom: 10px; margin-left: 20px"></div>
    </section>
</div>


<script type="text/javascript">
    
    /*
     * Evento "click" su lista vecchi ordini
     * 
     */
    $('#lista-vecchi-ordini .ristampa-ordine').live("click", function() {
        //recupero parametri passati
        var $param = $(this).attr('id');
        //eliminazione stringa "ord-id-"
        $param = $param.replace('ord-ser-','');
        //split parametri
        var $arr = $param.split('&');
        //parametri
        $id = $arr[0];
        $ts = $arr[1];
        $tavolo_id = $arr[2];
        $coperti = $arr[3];
        $totale = $arr[4];
        
        //Tavolo
        document.getElementById('old-ord-tav').innerHTML = 'Tavolo ' + $tavolo_id;
        
        //N° ordine
        document.getElementById('old-ord-ser').innerHTML = 'Ordine n° ' + $id;
        
        //N° coperti
        var str = '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">';
        str = str + 'Coperti ' + $coperti + '</span>';
        document.getElementById('old-ord-cop').innerHTML = str;
        
        //Totale
        var str = '<h2 class="name">Totale</h2>';
        str = str + '<h2 class="prezzo">' + formatMoney($totale, 2, true) + ' \u20ac</h2>';
        document.getElementById('old-ord-tot').innerHTML = str
        
        //Timestamp
        document.getElementById('old-ord-ts').innerHTML = $ts;
        
        //Recupero alimenti ordine
        $.ajax({
            type : "POST",
            data: 'id='+$id,
            url: "vecchio_ordine.php",
            dataType: 'json',
            cache: false,
            success: onVecchioOrdineSuccess,
            error: onVecchioOrdineError
        });
               
    });
    
    
    /*
     * Richiesta Ajax completata con successo
     *
     */
    function onVecchioOrdineSuccess(data, status) {
        
        //Verifica se utente loggato
        if ( !logged(data['err']) ) return;
        
        //alert("Successo lettura da database con Ajax!");
        
        var str = '';
        var strCass = '';
        
        for (var i=0; i<data['righe'].length; i++) {
            prezzoTot = parseFloat(data['righe'][i]["numero"]) * parseFloat(data['righe'][i]["prezzo"]);
            
            str = str + '<div class="old-ord-rig">';
            str = str + '<div class="num">' + data['righe'][i]["numero"] + '</div>';
            str = str + '<div class="name">' + data['righe'][i]["nome"] + '</div>';
            if ( (data['righe'][i]["menu_id"] == 0) || (data['righe'][i]["prezzo"] != 0) ) {
                str = str + '<div class="prezzo">' + formatMoney(prezzoTot, 2) + ' \u20ac</div>';
            }
            else str = str + '<div class="prezzo"></div>'; //Solo per riga con nome Menù
            str = str + '</div>';
            
            for (var j=0; j<data['righe'][i]["arrVar"].length; j++) {   
                str = str + '<div class="old-ord-rig-var">';
                str = str + '<div class="name">' + data['righe'][i]["arrVar"][j]["descrizione"] + '</div>';
                str = str + '</div>';
            }
            
            if (i == 0) { strCass = 'Cameriere: ' + data['righe'][i]["cassiere"]; }
        }
        
        //Riepilogo ordine
        document.getElementById('old-ord-righe').innerHTML = str;
        
        //Cassiere
        document.getElementById('old-ord-cs').innerHTML = strCass;
    }
    
    
    /*
     * Errore richiesta Ajax
     *
     */
    function onVecchioOrdineError(data, status) { 
        alert("Errore Ajax");
    }
    
</script>