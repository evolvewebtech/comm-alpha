<!doctype html>
<html lang="en">
<head>
  
  <meta charset="utf-8" />
  <title>Prova Isotope</title>
  
  <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- scripts at bottom of page -->

</head>
<body class="homepage ">
  
    <nav id="site-nav">
        <h1><a href="index.html">Prova Isotope</a></h1>
        <h2>Menu 1</h2>
        <ul></ul>

        <h2>Menu 2</h2>
        <ul></ul>

        <h2>Menu 3</h2>
        <ul></ul>

        <!-- <h2><a href="tests/index.html">Tests</a></h2> -->
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
                    <li><a id="show-all" href="#show-all" data-option-value="*:not(.categorie)" class="selected">show all</a></li>
                    <li><a id="categorie" href="#categorie" data-option-value=".categorie">categorie</a></li>

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

                <!--
                <li><a href="#primi" data-option-value=".primi">primi</a></li>
                <li><a href="#secondi" data-option-value=".secondi">secondi</a></li>
                <li><a href="#bevande" data-option-value=".bevande">bevande</a></li>
                <li><a href="#caffe" data-option-value=".caffe">caffe</a></li>
                -->
              </ul>
        </div>
        <div class="option-combo">
          <h2>Sort:</h2>
          <ul id="sort" class="option-set clearfix" data-option-key="sortBy">
            <li><a href="#mixed" data-option-value="number" class="selected">mixed</a></li>
            <li><a href="#categorie" data-option-value="original-order">categorie</a></li>
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
    
      
  <!--
    <div class="element alkaline-earth metal   " data-symbol="Mg" data-category="alkaline-earth">
      <p class="number">12</p>
      <h3 class="symbol">Mg</h3>
      <h2 class="name">Magnesium</h2>
      <p class="weight">24.305</p>
    </div>
    
  -->
      



    <?php
        
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        
        $arContacts = DataManager2::getAllCategoriesAsObjects();
        
        $echostr = "";
        $num = 0;
        
        foreach($arContacts as $objEntity) {
            $numAlmt = $objEntity->getNumberOfAlimenti();

            //ho aggiunto il tag <a></a>
            $echostr .= '<a class="options-set2" href="#'.$objEntity->nome.'" data-option-value=".'.$objEntity->nome.'">';
            $echostr .= '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
            $echostr .= '<p class="number">'.$num.'</p>';
            $echostr .= '<h3 class="symbol">'.$num.'</h3>';
            $echostr .= '<h2 class="name">'.$objEntity->nome.'</h2>';
            $echostr .= '<p class="weight">'.$num.'</p>';
            $echostr .= '</div>';
            $echostr .= '</a>';
            $num += 1;
            
            for($j=0; $j<$numAlmt; $j++) {
                $Almnt = $objEntity->getAlimento($j);
                
                $echostr .= '<div class="element '.$objEntity->nome.'" data-symbol="Sc" data-category="'.$objEntity->nome.'">';
                $echostr .= '<p class="number">'.$j.'</p>';
                $echostr .= '<h3 class="symbol">'.$j.'</h3>';
                $echostr .= '<h2 class="name">'.$Almnt->nome.'</h2>';
                $echostr .= '<p class="weight">'.$j.'</p>';
                $echostr .= '</div>';
            }
        }

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
        sortBy: 'number',
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
        }
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

      //Questa parte aggiunge le caselle con i link ai siti

    
      // Sites using Isotope markup
      var $sites = $('#sites'),
          $sitesTitle = $('<h2 class="loading"><img src="http://i.imgur.com/qkKy8.gif" />Loading sites using Isotope</h2>'),
          $sitesList = $('<ul class="clearfix"></ul>');
        
      $sites.append( $sitesTitle ).append( $sitesList );

      $sitesList.isotope({
        layoutMode: 'cellsByRow',
        cellsByRow: {
          columnWidth: 290,
          rowHeight: 400
        }
      });
    
      var ajaxError = function(){
        $sitesTitle.removeClass('loading').addClass('error')
          .text('Could not load sites using Isotope :(');
      };
    
      // dynamically load sites using Isotope from Zootool
      $.getJSON('http://zootool.com/api/users/items/?username=desandro' +
          '&apikey=8b604e5d4841c2cd976241dd90d319d7' +
          '&tag=bestofisotope&callback=?')
        .error( ajaxError )
        .success(function( data ){

          // proceed only if we have data
          if ( !data || !data.length ) {
            ajaxError();
            return;
          }
          var items = [],
              item, datum;

          for ( var i=0, len = data.length; i < len; i++ ) {
            datum = data[i];
            item = '<li><a href="' + datum.url + '">'
              + '<img src="' + datum.image.replace('/l.', '/m.') + '" />'
              + '<b>' + datum.title + '</b>'
              + '</a></li>';
            items.push( item );
          }
        
          var $items = $( items.join('') )
            .addClass('example');
            
          // set random number for each item
          $items.each(function(){
            $(this).attr('data-number', ~~( Math.random() * 100 + 15 ));
          });
        
          $items.imagesLoaded(function(){
            $sitesTitle.removeClass('loading').text('Sites using Isotope');
            $container.append( $items );
            $items.each(function(){
              var $this = $(this),
                  itemHeight = Math.ceil( $this.height() / 120 ) * 120 - 10;
              $this.height( itemHeight );
            });
            $container.isotope( 'insert', $items );
          });
        
        });
    
    */

    });
  </script>

  <footer>
  </footer>
  
</section> <!-- #content -->
  

</body>
</html>