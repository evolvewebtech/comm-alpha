
<!-- PAGINA ORDINE -->

<div class="comm-a">
<section id="contentList">
    <div id="list">
    <div id="list-ord_nav" class="list-ord_nav">
        <section id="options" class="clearfix">
            <div class="option-combo">
                <ul id="sort" class="option-set_list clearfix" data-option-key="sortBy">
                    <li><a href="#categorie" id="sortby-cat" data-option-value="cat" class="selected">Categorie</a></li>
                    <li><a href="#alphabetical" id="sortby-name" data-option-value="nome">Alfabetico</a></li>
                    <li><a href="#" id="ann-voci" data-option-value="annulla-voci">Annulla voci</a></li>
                </ul>
            </div>
        </section>
    </div>
    </div>

    <div id="list-ord" class="list-ord">
        <div id="container2"></div>
        <ul class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-dividertheme="b" data-theme="c" data-inset="true" data-role="listview"></ul>
    </div>

    <div id="list-ord_foo" class="list-ord_foo">
        <div style="height: 20px">
        <div class="ui-body ui-body-b list-ord_foo-cont">
        <ul class="ui-listview" data-role="listview" style="margin: 0px">
        <li class="ui-li ui-li-static ui-body-c comm-li-tot">
            <div id="totale">
            <h2 class="name">Totale:</h2>
            <h2 id="tot-conto" class="prezzo">0 €</h2>
            </div>
        </li>
        </ul>        
        <fieldset class="ui-grid-a">
            <div class="ui-block-a">
                <div class="list-foo-bt-1">
                <a href="#chiusura" id="conf-ord-btt" data-role="button" data-icon="check" class="ui-btn-right ui-disabled">Conferma</a>
                </div>
                <div class="list-foo-bt-2">
                <a href="#chiusura" id="conf-ord-btt2" data-role="button" data-mini="true" data-icon="check" data-iconpos="bottom" class="ui-btn-right ui-disabled"></a>
                </div>
            </div>
            <div class="ui-block-b">
                <div class="list-foo-bt-1">
                <a href="#diag-conf-canc-ord" data-rel="dialog" data-role="button" data-icon="delete" class="ui-btn-right">Annulla</a>
                </div>
                <div class="list-foo-bt-2">
                <a href="#diag-conf-canc-ord" data-rel="dialog" data-role="button" data-mini="true" data-icon="delete" data-iconpos="bottom" class="ui-btn-right"></a>
                </div>
            </div>
        </fieldset>
        </div>
        </div>
        <div id="view-menu" style="position: fixed; right: 120px; top: 180px"><a class="ui-btn-left" data-role="button" data-icon="arrow-l" data-inline="true">Menù</a></div>
    </div>

    <script type="text/javascript">
        //var h_nav = document.getElementById("list-ord_nav").style.height;
        //var h_foo = document.getElementById("list-ord_footer").style.height;
        //document.getElementById("list-ord").style.height = (window.innerHeight-250) - h_nav - h_foo + "px";
    </script>

</section> <!-- #contentList -->        
</div>



