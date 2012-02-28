
/*
 * Evento "pageshow" pagina "Ordine"
 *
 */
$("#ordine").live('pageshow', function() {

    $.ajax({
        type : "GET",
        url: "categorie.php",
        dataType: 'json',
        cache: false,
        success: onEventoInfoSuccess,
        error: onEventoInfoError
    });

    //Nascosta finestra "opzioni"
    $('#cont-comm-ord').show('fast');
    $('#cont-comm-opt').hide('fast');
    show_opt = false;   
});


/*
 * Richiesta Ajax completata con successo
 *
 */
function onEventoInfoSuccess(data, status) { 
    //alert("Successo lettura da database con Ajax!")    
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
            $strAl = $strAl + '<a class="options-set3" href="#'+data[$i].alimenti[$j].id+'" data-option-value=".'+data[$i].alimenti[$j].nome+'">';
            $strAl = $strAl + '<div class="element" style="background: #'+data[$i].colore_bottone_predef+'">';
            $strAl = $strAl + '<h2 class="el-name">'+data[$i].alimenti[$j].nome+'</h2>';
            $strAl = $strAl + '<h2 class="el-prezzo">'+data[$i].alimenti[$j].prezzo+' €</h2>';
            $strAl = $strAl + '<h3 class="el-cat">'+data[$i].nome+'</h3>';
            $strAl = $strAl + '</div>';
            $strAl = $strAl + '</a>';
            $strAl = $strAl + '</div>';
            
            $str = $str + $strAl;
            
            //Creazione oggetti Variante
            var arrVar = new Array();
            for($t=0; $t<data[$i].alimenti[$j].varianti.length; $t++) {
                //Creazione oggetto Variante contente i parametri
                var tempIdV = data[$i].alimenti[$j].varianti[$t].id;
                var tempDescrV = data[$i].alimenti[$j].varianti[$t].descrizione;
                var tempPrezzoV = data[$i].alimenti[$j].varianti[$t].prezzo;
                var variante = new Variante(tempIdV, tempDescrV, tempPrezzoV);
                //Aggiunta elementi all'array delle Varianti
                arrVar.push(variante);
            }
            
            //Creazione oggetto Alimento contente i parametri
            var tempId = data[$i].alimenti[$j].id;
            var tempCat = data[$i].nome;
            var tempNome = data[$i].alimenti[$j].nome;
            var tempPrezzo = data[$i].alimenti[$j].prezzo;
            var alimento = new Alim(tempId, tempCat, tempNome, tempPrezzo, 0, arrVar);
            //Aggiunta elementi all'array degli Alimenti
            arrAlim[tempId] = alimento;
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


/*
 * Errore richiesta Ajax
 *
 */
function onEventoInfoError(data, status) {
    alert("Errore Ajax")
}


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