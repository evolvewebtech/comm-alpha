
<!-- PAGINA HOME -->

<div class="scelta_op">
    <div class="button_opz" >
        <a id="new-ord-bt" class="comm-btn-1">
            <img src="css/images/symbol_add.png" />
            <span class="comm-btn-1-text">Nuovo ordine</span>
        </a>
    </div>
    <div class="button_opz">
        <a href="#info-ordini" class="comm-btn-1">
            <img src="css/images/symbol_information.png" />
            <span class="comm-btn-1-text" style="padding-left: 20px">Info ordini</span>
        </a>
    </div>
</div>


<script type="text/javascript">
    
    /**
     * Funzione evento pageshow pagina "Home"
     *
     */
    function homePageShow() {
        //Reset variabili
        idTavolo = 0;
        numTavolo = 0;
        numCoperti = 0;
        totale = 0;
        contanti = 0;
        sconto = 0;
        scontato = 0;
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

        document.getElementById('slider-0').value = 1;

        //Aggiornamento livelli e sconti cassiere
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
    }
    
    
    /**
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
        
        //Aggiornamento sale e tavoli
        $.ajax({
            type : "POST",
            data: '',
            url: "tavoli.php",
            dataType: 'json',
            cache: false,
            success: onTavoliSuccess,
            error: onTavoliError
        });
    }


    /**
     * Errore richiesta Ajax
     *
     */
    function onLivelliError(data, status) { 
        alert("Errore Ajax Livelli " + data['err']);
    }
    
    
    /**
     * Richiesta Ajax completata con successo
     *
     */
    function onTavoliSuccess(data, status) { 
        
        //Verifica se utente loggato
        if ( !logged(data['err']) ) return;
        
        //alert("Successo lettura da database con Ajax!");  
        
        //Creazione array sale e tavoli
        var str = '';
        for(var i=0; i<data['sale'].length; i++) {
            
            str = str + '<div id=sala-'+data['sale'][i]['id']+'>';
            
            var tavoli = new Array()
            for(var j=0; j<data['sale'][i]['tavoli'].length; j++) {
                var tavolo = data['sale'][i]['tavoli'][j];              
                tavoli.push(tavolo);
                
                var id = data['sale'][i]['tavoli'][j]['id'];
                var nome = data['sale'][i]['tavoli'][j]['nome'];
                
                str = str + '<a href="#'+id+'&'+nome+'" data-rel="dialog" class="comm-T-btn">';
                str = str + '<span class="comm-T-btn-text">'+nome+'</span>';
                str = str + '</a>';
            }
            
            str = str + '</div>';
            
            var sala = new Array();
            sala["id"] = data['sale'][i]['id'];
            sala["nome"] = data['sale'][i]['id'];
            sala["tavoli"] = tavoli;
            
            sale.push(sala);
        }
        
        document.getElementById('tab-buttons').innerHTML = str;
    }
    
    
    /**
     * Errore richiesta Ajax
     *
     */
    function onTavoliError(data, status) { 
        alert("Errore Ajax Tavoli " + data['err']);
    }
    
    
    /**
     * Evento click pulsante "Nuovo Ordine"
     * 
     */
    $('#new-ord-bt').live("click", function() {
        if (cassa_fissa) {
            document.location.href="#ordine";
            $.mobile.changePage( "#ordine", 'none', false, true);
        }
        else {
            document.location.href="#tavoli";
            $.mobile.changePage( "#tavoli", 'none', false, true);     
        }
    });
    
</script>
