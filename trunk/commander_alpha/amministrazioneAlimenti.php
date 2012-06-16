<?php
    require_once dirname(__FILE__)  . '/manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $objSession->Impress();
?>
<!--
todo: 1. Attenzione al refresh della pagina
      2. Alimento esaurito non segnato come presente
      3. Gestisci le eliminazioni:
            - rel_alimento_stampante
            - rel_variante_alimento
            - cmd_alimento_esaurito
      4. Se un alimento è presente in un menu non di deve eliminare:
            - rel_alimentomenu_alimento
            -
-->
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

    /*
     *
     */
    function selectOption(select_id, option_val) {
        $('#'+select_id+' option:selected').removeAttr('selected');
        $('#'+select_id+' option[value='+option_val+']').attr('selected','selected');
    }
</script>

<script src="media/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

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

           $data_alimento_variante = DataManager::getAllAlimentoVariante();
           $data_categoria         = DataManager::getAllCategoriaByGestoreID($gestore_id);
           /*
           foreach ($data_alimento_variante as $data_alID_stamp_ID) {
               echo '<pre style="background-color:white;">';
               print_r($data_alID_stamp_ID);
               echo "</pre>";
           }
           */
           $numero_alimento_stampante = count($data_alimento_tampante);

    ?>
    <h1>Gestisci gli Alimenti<small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneAlimenti.php"><b>Alimenti</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {


    /*
     * al caricamento della pagina controllo se l'alimento è esaurito
     *
     */
    $('a#button').each(function(){
        var alimento_ID = ($(this).next().val());
        console.log('alimentoID: '+alimento_ID);
        var finito = '';
            $.ajax({
                type: "POST",
                async: false,
                data: 'action=controllo&id='+alimento_ID,
                url: "manager/gestore/alimentoEsaurito.php",
                dataType: 'json',
                cache: false,
                success: function onAlimentoEsauritoSuccess(data, status) {

                               console.log(data);
                               finito = data.finito;
                               console.log(finito);
                            },
                error: onError
            });
            if (finito){
                       console.log("segnale disponibilie");
                        $(this).toggleClass("down");
                        $(this).html('SEGNALA COME DISPONIBILE');
                        $(this).css('background-color', 'green');

                    }else {
                       console.log("segnale esaurito");
                        $(this).toggleClass("down");
                        $(this).html('SEGNALA COME ESAURITO');
                        $(this).css('background-color', 'red');
                    }
            console.log('fine: '+alimento_ID);
    });


        $('#color-picker-1').empty().addColorPicker({
            clickCallback: function(color) {
                    $('#color-picker-1').next().val(rgb2hex(color));
                    $('#color-picker-1').next().css("background", rgb2hex(color));
                    $('#color-picker-1').next().css("color", rgb2hex(color));
            }
   	});

        $('#color-picker-dialog').empty().addColorPicker({
            clickCallback: function(color) {
                    $('#color-picker-dialog').next().val(rgb2hex(color));
                    $('#color-picker-dialog').next().css("background", rgb2hex(color));
                    $('#color-picker-dialog').next().css("color", rgb2hex(color));

            }
   	});

        $("#addNewTab").validate({
                    rules: {
                        tab_descrizione: {
                            required: false,
                            minlength: 2,
                            maxlength: 50
                        },
                        tab_prezzo: {
                            required: true,
                            number: true

                        },
                        tab_iva: {
                            required: false,
                            digits: true
                        },
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        },
                        tab_colore_bottone: {
                            required: false
                        },
                        tab_apeso: {
                            required: false,
                            minlength: 1,
                            maxlength: 1
                        },
                        tab_codice_prodotto: {
                            required: false
                        },
                        tab_quantita: {
                             required: false,
                             number: true
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descrizone",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 50 caratteri"
                        },
                        tab_prezzo: {
                            required: "Inserisci il prezzo",
                            number: "Inserisci solo cifre separate da un punto"
                        },
                        tab_iva: {
                            digits: "Inserisci solo cifre."
                        },
                        tab_nome: {
                            required: "Inserisci il nome",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 20 caratteri"
                        },
                        tab_colore_bottone: {
                        },
                        tab_apeso: {
                            minlength: 1,
                            maxlength: "Inserisci S per si o qualsiasi altro carattere per no"
                        },
                        tab_codice_prodotto: {
                        },
                        tab_quantita: {
                             number: "Inserisci solo cifre separate da un punto"
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


        var tab_counter          = <?=$numero_alimento?>,
            max_id               = <?=$max_id?>,
            gestore_id           = <?=$gestore_id?>,
            numero_rel_stampanti = <?= $numero_alimento_stampante ?>;

       
       // Stampanti-------------------------------------------------------------
       /*
        * Creo l'array che contiene l'id delle checkbox che devono essere
        * selezionate
        */
       var id_stampante_alimento = new Array();
       <?php foreach ($data_alimento_tampante as $data_alID_stamp_ID) { ?>
            id_stampante_alimento.push("<?=$data_alID_stamp_ID['alimento_id'];?>-<?=$data_alID_stamp_ID['stampante_id'];?>");
       <?php } ?>
       /*
        *  metto a checked=true le checkbox che rappresentano relazioni nel db
        */
       $.each(id_stampante_alimento, function(key, value){
           //alert(key + ': ' + value);
           $('#'+value).prop('checked', true);
       });


       // Varianti--------------------------------------------------------------
       /*
        * Creo l'array che contiene l'id delle checkbox che devono essere
        * selezionate
        */
       var id_variante_alimento = new Array();
       <? foreach ($data_alimento_variante as $al_ID_var_ID) { ?>
                id_variante_alimento.push("av<?=$al_ID_var_ID['alimento_id']?>-<?=$al_ID_var_ID['variante_id']?>");
       <? } ?>

       /*
        *  metto a checked=true le checkbox che rappresentano relazioni nel db
        */
       $.each(id_variante_alimento, function(key, value){
           //alert(key + ': ' + value);
           $('#'+value).prop('checked', true);
       });
       


       // Categorie-------------------------------------------------------------
       /*
        * inserisco all'interno di un array le categorie presenti nel db
        * $data_categoria
        */
       var id_categoria = {};
       <? foreach ($data_categoria as $categoria) { ?>
                id_categoria["<?=$categoria['nome']?>"] = <?=$categoria['id']?>;
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


                    $( ui.panel ).append('<div style="min-height:360px;">'+
                        '<div style="height: 40px;">'+
                            '<a style="float: left;min-height: 20px; min-width: 400px;" id="button-'+tab_counter+'" class="finish" title="button">SEGNALA COME ESAURITO</a>'+
                        '</div>'+
                        '<form id="alimentoForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>'+
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
                         '</fieldset>'
                    );
                    //dirigo l'utente alla tab appena creata.
                    $tabs.tabs('select', '#' + ui.panel.id);
            },

            remove: function( event, ui ) {

            },

            create: function(event, ui) {



                $('#color-picker-'+ui.index).empty().addColorPicker({
                    clickCallback: function(color) {

                            //$('#color-picker-'+ui.index).next().val(rgb2hex(color));
                            var field = $('#color-picker-'+ui.index).next();
                            field.val(rgb2hex(color));
                            //$('#color-picker-dialog').next().css('background', $('#color-picker-dialog').next().val());
                            field.css("background", rgb2hex(color));
                            field.css("color", rgb2hex(color));
                            //$('#color-picker-dialog').next().css('backround', '#ff0');
                    }
                });

                $("#alimentoForm-"+ui.index).validate({
                    rules: {
                        tab_descrizione: {
                            required: false,
                            minlength: 2,
                            maxlength: 50
                        },
                        tab_prezzo: {
                            required: true,
                            number: true

                        },
                        tab_iva: {
                            required: false,
                            digits: true
                        },
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        },
                        tab_colore_bottone: {
                            required: false
                        },
                        tab_apeso: {
                            required: false,
                            minlength: 1,
                            maxlength: 1
                        },
                        tab_codice_prodotto: {
                            required: false
                        },
                        tab_quantita: {
                             required: false,
                             number: true
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descrizone",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 50 caratteri"
                        },
                        tab_prezzo: {
                            required: "Inserisci il prezzo",
                            number: "Inserisci solo cifre separate da un punto"
                        },
                        tab_iva: {
                            digits: "Inserisci solo cifre."
                        },
                        tab_nome: {
                            required: "Inserisci il nome",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 20 caratteri"
                        },
                        tab_colore_bottone: {
                        },
                        tab_apeso: {
                            minlength: 1,
                            maxlength: "Inserisci S per si o qualsiasi altro carattere per no"
                        },
                        tab_codice_prodotto: {
                        },
                        tab_quantita: {
                             number: "Inserisci solo cifre separate da un punto"
                        }
                    }
                });
            },

            select: function( event, ui ) {

                ui.index+=1;

                $('#color-picker-'+ui.index).empty().addColorPicker({
                    clickCallback: function(color) {

                            //$('#color-picker-'+ui.index).next().val(rgb2hex(color));
                            var field = $('#color-picker-'+ui.index).next();
                            field.val(rgb2hex(color));
                            //$('#color-picker-dialog').next().css('background', $('#color-picker-dialog').next().val());
                            field.css("background", rgb2hex(color));
                            field.css("color", rgb2hex(color));
                            //$('#color-picker-dialog').next().css('backround', '#ff0');
                    }
                });

                $("#alimentoForm-"+ui.index).validate({
                    rules: {
                        tab_descrizione: {
                            required: true,
                            minlength: 2,
                            maxlength: 50
                        },
                        tab_prezzo: {
                            required: true,
                            number: true

                        },
                        tab_iva: {
                            required: false,
                            digits: true
                        },
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        },
                        tab_colore_bottone: {
                            required: false
                        },
                        tab_apeso: {
                            required: false,
                            minlength: 1,
                            maxlength: 1
                        },
                        tab_codice_prodotto: {
                            required: false
                        },
                        tab_quantita: {
                             required: false,
                             number: true
                        }
                    },
                    messages: {
                         tab_descrizione: {
                            required: "Inserisci la descrizone",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 50 caratteri"
                        },
                        tab_prezzo: {
                            required: "Inserisci il prezzo",
                            number: "Inserisci solo cifre separate da un punto"
                        },
                        tab_iva: {
                            digits: "Inserisci solo cifre."
                        },
                        tab_nome: {
                            required: "Inserisci il nome",
                            minlength: "Minimo 2 caratteri",
                            maxlength: "Massimo 20 caratteri"
                        },
                        tab_colore_bottone: {
                        },
                        tab_apeso: {
                            minlength: 1,
                            maxlength: "Inserisci S per si o qualsiasi altro carattere per no"
                        },
                        tab_codice_prodotto: {
                        },
                        tab_quantita: {
                             number: "Inserisci solo cifre separate da un punto"
                        }
                    }
                });
            }

    });


    /*
     * visualizzo o nascondo il pannello con tutte le varianti
     * disponibili
     *
     */
    $("button").click(function () {
        $(".variante").slideToggle("slow");
    });

    /*
     * dialogo di inserimento del nuovo alimento
     *
     */
    var $dialog = $( "#dialog" ).dialog({
            position: 'center',
            autoOpen: false,
            modal: true,
            width: '420px',
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
                    $dialog.dialog("open" );

            });

    // close icon: removing the tab on click
    // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
    $( "#tabs span.ui-icon-close" ).live( "click", function() {
            var index = $( "li", $tabs ).index( $( this ).parent() );
            $tabs.tabs( "remove", index );
    });

                $('a#button').click(function(){

                    var selected = $tabs.tabs('option', 'selected');
                    selected+=1;
                    var alimento_ID = $(this).next().val();
//                    alert(alimento_ID);
                    var finito = '';
                    $.ajax({
                        type: "POST",
                        data: 'action=finito&id='+alimento_ID,
                        url: "manager/gestore/alimentoEsaurito.php",
                        dataType: 'json',
                        cache: false,
                        success: function onAlimentoEsauritoSuccess(data, status) {

                                       finito = data.finito;

                                       if (data.err=='E002'){
                                           $('#code-err').html('Sessione scaduta o login non valido.');
                                           $dialogERR.dialog("open");
                                        } else if (data.err=='E001'){
                                           $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
                                           $dialogERR.dialog("open");
                                        } else if (data.err=='false'){
                                           $('#code-err').html('Errore.');
                                           $dialogERR.dialog("open");
                                        } else if(data.err==''){
                                           $('#code-ok').html('Alimento esaurito aggiornato correttamente.');
                                           $dialogOK.dialog( "open" );
                                       } else{
                                           $('#code-err').html('Errore durante l\'aggiornamento.');
                                           //$dialogERR.dialog("open");
                                       }
                                    },
                        error: onError
                    });
                    if (finito){
                        $(this).toggleClass("down");
                        $(this).html('SEGNALA COME DISPONIBILE');
                        $(this).css('background-color', 'green');

                    }else {
                        $(this).toggleClass("down");
                        $(this).html('SEGNALA COME ESAURITO');
                        $(this).css('background-color', 'red');

                    }
                });

    /*
     *
     * salvo il nuovo tavolo aggiunto al premere del bottone: salva.
     *
     */
    $("#save_alimento").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;

        /*
         * controllo che il form sia valido
         */
        if($("#alimentoForm-"+selected).valid()){

            /*
             * prelevo tutti i valori delle checkbox VARIANTI e ritorno una stringa
             * formattata json così costruita:
             *
             * av+tabID-stamapnteID=bool&av+tabID-stamapnteID=bool&tabID-stamapnteID=bool...
             * dove bool = true se la checkbox è selezionata, false altrimenti
             *
             */
            var params_v = $.param($('#varianteAlimentoForm-'+selected+' input:checkbox').map(function() {
               return { name: this.id, value: !!this.checked };
            }));
            $.ajax({
                type: "POST",
                data: params_v,
                url: "manager/gestore/alimentiVarianti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentiVariantiSuccess,
                error: onError
            });

            /*
             * prelevo tutti i valori delle checkbox STAMPANTI e ritorno una stringa
             * formattata json così costruita:
             *
             * tabID-stamapnteID=bool&tabID-stamapnteID=bool&tabID-stamapnteID=bool...
             * dove bool = true se la checkbox è selezionata, false altrimenti
             *
             */
            var params = $.param($('#stampanteAlimentoForm-'+selected+' input:checkbox').map(function() {
               return { name: this.id, value: !!this.checked };
            }));
            $.ajax({
                type: "POST",
                data: params,
                url: "manager/gestore/alimentiStampanti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentiStampantiSuccess,
                error: onError
            });

            var alimentoForm = $("#alimentoForm-"+selected).serialize();
            alimentoForm = alimentoForm+'&action=save&current_tab='+selected;
            $.ajax({
                type: "POST",
                data: alimentoForm,
                url: "manager/gestore/alimenti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentoSuccess,
                error: onError
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

            var alimentoForm = $("#alimentoForm-"+selected).serialize();
            alimentoForm = alimentoForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: alimentoForm,
                url: "manager/gestore/alimenti.php",
                dataType: 'json',
                cache: false,
                success: onAlimentoSuccess,
                error: onError
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
        if (data.action=='del'){

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='E008'){
               $('#code-err').html('Questo alimento &egrave; associato ad un menu fisso. Per poter eliminarlo cancellalo prima dal men&ugrave;.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'eliminazione dell\'alimento.');
               $dialogERR.dialog("open");
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

           }else{
               $('#code-err').html('Errore durante l\'eliminazione dell\'alimento.');
               $dialogERR.dialog("open");
           }

        }


        /*
         *  verico che l'operazione di salvataggio sia andata a buon fine.
         */
        if(data.action=='save'){

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=="false"){
               $('#code-err').html('Errore durante l\'inserimento o aggioramento dell\'alimento.');
               $dialogERR.dialog("open");

           } else if(data.err==''){
               $('#code-ok').html('Il nuovo alimento &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );

              //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                   location.reload();
               });
           } else{
               $('#code-err').html('Errore durante l\'aggiornamento dell\'alimento.');
               $dialogERR.dialog("open");
           }

        }
    }


    function onAlimentiStampantiSuccess(data, status) {
       if (data.err=='E002'){
           $('#code-err').html('Sessione scaduta o login non valido.');
           $dialogERR.dialog("open");

       } else if (data.err=='E001'){
           $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
           $dialogERR.dialog("open");

       } else if (data.err=='false'){
           $('#code-err').html('Errore durante l\'aggiornamento delle stampanti.');
           $dialogERR.dialog("open");
 
       } else if(data.err==''){
 
        } else{
           $('#code-err').html('Errore durante l\'aggiornamento delle stampanti.');
           $dialogERR.dialog("open");
       }

    }

    function onAlimentiVariantiSuccess(data, status) {
 
       if (data.err=='E002'){
           $('#code-err').html('Sessione scaduta o login non valido.');
           $dialogERR.dialog("open");

       } else if (data.err=='E001'){
           $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
           $dialogERR.dialog("open");

       } else if (data.err=='false'){
           $('#code-err').html('Errore durante l\'aggiornamento delle varianti.');
           $dialogERR.dialog("open");

       } else if(data.err==''){

       } else{
           $('#code-err').html('Errore durante l\'aggiornamento delle varianti.');
           $dialogERR.dialog("open");
       }
    }

    function onError(){
       $('#code-err').html('Errore nel file. Contattare l\'assistenza.');
       $dialogERR.dialog("open");
    }

});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo alimento">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_prezzo">Prezzo: </label>
                    <input type="text" name="tab_prezzo" id="tab_prezzo" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_iva">Iva: </label>
                    <input type="text" name="tab_iva" id="tab_iva" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_colore_bottone">Colore del bottone </label>
                    <div id='color-picker-dialog'></div>
                    <input type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="" class="addNewTab-color ui-widget-content ui-corner-all" />
                    <label for="tab_descrizione">Descrizione: </label>
                    <input type="textarea" name="tab_descrizione" id="tab_descrizione" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_apeso">A peso: </label>
                    <input type="text" name="tab_apeso" id="tab_apeso" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_codice_prodotto">Codice prodotto: </label>
                    <input type="text" name="tab_codice_prodotto" id="tab_codice_prodotto" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                    <label for="tab_quantita">Quantit&agrave;: </label>
                    <input type="text" name="tab_quantita" id="tab_quantita" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
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
                    <input type="text" name="tab_secondo_alimento_id" id="tab_secondo_alimento_id" value="" class="addNewTab-alimento ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>

        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi una alimento</span></button>
        <div class="clearfix"></div>

        <!-- tabs container -->
        <div class="alimento_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_alimento as $alimento) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.html_entity_decode(htmlentities($alimento['nome'])).'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_alimento as $alimento) {
                        $secondo_alimento_id = $alimento['alimento_id'];
                        if (!$secondo_alimento_id){
                            $secondo_alimento_id = '';
                        }
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:360px;">
                        <div style="height: 40px;">
