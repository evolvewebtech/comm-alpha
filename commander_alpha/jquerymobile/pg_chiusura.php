
<div class="ch-tot-ord">
    <div class="ch-tot-ord-left">
    <section class="ui-body ui-body-b" style="box-shadow: 3px 3px 10px #aaaaaa">
        <div class="ch-tot-ord-div">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li id="chius-head" class="ui-li ui-li-divider ui-btn ui-bar-d ui-li-has-count ui-btn-up-undefined comm-li-ch-t" data-role="list-divider" role="heading">
                <div>Tavolo x</div>
                <span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">Coperti 0</span>
            </li>
            <li class="ui-li ui-li-static ui-body-c comm-li-ch-big">
                <div id="chius-tot-ord">
                <h2 class="name">Totale conto</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            <li class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div id="chius-tot-pers">
                <h2 class="name">Totale per persona</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
        <div class="ch-tot-ord-div">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div id="chius-buoni">
                <h2 class="name">Buono prepagato</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            <li id="chius-contanti" class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div id="totale">
                <h2 class="name">Contanti</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
        </div>
        <div class="ch-tot-ord-div">
            <li id="chius-resto" class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div id="totale">
                <h2 class="name">Da ricevere</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
    </section>
    </div>
    
    <div class="ch-tot-ord-right">
        <div class="ch-tot-ord-div"></div>
        <a href="#buoni-pre" data-role="button" data-icon="search" class="ui-btn-right">Buoni prepagati</a>
        <a data-role="button" data-icon="star" class="ui-btn-right">Sconto</a>
        <a href="#diag-ins-cont" data-rel="dialog" data-role="button" data-icon="check" class="ui-btn-right">Contanti</a>
        <div class="ch-tot-ord-div"></div>
        <div class="ch-tot-ord-div"></div>
        <a href="#diag-conf-ord" data-rel="dialog" class="comm-C-btn comm-C-btn-conf">
            <img src="css/images/desktop_printer.png" />
            <span class="comm-C-btn-text">Conferma ordine</span>
        </a>
    </div>
</div>


<!--
<link rel="stylesheet" href="css/comm_C_Button.css" />
<a href="#" class="comm-C-btn">
    <span class="comm-C-btn-slide-text">$29</span>
    <img src="images/icons/1.png" alt="Photos" />
    <span class="comm-C-btn-text"><small>Available on the Apple</small> App Store</span>
    <span class="comm-C-btn-icon-right"><span></span></span>
</a>
-->


<script type="text/javascript">
    
    var id_ord_stmp = 0;
    
    $('#conferma-ordine').live("click", function() {
        
        var alimenti = new Array();
        
        //Estrazione dati dalla lista degli alimenti selezionati
        for (var i=0; i<arrList.length; i++) {
            
            var alimento = new Array();
            
            alimento[0] = arrList[i]._id;       //id
            alimento[1] = arrList[i]._num;      //numero 
            alimento[2] = arrList[i]._prezzo;   //prezzo
            alimento[3] = 0;                    //iva
            
            var varianti = new Array();           
            for (var j=0; j<arrList[i]._varianti.length; j++) {
                var variante = arrList[i]._varianti[j];
                varianti[j] = variante._id;
                //aggiornamento prezzo
                alimento[2] = parseFloat(alimento[2]) + parseFloat(variante._prezzo);
                alimento[2] = Math.round(alimento[2]*100) / 100;
            }          
            alimento[4] = varianti;             //varianti
            
            alimenti[i] = alimento;
        }
                
        //Creazione array
        var data = new Array();
        data = {
            n_coperti:  numCoperti,
            tavolo_id:  numTavolo,
            buono_ser:  buono_ser,
            buono_cred_us:   buono_cred_us,
            
            alimenti:   alimenti
        }
        
        //Creazione stringa Json
        data = JSON.stringify(data);
        
        
        $.ajax({
            type : "POST",
            data: data,
            url: "invio_ordine.php",
            dataType: 'json',
            cache: false,
            success: onInvioOrdineSuccess,
            error: onInvioOrdineError
        });
        
    });
    
    function onInvioOrdineSuccess(data, status) {
        
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
        
        //alert("Ordine " + data + " inviato con successo!");
        id_ord_stmp = data['next_id'];

        $.ajax({
            type: "POST",
            data: 'id='+id_ord_stmp,
            url: "stampaOrdine.php",
            dataType: 'json',
            cache: false,
            success: onStampaSuccess,
            error: onStampaError
        });
    }
    
    function onInvioOrdineError(data, status) { 
        alert("Errore Ajax registrazione ordine");
        
        document.location.href="#chiusura";
        $.mobile.changePage( "#chiusura", 'none', false, true);
    }
    
    function onStampaSuccess(data, status) {
        if (id_ord_stmp > 0) alert("Ordine " + id_ord_stmp + " inviato con successo!");
        else alert("Ordine inviato con successo!");
        
        document.location.href="#home";
        $.mobile.changePage( "#home", 'none', false, true);
    } 
    
    function onStampaError(data, status) { 
        alert("Errore stampa ordine " + id_ord_stmp);
        
        document.location.href="#home";
        $.mobile.changePage( "#home", 'none', false, true);
    }
   
</script>

