
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
                                0,
                                new Array() );

    //verifica se l'alimento è già stato aggiunto all'array
    var ver = false;  
    var memI = 0;
    for(var $i=0; $i<arrList.length; $i++) {
        if(arrList[$i]._id == $param) {            
            //se già aggiunte varianti non incrementa numero
            if (arrList[$i]._varianti.length > 0) {;}
            else {
                arrList[$i]._num += 1;
                memI = $i;
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
        $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[memI]._index+');">';
        $itemString = $itemString + '<div class="num">'+arrList[memI]._num+'</div>';
        $itemString = $itemString + '<div class="name">'+arrList[memI]._nome+'</div>';
        $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
        $itemString = $itemString + '</a>';
        $itemString = $itemString + '</div>';

        //modifica del div già creato
        document.getElementById(memI).innerHTML= $itemString;

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
        arrList[i]._index = i;
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
        $itemString = $itemString + '<li id='+i+' class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c" data-theme="c">';
        $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
        $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+i+');">';
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
function itemOpt($index){
    if (!show_opt || $index!=mem_index) { 
        $('#cont-comm-ord').hide('fast');
        $('#cont-comm-opt').show('fast');
        show_opt = true;
        //var $optionSet = $(this).parents('.comm-li-link');
        //$optionSet.find('.selected').removeClass('selected');
        //$(this).addClass('selected');
        var id = arrList[$index]._id;
        var str = "";
        if (arrAlim[id]._varianti.length == 0) {
            str = "Nessuna variante disponibile";
        }
        else {
            for($i=0; $i<arrAlim[id]._varianti.length; $i++) {
                //str = str + '<a data-role="button" data-icon="delete" class="ui-btn-right">'+arrAlim[id]._varianti[$i]._descrizione+'</a>';
                str = str + '<div class="var-class" href='+arrAlim[id]._varianti[$i]._id+'>'+arrAlim[id]._varianti[$i]._descrizione+'</div>';

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
    mem_index = $index;
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
    $index = mem_index;
    for(var $i=0; $i<arrList.length; $i++) {
        if (arrList[$i]._index == $index) {
            if (arrList[$i]._num > 1) {
                arrList[$i]._num -= 1;
                var costo = arrList[$i]._num * arrList[$i]._prezzo;

                var $itemString = '';
                $itemString = $itemString + '<div class="ui-btn-inner ui-li comm-li-alim">';
                $itemString = $itemString + '<a class="ui-link-inherit comm-li-link" href="#" onClick="itemOpt('+arrList[$i]._index+');">';
                $itemString = $itemString + '<div class="num">'+arrList[$i]._num+'</div>';
                $itemString = $itemString + '<div class="name">'+arrList[$i]._nome+'</div>';
                $itemString = $itemString + '<div class="prezzo">'+costo+' €</div>';
                $itemString = $itemString + '</a>';
                $itemString = $itemString + '</div>';

                //modifica del div già creato
                document.getElementById($index).innerHTML= $itemString;
            }
            else { 
                arrList.splice($i, 1);
                //var box = document.getElementById($index);
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
    $index = mem_index;
    for(var $i=0; $i<arrList.length; $i++) {
        if (arrList[$i]._index == $index) {
            arrList.splice($i, 1);
            //var box = document.getElementById($index);
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
    
    

/*
 * Evento click su una variante
 *
 */
$('.var-class').live("click", function() {
    //id variante cliccata
    var id_var = $(this).attr('href');

    //Estrazione info da array Alimenti
    var desc = "";
    var prezzo;
    
    var id = arrList[mem_index]._id;

    for($i=0; $i<arrAlim[id]._varianti.length; $i++) {
        if (arrAlim[id]._varianti[$i]._id == id_var) {
            desc = arrAlim[id]._varianti[$i]._descrizione;
            prezzo = arrAlim[id]._varianti[$i]._prezzo;
            break;
        }
    }

    //Creazione oggetto variante
    var variante = new varList(id_var, desc, prezzo);

    //Ricerca Alimento selezionato in array Lista
    var index = 0;
    for($j=0; $j<arrList.length; $j++) {
        if (arrList[$j]._index == mem_index) {
            index = $j;
            break;
        }
    }
    
    //Verifica se variante già inserita
    var varPresente = false;
    for($t=0; $t<arrList[index]._varianti.length; $t++) {
        if (arrList[index]._varianti[$t]._id == id_var) {
            varPresente = true;
            break;
        }
    }
    
    if (!varPresente) {
        //Estrazione Alimento dalla Lista per isolare
        //l'alimento con varianti dagli altri senza varianti
        if (arrList[index]._num > 1) {
            var temp = new alimList(arrList.length, arrList[index]._id, arrList[index]._cat, arrList[index]._nome, arrList[index]._prezzo, 1, new Array() );
            arrList.push(temp);
            arrList[index]._num -= 1;
            index = arrList.length - 1;
            
            var stt = "";
            for(var z=0; z<arrList.length; z++) {
                stt = stt + arrList[z]._num + ", ";
            }
            stt = stt + " - Elementi array = " + arrList.length;
            alert(stt);
        }
        
        //Aggiunta variante ad Alimento della Lista
        arrList[index]._varianti.push(variante);
        
        //aggiornamento lista
        if (mem_ord_type == "nome") {
            $itemString = ordinaLista("nome");
        }
        else $itemString = ordinaLista("cat"); 
        document.getElementById('container2').innerHTML = $itemString;
        
        alert("Selezionata variante: " + variante._descrizione + " - " + arrList[index]._varianti.length);
    }
    else alert("Variante già inserita"  + " - " + arrList[index]._varianti.length);
    
    variante = null;
});
    
    
/*
 *  Oggetto alimento
 *  
 */
function alimList(index, id, cat, nome, prezzo, num, varianti) {
    this._index = index;
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
       

