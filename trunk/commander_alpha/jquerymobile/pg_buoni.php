
<div class="ui-grid-b">
    <div class="ui-block-a"></div>
    <div class="ui-block-b">
        <label for="search-basic" style="font-size: 14px">Cerca codice buono prepagato:</label>
        <div style="margin-top: 8px"></div>
        <input type="search" name="search" id="searc-basic" value="" />
        <div style="margin-top: 15px"></div>
        <a id="cerca-buono" data-role="button" data-icon="search" class="ui-btn-right">Cerca</a>
        <div style="margin-top: 15px"></div>
        <div id="buono-trovato" style="text-align: center"></div>
    </div>
    <div class="ui-block-c"></div>
</div>


<script type="text/javascript">
    
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
    
    function onCercaBuonoSuccess(data, status) { 
        //alert("Credito buono: " + data[0] + " €, Nominativo: " + data[1]);
        
        var serialeBuono = document.getElementById('searc-basic').value;
        
        var str = "";
        str = str + '<ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-inset="true" data-role="listview">';
        str = str + '<li class="ui-li ui-li-static ui-body-c ui-corner-top ui-corner-bottom">';
        str = str + '<p class="ui-li-aside ui-li-desc" style="font-size: 14px">Credito residuo: ' + data[0] + '€</p>';
        str = str + '<h3 class="ui-li-heading">Buono n°: ' + serialeBuono + '</h3>';
        str = str + '<p class="ui-li-desc">Nominativo: ' + data[1] + '</p>';
        str = str + '</li>';
        str = str + '</ul>';
        
        document.getElementById('buono-trovato').innerHTML = str;
    }
    
    function onCercaBuonoError(data, status) { 
        //alert("Errore Ajax");
        
        var str = "Buono prepagato non trovato";
        document.getElementById('buono-trovato').innerHTML = str;
    }
    
</script>