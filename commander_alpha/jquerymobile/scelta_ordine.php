
<section id="content">
    <section id="cont-comm-ord">
        <section id="options" class="clearfix">
            <div class="option-combo">

                <!--
                    ho aggiunto un id ad ogni link, il valore è uguale al
                    valore di ogni attributo href
                -->
                <!-- <h2>Filter:</h2> -->
                    <ul id="filter" class="option-set clearfix" data-option-key="filter">
                        <li><a id="show-all" href="#show-all" data-option-value="*:not(.categorie), not(.menu_fissi)">Mostra tutto</a></li>
                        <li><a id="menu_fissi" href="#menu_fissi" data-option-value=".menu_fissi">Menù</a></li>
                        <li><a id="categorie" href="#categorie" data-option-value=".categorie" class="selected">Categorie</a></li>

                        <?php
                            require_once dirname(__FILE__).'/../manager/DataManager2.php';
                            $arContacts = DataManager2::getAllCategoriesAsObjects();
                            $echostr = "";
                            foreach($arContacts as $objEntity) {
                                $echostr .= '<li>';
                                $echostr .= '<a id="'.$objEntity->nome.'" href="#'.$objEntity->nome.'" data-option-value=".'.$objEntity->nome.'">'.$objEntity->nome.'</a>';
                                $echostr .= '</li>';
                            }
                            echo $echostr;
                        ?>
                </ul>
            </div>
            <div class="option-combo">
            <!-- <h2>Sort:</h2> -->
            <ul id="sort" class="option-set clearfix" data-option-key="sortBy">
                <!-- <li><a href="#mixed" data-option-value="number">mixed</a></li> -->
                <li><a href="#categorie" data-option-value="original-order" class="selected">Categorie</a></li>
                <li><a href="#alphabetical" data-option-value="alphabetical">Alfabetico</a></li>
            </ul>
            </div>
            <div class="option-combo">
            <!-- <h2>Layout: </h2> -->
            <ul id="layouts" class="option-set clearfix" data-option-key="layoutMode">
                <li><a href="#masonry" data-option-value="masonry">masonry</a></li>
                <li><a href="#fitRows" data-option-value="fitRows" class="selected">fitRows</a></li>
                <li><a href="#straightDown" data-option-value="straightDown">straightDown</a></li>
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
    
    <section id="cont-comm-opt" class="ui-body ui-body-e">
        <div class="comm-opt-title"></div>
        <div class="comm-opt">
            <div class="comm-opt-var">
            
            </div>
            <div class="comm-opt-del">
                
            </div>
        </div>
        <div class="comm-opt-foo"></div> 
        
        <div data-role="collapsible-set" data-collapsed="false" data-theme="a">
            <!-- <h1>H1 Heading</h1> -->
            <div id="opt-title" data-role="header" data-theme="b">
                <a id="close-opt" data-role="button" data-theme="b" data-icon="arrow-l" data-inline="true" class="ui-btn-left">Chiudi</a>
                <div id="opt-alim-name"><h2>Nome Alimento</h2></div>                
            </fieldset>
            </div>
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="e" data-theme="e" data-collapsed="false" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Aggiungi - Elimina</h3>
                <fieldset class="ui-grid-a">
                    <div class="ui-block-a">
                        <a id="alim-plus" data-role="button" data-icon="plus" class="ui-btn-right">Aggiungi 1</a>
                    </div>
                    <div class="ui-block-b">
                        <a id="alim-min" data-role="button" data-icon="minus" class="ui-btn-right">Cancella 1</a>
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
    
  
  <script src="../isotope/js/jquery-1.7.1.min.js"></script>
  <script src="../isotope/jquery.isotope.min.js"></script>
  <script src="ordine.js"></script>
  
</section> <!-- #content -->
