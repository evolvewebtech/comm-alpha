
var firstCat = '';
var memFirstUp = true;

/*
 * Evento "pageshow" pagina "Ordine"
 *
 */
$("#ordine").live('pageshow', function() {
    
    //Aggiornamento alimenti abilitato solo all'apertura della pagina "Home"
    if (!refreshAlim) {
        refreshAlim = true;
        
        data = 'firstUpdate=' + firstUpdate;
        firstUpdate = false;
        
        $.ajax({
            type : "POST",
            data: data,
            url: "menu.php",
            dataType: 'json',
            cache: false,
            success: onEventoInfoSuccess,
            error: onEventoInfoError
        });

        //Nascosta finestra "opzioni"
        $('#cont-comm-ord').show();
        $('#com-options .cl-comm-opt').hide();
        show_opt = false;

        //Aggiorna lista
        aggiornaLista("cat");
    
    }
    else {
        //aggiornamento layout Isotope
        setTimeout("layoutMansory()",200);
        //Selezionato pulsante "Categorie" in pagina ordini
        var $this = $('#fitRows');
        // don't proceed if already selected
        if ( !$this.hasClass('selected') ) {
            var $optionSet = $this.parents('.option-set');
            $optionSet.find('.selected').removeClass('selected');
            $this.addClass('selected');
        }
    }
    
    //Visualizzazione numero tavolo e numero coperti
    numCoperti = document.getElementById('slider-0').value;   
    str = 'Tavolo ' + numTavolo + ' - Coperti ' + numCoperti;
    document.getElementById('ord01').innerHTML = str;
    
    //Reset "Annulla voci"
    resetAnnullaVoci();
});


/*
 * Richiesta Ajax completata con successo
 *
 */