<!--                            <a style="float: left;min-height: 20px; min-width: 400px;" id="button-<?=$count?>" class="finish" title="button">SEGNALA COME ESAURITO</a>-->
                            <a style="float: left;min-height: 20px; min-width: 400px;" id="button" class="finish" title="button">SEGNALA COME ESAURITO</a>
                            <input type="hidden" name="alimento_id2" id="alimento_id2" value="<?=$alimento['id']?>" />
                        </div>
                        <form id="alimentoForm-<?=$count;?>" style="min-height:60px;float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=html_entity_decode(htmlentities($alimento['nome']))?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="<?=$alimento['prezzo']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="<?=$alimento['iva']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 20px;" class="tab_colore_bottone" for="tab_colore_bottone">Colore del bottone: </label>
                                <div id="color-picker-<?=$count;?>"></div>
                                <input style="margin-right: 9px;" type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="<?=$alimento['colore_bottone']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 89px;" class="tab_descrizione" for="tab_descrizione">Descrizione: </label>
                                <input style="margin-right: 9px;" type="textarea" name="tab_descrizione" id="tab_descrizione" value="<?=$alimento['descrizione']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 132px;" class="tab_apeso" for="tab_apeso">A peso: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_apeso" id="tab_apeso" value="<?=$alimento['apeso']?>" class="ui-widget-content ui-corner-all" />
                                <!-- futuro -->
                                <!--
                                <br /><label style="margin-right: 20px;" class="tab_path_imageo" for="tab_path_image">Carica immagine: </label>
                                <input style="float:right; margin-right: 9px;" type="text" name="tab_path_image" id="tab_path_image" value="<?//=$alimento['path_image']?>" class="ui-widget-content ui-corner-all" />
                                -->
                                <br /><label style="margin-right: 50px;" class="tab_codice_prodotto" for="tab_codice_prodotto">Codice prodotto: </label>
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
                                                    echo '<option selected="selected" value="'.$categoria['id'].'" >'.html_entity_decode(htmlentities($categoria['nome'])).'</option>';
                                                }else{
                                                    echo '<option value="'.$categoria['id'].'" >'.html_entity_decode(htmlentities($categoria['nome'])).'</option>';
                                                }
                                        }
                                    ?>
                                </select>

                                <br /><label style="margin-right: 20px;" class="tab_secondo_alimento_id" for="tab_secondo_alimento_id">Alimento composto: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_secondo_alimento_id" id="tab_secondo_alimento_id" value="<?=$secondo_alimento_id;?>" class="ui-widget-content ui-corner-all" />

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
                            </fieldset>
                        </form>

                        <!--
                            Area varianti
                        -->
                        <button style="float:left;width:235px;">Associa una variante</button>
                        <form class="variante" style="float:left; display:none;width:235px;" id="varianteAlimentoForm-<?=$count?>">
                        <fieldset>
                            <!-- //variante -->
                            <!--
                            <label style="margin-top: 10px;" class="tab_variante_associata_id" for="tab_variante_associata_id">Elenco varianti: </label><br />
                            -->
                                <?php
                                    $data_variante = DataManager::getAllVarianteByGestoreID($gestore_id);
                                    foreach ($data_variante as $variante) {
                                        echo '<input type="checkbox" name="varianti[]" id="av'.$alimento['id'].'-'.$variante['id'].'" value="'.$variante['id'].'" />'.$variante['descrizione'].'<br />';
                                    }
                                ?>
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
            <div class="clearfix"></div>
            </div>
        </div><!-- End demo -->

        <!-- footer -->
        <?php include_once 'footer.php';?>
</div><!-- end content -->

<script type="text/javascript">
    
</script>


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
