
<!-- PAGINA CONFERMA E CHIUSURA -->

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
            <li id="chius-sconto" class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div>
                <h2 class="name">Sconto</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            <li id="chius-contanti" class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div>
                <h2 class="name">Contanti</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
            </ul>
        </div>
        <div class="ch-tot-ord-div">
            <li id="chius-resto" class="ui-li ui-li-static ui-body-c comm-li-ch">
                <div>
                <h2 class="name">Da ricevere</h2>
                <h2 class="prezzo">0 €</h2>
                </div>
            </li>
        </div>
    </section>
    </div>
    
    <div class="ch-tot-ord-right">
        <div class="ch-tot-ord-div"></div>
        <a href="#buoni-pre" data-role="button" data-icon="search" class="ui-btn-right">Buoni prepagati</a>
        <a href="#diag-sconto" data-rel="dialog" data-role="button" data-icon="star" class="ui-btn-right">Sconto</a>
        <a href="#diag-ins-cont" data-rel="dialog" data-role="button" data-icon="check" class="ui-btn-right">Contanti</a>
        <div class="ch-tot-ord-div"></div>
        <div class="ch-tot-ord-div"></div>
        <a href="#diag-conf-ord" data-rel="dialog" class="comm-C-btn comm-C-btn-conf">
            <img src="css/images/desktop_printer.png" />
            <span class="comm-C-btn-text">Conferma ordine</span>
        </a>
    </div>
</div>


