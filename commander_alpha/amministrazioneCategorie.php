<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<link rel="stylesheet" href="media/css/color-picker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />

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

           $data_categoria = DataManager::getAllCategoriaByGestoreID($gestore_id);//($gestore_id);
           $numero_categoria = count($data_categoria);
           $max_id = DataManager::getMAXID('cmd_categoria');
//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
    <h1>Gestisci le categorie<small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneCategorie.php"><b>Categorie</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

        $('#color-picker-1').empty().addColorPicker({
            clickCallback: function(color) {
                    $('#color-picker-1').next().val(rgb2hex(color));
                    $('#color-picker-1').next().css("background", rgb2hex(color));
                    $('#color-picker-1').next().css("color", rgb2hex(color));
                    $('#debug').append( '<br />'+rgb2hex(color) );
            }
   	});


        $('#color-picker-dialog').empty().addColorPicker({
            clickCallback: function(color) {
                    $('#color-picker-dialog').next().val(rgb2hex(color));
                    $('#color-picker-dialog').next().css("background", rgb2hex(color));
                    $('#color-picker-dialog').next().css("color", rgb2hex(color));
                    $('#debug').append( '<br />'+rgb2hex(color) );
            }
   	});


       $("#addNewTab").validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_colore_bottone: {
                            required: true,
                            minlength: 2,
                            maxlength: 7
                        }
                    },
                    messages: {
                        tab_colore_bottone: {
                            required: "Seleziona un colore.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "esempio: #ffffff"
                        },
                        tab_nome: {
                            required: "Inserisci il nome della categoria",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        }
                    }
        });

    var $tab_nome_input           = $("#tab_nome"),
        $tab_colore_bottone_input = $('#tab_colore_bottone');

    var tab_counter = <?=$numero_categoria?>,
        max_id      = <?=$max_id?>,
        gestore_id  = <?=$gestore_id?>;


    tab_counter++;
    var next_id = max_id+1;

    $('#debug').append('<br />Numero: '         +tab_counter+
                       '<br />ID max: '         +max_id+
                       '<br />ID max next: '    +next_id+
                       '<br />Gestore ID: '     +gestore_id);

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {

                    var tab_content_nome           = $tab_nome_input.val(),
                        tab_content_colore_bottone = $tab_colore_bottone_input.val();

                    $( ui.panel ).append('<div style="min-height:100px;">'+
                        '<form id="categoriaForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<label style="margin-right: 128px;" class="tab_title" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 20px;" class="tab_colore_bottone" for="tab_colore_bottone">Colore del bottone: </label>'+
                                '<div id="color-picker-'+tab_counter+'"></div>'+
                                '<input style="float:right; margin-right: 9px;" type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="'+tab_content_colore_bottone+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="categoria_id" id="categoria_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_categoria">SALVA</button><br />'+
                             '<button type="submit" id="delete_categoria">ELIMINA</button>'+
                         '</fieldset>'
                    );

                    //dirigo l'utente alla tab appena creata.
                    $tabs.tabs('select', '#' + ui.panel.id);
            },

            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;

                $('#debug').append('<br />selected: '+ui.index);

                var formSel = $("#categoriaForm-"+ui.index);

                $('#color-picker-'+ui.index).empty().addColorPicker({
                    clickCallback: function(color) {
                        $('#color-picker-'+ui.index).next().val(rgb2hex(color));
                        $('#color-picker-'+ui.index).next().css("background", rgb2hex(color));
                        $('#color-picker-'+ui.index).next().css("color", rgb2hex(color));

                        $('#debug').append( '<br />'+rgb2hex(color) );
                    }
                });

                $("#categoriaForm-"+ui.index).validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_colore_bottone: {
                            required: true,
                            minlength: 2,
                            maxlength: 7
                        }
                    },
                    messages: {
                        tab_colore_bottone: {
                            required: "Seleziona un colore.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "esempio: #ffffff"
                        },
                        tab_nome: {
                            required: "Inserisci il nome della categoria",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
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
    $("#save_categoria").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#categoriaForm-"+selected).valid()){

            var categoriaForm = $("#categoriaForm-"+selected).serialize();
            categoriaForm = categoriaForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: categoriaForm,
                url: "manager/gestore/categorie.php",
                dataType: 'json',
                cache: false,
                success: onCategoriaSuccess,
                error: onError
            });
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_categoria").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Categoria?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var categoriaForm = $("#categoriaForm-"+selected).serialize();
            categoriaForm = categoriaForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: categoriaForm,
                url: "manager/gestore/categorie.php",
                dataType: 'json',
                cache: false,
                success: onCategoriaSuccess,
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

    function onCategoriaSuccess(data, status) {

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
               $('#code-err').html('Errore durante l\'eliminazione della categoria.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('La ctegoria &egrave stata eliminata.');
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

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'inserimento o aggioramento della categoria.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('La nuova categoria &egrave stata aggiunta.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+
                                   ' ID_gestore: '    + data.gestore_id+
                                   ' ID_categoria: '  + data.categoria_id+
                                   ' Nome:'           + data.nome +
                                   ' Colore: '        + data.colore_bottone+
                                   ' Current: '       + data.current_tab+
                                   ' Err: '           + data.err );
               //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });
           }
        }
    }
    function onError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }


});
</script>

        <!-- dialogs -->
	<div id="dialog" class="dialog-color" title="Dati nuova categoria">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_nome">Nome </label><br />
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <br /><label for="tab_colore_bottone">Colore del bottone</label>
                    <div id='color-picker-dialog'></div>
                    <input type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="" class="addNewTab-color ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>

        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi una categoria</span></button>
        <div class="clearfix"></div>
        <!-- tabs container -->
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_categoria as $categoria) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$categoria['nome'].'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_categoria as $categoria) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:100px;">
                        <form id="categoriaForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <label style="margin-right: 128px;" class="tab_title" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$categoria['nome']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 20px;" class="tab_colore_bottone" for="tab_colore_bottone">Colore del bottone: </label>
                                <div id="color-picker-<?=$count?>"></div>
                                <input style="float:right; margin-right: 9px;" type="text" name="tab_colore_bottone" id="tab_colore_bottone" value="<?=$categoria['colore_bottone_predef']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="categoria_id" id="categoria_id" value="<?=$categoria['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$categoria['gestore_id']?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_categoria">SALVA</button><br />
                            <button type="submit" id="delete_categoria">ELIMINA</button>
                        </fieldset>
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

        <!-- footer -->
        <?php include_once 'footer.php';?>
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