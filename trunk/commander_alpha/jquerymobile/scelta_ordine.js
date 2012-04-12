
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

    //creazione oggetto alimento contente i parametri
    var alimento = new alimList(arrList.length,
                                $param,
                                arrAlim[$param]._cat,
                                arrAlim[$param]._nome,
                                arrAlim[$param]._prezzo,
                                arrAlim[$param]._descrizione,
                                0,
                                new Array(),
                                false );

    //verifica se l'alimento è già stato aggiunto all'array
    var ver = false;  
    var memI = 0; //memoria indice lista
    for(var i=0; i<arrList.length; i++) {
        if(arrList[i]._id == $param) {            
            //se già aggiunte varianti non incrementa numero
            if (arrList[i]._varianti.length > 0) {;}
            else {
                arrList[i]._num += 1;
                memI = i;
                ver = true;
                break
            }
        }          
    }

    //aggiunta alimento alla lista
    if (!ver) {           
        alimento._num = 1;
        arrList.push(alimento);
        alimento = null;

        //aggiornamento lista
        if (mem_ord_type == "nome") {
            aggiornaLista("nome");
        }
        else aggiornaLista("cat"); 
    }
    //incremento quantità alimento (senza aggiornale l'intera lista)
    else { 
        var costo = arrList[memI]._num * arrList[memI]._prezzo;

        var $itemString = '';
        $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
        $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#'+memI+'">';
        $itemString = $itemString + '<div class="num">'+arrList[memI]._num+'</div>';
        $itemString = $itemString + '<div class="name">'+arrList[memI]._nome+'</div>';
        $itemString = $itemString + '<div class="prezzo">'+costo+' \u20ac</div>'; //carattere "€" -> "\u20ac"
        $itemString = $itemString + '</a>';
        $itemString = $itemString + '</div>';

        //modifica del div già creato
        document.getElementById("list-" + memI).innerHTML= $itemString;

        //aggiornamento totale ordine
        aggiornaTotale();
    }
    return false;
    });
    
    
    /*
     *  Script per la selezione di un Menu Fisso
     *  da aggiungere alla lista dell'ordine
     *
     */
    var $optionSets4 = $('.options-set4');
    $optionSets4.live("click", function() {

    var $this = $(this);
    
    //salvo nella var $param i parametri passati
    var $param = $this.attr('href');
    
    //eliminazione carattere iniziale '#'
    $param = $param.replace('#','');
    
    //alert("Menù selezionato: " + arrMenu[$param]._nome);
    
    //creazione oggetto menu contente i parametri
    var menu = new alimList(arrList.length,
                                $param,
                                "Menu",
                                "Menù " + arrMenu[$param]._nome,
                                arrMenu[$param]._prezzo,
                                arrMenu[$param]._descrizione,
                                0,
                                new Array(),
                                true );
    
    menu._num = 1;
    arrList.push(menu);
    menu = null;
    
    //Creazione oggetto Menù Fisso selezionato                                    
    var arrTempCat = new Array();    
    var menuSel = new Menu(arrMenu[$param]._id,
                           arrMenu[$param]._nome,
                           arrMenu[$param]._prezzo,
                           arrMenu[$param]._descrizione, arrTempCat);
    arrMenuSel.push(menuSel);
                        
    //aggiornamento lista
    if (mem_ord_type == "nome") {
        aggiornaLista("nome");
    }
    else aggiornaLista("cat");
        
    });


    /*
     * Script per ordinare la lista degli alimenti selezionati
     * per categorie o in ordine alfabetico
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
        aggiornaLista("nome");                  
    } else {
        aggiornaLista("cat");                  
    }

    //finestra "opzioni" nascosta
    $('#cont-comm-ord').show('fast');
    $('#cont-comm-opt').hide('fast');
    $('#cont-comm-opt-menu').hide('fast');
    show_opt = false;
    
    //rimozione classe "selected"
    $('#container2').find('.selected').removeClass('selected');
            
    return false;
    });
});
   
      
/*
 * Confronto tra stringhe
 * 
 */
function confrontaStringhe(a,b) {
    var minA = a.toLowerCase();
    var minB = b.toLowerCase();
    if (minA > minB) {return true;}
    else {return false;}
}
      
      
/*
 * Ordinamento lista
 * 
 */
