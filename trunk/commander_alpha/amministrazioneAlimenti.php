<?php
    require_once dirname(__FILE__)  . '/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<!--
todo: 1. selezionare le checkbox come checked in base alle stampanti assiociate
         al caricamento della pagina
      2. Creare chiamata ajax per segnalare come esaurito (o meno) un alimento
      3. Creare tutti i controlli del form
      4. Creare un array javascript con all'interno le categorie

-->


<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/color-picker.css" type="text/css" media="screen" />

<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script src="media/js/ui/jquery.ui.core.js"></script>
<script src="media/js/ui/jquery.ui.widget.js"></script>
<script src="media/js/ui/jquery.ui.tabs.js"></script>
<script src="media/js/ui/jquery.ui.button.js"></script>
<script src="media/js/ui/jquery.ui.dialog.js"></script>
<script src="media/js/ui/jquery.ui.position.js"></script>
<script src="media/js/ui/jquery.ui.draggable.js"></script>
<script src="media/js/color-picker.js"></script>
<script type='text/javascript'>
    /*
     *  rgb2hex.
     */
    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        function hex(x) {
            return ("0" + parseInt(x).toString(16)).slice(-2);
        }
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function selectOption(select_id, option_val) {
        $('#'+select_id+' option:selected').removeAttr('selected');
        $('#'+select_id+' option[value='+option_val+']').attr('selected','selected');
    }
</script>

<script src="media/js/jquery.validate.min.js"></script>

<style type="text/css">
    /*
     * foglio di stile per gli errori di digitazione client-side
     *
     */
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    p { clear: both; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
<style>
    /*
     * foglio di stile per i dialoghi
     *
     */
    #dialog label, #dialog input { display:block; }
    #dialog label { margin-top: 0.5em; }
    #dialog input, #dialog textarea { width: 95%; }
    #tabs { margin-top: 1em; }
    #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
<style type="text/css">
    /*
     * foglio di stile per la pagina corrente
     *
     */
    .clearfix{ display: block; height: 0; clear: both; visibility: hidden; }
    .details{ margin:15px 20px; }
    h4{ font:300 16px 'Helvetica Neue', Helvetica, Arial, sans-serif;
        line-height:160%; letter-spacing:0.15em; color:#fff;
        text-shadow:1px 1px 0 rgb(0,0,0); }
    p{ font:300 12px 'Lucida Grande', Tahoma, Verdana, sans-serif;
       color:#000;}
    a{ text-decoration:none; }
    #plus{
        padding: 2px;
    }
    #add_span{
        float: right;
        margin: 5px;
    }
    #add_tab{
        cursor: pointer;
    }
    #add_tab .ui-button-text{
        padding: 2px;
    }
    span.ui-dialog-title{
        color:white;
    }
    #save_alimento{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
       cursor: pointer;
    }
    #delete_alimento{
       width: 100px;
       height: 50px;
       background-color: red;
       text-transform: uppercase;
       cursor: pointer;
    }

    a.finish {
        /*background: #ccc;*/
        background: red;
        cursor: pointer;
        border-top: solid 2px #eaeaea;
        border-left: solid 2px #eaeaea;
        border-bottom: solid 2px #777;
        border-right: solid 2px #777;
        padding: 5px 5px;
        }

    a.down.finish{
        /*background: #bbb;*/
        background: green;
        border-top: solid 2px #777;
        border-left: solid 2px #777;
        border-bottom:solid 2px  #eaeaea;
        border-right: solid 2px #eaeaea;
        }

</style>

<div id="content">
    <?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

           $gestore_id = $gestore->id;
           $utente_registrato_id = $gestore->utente_registrato_id;

           $data_alimento = DataManager::getAllAlimentoByGestoreID($gestore_id);//($gestore_id);
           $numero_alimento = count($data_alimento);
           $max_id = DataManager::getMAXID('cmd_alimento');
           if (!$max_id){
               $max_id=0;
           }

           $data_alimento_tampante = DataManager::getAllAlimentoStampante();
           $data_categoria         = DataManager::getAllCategoriaByGestoreID($gestore_id);
           /*
           foreach ($data_alimento_tampante as $data_alID_stamp_ID) {
               echo '<pre style="color:white;">';
               print_r($data_alID_stamp_ID);
               echo "</pre>";
           }
           */
           $numero_alimento_stampante = count($data_alimento_tampante);

