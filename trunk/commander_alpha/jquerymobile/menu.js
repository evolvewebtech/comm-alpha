
/*
 * Evento "pageshow" pagina "Ordine"
 *
 */
$("#ordine").live('pageshow', function() {

    $.ajax({
        type : "GET",
        url: "menu.php",
        dataType: 'json',
        cache: false,
        success: onEventoInfoSuccess,
        error: onEventoInfoError
    });

    //Nascosta finestra "opzioni"
    $('#cont-comm-ord').show('fast');
    $('#cont-comm-opt').hide('fast');
    show_opt = false;
    
    //Aggiorna lista
    aggiornaLista("cat");
});


/*
 * Richiesta Ajax completata con successo
 *
 */
function onEventoInfoSuccess(data, status) { 
    //alert("Successo lettura da database con Ajax!")  
    
    //Aggiunta Categorie e Alimenti al container Isotope    
    var $str = "";
    for($i=0; $i<data[0].length; $i++) {
        $str = $str + '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
        $str = $str + '<a class="options-set2" href="#'+data[0][$i].nome+'" data-option-value=".'+data[0][$i].nome+'">';
        $str = $str + '<div class="element" style="background: '+data[0][$i].colore_bottone_predef+'">';
        $str = $str + '<h2 class="el-name">'+data[0][$i].nome+'</h2>';
        $str = $str + '</div>';
        $str = $str + '</a>';
        $str = $str + '</div>';
        
        for($j=0; $j<data[0][$i].alimenti.length; $j++) {
            var $strAl = "";
            $strAl = $strAl + '<div class="element '+data[0][$i].nome+'" data-symbol="Sc" data-category='+data[0][$i].nome+'>';
            $strAl = $strAl + '<a class="options-set3" href="#'+data[0][$i].alimenti[$j].id+'" data-option-value=".'+data[0][$i].alimenti[$j].nome+'">';
            $strAl = $strAl + '<div class="element" style="background: '+data[0][$i].colore_bottone_predef+'">';
            $strAl = $strAl + '<h2 class="el-name">'+data[0][$i].alimenti[$j].nome+'</h2>';
            $strAl = $strAl + '<h2 class="el-prezzo">'+data[0][$i].alimenti[$j].prezzo+' \u20ac</h2>'; //carattere "€" -> "\u20ac"
            $strAl = $strAl + '<h3 class="el-cat">'+data[0][$i].nome+'</h3>';
            $strAl = $strAl + '</div>';
            $strAl = $strAl + '</a>';
            $strAl = $strAl + '</div>';
            
            $str = $str + $strAl;
            
            //Creazione oggetti Variante
            var arrTempVar = new Array();
            for($t=0; $t<data[0][$i].alimenti[$j].varianti.length; $t++) {
                //Creazione oggetto Variante
                var variante = new Variante(data[0][$i].alimenti[$j].varianti[$t].id,
                                            data[0][$i].alimenti[$j].varianti[$t].descrizione,
                                            data[0][$i].alimenti[$j].varianti[$t].prezzo);
                
                //Aggiunta elementi all'array delle Varianti
                arrTempVar.push(variante);
            }
            
            //Creazione oggetto Alimento
            var alimento = new Alim(data[0][$i].alimenti[$j].id,
                                    data[0][$i].nome,
                                    data[0][$i].alimenti[$j].nome,
                                    data[0][$i].alimenti[$j].prezzo,
                                    data[0][$i].alimenti[$j].descrizione,
                                    0,
                                    arrTempVar);
                                    
            //Aggiunta elementi all'array degli Alimenti
            arrAlim[data[0][$i].alimenti[$j].id] = alimento;
        }
    }
    
    
    //Aggiunta Menu Fissi al container Isotope   
    for($i=0; $i<data[1].length; $i++) {
        $str = $str + '<div class="element menu_fissi" data-symbol="Sc" data-category="menu_fissi">';
        $str = $str + '<a class="options-set4" href="#'+data[1][$i].id+'" data-option-value=".'+data[1][$i].nome+'">';
        $str = $str + '<div class="element" style="background: #abcd00">';
        $str = $str + '<h2 class="el-name">'+data[1][$i].nome+'</h2>';
        $str = $str + '<h2 class="el-prezzo">'+data[1][$i].prezzo+' \u20ac</h2>'; //carattere "€" -> "\u20ac"
        $str = $str + '</div>';
        $str = $str + '</a>';
        $str = $str + '</div>';
        
        var arrTempCat = new Array();
        for($j=0; $j<data[1][$i].categorie.length; $j++) {
            var $strAl = "";
            $strAl = $strAl + "";
            
            $str = $str + $strAl;
            
            //Creazione oggetti Alimenti
            var arrTempAlim = new Array();
            for($t=0; $t<data[1][$i].categorie[$j].alimenti.length; $t++) {
                
                var arrTempVar = new Array();
                for($s=0; $s<data[1][$i].categorie[$j].alimenti[$t].varianti.length; $s++) {
                    //Creazione oggetto Variante
                    var variante = new Variante(data[1][$i].categorie[$j].alimenti[$t].varianti[$s].id,
                                                data[1][$i].categorie[$j].alimenti[$t].varianti[$s].descrizione,
                                                data[1][$i].categorie[$j].alimenti[$t].varianti[$s].prezzo);
                    
                    //Aggiunta elementi all'array delle Varianti
                    arrTempVar.push(variante);
                }   
                
                //Creazione oggetto Alimento
                var alimento = new AlimMenu(data[1][$i].categorie[$j].alimenti[$t].id,
                                            data[1][$i].categorie[$j].alimenti[$t].nome,
                                            arrTempVar);
                
                //Aggiunta elementi all'array delle Varianti
                arrTempAlim.push(alimento);
            }

            //Creazione oggetto CatManu contente i parametri
            var categoria = new CatMenu(data[1][$i].categorie[$j].id,
                                        data[1][$i].categorie[$j].nome_cat,
                                        arrTempAlim);
            
            //Aggiunta elementi all'array delle categorie
            arrTempCat.push(categoria);
        }
        
        //Creazione oggetto Menu
        var menu = new Menu(data[1][$i].id,
                            data[1][$i].nome,
                            data[1][$i].prezzo,
                            data[1][$i].descrizione,
                            arrTempCat);
        
        //Aggiunta Menu all'array
        arrMenu[menu._id] = menu;
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
 *  Oggetto Menu
 *  
 */
function Menu(id, nome, prezzo, descrizione, categorie) {
    this._id = id;
    this._nome = nome;
    this._prezzo = prezzo;
    this._descrizione = descrizione;
    this._categorie = categorie;
}



/*
 *  Oggetto CatMenu
 *  
 */
function CatMenu(id, nome, alimenti) {
    this._id = id;
    this._nome = nome;
    this._alimenti = alimenti;
}



/*
 *  Oggetto AlimMenu
 *  
 */
function AlimMenu(id, nome, varianti) {
    this._id = id;
    this._nome = nome;
    this._varianti = varianti;
}


/*
 *  Oggetto Alimento
 *  
 */
function Alim(id, cat, nome, prezzo, descrizione, num, varianti) {
    this._id = id;
    this._cat = cat;
    this._nome = nome;
    this._prezzo = prezzo;
    this._descrizione = descrizione;
    this._num = num;
    this._varianti = varianti;
}


/*
 *  Oggetto Variante
 *  
 */
function Variante(id, descrizione, prezzo) {
    this._id = id;
    this._descrizione = descrizione;
    this._prezzo = prezzo;
}