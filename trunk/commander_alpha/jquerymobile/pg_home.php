
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
        
        //Reset visualizzazione contanti ricevuti
        setBuono('0');
        setSconto('0');
        setContanti('0');
    }

    
    /**
     * Evento click pulsante "Nuovo Ordine"
     * 
     */
    $('#new-ord-bt').live("click", function() {
        if (cassa_fissa) {
            document.location.href="#ordine";
        }
        else {
            document.location.href="#tavoli";   
        }
    });
    
</script>