//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
    <h1>Gestisci gli Alimenti
        <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
            Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                     <a style="color:#fff; font-size: 14px;" href="amministrazioneAlimenti.php"><b>Alimenti</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

        $('#color-picker-dialog').empty().addColorPicker({
            clickCallback: function(color) {
                    $('#color-picker-dialog').next().val(rgb2hex(color));
                    $('#color-picker-dialog').next().css('backround-color', $('#color-picker-dialog').next().val());
                    $('#debug').append( '<br />'+rgb2hex(color) );
            }
   	});


        $("#addNewTab").validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        },
                    tab_colore_bottone: {
                            required: true,
                            minlength: 2,
                            maxlength: 7
                        }
                    },
                    messages: {
                        tab_indirizzo: {
                            required: "Inserisci un indirizzo IP.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        },
                        tab_colore_bottone: {
                            required: "Seleziona un colore.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        }
                    }
        });

    var $tab_nome_input                     = $("#tab_nome"),
        $tab_prezzo_input                   = $('#tab_prezzo'),
        $tab_iva_input                      = $('#tab_iva'),
        $tab_colore_bottone_input           = $('#tab_colore_bottone'),
        $tab_descrizione_input              = $('#tab_descrizione'),
        $tab_apeso_input                    = $('#tab_apeso'),
        $tab_codice_prodotto_input          = $('#tab_codice_prodotto'),
        $tab_quantita_input                 = $('#tab_quantita'),
        $tab_categoria_id_input             = $('#tab_categoria_id'),
        $tab_secondo_alimento_id_input      = $('#tab_secondo_alimento_id'),
        $tab_stampante_associata_id_input   = $('#tab_stampante_associata_id');


    var tab_counter = <?=$numero_alimento?>,
        max_id      = <?=$max_id?>,
        gestore_id  = <?=$gestore_id?>,
        numero_rel_stampanti = <?= $numero_alimento_stampante ?>;

   /*
    *
    * ho creato l'array che contiene l'id delle checkbox che devono essere
    * selezionate
    *
    */
   var id_stampante_alimento = new Array();
   <? foreach ($data_alimento_tampante as $data_alID_stamp_ID) { ?>
            id_stampante_alimento.push("<?=$data_alID_stamp_ID[alimento_id]?>-<?=$data_alID_stamp_ID[stampante_id]?>");
   <? } ?>

    /*
    *
    *  metto a checked=true le checkbox che rappresentano relazioni nel db
    */
   $.each(id_stampante_alimento, function(key, value){
       //alert(key + ': ' + value);
       $('#'+value).prop('checked', true);
   });

   /*
    * inserisco all'interno di un array le categorie presenti nel db
    * $data_categoria
    */
   var id_categoria = {};
   <? foreach ($data_categoria as $categoria) { ?>
            id_categoria["<?=$categoria[nome]?>"] = <?=$categoria[id]?>;
   <? } ?>

    /*
     * ritorno una stringa contenete le select delle categorie
     * e come selected quella = al parametro passato: categoria
     */
    function printSelectCategoria(categoria) {
        var selectCategoria = '';
          $.map(id_categoria, function(key, value){
                if (key==categoria){
                    selectCategoria=selectCategoria+'<option selected="selected" value="'+key+'">'+value+'</option>';
                }else {
                selectCategoria=selectCategoria+'<option value="'+key+'">'+value+'</option>';
                }
          });
          return selectCategoria;
    }


    tab_counter++;
    var next_id = max_id+1;

    $('#debug').append('<br />Numero: '         +tab_counter+
                       '<br />ID max: '         +max_id+
                       '<br />ID max next: '    +next_id+
                       '<br />Gestore ID: '     +gestore_id+
                       '<br />numero al-sta: '  +numero_rel_stampanti+
                       '<br />lista ID:'        +id_stampante_alimento+
                       '<br />categorie_id: '   +id_categoria);

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {

                    var tab_content_nome                    = $tab_nome_input.val(),
                        tab_content_prezzo                  = $tab_prezzo_input.val(),
                        tab_content_iva                     = $tab_iva_input.val(),
                        tab_content_colore_bottone          = $tab_colore_bottone_input.val(),
                        tab_content_descrizione             = $tab_descrizione_input.val(),
                        tab_content_apeso                   = $tab_apeso_input.val(),
                        tab_content_codice_prodotto         = $tab_codice_prodotto_input.val(),
                        tab_content_quantita                = $tab_quantita_input.val(),
                        tab_content_categoria_id            = $tab_categoria_id_input.val(),
                        tab_content_secondo_alimento_id     = $tab_secondo_alimento_id_input.val(),
                        tab_content_stampante_associata_id  = $tab_stampante_associata_id_input.val();


/*
 * ----------------------------------------------------------
 *
 * crea array con categorie e confronta, poi carica nel selct
 * idem per stampanti
 *
 * ----------------------------------------------------------
 */

                    $( ui.panel ).append('<div style="min-height:400px;">'+
                        '<div style="height: 40px;">'+
                            '<a style="float: left;min-height: 20px; min-width: 400px;" id="button-'+tab_counter+'" class="finish" title="button">SEGNALA COME ESAURITO</a>'+
                        '</div>'+
                        '<form id="alimentoForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<br /><label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="'+tab_content_prezzo+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="'+tab_content_iva+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 58px;" class="tab_colore_bottone" for="tab_colore_bottone">Colore del bottone: </label>'+
                                '<div id="color-picker-'+tab_counter+'"></div>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="'+tab_content_colore_bottone+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 89px;" class="tab_descrizione" for="tab_descrizione">Descrizione: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="'+tab_content_descrizione+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 132px;" class="tab_apeso" for="tab_apeso">A peso: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_apeso" id="tab_apeso" value="'+tab_content_apeso+'" class="ui-widget-content ui-corner-all" />'+
                                '<!-- futuro -->'+
                                '<!--'+
                                '<br /><label style="margin-right: 20px;" class="tab_path_imageo" for="tab_path_image">Carica immagine: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_path_image" id="tab_path_image" value="<?//$alimento['path_image']?>" class="ui-widget-content ui-corner-all" />'+
                                '-->'+
                                '<br /><label style="margin-right: 52px;" class="tab_codice_prodotto" for="tab_codice_prodotto">Codice prodotto: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_codice_prodotto" id="tab_codice_prodotto" value="'+tab_content_codice_prodotto+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 116px;" class="tab_quantita" for="tab_quantita">Quantit&agrave;: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_quantita" id="tab_quantita" value="'+tab_content_quantita+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 106px;" class="tab_categoria_id" for="tab_categoria_id">Categoria: </label>'+
                        '<select id="tab_categoria_id" name="tab_categoria_id">'+
                            '<option value="0"> - nessuna categoria - </option>'+
                            printSelectCategoria(tab_content_categoria_id)+
                            //'<option selected="selected" value="'+tab_content_categoria_id+'">salva e ricarica</option>'+
                        '</select>'+
                                '<br /><label style="margin-right: 20px;" class="tab_secondo_alimento_id" for="tab_secondo_alimento_id">Alimento composto: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_secondo_alimento_id" id="tab_secondo_alimento_id" value="'+tab_content_secondo_alimento_id+'" class="ui-widget-content ui-corner-all" />'+

                                '<input type="hidden" name="alimento_id" id="alimento_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>" />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_alimento">SALVA</button><br />'+
                             '<button type="submit" id="delete_alimento">ELIMINA</button>'+
                         '</fieldset>'+
                         '<form style="float:left;" id="stampanteAlimentoForm-'+tab_counter+'">'+
                            '<fieldset>'+
                                '<!-- //stampante -->'+
                                '<label style="margin-right: 94px;" class="tab_stampante_associata_id" for="tab_stampante_associata_id">Stampante: </label><br />'+
                                    <?
                                        $data_stampante = DataManager::getAllStampanteByGestoreID($gestore_id);//($gestore_id);
                                        foreach ($data_stampante as $stampante) {
                                    ?>
                                        '<input type="checkbox" name="stampanti[]" id="<?=$alimento['id']?>-<?=$stampante['id']?>" value="<?=$stampante['id']?>" /><?=$stampante['nome']?><br />'+
                                    <?    }
                                    ?>
                                '<input type="hidden" name="alimento_id" id="alimento_id" value="3" />'+
                            '</fieldset>'+
                        '</form>'+
                        ''
                    );

                    //dirigo l'utente alla tab appena creata.
                    $tabs.tabs('select', '#' + ui.panel.id);
            },

            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;

                $('#debug').append('<br />selected: '+ui.index);

                var formSel = $("#alimentoForm-"+ui.index);

                $('a#button-'+ui.index).click(function(){
                    $('#debug').append( '<br />'+'click' );
                    $(this).toggleClass("down");
        //            alert($(this).attr('class'));
                    if ($(this).attr('class')=='finish down'){
                        $(this).html('SEGNALA COME DISPONIBILE');
                            $('#debug').append( '<br />'+'finito' );
                            $.ajax({
                                type: "POST",
                                data: 'finito',
                                url: "manager/gestore/alimentoEsaurito.php",
                                dataType: 'json',
                                cache: false,
                                success: onAlimentoEsauritoSuccess,
                                error: onAlimentoEsauritoError
                            });

                    }else{
                        $(this).html('SEGNALA COME ESAURITO');
                           $('#debug').append( '<br />'+'finito' );
                           $.ajax({
                                type: "POST",
                                data: 'disponibile',
                                url: "manager/gestore/alimentoEsaurito.php",
                                dataType: 'json',
                                cache: false,
                                success: onAlimentoEsauritoSuccess,
                                error: onAlimentoEsauritoError
                            });
                    }
                });


                $('#color-picker-'+ui.index).empty().addColorPicker({
                    clickCallback: function(color) {

                            //$('#color-picker-'+ui.index).next().val(rgb2hex(color));
                            var field = $('#color-picker-'+ui.index).next();
                            field.val(rgb2hex(color));
                            field.css("background-color", "#72A4D2s")
                            //$('#color-picker-dialog').next().css('backround-color', '#ff0');
                            $('#debug').append( '<br />'+rgb2hex(color) );
                    }
                });

                $("#alimentoForm-"+ui.index).validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        },
                        tab_indirizzo: {
                            required: true,
                            url: true
                        }
                    },
                    messages: {
                        tab_indirizzo: {
                            required: "Inserisci un indirizzo IP.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        },
                        tab_nome: {
                            required: "Inserisci il nome della alimento",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 20 caratteri"
                        }
                    }
                });
            }

    });

    // modal dialog init: custom buttons and a "close" callback reseting the form inside
    var $dialog = $( "#dialog" ).dialog({
            position: 'center',
            autoOpen: false,
            modal: true,
            buttons: {
                    Aggiungi: function() {
                            if($("#addNewTab").valid()){
                                addTab();
                                $( this ).dialog( "close" );
                            }
                    },
                    Annulla: function() {
                            $( this ).dialog( "close" );
                    }
            },
            open: function() {
                    $tab_nome_input.focus();
            },
            close: function() {
                    $form[ 0 ].reset();
            }
    });

    // addTab form: calls addTab function on submit and closes the dialog
    var $form = $( "form", $dialog ).submit(function() {
            addTab();
            $dialog.dialog( "close" );
            return false;
    });

    // actual addTab function: adds new tab using the title input from the form above
    function addTab() {
            var tab_title = $tab_nome_input.val() || "Tab " + tab_counter;
            //creo la nuova tab
            $tabs.tabs( "add", "#tabs-" + tab_counter, tab_title );

            //aggiorno le variabili per la prossima nuova tab
            tab_counter++;
            next_id++;
    }

    // addTab button: just opens the dialog
    $( "#add_tab" )
            .button()
            .click(function() {
                    $dialog.dialog( "open" );
            });

    // close icon: removing the tab on click
    // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
    $( "#tabs span.ui-icon-close" ).live( "click", function() {
            var index = $( "li", $tabs ).index( $( this ).parent() );
            $tabs.tabs( "remove", index );
    });



    /*
     *
     * salvo il nuovo tavolo aggiunto al premere del bottone: salva.
     *
     */
    $("#save_alimento").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        /*
         * controllo che il form sia valido
         */
        if($("#alimentoForm-"+selected).valid()){

            /*
             * prelevo tutti i valori delle checkbox e ritorno una stringa
             * formattata json così costruita:
             *
             * tabID-stamapnteID=bool&tabID-stamapnteID=bool&tabID-stamapnteID=bool...
             * dove bool = true se la checkbox è selezionata, false altrimenti
             *
             */
            var params = $.param($(':checkbox').map(function() {
               return { name: this.id, value: !!this.checked };
            }));
            $('#debug').append('<br />'+params);
            $.ajax({
                type: "POST",
                data: params,
                url: "manager/gestore/alimentiStampanti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentiStampantiSuccess,
                error: onAlimentiStampantiError
            });

            $('#debug').append('<br />form valid');
            var alimentoForm = $("#alimentoForm-"+selected).serialize();
            alimentoForm = alimentoForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: alimentoForm,
                url: "manager/gestore/alimenti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentoSuccess,
                error: onAlimentoError
            });

        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_alimento").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questa alimento?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);
            $('#debug').append('<br />deleting ...');

            var alimentoForm = $("#alimentoForm-"+selected).serialize();
            alimentoForm = alimentoForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: alimentoForm,
                url: "manager/gestore/alimenti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentoSuccess,
                error: onAlimentoError
            });
        }
    });

    var $dialogOK = $( "#dialogOK" ).dialog({
            position: 'center',
            autoOpen: false,
            modal: true,
            buttons: {
                    Chiudi: function() {
                            $( this ).dialog( "close" );
                    }
            },
            open: function() {
            },
            close: function() {
            }
    });

    var $dialogERR = $( "#dialogERR" ).dialog({
            position: 'center',
            autoOpen: false,
            modal: true,
            buttons: {
                    Chiudi: function() {
                            $( this ).dialog( "close" );
                    }
            },
            open: function() {
            },
            close: function() {
            }
    });

    function onAlimentoSuccess(data, status) {

        $('#debug').append('<br />ajax: success');

        if (data.action=='del'){

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'eliminazione dell\'alimento.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('L\'alimento &egrave stato eliminato.');
               $dialogOK.dialog( "open" );

               //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });

           }

        }

        /*
         *  verico che l'operazione di salvataggio sia andata a buon fine.
         */
        if(data.action=='save'){

           $('#debug').append('<br />ajax op: save');

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'inserimento o aggioramento dell\'alimento.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('Il nuovo alimento &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+
                                   ' ID_gestore: '    + data.gestore_id +
                                   ' ID_alimento: '   + data.id +
                                   ' ID 2 alimento: ' + data.alimento_id +
                                   ' ID_categoria: '  + data.categoria_id +
                                   ' Nome:'           + data.nome +
                                   ' Prezzo: '        + data.prezzo +
                                   ' IVA: '           + data.iva +
                                   ' colore: '        + data.colore_bottone +
                                   ' descrizione: '   + data.descrizione +
                                   ' apeso: '         + data.apeso +
                                   ' path img: '      + data.path_image +
                                   ' cod prod: '      + data.codice_prodotto +
                                   ' Quantita: '      + data.quantita +
                                   ' Current: '       + data.current_tab +
                                   ' Err: '           + data.err );
           }
        }
    }


    function onAlimentiStampantiSuccess(data, status) {

        $('#debug').append('<br />ajax stampanti: success');

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'aggiornamento delle stampanti.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('Le stampanti sono state aggiornate correttamente.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+data.post+'<br />ERR: '+data.err+'<br />');
           }

    }

    function onAlimentoEsauritoSuccess(data, status) {

        $('#debug').append('<br />ajax alimento esautito: success');

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('Alimento esaurito aggiornato correttamente.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+data.post+'<br />ERR: '+data.err+'<br />');
           }

    }

    /*
     *  se si presentano errori durante le chiamate ajax
     */
    function onAlimentoError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }
    function onAlimentiStampantiError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }
    function onAlimentoEsauritoError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);
    }

});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo alimento">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_prezzo">Prezzo: </label>
                    <input type="text" name="tab_prezzo" id="tab_prezzo" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_iva">Iva: </label>
                    <input type="text" name="tab_iva" id="tab_iva" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_colore_bottone">Colore del bottone </label>
                    <div id='color-picker-dialog'></div>
                    <input type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_descrizione">Descrizione: </label>
                    <input type="text" name="tab_descrizione" id="tab_descrizione" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_apeso">A peso: </label>
                    <input type="text" name="tab_apeso" id="tab_apeso" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_codice_prodotto">Codice prodotto: </label>
                    <input type="text" name="tab_codice_prodotto" id="tab_codice_prodotto" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_quantita">Quantit&agrave;: </label>
                    <input type="text" name="tab_quantita" id="tab_quantita" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_categoria_id">Categoria: </label>
                    <!--
                    <input type="text" name="tab_categoria_id" id="tab_categoria_id" value="" class="ui-widget-content ui-corner-all" />
                    -->
                    <select id="tab_categoria_id" name="tab_categoria_id">
                        <option value="0"> - nessuna categoria - </option>
                        <?php
                            $data_categoria = DataManager::getAllCategoriaByGestoreID($gestore_id);//($gestore_id);
                            $numero_categoria = count($data_categoria);
                            foreach ($data_categoria as $categoria) {
                                    echo '<option value="'.$categoria['id'].'" >'.$categoria['nome'].'</option>';
                            }
                        ?>
                    </select>
                    <label for="tab_secondo_alimento_id">Alimento composto: </label>
                    <input type="text" name="tab_secondo_alimento_id" id="tab_secondo_alimento_id" value="" class="ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>
        <div id="dialogOK" title="Ok!">
            <fieldset style="background-color:#00CF00">
                <p id="code-ok"></p>
                <p>Operazione avvenuta con successo.</p>
            </fieldset>
  	</div>
	<div id="dialogERR" title="Ops!">
            <fieldset style="background-color:red">
                <p id="code-err"></p>
                <p>OPS! Si &egrave; verificato un errore, riprova.<br />Se l'errore persiste contatta l'assistenza.</p>
            </fieldset>
  	</div>

        <!-- tabs container -->
        <div class="alimento_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_alimento as $alimento) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$alimento['nome'].'</a></li>';
                          $count++;
                        }
                    ?>
                    <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi un alimento</span></button>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_alimento as $alimento) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:450px;">
                        <div style="height: 40px;">
                            <a style="float: left;min-height: 20px; min-width: 400px;" id="button-<?=$count?>" class="finish" title="button">SEGNALA COME ESAURITO</a>
                        </div>
                        <form id="alimentoForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <br /><label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$alimento['nome']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="<?=$alimento['prezzo']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="<?=$alimento['iva']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 20px;" class="tab_colore_bottone" for="tab_colore_bottone">Colore del bottone: </label>
                                <div id="color-picker-<?=$count?>"></div>
                                <input style="margin-right: 9px;" type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="<?=$alimento['colore_bottone']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 89px;" class="tab_descrizione" for="tab_descrizione">Descrizione: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="<?=$alimento['descrizione']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 132px;" class="tab_apeso" for="tab_apeso">A peso: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_apeso" id="tab_apeso" value="<?=$alimento['apeso']?>" class="ui-widget-content ui-corner-all" />
                                <!-- futuro -->
                                <!--
                                <br /><label style="margin-right: 20px;" class="tab_path_imageo" for="tab_path_image">Carica immagine: </label>
                                <input style="float:right; margin-right: 9px;" type="text" name="tab_path_image" id="tab_path_image" value="<?//=$alimento['path_image']?>" class="ui-widget-content ui-corner-all" />
                                -->
                                <br /><label style="margin-right: 52px;" class="tab_codice_prodotto" for="tab_codice_prodotto">Codice prodotto: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_codice_prodotto" id="tab_codice_prodotto" value="<?=$alimento['codice_prodotto']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 116px;" class="tab_quantita" for="tab_quantita">Quantit&agrave;: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_quantita" id="tab_quantita" value="<?=$alimento['quantita']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 106px;" class="tab_categoria_id" for="tab_categoria_id">Categoria: </label>
                                <!--
                                <input style="margin-right: 9px;" type="text" name="tab_categoria_id" id="tab_categoria_id" value="<?=$alimento['categoria_id']?>" class="ui-widget-content ui-corner-all" />
                                -->
                                <select id="tab_categoria_id" name="tab_categoria_id">
                                    <option value="0"> - nessuna categoria - </option>
                                    <?php
                                        $data_categoria = DataManager::getAllCategoriaByGestoreID($gestore_id);//($gestore_id);
                                        $numero_categoria = count($data_categoria);
                                        foreach ($data_categoria as $categoria) {
                                                if ($categoria['id'] == $alimento['categoria_id']){
                                                    echo '<option selected="selected" value="'.$categoria['id'].'" >'.$categoria['nome'].'</option>';
                                                }else{
                                                    echo '<option value="'.$categoria['id'].'" >'.$categoria['nome'].'</option>';
                                                }
                                        }
                                    ?>
                                </select>

                                <br /><label style="margin-right: 20px;" class="tab_secondo_alimento_id" for="tab_secondo_alimento_id">Alimento composto: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_secondo_alimento_id" id="tab_secondo_alimento_id" value="<?=$alimento['secondo_alimento_id']?>" class="ui-widget-content ui-corner-all" />

                                <input type="hidden" name="alimento_id" id="alimento_id" value="<?=$alimento['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$alimento['gestore_id']?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_alimento">SALVA</button><br />
                            <button type="submit" id="delete_alimento">ELIMINA</button>
                        </fieldset>
                        
                        <form style="float:left;" id="stampanteAlimentoForm-<?=$count?>">
                            <fieldset>
                                <!-- //stampante -->
                                <label style="margin-right: 94px;" class="tab_stampante_associata_id" for="tab_stampante_associata_id">Stampante: </label><br />
                                    <?php
                                        $data_stampante = DataManager::getAllStampanteByGestoreID($gestore_id);//($gestore_id);
                                        $numero_stampante = count($data_stampante);
                                        foreach ($data_stampante as $stampante) {
                                            echo '<input type="checkbox" name="stampanti[]" id="'.$alimento['id'].'-'.$stampante['id'].'" value="'.$stampante['id'].'" />'.$stampante['nome'].'<br />';
                                        }
                                    ?>
                                <input type="hidden" name="alimento_id" id="alimento_id" value="3" />
                            </fieldset>
                        </form>
                    </div>
                    <?php
                        $count++;
                        echo '</div>';
                    }
                ?>
            <!--
                </div>
            -->
            </div>
        </div><!-- End demo -->

        <h4 style="margin-left: 10px; float:left; width: 920px;">
            <a style="color:#fff;" href="logout.php">esci</a> |
            <a style="color:#fff;" href="support.php">supporto</a> |
            <a style="color:#fff;" href="license.php">credit</a>
        </h4>
</div><!-- end content -->

        <!-- DEBUG -->
        <div id="debug" style="width: 920px;float:left; margin-top: 30px;color:white; font-size: 10px;">DEBUG:</div>
<?php
        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <br /><a style="color:#fff;" href="logout.php"> <-- LOGIN</a>
            </h4>';
    }
?>