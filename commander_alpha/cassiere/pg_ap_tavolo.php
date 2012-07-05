
<!-- PAGINA APERTURA TAVOLO -->

<section id="section-tab" class="ui-body ui-body-b">  
    <div style="font-size: 1.2em">Selezionare la sala:</div>
    <div id="sale-buttons" class="sale-buttons"></div>
    <div style="clear: both; margin-bottom: 5px"></div>
    <div style="font-size: 1.2em">Selezionare il tavolo:</div>
    <div id="tab-buttons" class="tab-buttons"></div>
</section>

<div class="scelta_cop">
    <form>
        <label for="slider-0" style="text-align: center">Numero di coperti:</label>
        <input type="number" name="slider" id="slider-0" value="1" style="text-align: center; font-size: 150%" />
        <div id="cop-buttons" class="ui-grid-a">
            <div class="ui-block-a">
                <a data-role="button" data-icon="plus" data-iconpos="top" class="ui-btn-right cop-plus"></a>
            </div>
            <div class="ui-block-b">
                <a data-role="button" data-icon="minus" data-iconpos="top" class="ui-btn-right cop-min"></a>
            </div>
        </div><!-- /grid-a -->    
    </form>
    <h1></h1>
    <a id="sel-table" href="#ordine" data-role="button" data-icon="grid" class="ui-btn-right ui-disabled">Inserimento ordine</a>
    <h3></h3>
    <a href="#home" data-role="button" data-icon="delete" class="ui-btn-right">Annulla ordine</a>
</div>


<script type="text/javascript">
    
    function resetTavolo() {
        if (cassa_fissa) document.getElementById('slider-0').value = 0;
        else document.getElementById('slider-0').value = 1;
        
        //Rimozione classe 'selected' ai pulsanti dei tavoli
        var $optionSet = $('#tab-buttons');
        $optionSet.find('.selected').removeClass('selected');
    }
    
    
    function tavoliPageShow() {
        //Aggiornamento livelli e sconti cassiere
        if (refreshTab) {
            refreshTab = false;

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
        
        enDisButton();
    }
    
    
    /**
     * Richiesta Ajax completata con successo
     *
     */
    function onTavoliSuccess(data, status) { 

        //Verifica se utente loggato
        if ( !logged(data['err']) ) return;

        //alert("Successo lettura Tavoli da database con Ajax!");  

        //Creazione array sale e tavoli
        var strS = '';
        var str = '';
        for(var i=0; i<data['sale'].length; i++) {

            var id = data['sale'][i]['id'];
            strS = strS + '<a id="s'+id+'" href="#'+data['sale'][i]['id'] + '&' + data['sale'][i]['nome']+'" data-rel="dialog" class="comm-S-btn sale-btn">';
            strS = strS + '<span class="comm-S-btn-text">'+data['sale'][i]['nome']+'</span>';
            strS = strS + '</a>';

            str = str + '<div id=sala-'+data['sale'][i]['id']+'>';

            var tavoli = new Array()
            for(var j=0; j<data['sale'][i]['tavoli'].length; j++) {
                var tavolo = data['sale'][i]['tavoli'][j];              
                tavoli.push(tavolo);

                var id = data['sale'][i]['tavoli'][j]['id'];
                var nome = data['sale'][i]['tavoli'][j]['nome'];

                str = str + '<a href="#'+id+'&'+nome+'" data-rel="dialog" class="comm-T-btn tab-btn">';
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

        document.getElementById('sale-buttons').innerHTML = strS;
        document.getElementById('tab-buttons').innerHTML = str;
        
        //Visualizzazione predefinita dei tavoli della prima sala
        for(var t=0; t<sale.length; t++) {
            var str = 'sala-' + sale[t]["id"];
            if (t==0) {
                document.getElementById(str).style.display='block';
                //Aggiunta classe "selected" al pulsante
                $('#s'+sale[t]["id"]).addClass('selected');
            }
            else document.getElementById(str).style.display='none';
        }
    }


    /**
     * Errore richiesta Ajax
     *
     */
    function onTavoliError(data, status) { 
        alert("Errore Ajax Tavoli " + data['err']);
    }
    
    
    $("#sale-buttons .comm-S-btn").live("click" , function() {
        //Memorizzazione id sala e nome sala del pulsante cliccato
        var param = $(this).attr('href');
        param = param.replace('#','');
        var $arr = param.split('&');
        //Aggiunta/rimozione classe 'selected' al pulsante cliccato
        var $optionSet = $(this).parents('.sale-buttons');
        $optionSet.find('.selected').removeClass('selected');
        $(this).addClass('selected');
        //Visualizzazione tavoli sale
        for(var t=0; t<sale.length; t++) {
            var str = 'sala-' + sale[t]["id"];
            if (sale[t]["id"]==$arr[0]) document.getElementById(str).style.display='block';
            else document.getElementById(str).style.display='none';
        }
    });
    
    $("#tab-buttons .comm-T-btn").live("click" , function() {
        //Memorizzazione id tavolo e nome tavolo del pulsante cliccato
        var param = $(this).attr('href');
        param = param.replace('#','');
        var $arr = param.split('&');
        idTavolo = $arr[0];
        numTavolo = $arr[1];
        //Aggiunta/rimozione classe 'selected' al pulsante cliccato
        var $optionSet = $(this).parents('.tab-buttons');
        $optionSet.find('.selected').removeClass('selected');
        $(this).addClass('selected');
        enDisButton();
    });
    
//    $("#text-num-t").live("change" , function() {
//        enDisButton();
//    });

    $("#slider-0").live("change" , function() {
        enDisButton();
    });

    function enDisButton () {
        //Abilita/disabilita pulsante "Inserimento ordine"
        if ((idTavolo <= 0) | (document.getElementById('slider-0').value < 0) ) {
            $('#sel-table').addClass('ui-disabled');
        }
        else $('#sel-table').removeClass('ui-disabled');
    }

    $('#cop-buttons .cop-plus').live("click", function() {
        var temp = document.getElementById('slider-0').value;
        if (temp < 50) {
            document.getElementById('slider-0').value = parseInt(temp) + 1;
        }
        enDisButton();
    });

    $('#cop-buttons .cop-min').live("click", function() {
        var temp = document.getElementById('slider-0').value;
        if (temp > 0) {
            document.getElementById('slider-0').value = parseInt(temp) - 1;
        }
        enDisButton();
    });
</script>
            