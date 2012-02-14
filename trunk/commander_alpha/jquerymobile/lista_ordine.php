
<div id="list-ord_nav" class="list-ord_nav">
    <section id="options" class="clearfix">
        <div class="option-combo">
            <ul id="sort" class="option-set_list clearfix" data-option-key="sortBy">
                <li><a href="#categorie" data-option-value="original-order" class="selected">Categorie</a></li>
                <li><a href="#alphabetical" data-option-value="alphabetical">Alfabetico</a></li>
            </ul>
        </div>
    </section>
</div>

<div id="list-ord" class="list-ord">
    <div id="container2" class="super-list variable-sizes clearfix"></div>
    <script>
        //Dichiarazione variabili;
        var arrList = new Array();
        var totale = 0;
    </script>               
</div>

<div id="list-ord_footer" class="list-ord_footer">
    <div id="totale" class="element_list">
        <h2 class="name">Totale:</h2>
        <h2 class="prezzo">0 €</h2>
    </div>
    <a href="#chiusura" data-role="button" data-icon="home" class="ui-btn-right">Conferma ordine</a>
</div>


<script type="text/javascript">
    var h_nav = document.getElementById("list-ord_nav").style.height;
    var h_foo = document.getElementById("list-ord_footer").style.height;
    document.getElementById("list-ord").style.height = (window.innerHeight-250) - h_nav - h_foo + "px";
</script>

<!--
<script src="../isotope/js/jquery-1.7.1.min.js"></script>
<script src="../isotope/jquery.isotope.min.js"></script>
<script>
    
    $(function(){
    
      var $container2 = $('#container2');
      
      /*
      $('#container2').isotope({
        sortBy: 'alphabetical',
        //itemSelector : '.element_list',
        getSortData: {
          categorie: function( $elem ) {
            return parseInt( $elem.find('.num').text(), 10 );
          },
          alphabetical: function( $elem ) {
            return $elem.find('.name').text();
          }
        }
        
      });*/
     
      
      /*
      var $optionSets2 = $('#options .option-set_list'),
          $optionLinks2 = $optionSets2.find('a');

      $optionLinks2.click(function(){
        var $this = $(this);

        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set_list');
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
          $container2.isotope( options );
        }
        
        return false;
      });*/
      
    });  
</script>
-->