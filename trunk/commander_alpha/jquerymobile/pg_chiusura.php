
<div class="ch-tot-ord">
    <section class="ui-body ui-body-b" style="box-shadow: 3px 3px 10px #aaaaaa">
        <div class="ch-tot-ord-div">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li id="chius-head" class="ui-li ui-li-divider ui-btn ui-bar-d ui-li-has-count ui-btn-up-undefined" data-role="list-divider" role="heading" style="padding-top: 14px; padding-bottom: 14px">
                <div>Tavolo x</div>
                <span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">Coperti 0</span>
            </li>
            <li class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="chius-tot-ord">
                <h2 class="name">Totale conto</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            <li class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="chius-tot-pers">
                <h2 class="name">Totale per persona</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
        <div class="ch-tot-ord-div">
            <ul class="ui-listview" data-role="listview" style="margin: 0px">
            <li class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="chius-buoni">
                <h2 class="name">Buono prepagato</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            <li id="chius-contanti" class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="totale">
                <h2 class="name">Contanti</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
        </div>
        <div class="ch-tot-ord-div">
            <li id="chius-resto" class="ui-li ui-li-static ui-body-c comm-li-tot">
                <div id="totale">
                <h2 class="name">Da ricevere</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
    </section>
</div>


<div class="ch-tot-ord-div"></div>
<div class="ui-grid-c">
    <div class="ui-block-a">
        <a href="#chiusura" data-role="button" data-icon="home" class="ui-btn-right">Gruppo</a>
    </div>
    <div class="ui-block-b">
        <a href="#buoni-pre" data-role="button" data-icon="home" class="ui-btn-right">Buoni prepagati</a>
    </div>
    <div class="ui-block-c">
        <a href="#chiusura" data-role="button" data-icon="home" class="ui-btn-right">Sconto</a>
    </div>
    <div class="ui-block-d">
        <a href="#diag-ins-cont" data-rel="dialog" data-role="button" data-icon="home" class="ui-btn-right">Contanti</a>
    </div>
</div>

<a id="conferma-ordine" href="#home" data-role="button" data-icon="home" class="ui-btn-right">Conferma ordine</a>


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
        alert("Ordine " + data + " inviato con successo!");
    }
    
    function onInvioOrdineError(data, status) { 
        alert("Errore Ajax");
    }
   
</script>