function aggiornaLista(type) {
    //Memorizzazione tipo ordinamento selezionato
    mem_ord_type = type;
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
                    if (mem_index == j) { mem_index = i; }
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
                    if (mem_index == j) { mem_index = i; }
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
                        if (mem_index == j) { mem_index = i; }
                    }
                }
            }   
        }
    }
    
    //Ricreo tutti i div della lista
    //dopo aver ordinato l'array
    var $itemString = "";  
    var memCat = "";
    for(var i=0; i<arrList.length; i++) {
        arrList[i]._index = i;
        //calcolo costo per alimento
        var costo = arrList[i]._num * arrList[i]._prezzo;
        //aggiunta separatore categorie
        if (type == "cat" & memCat != arrList[i]._cat) {
            $itemString = $itemString + '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-btn-up-undefined" data-role="list-divider" role="heading" style="height:20px;padding-top:0px;padding-bottom:0px">'+arrList[i]._cat+'</li>';
            memCat = arrList[i]._cat;
        }
        //verifica se l'alimento è selezionato
        var selClass = "";
        //if ($('#list-'+i).find('.selected').hasClass('selected')) {selClass = " selected";}
        if (mem_index == i) {selClass = " selected";}
        //creazione div
        $itemString = $itemString + '<li id=list-'+i+' class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c" data-theme="c">';
        $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
        $itemString = $itemString + '<a class="ui-link-inherit comm-li-link'+ selClass +'" href="#'+i+'">';
        $itemString = $itemString + '<div class="num">'+arrList[i]._num+'</div>';
        $itemString = $itemString + '<div class="name">'+arrList[i]._nome+'</div>';
        $itemString = $itemString + '<div class="prezzo">'+costo+' \u20ac</div>';
        $itemString = $itemString + '</a>';
        $itemString = $itemString + '</div>';
        $itemString = $itemString + '</li>';
        
        //aggiunta varianti dell'alimento
        for(var j=0; j<arrList[i]._varianti.length; j++) {
            $itemString = $itemString + '<li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c" data-theme="c">';
            $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim-var">';
            $itemString = $itemString + '<a class="comm-li-link-var'+ selClass +'">';
            $itemString = $itemString + '<div class="name">'+arrList[i]._varianti[j]._descrizione+'</div>';
            $itemString = $itemString + '<div class="prezzo">'+arrList[i]._varianti[j]._prezzo+' \u20ac</div>';
            $itemString = $itemString + '</a>';
            $itemString = $itemString + '</div>';
            $itemString = $itemString + '</li>';
        }
        //Se alimento lista è un Menù Fisso memorizzo oggetto
        if (arrList[i]._cat == "Menu") {
            //arrMenuSel[i] = arrMenu[arrList[i]._id];
        }
    }
    
    document.getElementById('container2').innerHTML = $itemString;
    
    //aggiornamento totale ordine
    aggiornaTotale();
    
    //pulsante "conferma"
    if (arrList.length >= 1) {
        $('#conf-ord-btt').removeClass('ui-disabled');
    }
    else $('#conf-ord-btt').addClass('ui-disabled');
}


/*
 * Funzione per il calcolo del totale dell'ordine
 *
 */
function aggiornaTotale(){
    totale = 0;
    for(var i=0; i<arrList.length; i++) {
        totale += arrList[i]._num * arrList[i]._prezzo;
        
        var varPrezzo = 0;
        //verifica se variazione prezzo da varianti
        for(var j=0; j<arrList[i]._varianti.length; j++) {
            varPrezzo += Number(arrList[i]._varianti[j]._prezzo);
        }
        totale += Number(arrList[i]._num) * Number(varPrezzo);
    }
    var $itemString = '';
    $itemString = $itemString + '<h2 class="name">Totale:</h2>';
    $itemString = $itemString + '<h2 class="prezzo">'+totale+' \u20ac</h2>';

    //modifica del div già creato
    document.getElementById("totale").innerHTML= $itemString; 
}

       
/*
 * Toggle visualizzazione sezione "opzioni"
 * 
 */
$('.comm-li-link').live("click", function() {
    //salvo nella var $param i parametri passati
    var $param = $(this).attr('href');
    //eliminazione carattere iniziale '#'
    var $index = $param.replace('#','');
    
    //visualizzazione finestra opzioni "Alimento" o opzioni "MenuFisso"
    if (arrList[$index]._cat == "Menu") visualizzaOpzioniMenu($(this), $index);
    else visualizzaOpzioni($(this), $index);
});


