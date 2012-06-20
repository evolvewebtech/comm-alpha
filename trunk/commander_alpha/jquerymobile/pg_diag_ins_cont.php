
<div class="ui-grid-d">
    <div class="ui-block-a">
        <a id="cont-5" data-role="button" class="ui-btn-right">5 €</a>
    </div>
    <div class="ui-block-b">
        <a id="cont-10" data-role="button" class="ui-btn-right">10 €</a>
    </div>
    <div class="ui-block-c">
        <a id="cont-20" data-role="button" class="ui-btn-right">20 €</a>
    </div>
    <div class="ui-block-d">
        <a id="cont-50" data-role="button" class="ui-btn-right">50 €</a>
    </div>
    <div class="ui-block-e">
        <a id="cont-100" data-role="button" class="ui-btn-right">100 €</a>
    </div>
</div>


<div data-role="fieldcontain">
    <label for="name">Contanti ricevuti:</label>
    <input type="number" name="name" id="cont-ric" value="" placeholder="€" />
</div>

<a href="#chiusura" id="conf-cont" data-role="button" data-icon="check" class="ui-btn-right" data-transition="fade">Conferma</a>


<script type="text/javascript">
    
    $('#cont-5').live("click", function() {
        document.getElementById('cont-ric').value = 5;
    });
    
    $('#cont-10').live("click", function() {
        document.getElementById('cont-ric').value = 10;
    });
    
    $('#cont-20').live("click", function() {
        document.getElementById('cont-ric').value = 20;
    });
    
    $('#cont-50').live("click", function() {
        document.getElementById('cont-ric').value = 50;
    });
    
    $('#cont-100').live("click", function() {
        document.getElementById('cont-ric').value = 100;
    });
    
    
    $('#conf-cont').live("click", function() {
        
        contanti = parseFloat(document.getElementById('cont-ric').value);
        contanti = Math.round(contanti*100) / 100;
        
        if (contanti > 0) {;}
        else contanti = 0;
        
        setContanti(contanti);
    });
    
</script>