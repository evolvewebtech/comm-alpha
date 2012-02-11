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
                $echostr .= '<a class="options-set3" href="'.$Almnt->id.'&'.$objEntity->nome.'&'.$Almnt->nome.'&'.$Almnt->prezzo.'" data-option-value=".'.$Almnt->nome.'">';
                if ($Almnt->colore_bottone == ""){
                    $echostr .= '<div class="element" style="background: #'.$objEntity->colore_bottone_predef.'">';
                }
                else $echostr .= '<div class="element" style="background: #'.$Almnt->colore_bottone.'">';
                $echostr .= '<p class="number">'.$Almnt->id.'</p>';
                $echostr .= '<h3 class="symbol">'.$Almnt->id.'</h3>';
                $echostr .= '<h2 class="name">'.$Almnt->nome.'</h2>';
                $echostr .= '<p class="weight">'.$Almnt->id.'</p>';
                $echostr .= '</div>';
                $echostr .= '</a>';
                $echostr .= '</div>';
            }
        }
        
        echo $echostr;
        
    ?>
  </div>
  <div id="sites"></div>
  
   
  <script src="../isotope/js/jquery-1.7.1.min.js"></script>
  <script src="../isotope/jquery.isotope.min.js"></script>
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
    
      
      /*
       *  Script che permette di filtrare gli elementi
       *  cliccando anche sui pulsanti "Filtro"
       *
       */
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

      
      /*
       *  Script per aggiungere gli alimenti selezionati
       *  alla lista dell'ordine
       *  
       */
      var $optionSets3 = $('.options-set3');
      $optionSets3.click(function(){
        
        var $this = $(this);
        
        //salvo nella var $param i parametri passati
        var $param = $this.attr('href');
        
        //separazione dei parametri passati con "href"  
        var $arrParam = $param.split('&');
        
        //creazione oggetto alimento contente i parametri
        var alimento = new Alim($arrParam[0], $arrParam[1], $arrParam[2], $arrParam[3], 0);
        
        //verifica se l'alimento è già stato aggiunto all'array
        var ver = false;  
        var memI = 0;
        for(var $i=0; $i<arrList.length; $i++) {
            if(arrList[$i]._id == $arrParam[0]) {
                arrList[$i]._num += 1;
                memI = $i;
                ver=true;
                break
            }          
        }

        //aggiunta alimento alla lista
        if (!ver) {           
            alimento._num = 1;
            arrList.push(alimento);
            
            var $itemString = '<div id='+$arrParam[0]+' class="element_list '+$arrParam[1]+'" data-symbol="Sc" data-category="'+$arrParam[1]+'" data-option-value=".'+$arrParam[0]+'">';
            $itemString = $itemString + '<a href="#" onClick="cancella('+$arrParam[0]+');">';
            $itemString = $itemString + '<div class="element_list el">';
            $itemString = $itemString + '<h1 class="num">'+alimento._num+'</h1>';
            $itemString = $itemString + '<h2 class="name">'+$arrParam[2]+'</h2>';
            $itemString = $itemString + '<h2 class="prezzo">'+$arrParam[3]+' €</h2>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</a>';
            $itemString = $itemString + '</div>';
                       
            var $newItems = $itemString;
            $('#container2').append( $newItems ).isotope( 'addItems', $newItems );          
        }
        //incremento quantità alimento
        else { 
            var costo = arrList[memI]._num * $arrParam[3];
            
            var $itemString = '';
            $itemString = $itemString + '<a href="#" onClick="cancella('+arrList[memI]._id+');">';
            $itemString = $itemString + '<div class="element_list el">';
            $itemString = $itemString + '<h1 class="num">'+arrList[memI]._num+'</h1>';
            $itemString = $itemString + '<h2 class="name">'+arrList[memI]._nome+'</h2>';
            $itemString = $itemString + '<h2 class="prezzo">'+costo+' €</h2>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</a>';
            
            //modifica del div già creato
            document.getElementById($arrParam[0]).innerHTML= $itemString;
        }
        
        
        var totale = 0;
        for(var $i=0; $i<arrList.length; $i++) {
            totale += arrList[$i]._prezzo * arrList[$i]._num;           
        }
        var $itemString = '';
        $itemString = $itemString + '<h2 class="name">Totale:</h2>';
        $itemString = $itemString + '<h2 class="prezzo">'+totale+' €</h2>';
        
        //modifica del div già creato
        document.getElementById("totale").innerHTML= $itemString;
        
        return false;
      });
      
      
      
      /*
       *  Oggetto alimento
       *  
       */
      function Alim(id, cat, nome, prezzo, num) {
        this._id = id;
        this._cat = cat;
        this._nome = nome;
        this._prezzo = prezzo;
        this._num = num;
      }
      
      
    });
  </script>
  
  
  
  
  <script language="JavaScript">
    function cancella($id){        
        for(var $i=0; $i<arrList.length; $i++) {
            if (arrList[$i]._id == $id) {
                if (arrList[$i]._num > 1) {
                    arrList[$i]._num -= 1;
                    var costo = arrList[$i]._num * arrList[$i]._prezzo;

                    var $itemString = '';
                    $itemString = $itemString + '<a href="#" onClick="cancella('+$id+');">';
                    $itemString = $itemString + '<div class="element_list el">';
                    $itemString = $itemString + '<h1 class="num">'+arrList[$i]._num+'</h1>';
                    $itemString = $itemString + '<h2 class="name">'+arrList[$i]._nome+'</h2>';
                    $itemString = $itemString + '<h2 class="prezzo">'+costo+' €</h2>';
                    $itemString = $itemString + '</div>';
                    $itemString = $itemString + '</a>';

                    //modifica del div già creato
                    document.getElementById($id).innerHTML= $itemString;
                }
                else { 
                    arrList.splice($i, 1);
                    var box = document.getElementById($id);
                    box.innerHTML= "";
                    box.parentNode.removeChild(box);
                }
                break;
            }
        }
        
        var totale = 0;
        for(var $i=0; $i<arrList.length; $i++) {
            totale += arrList[$i]._prezzo * arrList[$i]._num;           
        }
        var $itemString = '';
        $itemString = $itemString + '<h2 class="name">Totale:</h2>';
        $itemString = $itemString + '<h2 class="prezzo">'+totale+' €</h2>';
        
        //modifica del div già creato
        document.getElementById("totale").innerHTML= $itemString; 
    }
    

       
  </script>
 
</section> <!-- #content -->
