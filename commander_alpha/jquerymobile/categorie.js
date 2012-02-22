
$("#ordine").live('pageshow', function() {

        $.ajax({
            type : "GET",
            url: "categorie.php",
            dataType: 'json',
            cache: false,
            success: onEventoInfoSuccess,
            error: onEventoInfoError
        });
        return false;    
 });


function onEventoInfoSuccess(data, status) { 
    //alert("Successo lettura da database con Ajax!")
    //alert(data[0].alimenti[0].nome);
    
    //Aggiunta elementi al container Isotope    
    var $str = "";
    for($i=0; $i<data.length; $i++) {
        $str = $str + '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
        $str = $str + '<a class="options-set2" href="#'+data[$i].nome+'" data-option-value=".'+data[$i].nome+'">';
        $str = $str + '<div class="element" style="background: #'+data[$i].colore_bottone_predef+'">';
        $str = $str + '<h2 class="el-name">'+data[$i].nome+'</h2>';
        $str = $str + '</div>';
        $str = $str + '</a>';
        $str = $str + '</div>';
        
        for($j=0; $j<data[$i].alimenti.length; $j++) {
            var $strAl = "";
            $strAl = $strAl + '<div class="element '+data[$i].nome+'" data-symbol="Sc" data-category='+data[$i].nome+'>';
            $strAl = $strAl + '<a class="options-set3" href="#'+data[$i].alimenti[$j].id+'&'+data[$i].nome+'&'+data[$i].alimenti[$j].nome+'&'+data[$i].alimenti[$j].prezzo+'" data-option-value=".'+data[$i].alimenti[$j].nome+'">';
            $strAl = $strAl + '<div class="element" style="background: #'+data[$i].colore_bottone_predef+'">';
            $strAl = $strAl + '<h2 class="el-name">'+data[$i].alimenti[$j].nome+'</h2>';
            $strAl = $strAl + '<h2 class="el-prezzo">'+data[$i].alimenti[$j].prezzo+' €</h2>';
            $strAl = $strAl + '<h3 class="el-cat">'+data[$i].nome+'</h3>';
            $strAl = $strAl + '</div>';
            $strAl = $strAl + '</a>';
            $strAl = $strAl + '</div>';
            
            $str = $str + $strAl;
        }   
    }
    
    //Svuotamento container Isotope
    $('#container').isotope( 'remove' );
    //Aggiunta dei nuovi elementi a Isotope
    $('#container').append( $str ).isotope( 'insert', $str );
       
      /*
       * Inizializzazione Isotope
       *
       */
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
        filter: '.categorie',
        layoutMode: 'fitRows'
      });
    
}

function onEventoInfoError(data, status) {
    alert("Errore Ajax")
}
