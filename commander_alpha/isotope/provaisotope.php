<!doctype html>
<html lang="it-IT">
<head>
  
  <meta charset="utf-8" />
  <title>Prova Isotope</title>
  
  <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- scripts at bottom of page -->

</head>
<body class="homepage ">
  
    <nav id="site-nav">
        <h1><a href="provaisotope.php">Prova Isotope</a></h1>
        <h2>Menu 1</h2>
        <ul></ul>

        <h2>Menu 2</h2>
        <ul></ul>   

        <h2>Menu 3</h2>
        <ul></ul>

        <!-- Lista degli alimenti selezionati -->
        <div id="container2" class="super-list variable-sizes clearfix"></div>
    </nav> <!-- #site-nav -->
 
    
    <section id="content">
    <section id="options" class="clearfix">
        <div class="option-combo">

            <!--
                ho aggiunto un id ad ogni link, il valore è uguale al
                valore di ogni attributo href
            -->
            <h2>Filter:</h2>
                <ul id="filter" class="option-set clearfix" data-option-key="filter">
                    <li><a id="show-all" href="#show-all" data-option-value="*:not(.categorie), not(.menu_fissi)">show all</a></li>
                    <li><a id="menu_fissi" href="#menu_fissi" data-option-value=".menu_fissi">menu fissi</a></li>
                    <li><a id="categorie" href="#categorie" data-option-value=".categorie" class="selected">categorie</a></li>

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
          <h2>Sort:</h2>
          <ul id="sort" class="option-set clearfix" data-option-key="sortBy">
            <li><a href="#mixed" data-option-value="number">mixed</a></li>
            <li><a href="#categorie" data-option-value="original-order" class="selected">categorie</a></li>
            <li><a href="#alphabetical" data-option-value="alphabetical">alphabetical</a></li>
          </ul>
        </div>
        <div class="option-combo">
          <h2>Layout: </h2>
          <ul id="layouts" class="option-set clearfix" data-option-key="layoutMode">
            <li><a href="#masonry" data-option-value="masonry" class="selected">masonry</a></li>
            <li><a href="#fitRows" data-option-value="fitRows">fitRows</a></li>
            <li><a href="#straightDown" data-option-value="straightDown">straightDown</a></li>
          </ul>
        </div>
    </section>
  
    <div id="container" class="super-list variable-sizes clearfix">

        <?php        
            require_once dirname(__FILE__).'/../manager/DataManager2.php';

            $arContacts = DataManager2::getAllCategoriesAsObjects();

            $echostr = "";
            $num = 0;

            foreach($arContacts as $objEntity) {
                $numAlmt = $objEntity->getNumberOfAlimenti();

                $echostr .= '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
                $echostr .= '<a class="options-set2" href="#'.$objEntity->nome.'" data-option-value=".'.$objEntity->nome.'">';
                $echostr .= '<div class="element" style="background: #'.$objEntity->colore_bottone_predef.'">';
                $echostr .= '<p class="number">'.$num.'</p>';
                $echostr .= '<h3 class="symbol">'.$num.'</h3>';
                $echostr .= '<h2 class="name">'.$objEntity->nome.'</h2>';
                $echostr .= '<p class="weight">'.$num.'</p>';
                $echostr .= '</div>';
                $echostr .= '</a>';
                $echostr .= '</div>';
                $num += 1;

                for($j=0; $j<$numAlmt; $j++) {
                    $Almnt = $objEntity->getAlimento($j);

                    $echostr .= '<div class="element '.$objEntity->nome.'" data-symbol="Sc" data-category="'.$objEntity->nome.'">';
                    $echostr .= '<a class="options-set3" href="#'.$Almnt->nome.'" data-option-value=".'.$Almnt->nome.'">';
                    if ($Almnt->colore_bottone == ""){
                        $echostr .= '<div class="element" style="background: #'.$objEntity->colore_bottone_predef.'">';
                    }
                    else $echostr .= '<div class="element" style="background: #'.$Almnt->colore_bottone.'">';
                    $echostr .= '<p class="number">'.$j.'</p>';
                    $echostr .= '<h3 class="symbol">'.$j.'</h3>';
                    $echostr .= '<h2 class="name">'.$Almnt->nome.'</h2>';
                    $echostr .= '<p class="weight">'.$j.'</p>';
                    $echostr .= '</div>';
                    $echostr .= '</a>';
                    $echostr .= '</div>';
                }
            }

            //$echostr .= '<href="#categorie">';
            echo $echostr;

        ?>
    </div>
    <div id="sites"></div>


    <script src="js/jquery-1.7.1.min.js"></script>
    <script src="jquery.isotope.min.js"></script>
    <script>
        $(function(){

        var $container = $('#container');

        $container.isotope({
            masonry: {
            columnWidth: 120
            },
            sortBy: 'categorie',
            getSortData: {
            number: function( $elem ) {
                var number = $elem.hasClass('element') ? 
                $elem.find('.number').text() :
                $elem.attr('data-number');
                return parseInt( number, 10 );
            },
            alphabetical: function( $elem ) {
                var name = $elem.find('.name'),
                    itemText = name.length ? name : $elem;
                return itemText.text();
            }
            },
            //Aggiunto per visualizzare solo le categorie
            //al caricamento della pagina
            filter: '.categorie'
        });


        var $optionSets = $('#options .option-set'),
            $optionLinks = $optionSets.find('a');

        $optionLinks.click(function(){
            var $this = $(this);

            // don't proceed if already selected
            if ( $this.hasClass('selected') ) {
            return false;
            }
            var $optionSet = $this.parents('.option-set');
            $optionSet.find('.selected').removeClass('selected');
            $this.addClass('selected');

            // make option object dynamically, i.e. { filter: '.my-filter-class' }
            var options = {},
                key = $optionSet.attr('data-option-key'),
                value = $this.attr('data-option-value');
            // parse 'false' as false boolean
            value = value === 'false' ? false : value;
            options[ key ] = value;
            if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
            // changes in layout modes need extra logic
            changeLayoutMode( $this, options )
            } else {
            // otherwise, apply new options
            $container.isotope( options );
            }

            return false;
        });

        /*
        *  Script che permette di filtrare gli elementi
        *  cliccando anche su di essi.
        *  Funziona solo per il menu "#filter"
        *
        */
        var $optionSets2 = $('.options-set2');
        $optionSets2.click(function(){

            var $this = $(this);

            //salvo nella var $categoria il valore della
            //categoria che desidero impostare a selected
            var $categoria = $this.attr('href');

            //devo prelevare l'oggetto con questa categoria
            //per poter aggiungerlgi la classe selected
            var $this = $($categoria);
            //console.log($this);

            //non dovrebbe + occorrere, da testare
            // don't proceed if already selected
            if ( $this.hasClass('selected') ) {
            alert('azz');
            return false;
            }        


            //ul#filter.option-set]
            var $optionSet = $('#filter');

            $optionSet.find('.selected').removeClass('selected');
            $this.addClass('selected');

            // make option object dynamically, i.e. { filter: '.my-filter-class' }
            var options = {},
                key = $optionSet.attr('data-option-key'),
                value = $this.attr('data-option-value');
            // parse 'false' as false boolean
            value = value === 'false' ? false : value;
            options[ key ] = value;$
            if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
            // changes in layout modes need extra logic
            changeLayoutMode( $this, options )
            } else {
            // otherwise, apply new options
            $container.isotope( options );
            }

            return false;
        });





        var $optionSets3 = $('.options-set3');
        $optionSets3.click(function(){

            var $this = $(this);

            //salvo nella var $nome il nome dell'alimento
            var $nome = $this.attr('href');

            //aggiungo al container2 il nome degli alimenti cliccati
            var $itemString = '<div>' + $nome + '</div>';
            var $newItems = $($itemString);
            $('#container2').append( $newItems ).isotope( 'addItems', $newItems );

            //alert($nome);

            return false;
        });



        });
    </script>



    </section> <!-- #content -->    

  
  <footer>
  </footer>
  
</section> <!-- #content -->
  

</body>
</html>