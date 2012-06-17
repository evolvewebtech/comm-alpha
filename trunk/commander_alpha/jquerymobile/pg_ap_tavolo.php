
<!-- PAGINA APERTURA TAVOLO -->

<div style="text-align:center; font-size: 1.2em">Selezionare un tavolo:</div>
<div id="tab-buttons" class="tab-buttons"></div>
<div id="scelta_tav" class="scelta_tav">
    <h1></h1>
    <!-- <label for="text-num-t">Inserire il numero del tavolo:</label> -->
    <!-- <input type="text" name="name" id="text-num-t" value="" placeholder="Numero tavolo" /> -->
    <h1></h1>
    <form>
        <label for="slider-0">Inserire il numero di coperti:</label>
        <input type="number" name="slider" id="slider-0" value="1" />
        <!-- <input type="range" name="slider" id="slider-0" value="1" min="0" max="50"  /> -->
        <div class="ui-grid-a">
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
    
    function tavoliPageShow() {
        $('#sala-16').show('fast');
        $('#sala-11').hide('fast');
        $('#sala-14').hide('fast');
        $('#sala-15').hide('fast');
        $('#sala-17').hide('fast');
    }
    
    $(".comm-T-btn").live("click" , function() {
        var param = $(this).attr('href');
        param = param.replace('#','');
        var $arr = param.split('&');
        idTavolo = $arr[0];
        numTavolo = $arr[1];
    });
    
//    $("#text-num-t").live("change" , function() {
//        enDisButton();
//    });

    $("#slider-0").live("change" , function() {
        enDisButton();
    });

    function enDisButton () {
        //Abilita/disabilita pulsante "Inserimento ordine"
        //if ((document.getElementById('text-num-t').value == "") | (document.getElementById('slider-0').value <= 0) ) {
        if (document.getElementById('slider-0').value <= 0) {
            $('#sel-table').addClass('ui-disabled');
        }
        else $('#sel-table').removeClass('ui-disabled');
    }

    $('.cop-plus').live("click", function() {
        var temp = document.getElementById('slider-0').value;
        if (temp < 50) {
            document.getElementById('slider-0').value = parseInt(temp) + 1;
        }
    });

    $('.cop-min').live("click", function() {
        var temp = document.getElementById('slider-0').value;
        if (temp > 0) {
            document.getElementById('slider-0').value = parseInt(temp) - 1;
        }
    });
</script>
            