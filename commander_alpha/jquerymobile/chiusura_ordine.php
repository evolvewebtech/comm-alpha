
<div class="ui-grid-b">
    <div class="ui-block-a"></div>
    <div class="ui-block-b">
        <ul class="ui-listview" data-role="listview" style="margin: 0px">
        <li id="chius-head" class="ui-li ui-li-divider ui-btn ui-bar-d ui-li-has-count ui-btn-up-undefined" data-role="list-divider" role="heading" style="padding-top: 4px; padding-bottom: 4px">
            <h1>Tavolo x</h1>
            <span class="ui-li-count ui-btn-up-c ui-btn-corner-all" style="margin-top: -14px">Coperti 0</span>
        </li>
        <li class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="chius-tot-ord">
            <h2 class="name">Totale conto</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        <li class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="chius-buoni">
            <h2 class="name">Buoni prepagati</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        <li class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="chius-tot-pers">
            <h2 class="name">Totale per persona</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        </ul><h2></h2> 
        <ul class="ui-listview" data-role="listview" style="margin: 0px">
        <li id="chius-contanti" class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="totale">
            <h2 class="name">Contanti</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        <li id="chius-resto" class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="totale">
            <h2 class="name">Resto</h2>
            <h2 class="prezzo">0 €</h2>
            </div>
        </li>
        </ul> 
    </div>
    <div class="ui-block-c"></div>
</div>
<h2></h2>
<div class="ui-grid-c">
    <div class="ui-block-a">
        <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Gruppo</a>
    </div>
    <div class="ui-block-b">
        <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Buono</a>
    </div>
    <div class="ui-block-c">
        <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Sconto</a>
    </div>
    <div class="ui-block-d">
        <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Singolo</a>
    </div>
</div>

<a id="conferma-ordine" data-role="button" data-icon="home" class="ui-btn-right">Conferma ordine</a>

<!--
<link rel="stylesheet" href="css/comm_C_Button.css" />
<a href="#" class="comm-C-btn">
    <span class="comm-C-btn-slide-text">$29</span>
    <img src="images/icons/1.png" alt="Photos" />
    <span class="comm-C-btn-text"><small>Available on the Apple</small> App Store</span>
    <span class="comm-C-btn-icon-right"><span></span></span>
</a>
-->
            
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script type="text/javascript">
    
    $('#conferma-ordine').live("click", function() {
        
        $.ajax({
            type : "GET",
            url: "invio_ordine.php",
            dataType: 'json',
            cache: false,
            success: onInvioOrdineSuccess,
            error: onInvioOrdineError
        });
        
    });
    
    function onInvioOrdineSuccess(data, status) { 
        
    }
    
    function onInvioOrdineError(data, status) { 
        alert("Errore Ajax");
    }
    
</script>