<script type="text/javascript">
    
    var id_ord_stmp = 0;
    
    
    /**
     * Funzione evento pageshow pagina "Chiusura"
     *
     */
    function chiusuraPageShow() {
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
            if (numCoperti > 0) {
                totPersona = parseFloat(totale) / parseFloat(numCoperti);
                totPersona = Math.round(totPersona*100) / 100;
            }
            else totPersona = 0; 
        }
        catch(err) {;}

        str = "";
        str = str + '<h2 class="name">Totale per persona</h2>';
        str = str + '<h2 class="prezzo">' + formatMoney(totPersona,2,true) + ' \u20ac</h2>';
        document.getElementById('chius-tot-pers').innerHTML = str;

        var soldi = 0;
        var strSoldi = "";
        var strColor = "";
        if ( (contanti + buono_cred_us) < (totale - scontato) ) {
            soldi = totale - scontato - contanti - buono_cred_us;
            strSoldi = "Da ricevere";
            strColor = "#F00";
        }
        else {
            soldi = contanti + buono_cred_us - (totale - scontato);
            strSoldi = "Resto";
            strColor = "#00A700";
        }

        soldi = Math.round(soldi*100) / 100;

        str = "";
        str = str + '<h2 class="name" style="color: ' + strColor + '">' + strSoldi + '</h2>';
        str = str + '<h2 class="prezzo" style="color: ' + strColor + '">' + formatMoney(soldi,2,true) + ' \u20ac</h2>';
        document.getElementById('chius-resto').innerHTML = str;

        setBuono(buono_cred_us);
    }
    
    
    /**
     * Evento click pulsante sconto (dialog scelta sconto)
     * 
     */
    $('#diag-sconto-bt .cl-sconto').live("click", function() {
        var param = $(this).attr('href');
        sconto = parseFloat(param.replace('#',''));
        scontato = parseFloat( (parseFloat(totale) * sconto) / 100 );
               
        setSconto(scontato);
        
        //Annullamento pagamento con buono prepagato
        if (sconto > 0) {
            usa_buono = false;
            buono_ser = "";
            buono_cred_us = 0;
        }
        
        document.location.href="#chiusura";
    });
    
    
    /**
     * Funziona aggiornamento visualizzazione credito buono usato
     *
     */
    function setBuono(cont) {
        str = "";
        str = str + '<h2 class="name">Buono prepagato</h2>';
        str = str + '<h2 class="prezzo">' + formatMoney(cont,2,true) + ' \u20ac</h2>';
        document.getElementById('chius-buoni').innerHTML = str;
    }
    
    
    /**
     * Funziona aggiornamento visualizzazione sconto applicato
     *
     */
    function setSconto(cont) {
        str = '';
        str = str + '<h2 class="name">Sconto</h2>';
        str = str + '<h2 class="prezzo">' + formatMoney(cont,2,true) + ' \u20ac</h2>';
        document.getElementById('chius-sconto').innerHTML = str;
    }
    
    
    /**
     * Funziona aggiornamento visualizzazione contanti ricevuti
     *
     */
    function setContanti(cont) {
        str = "";
        str = str + '<h2 class="name">Contanti</h2>';
        str = str + '<h2 class="prezzo">' + formatMoney(cont,2,true) + ' \u20ac</h2>';
        document.getElementById('chius-contanti').innerHTML = str;
    }
    
    
    /**
     * Evento click pulsante invio ordine
     * 
     */
    $('#conferma-ordine').live("click", function() {
        invioOrdine();
    });
    
    
    /**
     * Funzione invio ordine
     * 
     */
    function invioOrdine() {
         
        var alimenti = new Array();
        
        //Estrazione dati dalla lista degli alimenti selezionati
        for (var i=0; i<arrList.length; i++) {
            
            if (!arrList[i]._menu) {
                var alimento = new Array();

                alimento[0] = arrList[i]._id;       //id
                alimento[1] = arrList[i]._num;      //numero 
                alimento[2] = arrList[i]._prezzo;   //prezzo
                alimento[3] = 0;                    //iva
                alimento[5] = 0;                    //id menù fisso

                var varianti = new Array();           
                for (var j=0; j<arrList[i]._varianti.length; j++) {
                    var variante = arrList[i]._varianti[j];
                    varianti[j] = variante._id;
                    //aggiornamento prezzo
                    alimento[2] = parseFloat(alimento[2]) + parseFloat(variante._prezzo);
                    alimento[2] = Math.round(alimento[2]*100) / 100;
                }          
                alimento[4] = varianti;             //varianti

                alimenti.push(alimento);
            }
            
            else {
                
                //Estrazione dati dalla lista dei menù fissi selezionati
                for (var t=0; t<arrMenuSel.length; t++) {
                    if (arrMenuSel[t]._id == arrList[i]._id) {
                        
                        //Per ogni menù fisso aggiungo una RigaOrdine con il prezzo del menù
                        var alimentoM = new Array();
                        alimentoM[0] = 0;                    //id
                        alimentoM[1] = 1;                    //numero 
                        alimentoM[2] = arrMenuSel[t]._prezzo;//prezzo
                        alimentoM[3] = 0;                    //iva
                        alimentoM[5] = arrMenuSel[t]._id;    //id menù fisso
                        alimenti.push(alimentoM);
                        
                        //Aggiunta RigheOrdine con gli alimenti del menù
                        for (var s=0; s<arrMenuSel[t]._categorie.length; s++) {
                            for (var u=0; u<arrMenuSel[t]._categorie[s]._alimenti.length; u++) {

                                var alimento = new Array();

                                alimento[0] = arrMenuSel[t]._categorie[s]._alimenti[u]._id;       //id
                                alimento[1] = 1;                    //numero 
                                alimento[2] = 0;                    //prezzo
                                alimento[3] = 0;                    //iva
                                alimento[5] = arrMenuSel[t]._id;    //id menù fisso

                                var varianti = new Array(); 
                                for (var v=0; v<arrMenuSel[t]._categorie[s]._alimenti[u]._varianti.length; v++) {
                                    var variante = arrMenuSel[t]._categorie[s]._alimenti[u]._varianti[v];
                                    varianti[v] = variante._id;
                                }
                                alimento[4] = varianti;             //varianti

                                alimenti.push(alimento);
                            }
                        }
                    }
                }            
            }
        }
                        
        //Creazione array
        var data = new Array();
        data = {
            n_coperti:  numCoperti,
            tavolo_id:  idTavolo,
            buono_ser:  buono_ser,
            buono_cred_us:   buono_cred_us,
            sconto: sconto,
            asporto: cassa_fissa,
            alimenti:   alimenti
        }
        
        //Creazione stringa Json
        data = JSON.stringify(data);
        
        showModal();
        $.ajax({
            type : "POST",
            data: data,
            url: "invio_ordine.php",
            dataType: 'json',
            cache: false,
            success: onInvioOrdineSuccess,
            error: onInvioOrdineError
        });
               
    }
    
    
    /**
     * Richiesta Ajax completata con successo
     *
     */
    function onInvioOrdineSuccess(data, status) {
        hideModal();
        console.log("Ordine inviato al server");
        console.log(data);
        
        //Verifica se utente loggato
        if ( !logged(data['err']) ) return;
        
        id_ord_stmp = data['next_id'];

        if (data['err'] == '') {
            onStampaSuccess(data, status); 
        }
        else if (data['err'] == 'E200') {
            onStampaError(data, status);
        }
        else {
            onInvioOrdineError(data, status);
        }       
        //alert("Ordine " + data + " inviato con successo!");
    }
    
    
    /**
     * Errore richiesta Ajax
     *
     */
    function onInvioOrdineError(data, status) {
        hideModal();
        console.log("Errore invio ordine");
        console.log(data);
        
        if (!data['err']) onStampaError(data, status);
        else {
            alert("Errore Ajax registrazione ordine: " + data['err']);
        
            document.location.href="#chiusura";
        }
    }
    
    function onStampaSuccess(data, status) {
        if (id_ord_stmp > 0) alert("Ordine " + id_ord_stmp + " inviato con successo!");
        else alert("Ordine inviato con successo!");
        
        document.location.href="#home";
    } 
    
    function onStampaError(data, status) { 
        alert("Errore stampa ordine");
        
        document.location.href="#home";
    }
</script>