function visualizzaOpzioni($obj, $index) {
    if (!show_opt || $index!=mem_index) { 
        $('#cont-comm-ord').hide('fast');
        $('#cont-comm-opt').show('fast');
        $('#cont-comm-opt-menu').hide('fast');
        show_opt = true;
        //Visualizzazione nome alimento in finestra opzioni
        document.getElementById('opt-alim-name').innerHTML = "<h2>" + arrList[$index]._nome + "</h2>";
        //Visualizzazione descrizione alimento
        document.getElementById('alim-desc').innerHTML = arrList[$index]._descrizione;
        //aggiunta classe "selected" all'alimento selezionato
        $('#container2').find('.selected').removeClass('selected');
        $obj.addClass('selected');
        //visualizzazione varianti disponibili
        var id = arrList[$index]._id;
        var str = "";
        if (!arrList[$index]._menu) {
            if (arrAlim[id]._varianti.length == 0) { str = "Nessuna variante disponibile"; }
            else {
                for(i=0; i<arrAlim[id]._varianti.length; i++) {
                    //Verifica se variante già inserita
                    var varPresente = false;
                    for(var t=0; t<arrList[$index]._varianti.length; t++) {
                        if (arrList[$index]._varianti[t]._id == arrAlim[id]._varianti[i]._id) {
                            varPresente = true;
                            break;
                        }
                    }
                    //aggiunta classe "selected" se variante già selezionata
                    var selClass = "";
                    if (varPresente) { selClass = " selected";}

                    //str = str + '<a data-role="button" data-icon="delete" class="ui-btn-right">'+arrAlim[id]._varianti[i]._descrizione+'</a>';
                    str = str + '<div class="comm_checkbox'+selClass+'" href='+arrAlim[id]._varianti[i]._id+'>';
                    str = str + '<div class="cc_icon"></div>';
                    str = str + '<div class="cc_text">' + arrAlim[id]._varianti[i]._descrizione + '</div>';
                    str = str + '</div>';
                }
            }
        }
        else { str = "Nessuna variante disponibile"; }
        //modifica del div già creato
        document.getElementById('opt-var').innerHTML = str;
        
        mem_index = $index;
    }
    else {
        $('#cont-comm-ord').show('fast');
        $('#cont-comm-opt').hide('fast');
        $('#cont-comm-opt-menu').hide('fast');
        show_opt = false;
        
        //cancellazione classe "selected"
        $('#container2').find('.selected').removeClass('selected');
        
        mem_index = -1;
    }
}


function visualizzaOpzioniMenu($obj, $index) {
    if (!show_opt || $index!=mem_index) { 
        $('#cont-comm-ord').hide('fast');
        $('#cont-comm-opt').hide('fast');
        $('#cont-comm-opt-menu').show('fast');
        show_opt = true;
        //Visualizzazione nome alimento in finestra opzioni
        document.getElementById('opt-alim-name-menu').innerHTML = "<h2>" + arrList[$index]._nome + "</h2>";
        //aggiunta classe "selected" all'alimento selezionato
        $('#container2').find('.selected').removeClass('selected');
        $obj.addClass('selected');
        
        mem_index = $index;
    }
    else {
        $('#cont-comm-ord').show('fast');
        $('#cont-comm-opt').hide('fast');
        $('#cont-comm-opt-menu').hide('fast');
        show_opt = false;
        
        //cancellazione classe "selected"
        $('#container2').find('.selected').removeClass('selected');
        
        mem_index = -1;
    }
}


/*
 * Chiusura finestra opzioni
 * 
 */
$('.close-opt').bind("click", function() {
    $('#cont-comm-ord').show('fast');
    $('#cont-comm-opt').hide('fast');
    $('#cont-comm-opt-menu').hide('fast');
    show_opt = false;

    //cancellazione classe "selected"
    $('#container2').find('.selected').removeClass('selected');

    mem_index = -1;  
});


/*
 * Decremento o cancellazione alimento dalla lista
 * 
 */
$('#alim-min').bind("click", function() {
    $index = mem_index;
    for(var i=0; i<arrList.length; i++) {
        if (arrList[i]._index == $index) {
            if (arrList[i]._num > 1) {
                arrList[i]._num -= 1;
            }
            else { 
                arrList.splice(i, 1);
                mem_index = -1;
                
                //rimozione classe "selected"
                $('#container2').find('.selected').removeClass('selected');

                //finestra "opzioni" nascosta
                $('#cont-comm-ord').show('fast');
                $('#cont-comm-opt').hide('fast');
                $('#cont-comm-opt-menu').hide('fast');
                show_opt = false;
            }
            
            //aggiornamento lista
            if (mem_ord_type == "nome") {
                aggiornaLista("nome");
            }
            else aggiornaLista("cat"); 
            break;
        }
    }       
});
    

/*
 * Incremento alimento selezionato da opzioni varianti
 * 
 */
$('#alim-plus').bind("click", function() {
    arrList[mem_index]._num += 1;
    
    //aggiornamento lista
    if (mem_ord_type == "nome") {
        aggiornaLista("nome");
    }
    else aggiornaLista("cat"); 
}); 


