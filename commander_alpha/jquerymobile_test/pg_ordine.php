
<div class="comm-a">
<section id="contentList">
    <div id="list">
    <div id="list-ord_nav" class="list-ord_nav">
        <section id="options" class="clearfix">
            <div class="option-combo">
                <ul id="sort" class="option-set_list clearfix" data-option-key="sortBy">
                    <li><a href="#categorie" data-option-value="cat" class="selected">Categorie</a></li>
                    <li><a href="#alphabetical" data-option-value="nome">Alfabetico</a></li>
                </ul>
            </div>
        </section>
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
            <div class="option-combo">
            <!-- <h2>Sort:</h2> -->
            <ul id="sort" class="option-set clearfix" data-option-key="sortBy">
                <!-- <li><a href="#mixed" data-option-value="number">mixed</a></li> -->
                <li><a id="catSort" href="#categorie" data-option-value="original-order" class="selected">Categorie</a></li>
                <li><a href="#alphabetical" data-option-value="alphabetical">Alfabetico</a></li>
            </ul>
            </div>
            <div class="option-combo">
            <!-- <h2>Layout: </h2> -->
            <ul id="layouts" class="option-set clearfix" data-option-key="layoutMode">
                <li><a id="fitRows" href="#fitRows" data-option-value="fitRows" class="selected">Vista 1</a></li>
                <li><a href="#masonry" data-option-value="masonry">Vista 2</a></li>
                <li><a href="#straightDown" data-option-value="straightDown">Vista 3</a></li>
            </ul>
            </div>
        </section>

        <div id="container" class="super-list variable-sizes clearfix">

        <?php        
            /*require_once dirname(__FILE__).'/../manager/DataManager2.php';

            $arCat = DataManager2::getAllCategoriesAsObjects();

            $echostr = "";
            $num = 0;

            foreach($arCat as $objEntity) {
                $numAlmt = $objEntity->getNumberOfAlimenti();

                $echostr .= '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
                $echostr .= '<a class="options-set2" href="#'.$objEntity->nome.'" data-option-value=".'.$objEntity->nome.'">';
                $echostr .= '<div class="element" style="background: #'.$objEntity->colore_bottone_predef.'">';
                $echostr .= '<h2 class="el-name">'.$objEntity->nome.'</h2>';
                $echostr .= '</div>';
                $echostr .= '</a>';
                $echostr .= '</div>';
                $num += 1;

                for($j=0; $j<$numAlmt; $j++) {
                    $Almnt = $objEntity->getAlimento($j);

                    $echostr .= '<div class="element '.$objEntity->nome.'" data-symbol="Sc" data-category="'.$objEntity->nome.'">';
                    $echostr .= '<a class="options-set3" href="'.$Almnt->id.'&'.$objEntity->nome.'&'.$Almnt->nome.'&'.$Almnt->prezzo.'" data-option-value=".'.$Almnt->nome.'">';
                    if ($Almnt->colore_bottone == ""){
                        $echostr .= '<div class="element" style="background: #'.$objEntity->colore_bottone_predef.'">';
                    }
                    else $echostr .= '<div class="element" style="background: #'.$Almnt->colore_bottone.'">';
                    $echostr .= '<h2 class="el-name">'.$Almnt->nome.'</h2>';
                    $echostr .= '<h2 class="el-prezzo">'.$Almnt->prezzo.' €</h2>';
                    $echostr .= '<h3 class="el-cat">'.$objEntity->nome.'</h3>';
                    $echostr .= '</div>';
                    $echostr .= '</a>';
                    $echostr .= '</div>';
                }
            }

            echo $echostr;*/

        ?>
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
<!-- <script src="scelta_ordine.js"></script> -->

</section> <!-- #content -->

</div> 