<div class="comm-b">
<section id="content">
    <section id="cont-comm-ord">
        <section id="options" class="clearfix">
            <div class="option-combo">
                <!-- <h2>Filter:</h2> -->
                    <ul id="filter" class="option-set clearfix" data-option-key="filter">
                        <li><a id="categorie" href="#categorie" data-option-value=".categorie" class="selected">Categorie</a></li>
                        <li><a id="show-all" href="#show-all" data-option-value="*:not(.categorie), not(.menu_fissi)">Mostra tutto</a></li>
                        <li><a id="menu_fissi" href="#menu_fissi" data-option-value=".menu_fissi">Menù</a></li>
                </ul>
            </div>
            <div class="isotope-sort">
            <div class="option-combo">
            <!-- <h2>Sort:</h2> -->
            <ul id="sort" class="option-set clearfix" data-option-key="sortBy">
                <!-- <li><a href="#mixed" data-option-value="number">mixed</a></li> -->
                <li><a id="catSort" href="#categorie" data-option-value="original-order" class="selected">Ord. Cat.</a></li>
                <li><a href="#alphabetical" data-option-value="alphabetical">Ord. Alfab.</a></li>
            </ul>
            </div>
            </div>
            <div class="option-combo">
            <!-- <h2>Layout: </h2> -->
            <ul id="layouts" class="option-set clearfix" data-option-key="layoutMode">
                <li><a id="fitRows" href="#fitRows" data-option-value="fitRows" class="selected">Vista 1</a></li>
                <li><a href="#masonry" data-option-value="masonry">Vista 2</a></li>
                <li><a href="#straightDown" data-option-value="straightDown">Vista 3</a></li>
            </ul>
            </div>
            <div id="view-list"><a class="ui-btn-left" data-role="button" data-icon="arrow-r" data-inline="true">Lista</a></div>
        </section>

        <div id="container" class="super-list variable-sizes clearfix">
        <?php        ?>
        </div>
    </section>

    <!-- SEZIONE "OPZIONI" ALIMENTO -->
    <section id="cont-comm-opt" class="ui-body ui-body-e cl-comm-opt">       
        <div data-role="collapsible-set" data-collapsed="false" data-theme="a">
            <div class="opt-title" data-role="header" data-theme="b">
                <a class="ui-btn-left close-opt" data-role="button" data-theme="b" data-icon="arrow-l" data-inline="true">Chiudi</a>
                <div id="opt-alim-name" class="opt-alim-name"><h2>Nome Alimento</h2></div>                
            </div>
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="e" data-theme="e" data-collapsed="false" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Aggiungi - Elimina</h3>
                <fieldset class="ui-grid-a">
                    <div class="ui-block-a">
                        <a class="ui-btn-right alim-plus" data-role="button" data-icon="plus">Aggiungi 1</a>
                    </div>
                    <div class="ui-block-b">
                        <a class="ui-btn-right alim-min" data-role="button" data-icon="minus">Cancella 1</a>
                    </div>	   
                </fieldset>
                <a id="canc-all" href="#diag-conf-canc-all" data-rel="dialog" data-role="button" data-icon="delete" class="ui-btn-right">Cancella tutto</a>
            </div>
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="b" data-theme="b" data-collapsed="true" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Varianti</h3>
                <div id="opt-var"></div>
            </div>
        </div>
        <div id="alim-desc"></div>
    </section>
    
    <!-- SEZIONE "OPZIONI" MENU' FISSO -->
    <section id="cont-comm-opt-menu" class="ui-body ui-body-b cl-comm-opt">       
        <div data-role="collapsible-set" data-collapsed="false" data-theme="a">
            <div class="opt-title" data-role="header" data-theme="c">
                <a class="ui-btn-left close-opt" data-role="button" data-theme="c" data-icon="arrow-l" data-inline="true">Chiudi</a>
                <div id="opt-alim-name-menu" class="opt-alim-name"><h2>Nome Alimento</h2></div>                
            </div>
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="c" data-theme="c" data-collapsed="true" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Aggiungi - Elimina</h3>
                <fieldset class="ui-grid-a">
                    <div class="ui-block-a">
                        <a class="ui-btn-right alim-plus" data-role="button" data-icon="plus">Aggiungi 1</a>
                    </div>
                    <div class="ui-block-b">
                        <a class="ui-btn-right alim-min" data-role="button" data-icon="minus">Cancella 1</a>
                    </div>	   
                </fieldset>
                <a id="canc-all" href="#diag-conf-canc-all" data-rel="dialog" data-role="button" data-icon="delete" class="ui-btn-right">Cancella tutto</a>
            </div>
        </div>
        <h3>Selezionare le voci per comporre il menù:</h3>
        <div id="menu-sc-cat"></div>    
    </section>


<script src="../isotope/js/jquery-1.7.1.min.js"></script>
<script src="../isotope/jquery.isotope.min.js"></script>
<script src="scelta_ordine.js"></script>

</section> <!-- #content -->

</div> 