/*
 * Cancellazione alimento dalla lista
 * 
 */
$('.canc-all-conf').live("click", function() {
    $index = mem_index;
    for(var i=0; i<arrList.length; i++) {
        if (arrList[i]._index == $index) {
            arrList.splice(i, 1);
            mem_index = -1;
            
            //rimozione classe "selected"
            $('#container2').find('.selected').removeClass('selected');
            
            //aggiornamento lista
            if (mem_ord_type == "nome") {
                aggiornaLista("nome");
            }
            else aggiornaLista("cat"); 
            
            //finestra "opzioni" nascosta
            $('#cont-comm-ord').show('fast');
            $('#cont-comm-opt').hide('fast');
            $('#cont-comm-opt-menu').hide('fast');
            show_opt = false;
            break;
        }
    }        
});


/*
 * Annulla cancellazione alimento
 * 
 */
$('.canc-ann').live("click", function() {
    mem_index = -1;  
    //eliminata classe "selected"
    $('#container2').find('.selected').removeClass('selected');
});


/*
 * Evento click su una variante
 *
 */
$('.comm_checkbox').live("click", function() {
    //aggiunta o rimozione della classe "selected"
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    }
    else {
        $(this).addClass('selected');
    }
    
    //id variante cliccata
    var id_var = $(this).attr('href');

    //Estrazione info da array Alimenti
    var desc = "";
    var prezzo;
    
    var id = arrList[mem_index]._id;

    for(var i=0; i<arrAlim[id]._varianti.length; i++) {
        if (arrAlim[id]._varianti[i]._id == id_var) {
            desc = arrAlim[id]._varianti[i]._descrizione;
            prezzo = arrAlim[id]._varianti[i]._prezzo;
            break;
        }
    }

    //Creazione oggetto variante
    var variante = new varList(id_var, desc, prezzo);

    //Ricerca Alimento selezionato in array Lista
    var index = 0;
    for(var j=0; j<arrList.length; j++) {
        if (arrList[j]._index == mem_index) {
            index = j;
            break;
        }
    }
    
    //Verifica se variante già inserita
    var varPresente = false;
    var indexVar = 0;
    for(var t=0; t<arrList[index]._varianti.length; t++) {
        if (arrList[index]._varianti[t]._id == id_var) {
            varPresente = true;
            indexVar = t;
            break;
        }
    }
    
    if (!varPresente) {
        //Estrazione Alimento dalla Lista per isolare
        //l'alimento con varianti dagli altri senza varianti
        if (arrList[index]._num > 1) {
            var temp = new alimList(arrList.length,
                                    arrList[index]._id,
                                    arrList[index]._cat,
                                    arrList[index]._nome,
                                    arrList[index]._prezzo,
                                    arrList[index]._descrizione,
                                    1,
                                    new Array(),
                                    false );
            arrList.push(temp);
            arrList[index]._num -= 1;
            index = arrList.length - 1;
        }
        
        //Aggiunta variante ad Alimento della Lista
        arrList[index]._varianti.push(variante);
        
        //Modifica indice alimento selezionato
        mem_index = index;   
         
        //alert("Selezionata variante: " + variante._descrizione + " - " + arrList[index]._varianti.length);
    }
    else {
        //rimozione variante dall'array
        arrList[index]._varianti.splice(indexVar, 1);
        //rimozione classe "selected"
        $(this).removeClass('selected');
        
        //alert("Variante rimossa"  + " - " + arrList[index]._varianti.length);
    }
    
    //aggiornamento lista
    if (mem_ord_type == "nome") {
        aggiornaLista("nome");
    }
    else aggiornaLista("cat"); 
    
    variante = null;
});
    

/*
 * Annulla ordine
 * 
 */
$('#canc-ord').live("click", function() {
    //reset variabili e array lista
    arrList = new Array();
    show_opt = false;
    mem_index = -1;
    mem_ord_type = "cat";
});


/*
 *  Funzione per crazione oggetti "alimento"
 *  
 */
function alimList(index, id, cat, nome, prezzo, descrizione, num, varianti, menu) {
    this._index = index;
    this._id = id;
    this._cat = cat;
    this._nome = nome;
    this._prezzo = prezzo;
    this._descrizione = descrizione;
    this._num = num;
    this._varianti = varianti;
    this._menu = menu;
}


/*
 *  Funzione per crazione oggetti "variante"
 *  
 */
function varList(id, descrizione, prezzo) {
    this._id = id;
    this._descrizione = descrizione;
    this._prezzo = prezzo;
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