function onEventoInfoSuccess(data, status) { 
    //alert("Successo lettura da database con Ajax!")
    
    //Verifica se utente loggato
    if ( !logged(data['err']) ) return;
    
    //Verifica se alimenti già aggiornati
    if (!data['aggiornato']) { 
        //Svuotamento array
        arrAlim = new Array();
        arrMenu = new Array();
        //Svuotamento iniziale "categorie" da container Isotope
        if (!memFirstUp) {
            var $removable = $('#container').find( ".categorie" );
            $('#container').isotope( 'remove', $removable );
        }
        
        var $str = '';
        //Aggiunto pulsante per tornare alle categorie
        if ((data['cat'].length>1) && (memFirstUp)) {
            $str = $str + '<div class="element elback" data-symbol="Sc" data-category="">';
            $str = $str + '<a class="options-set2" href="#categorie" data-option-value=".categorie">';
            $str = $str + '<div class="element" style="background: white">';
            $str = $str + '<div class="element-ac">';
            $str = $str + '<h2 class="el-name" style="color: black">Torna a tutte le categorie</h2>';
            $str = $str + '</div>';
            $str = $str + '</div>';
            $str = $str + '</a>';
            $str = $str + '</div>';
            
            //Aggiunta dei nuovi elementi a Isotope
            $('#container').append( $str );
        }

        //Aggiunta Categorie e Alimenti al container Isotope           
        var textColor = '';
        for($i=0; $i<data['cat'].length; $i++) {
            //colore testo
            textColor = contrastColor( data['cat'][$i].colore_bottone_predef );

            //Visualizzate solo categoria non vuote
            if (data['cat'][$i].alimenti.length > 0) {
                $str = "";
                $str = $str + '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
                $str = $str + '<a class="options-set2" href="#'+data['cat'][$i].nome+'" data-option-value=".'+data['cat'][$i].nome+', .elback">';
                $str = $str + '<div class="element" style="background: '+data['cat'][$i].colore_bottone_predef+'">';
                $str = $str + '<h2 class="el-name" style="color: '+textColor+'">'+data['cat'][$i].nome+'</h2>';
                $str = $str + '</div>';
                $str = $str + '</a>';
                $str = $str + '</div>';

                //Aggiunta dei nuovi elementi a Isotope
                $('#container').append( $str );
                if (!memFirstUp) $('#container').isotope( 'reloadItems' );
            }

            if (data['cat'].length == 1) firstCat = data['cat'][$i].nome;
            else firstCat = '';

            //Svuotamento iniziale "alimenti" da container Isotope
            if (!memFirstUp) {
                $removable = $('#container').find( "." + data['cat'][$i].nome );
                $('#container').isotope( 'remove', $removable );
            }

            for($j=0; $j<data['cat'][$i].alimenti.length; $j++) {
                //Verifica se alimento esaurito
                if (data['cat'][$i].alimenti[$j].esaurito == 0) {
                    //Colore pulsante alimento
                    var $strColorCat = data['cat'][$i].colore_bottone_predef;
                    var $strColorAlim = data['cat'][$i].alimenti[$j].colore_bottone;
                    var $strColor = '';
                    if ($strColorAlim == '') $strColor = $strColorCat;
                    else $strColor = $strColorAlim;
                    //colore testo
                    textColor = contrastColor( $strColor );

                    var elName = data['cat'][$i].alimenti[$j].nome;
                    if (elName.length > 17) elName = elName.substring(0,15) + '...';

                    $str = "";
                    $str = $str + '<div class="element '+data['cat'][$i].nome+'" data-symbol="Sc" data-category='+data['cat'][$i].nome+'>';
                    $str = $str + '<a class="options-set3" href="#'+data['cat'][$i].alimenti[$j].id+'" data-option-value=".'+data['cat'][$i].alimenti[$j].nome+'">';
                    $str = $str + '<div class="element" style="background: '+$strColor+'">';
                    $str = $str + '<div class="element-ac">';
                    $str = $str + '<h2 class="el-name" style="color: '+textColor+'">'+elName+'</h2>';
                    $str = $str + '<h2 class="el-prezzo" style="color: '+textColor+'">'+formatMoney(data['cat'][$i].alimenti[$j].prezzo,2,true)+' \u20ac</h2>'; //carattere "€" -> "\u20ac"
                    $str = $str + '<h3 class="el-cat" style="color: '+textColor+'">'+data['cat'][$i].nome+'</h3>';
                    $str = $str + '</div>';
                    $str = $str + '</div>';
                    $str = $str + '</a>';
                    $str = $str + '</div>';

                    //Aggiunta dei nuovi elementi a Isotope
                    $('#container').append( $str );
                    if (!memFirstUp) $('#container').isotope( 'reloadItems' );

                    //Creazione oggetti Variante
                    var arrTempVar = new Array();
                    for($t=0; $t<data['cat'][$i].alimenti[$j].varianti.length; $t++) {
                        //Creazione oggetto Variante
                        var variante = new Variante(data['cat'][$i].alimenti[$j].varianti[$t].id,
                                                    data['cat'][$i].alimenti[$j].varianti[$t].descrizione,
                                                    data['cat'][$i].alimenti[$j].varianti[$t].prezzo);

                        //Aggiunta elementi all'array delle Varianti
                        arrTempVar.push(variante);
                    }

                    //Creazione oggetto Alimento
                    var alimento = new Alim(data['cat'][$i].alimenti[$j].id,
                                            data['cat'][$i].nome,
                                            data['cat'][$i].alimenti[$j].nome,
                                            data['cat'][$i].alimenti[$j].prezzo,
                                            data['cat'][$i].alimenti[$j].descrizione,
                                            0,
                                            arrTempVar);

                    //Aggiunta elementi all'array degli Alimenti
                    arrAlim[data['cat'][$i].alimenti[$j].id] = alimento;
                }
            }
        }

        textColor = contrastColor( '#faeb9e' );

        //Aggiunto pulsante categoria "Menù fissi""
        if (data['menu'].length > 0) {
            $str = "";
            $str = $str + '<div class="element categorie" data-symbol="Sc" data-category="categorie">';
            $str = $str + '<a class="options-set2" href="#menu_fissi" data-option-value=".menu_fissi, .elback">';
            $str = $str + '<div class="element" style="background: #faeb9e">';
            $str = $str + '<h2 class="el-name" style="color: '+textColor+'">Menù fissi</h2>';
            $str = $str + '</div>';
            $str = $str + '</a>';
            $str = $str + '</div>';

            //Aggiunta dei nuovi elementi a Isotope
            $('#container').append( $str );
             if (!memFirstUp) $('#container').isotope( 'reloadItems' );
        }

        //Svuotamento iniziale "menu fissi" da container Isotope
        if (!memFirstUp) {
            $removable = $('#container').find( ".menu_fissi" );
            $('#container').isotope( 'remove', $removable );
        }

        //Aggiunta Menu Fissi al container Isotope   
        for($i=0; $i<data['menu'].length; $i++) {
            $str = "";
            $str = $str + '<div class="element menu_fissi" data-symbol="Sc" data-category="menu_fissi">';
            $str = $str + '<a class="options-set4" href="#'+data['menu'][$i].id+'" data-option-value=".'+data['menu'][$i].nome+'">';
            $str = $str + '<div class="element" style="background: #faeb9e">';
            $str = $str + '<div class="element-ac">';
            $str = $str + '<h2 class="el-name" style="color: '+textColor+'">'+data['menu'][$i].nome+'</h2>';
            $str = $str + '<h2 class="el-prezzo" style="color: '+textColor+'">'+formatMoney(data['menu'][$i].prezzo,2,true)+' \u20ac</h2>'; //carattere "€" -> "\u20ac"
            $str = $str + '</div>';
            $str = $str + '</div>';
            $str = $str + '</a>';
            $str = $str + '</div>';

            //Aggiunta dei nuovi elementi a Isotope
            $('#container').append( $str );
            if (!memFirstUp) $('#container').isotope( 'reloadItems' );

            var arrTempCat = new Array();
            for($j=0; $j<data['menu'][$i].categorie.length; $j++) {

                //Creazione oggetti Alimenti
                var arrTempAlim = new Array();
                for($t=0; $t<data['menu'][$i].categorie[$j].alimenti.length; $t++) {

                    var arrTempVar = new Array();
                    for($s=0; $s<data['menu'][$i].categorie[$j].alimenti[$t].varianti.length; $s++) {
                        //Creazione oggetto Variante
                        var variante = new Variante(data['menu'][$i].categorie[$j].alimenti[$t].varianti[$s].id,
                                                    data['menu'][$i].categorie[$j].alimenti[$t].varianti[$s].descrizione,
                                                    data['menu'][$i].categorie[$j].alimenti[$t].varianti[$s].prezzo);

                        //Aggiunta elementi all'array delle Varianti
                        arrTempVar.push(variante);
                    }   

                    //Creazione oggetto Alimento
                    var alimento = new AlimMenu(data['menu'][$i].categorie[$j].alimenti[$t].id,
                                                data['menu'][$i].categorie[$j].alimenti[$t].nome,
                                                arrTempVar);

                    //Aggiunta elementi all'array delle Varianti
                    arrTempAlim.push(alimento);
                }

                //Creazione oggetto CatManu contente i parametri
                var categoria = new CatMenu(data['menu'][$i].categorie[$j].id,
                                            data['menu'][$i].categorie[$j].nome_cat,
                                            arrTempAlim);

                //Aggiunta elementi all'array delle categorie
                arrTempCat.push(categoria);
            }

            //Creazione oggetto Menu
            var menu = new Menu(data['menu'][$i].id,
                                data['menu'][$i].nome,
                                data['menu'][$i].prezzo,
                                data['menu'][$i].descrizione,
                                arrTempCat);

            //Aggiunta Menu all'array
            arrMenu[menu._id] = menu;
        }


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
        
        //Modifica layout Isotope per corretta visualizzazione elementi nel container
        setTimeout("layoutMansory()",200);
    
    } //if (!data['aggiornato'])
    else {
        if (firstCat != '') filterIsotope('.' + firstCat);
        else filterIsotope('.categorie');
    }
    
    //Selezionato pulsante "Categorie" in pagina ordini
    var $this = $('#categorie');
    // don't proceed if already selected
    if ( !$this.hasClass('selected') ) {
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
    }
    
    //Selezionato pulsante "Categorie" in pagina ordini
    $this = $('#catSort');
    // don't proceed if already selected
    if ( !$this.hasClass('selected') ) {
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
    }
    
    memFirstUp = false;
    //Modifica layout Isotope per corretta visualizzazione elementi nel container
    //setTimeout("layoutMansory()",200);
}


/*
 * Errore richiesta Ajax
 *
 */
function onEventoInfoError(data, status) {
    alert("Errore Ajax");
}


/*
 * Funzione modifica layout Isotope
 *
 */
function layoutMansory() {
  //$('#container').isotope({ layoutMode : 'masonry' });
  $('#container').isotope( 'reLayout' );
  setTimeout("layoutFitRows()",200);
}


/*
 * Funzione modifica layout Isotope
 *
 */
function layoutFitRows() {
  $('#container').isotope({ layoutMode : 'fitRows' });
  $('#container').isotope( 'reLayout' );
  if (firstCat != '') filterIsotope('.' + firstCat);
}


/*
 * Funzione modifica filtro Isotope
 *
 */
function filterIsotope(filterStr) {
  $('#container').isotope({ filter : filterStr });
  $('#container').isotope( 'reLayout' );
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