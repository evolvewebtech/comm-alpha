
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
        
        <div data-role="collapsible" data-collapsed="false" data-theme="a">
            <!-- <h1>H1 Heading</h1> -->
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="e" data-theme="e" data-collapsed="false" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Aggiungi - Elimina</h3>
                <a id="canc" data-role="button" data-icon="home" class="ui-btn-right">Cancella</a>
                <a id="canc-all" data-role="button" data-icon="home" class="ui-btn-right">Cancella tutto</a>
            </div>
            <div class="ui-collapsible ui-collapsible-collapsed" data-content-theme="b" data-theme="b" data-collapsed="true" data-role="collapsible">
                <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">Varianti</h3>
            </div>
        </div>
    </section>
    
  
  <script src="../isotope/js/jquery-1.7.1.min.js"></script>
  <script src="../isotope/jquery.isotope.min.js"></script>
  <script>
    $(function(){
    
      var $container = $('#container');
      
      /*
       * Inizializzazione Isotope
       *
       */
      /*$container.isotope({
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
        filter: '.categorie',
        layoutMode: 'fitRows'
      });*/
    
      
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
      $optionSets2.live("click", function() {

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
       *  Script per aggiungere gli alimenti selezionati
       *  alla lista dell'ordine
       *  
       */
      var $optionSets3 = $('.options-set3');
      $optionSets3.live("click", function() {
        
        var $this = $(this);
        
        //salvo nella var $param i parametri passati
        var $param = $this.attr('href');
        
        //eliminazione carattere iniziale '#'
        $param = $param.replace('#','');
        
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
            
            //ordinamento lista           
            $itemString = ordinaLista("cat");
                   
            var $newItems = $itemString; 
            document.getElementById('container2').innerHTML = $newItems;
        }
        //incremento quantità alimento
        else { 
            var costo = arrList[memI]._num * $arrParam[3];
            
            var $itemString = '';
            /*$itemString = $itemString + '<a href="#" onClick="itemOpt('+arrList[memI]._id+');">';
            $itemString = $itemString + '<div class="element_list el">';
            $itemString = $itemString + '<h1 class="num">'+arrList[memI]._num+'</h1>';
            $itemString = $itemString + '<h2 class="name">'+arrList[memI]._nome+'</h2>';
            $itemString = $itemString + '<h2 class="prezzo">'+costo+' €</h2>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</a>';
            */
            $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
            $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[memI]._id+');">';
            $itemString = $itemString + '<div class="num">'+arrList[memI]._num+'</div>';
            $itemString = $itemString + '<div class="name">'+arrList[memI]._nome+'</div>';
            $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
            $itemString = $itemString + '</a>';
            $itemString = $itemString + '</div>';
            
            //modifica del div già creato
            document.getElementById($arrParam[0]).innerHTML= $itemString;
            
            //aggiorna totale costo
            totale = 0;
            for(var $i=0; $i<arrList.length; $i++) {
                totale += arrList[$i]._num * arrList[$i]._prezzo;           
            }
        }
        
        //aggiornamento visualizzazione totale costo
        var $itemString = '';
        $itemString = $itemString + '<h2 class="name">Totale:</h2>';
        $itemString = $itemString + '<h2 class="prezzo">'+totale+' €</h2>';       
        //modifica del div già creato
        document.getElementById("totale").innerHTML= $itemString;
        
        return false;
      });
      
      
      /*
       * Script per ordinare la lista degli alimenti selezionati
       * 
       */
      var $optionSetsList = $('#options .option-set_list'),
          $optionSetsList = $optionSetsList.find('a');

      $optionSetsList.click(function(){
        var $this = $(this);

        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set_list');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
        
        // make option object dynamically
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( value === 'nome' ) {
          var $itemString = ordinaLista("nome");                  
          var $newItems = $itemString; 
          document.getElementById('container2').innerHTML = $newItems;
        } else {
          var $itemString = ordinaLista("cat");                  
          var $newItems = $itemString; 
          document.getElementById('container2').innerHTML = $newItems;
        }
        
        return false;
      });
      
      
      /*
       *  Oggetto alimento
       *  
       */
      function Alim(id, cat, nome, prezzo, num, varianti) {
        this._id = id;
        this._cat = cat;
        this._nome = nome;
        this._prezzo = prezzo;
        this._num = num;
        this._varianti = varianti;
      }
      
      
      /*
       *  Oggetto variante
       *  
       */
      function Variante(id, descrizione, prezzo) {
        this._id = id;
        this._descrizione = descrizione;
        this._prezzo = prezzo;
      }
      
          
      /*
       * Confronto tra stringhe
       * 
       */
      function confrontaStringhe(a,b) {
        var minA = a.toLowerCase();
        var minB = b.toLowerCase();
        if (minA > minB) { return true; }
        else { return false; }
      }
      
      /*
       * Ordinamento lista
       * 
       */
      function ordinaLista(type) {
        //Ordinamento array in ordine afabetico
        if (type == "nome") {
            for(var i=0; i<arrList.length-1; i++) {
                for(var j=i+1; j<arrList.length; j++) {
                    //ordinato per ID
                    //if(arr[i]._id > arr[j]._id) {
                    //ordinato in ordine alfabetico
                    if (confrontaStringhe(arrList[i]._nome, arrList[j]._nome)) {
                        var t = arrList[i];
                        arrList[i] = arrList[j];
                        arrList[j] = t;
                    }                  
                }   
            }
        }
        //Ordinamento array per categorie
        if (type == "cat" ) {
            //1° ordinamento -> categorie
            for(var i=0; i<arrList.length-1; i++) {
                for(var j=i+1; j<arrList.length; j++) {
                    //ordinato per ID
                    //if(arr[i]._id > arr[j]._id) {
                    //ordinato in ordine alfabetico
                    if (confrontaStringhe(arrList[i]._cat, arrList[j]._cat)) {
                        var t = arrList[i];
                        arrList[i] = arrList[j];
                        arrList[j] = t;
                    }                  
                }   
            }
            //2° ordinamento -> nomi
            for(var i=0; i<arrList.length-1; i++) {
                for(var j=i+1; j<arrList.length; j++) {
                    //ordinato per ID
                    //if(arr[i]._id > arr[j]._id) {
                    //ordinato in ordine alfabetico
                    if (arrList[i]._cat == arrList[j]._cat) {
                        if (confrontaStringhe(arrList[i]._nome, arrList[j]._nome)) {
                            var t = arrList[i];
                            arrList[i] = arrList[j];
                            arrList[j] = t;
                        }
                    }
                }   
            }
        }
        return aggiornaLista(type);
      }
      
      /*
       * Aggiornamento visualizzazione lista
       * 
       */
      function aggiornaLista(type) {
        //Ricreo tutti i div della lista
        //dopo aver ordinato l'array
        var $itemString = "";  
        var memCat = "";
        totale = 0;
        for(var i=0; i<arrList.length; i++) {
            //calcolo costo per alimento
            var costo = arrList[i]._num * arrList[i]._prezzo;
            //calcolo totale costo
            totale += costo; 
            //aggiunta separatore categorie
            if (type == "cat" & memCat != arrList[i]._cat) {
                //$itemString = $itemString + '<div id="id-cat" class="element_list">';
                //$itemString = $itemString + '<h2 class="name">'+arrList[i]._cat+'</h2>';
                //$itemString = $itemString + '</div>';
                $itemString = $itemString + '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-btn-up-undefined" data-role="list-divider" role="heading" style="height:20px;padding-top:0px;padding-bottom:0px">'+arrList[i]._cat+'</li>';
                memCat = arrList[i]._cat;
            }
            //creazione div
            /*$itemString = $itemString + '<div id='+arrList[i]._id+' class="element_list '+arrList[i]._cat+'" data-symbol="Sc" data-category="'+arrList[i]._cat+'" data-option-value=".'+arrList[i]._id+'">';
            $itemString = $itemString + '<a href="#" onClick="itemOpt('+arrList[i]._id+');">';
            $itemString = $itemString + '<div class="element_list el">';
            $itemString = $itemString + '<h1 class="num">'+arrList[i]._num+'</h1>';
            $itemString = $itemString + '<h2 class="name">'+arrList[i]._nome+'</h2>';
            $itemString = $itemString + '<h2 class="prezzo">'+costo+' €</h2>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</a>';
            $itemString = $itemString + '</div>';
            */
            
            $itemString = $itemString + '<li id='+arrList[i]._id+' class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c" data-theme="c">';
            $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
            $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[i]._id+');">';
            $itemString = $itemString + '<div class="num">'+arrList[i]._num+'</div>';
            $itemString = $itemString + '<div class="name">'+arrList[i]._nome+'</div>';
            $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
            $itemString = $itemString + '</a>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</li>';
        }
        return $itemString;
      }
      
    });
  </script>
  
  
  
  
  <script>
    /*
     * Inizializzazione sezione "opzioni"
     * 
     */
    $(function() {
        $('#cont-comm-ord').show('fast');
        $('#cont-comm-opt').hide('fast');
    });
    
    
    /*
     * Toggle visualizzazione sezione "opzioni"
     * 
     */
    function itemOpt($id){
        if (!show_opt || $id!=mem_id) { 
            $('#cont-comm-ord').hide('fast');
            $('#cont-comm-opt').show('fast');
            show_opt = true;
            //var $optionSet = $(this).parents('.comm-li-link');
            //$optionSet.find('.selected').removeClass('selected');
            //$(this).addClass('selected');
        }
        else {
            $('#cont-comm-ord').show('fast');
            $('#cont-comm-opt').hide('fast');
            show_opt = false;
            //var $optionSet = $(this).parents('.comm-li-link');
            //$optionSet.find('.selected').removeClass('selected');
        }
        mem_id = $id;       
    }
    
    
    function aggiornaTotale(){
        var totale = 0;
        for(var $i=0; $i<arrList.length; $i++) {
            totale += arrList[$i]._num * arrList[$i]._prezzo;           
        }
        var $itemString = '';
        $itemString = $itemString + '<h2 class="name">Totale:</h2>';
        $itemString = $itemString + '<h2 class="prezzo">'+totale+' €</h2>';
        
        //modifica del div già creato
        document.getElementById("totale").innerHTML= $itemString; 
    }
        
    
    /*
     * Decremento o cancellazione alimento dalla lista
     * 
     */
    $('#canc').bind("click", function() {
        $id = mem_id;
        for(var $i=0; $i<arrList.length; $i++) {
            if (arrList[$i]._id == $id) {
                if (arrList[$i]._num > 1) {
                    arrList[$i]._num -= 1;
                    var costo = arrList[$i]._num * arrList[$i]._prezzo;

                    var $itemString = '';
                    /*$itemString = $itemString + '<a href="#" onClick="itemOpt('+$id+');">';
                    $itemString = $itemString + '<div class="element_list el">';
                    $itemString = $itemString + '<h1 class="num">'+arrList[$i]._num+'</h1>';
                    $itemString = $itemString + '<h2 class="name">'+arrList[$i]._nome+'</h2>';
                    $itemString = $itemString + '<h2 class="prezzo">'+costo+' €</h2>';
                    $itemString = $itemString + '</div>';
                    $itemString = $itemString + '</a>';
                    */
                    
                    $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
                    $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[$i]._id+');">';
                    $itemString = $itemString + '<div class="num">'+arrList[$i]._num+'</div>';
                    $itemString = $itemString + '<div class="name">'+arrList[$i]._nome+'</div>';
                    $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
                    $itemString = $itemString + '</a>';
                    $itemString = $itemString + '</div>';

                    //modifica del div già creato
                    document.getElementById($id).innerHTML= $itemString;
                }
                else { 
                    arrList.splice($i, 1);
                    var box = document.getElementById($id);
                    box.innerHTML= "";
                    box.parentNode.removeChild(box);
                    
                    $('#cont-comm-ord').show('fast');
                    $('#cont-comm-opt').hide('fast');
                    show_opt = false;
                }
                break;
            }
        }
        
        aggiornaTotale();
    });
    
    
    /*
     * Cancellazione alimento dalla lista
     * 
     */
    $('#canc-all').bind("click", function() {
        $id = mem_id;
        for(var $i=0; $i<arrList.length; $i++) {
            if (arrList[$i]._id == $id) {
                arrList.splice($i, 1);
                var box = document.getElementById($id);
                box.innerHTML= "";
                box.parentNode.removeChild(box);

                $('#cont-comm-ord').show('fast');
                $('#cont-comm-opt').hide('fast');
                show_opt = false;
                break;
            }
        }
        
        aggiornaTotale();
    });
    

  </script>
  
</section> <!-- #content -->
