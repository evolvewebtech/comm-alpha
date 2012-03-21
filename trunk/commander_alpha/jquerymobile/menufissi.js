
/*
 * Evento "pageshow" pagina "Ordine"
 *
 */
$("#ordine").live('pageshow', function() {

    $.ajax({
        type : "GET",
        url: "menufissi.php",
        dataType: 'json',
        cache: false,
        success: onEventoInfoSuccess2,
        error: onEventoInfoError2
    });

});



/*
 * Richiesta Ajax completata con successo
 *
 */
function onEventoInfoSuccess2(data, status) { 
    //alert("Successo lettura da database con Ajax!")    
    //Aggiunta elementi al container Isotope    
    var $str = "";
    for($i=0; $i<data.length; $i++) {
        $str = $str + "*** " + data[$i].nome + " " + data[$i].prezzo + ": ";
        
        var arrTempCat = new Array();
        for($j=0; $j<data[$i].categorie.length; $j++) {
            var $strAl = "";
            $strAl = $strAl + "";
            
            $str = $str + $strAl;
            
            //Creazione oggetti Alimenti
            var arrTempAlim = new Array();
            for($t=0; $t<data[$i].categorie[$j].alimenti.length; $t++) {
                
                var arrTempVar = new Array();
                for($s=0; $s<data[$i].categorie[$j].alimenti[$t].varianti.length; $s++) {
                    //Creazione oggetto Variante
                    var variante = new Variante(data[$i].categorie[$j].alimenti[$t].varianti[$s].id,
                                                data[$i].categorie[$j].alimenti[$t].varianti[$s].descrizione,
                                                data[$i].categorie[$j].alimenti[$t].varianti[$s].prezzo);
                    
                    //Aggiunta elementi all'array delle Varianti
                    arrTempVar.push(variante);
                }   
                
                //Creazione oggetto Alimento
                var alimento = new AlimMenu(data[$i].categorie[$j].alimenti[$t].id,
                                            data[$i].categorie[$j].alimenti[$t].nome,
                                            arrTempVar);
                
                //Aggiunta elementi all'array delle Varianti
                arrTempAlim.push(alimento);
            }

            //Creazione oggetto CatManu contente i parametri
            var categoria = new CatMenu(data[$i].categorie[$j].id,
                                        data[$i].categorie[$j].nome_cat,
                                        arrTempAlim);
            
            //Aggiunta elementi all'array delle categorie
            arrTempCat.push(categoria);
        }
        
        //Creazione oggetto Menu
        var menu = new Menu(data[$i].id,
                            data[$i].nome,
                            data[$i].prezzo,
                            data[$i].descrizione,
                            arrTempCat);
        
        //Aggiunta Menu all'array
        arrMenu[data[$i].id] = menu;
    }
    
    alert($str);
}



/*
 * Errore richiesta Ajax
 *
 */
function onEventoInfoError2(data, status) {
    alert("Errore Ajax");
}



/*
 *  Oggetto menu
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
 *  Oggetto alimento
 *  
 */
function AlimMenu(id, cat, nome, prezzo, num, varianti) {
    this._id = id;
    this._nome = nome;
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