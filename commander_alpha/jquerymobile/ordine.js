
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
    var alimento = new alimList($param,
                                arrAlim[$param]._cat,
                                arrAlim[$param]._nome,
                                arrAlim[$param]._prezzo,
                                0,
                                new Array() );

    //verifica se l'alimento è già stato aggiunto all'array
    var ver = false;  
    var memI = 0;
    for(var $i=0; $i<arrList.length; $i++) {
        if(arrList[$i]._id == $param) {
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
        alimento = null;

        //ordinamento lista           
        $itemString = ordinaLista("cat");

        var $newItems = $itemString; 
        document.getElementById('container2').innerHTML = $newItems;
    }
    //incremento quantità alimento
    else { 
        var costo = arrList[memI]._num * arrList[memI]._prezzo;

        var $itemString = '';
        $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
        $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[memI]._id+');">';
        $itemString = $itemString + '<div class="num">'+arrList[memI]._num+'</div>';
        $itemString = $itemString + '<div class="name">'+arrList[memI]._nome+'</div>';
        $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
        $itemString = $itemString + '</a>';
        $itemString = $itemString + '</div>';

        //modifica del div già creato
        document.getElementById($param).innerHTML= $itemString;

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

});
   
      
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
            $itemString = $itemString + '<li class="ui-li ui-li-divider ui-btn ui-bar-b ui-btn-up-undefined" data-role="list-divider" role="heading" style="height:20px;padding-top:0px;padding-bottom:0px">'+arrList[i]._cat+'</li>';
            memCat = arrList[i]._cat;
        }
        //creazione div
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
        var str = "";
        if (arrAlim[$id]._varianti.length == 0) {
            str = "Nessuna variante disponibile";
        }
        else {
            for($i=0; $i<arrAlim[$id]._varianti.length; $i++) {
                //str = str + '<a data-role="button" data-icon="delete" class="ui-btn-right">'+arrAlim[$id]._varianti[$i]._descrizione+'</a>';
                str = str + '<div class="var-class" href='+arrAlim[$id]._varianti[$i]._id+'>'+arrAlim[$id]._varianti[$i]._descrizione+'</div>';

            }
        }
        //modifica del div già creato
        document.getElementById('opt-var').innerHTML = str;
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
                //var box = document.getElementById($id);
                //box.innerHTML= "";
                //box.parentNode.removeChild(box);
                
                //aggiornamento lista
                if (mem_ord_type == "nome") {
                    $itemString = ordinaLista("nome");
                }
                else $itemString = ordinaLista("cat"); 
                document.getElementById('container2').innerHTML = $itemString;

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
$('.canc-all-conf').live("click", function() {
    $id = mem_id;
    for(var $i=0; $i<arrList.length; $i++) {
        if (arrList[$i]._id == $id) {
            arrList.splice($i, 1);
            //var box = document.getElementById($id);
            //box.innerHTML= "";
            //box.parentNode.removeChild(box);
            
            //aggiornamento lista
            if (mem_ord_type == "nome") {
                $itemString = ordinaLista("nome");
            }
            else $itemString = ordinaLista("cat"); 
            document.getElementById('container2').innerHTML = $itemString;

            $('#cont-comm-ord').show('fast');
            $('#cont-comm-opt').hide('fast');
            show_opt = false;
            break;
        }
    }        
    aggiornaTotale();
});
    
    
    
$('.var-class').live("click", function() {
    //id variante cliccata
    var id_var = $(this).attr('href');

    //Estrazione info da array Alimenti
    var desc = "";
    var prezzo;

    for($i=0; $i<arrAlim[mem_id]._varianti.length; $i++) {
        if (arrAlim[mem_id]._varianti[$i]._id == id_var) {
            desc = arrAlim[mem_id]._varianti[$i]._descrizione;
            prezzo = arrAlim[mem_id]._varianti[$i]._prezzo;
            break;
        }
    }

    //Creazione oggetto variante
    var variante = new varList(id_var, desc, prezzo);

    //Ricerca Alimento selezionato in array Lista
    var id_alim = 0;
    for($j=0; $j<arrList.length; $j++) {
        if (arrList[$j]._id == mem_id) {
            id_alim = $j;
            break;
        }
    }
    
    //Aggiunta variante ad Alimento della Lista
    arrList[id_alim]._varianti.push(variante);
    alert("Selezionata variante: " + variante._descrizione);
    variante = null;
});
    
    
/*
 *  Oggetto alimento
 *  
 */
function alimList(id, cat, nome, prezzo, num, varianti) {
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
function varList(id, descrizione, prezzo) {
    this._id = id;
    this._descrizione = descrizione;
    this._prezzo = prezzo;
}
       

