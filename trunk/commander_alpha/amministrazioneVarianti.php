<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $objSession->Impress();

    header('Content-Type: text/html; charset=utf-8');
?>
<!--
todo: 1. Creare tutti i controlli del form
      2. Tgliere debug
      3. Sistemare title tab
-->
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />

<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script src="media/js/ui/jquery.ui.core.js"></script>
<script src="media/js/ui/jquery.ui.widget.js"></script>
<script src="media/js/ui/jquery.ui.tabs.js"></script>
<script src="media/js/ui/jquery.ui.button.js"></script>
<script src="media/js/ui/jquery.ui.dialog.js"></script>
<script src="media/js/ui/jquery.ui.position.js"></script>
<script src="media/js/ui/jquery.ui.draggable.js"></script>

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

           $data_variante = DataManager::getAllVarianteByGestoreID($gestore_id);//($gestore_id);
           $numero_variante = count($data_variante);
           $max_id = DataManager::getMAXID('cmd_variante');
           if (!$max_id){
               $max_id=0;
           }
           //echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    
    ?>
    <h1>Gestisci le varianti<small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneVarianti.php"><b>Varianti</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

       $("#addNewTab").validate({
                    rules: {
                        tab_descrizione: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_prezzo: {
                            required: true,
                            number: true

                        },
                        tab_iva: {
                            required: false,
                            digits: true
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descrizione della variante",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        },
                    tab_prezzo: {
                            required: "Inserisci il prezzo della variante",
                            number: "cifre separate da un punto"
                        },
                        tab_iva: {
                            required: "Inserisci ll valore dell'IVA",
                            digits:"Inserisci solo cifre"
                        }
                    }
        });

    var //$tab_nome_input                     = $("#tab_nome"),
        $tab_prezzo_input                   = $('#tab_prezzo'),
        $tab_iva_input                      = $('#tab_iva'),
        $tab_descrizione_input              = $('#tab_descrizione');

    var tab_counter = <?=$numero_variante?>,
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

                    var //tab_content_nome                    = $tab_nome_input.val(),
                        tab_content_prezzo                  = $tab_prezzo_input.val(),
                        tab_content_iva                     = $tab_iva_input.val(),
                        tab_content_descrizione             = $tab_descrizione_input.val();

                    $( ui.panel ).append('<div style="min-height:100px;">'+
                        '<form id="varianteForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="'+tab_content_prezzo+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>'+
                                '<!-- class="tab_descrizione" -->'+
                                '<input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="'+tab_content_iva+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 89px;" class="tab_title" for="tab_descrizione">Descrizione: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="'+tab_content_descrizione+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="variante_id" id="variante_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_variante">SALVA</button><br />'+
                             '<button type="submit" id="delete_variante">ELIMINA</button>'+
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

                var formSel = $("#varianteForm-"+ui.index);

                $("#varianteForm-"+ui.index).validate({
                    rules: {
                        tab_descrizione: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_prezzo: {
                            required: true,
                            number: true

                        },
                        tab_iva: {
                            required: false,
                            digits: true
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descrizione della variante",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        },
                    tab_prezzo: {
                            required: "Inserisci il prezzo della variante",
                            number: "cifre separate da un punto"
                        },
                        tab_iva: {
                            required: "Inserisci ll valore dell'IVA",
                            digits:"Inserisci solo cifre"
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
                    $tab_descrizione_input.focus();
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
            var tab_title = $tab_descrizione_input.val() || "Tab " + tab_counter;
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
    $("#save_variante").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#varianteForm-"+selected).valid()){

            var varianteForm = $("#varianteForm-"+selected).serialize();
            varianteForm = varianteForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: varianteForm,
                url: "manager/gestore/varianti.php",
                dataType: 'json',
                cache: false,
                success: onVarianteSuccess,
                error: onError
            });
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_variante").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Variante?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var varianteForm = $("#varianteForm-"+selected).serialize();
            varianteForm = varianteForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: varianteForm,
                url: "manager/gestore/varianti.php",
                dataType: 'json',
                cache: false,
                success: onVarianteSuccess,
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

    function onVarianteSuccess(data, status) {

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
               $('#code-err').html('Errore durante l\'eliminazione della variante.');
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento della variante.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('La nuova variante &egrave stata aggiunta.');
               $('#debug').append( '<br />DATA SAVED:<br />'+
                                   ' ID_gestore: '    + data.gestore_id+
                                   ' ID_variante: '   + data.variante_id+
                                   ' Nome:'           + data.nome +
                                   ' Prezzo: '        + data.prezzo +
                                   ' IVA: '           + data.iva +
                                   ' descrizione: '   + data.descrizione +                                   
                                   ' Current: '       + data.current_tab+
                                   ' Err: '           + data.err );
               $dialogOK.dialog( "open" );
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
	<div id="dialog" title="Dati nuova variante">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <!--
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="ui-widget-content ui-corner-all" />
                    -->
                    <label for="tab_prezzo">Prezzo: </label>
                    <input type="text" name="tab_prezzo" id="tab_prezzo" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label for="tab_iva">Iva: </label>
                    <input type="text" name="tab_iva" id="tab_iva" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label for="tab_descrizione">Descrizione: </label>
                    <input type="text" name="tab_descrizione" id="tab_descrizione" value="" class="addNewTab ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>
        <!-- tabs container -->

        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi nuova variante</span></button>
        <div class="clearfix"></div>
        <!-- tabs container -->
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_variante as $variante) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$variante['descrizione'].'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_variante as $variante) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:100px;">
                        <form id="varianteForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <!--class="tab_descrizione"-->
                                <label style="margin-right: 89px;" class="tab_title" for="tab_descrizione">Descrizione: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="<?=$variante['descrizione']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="<?=$variante['prezzo']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="<?=$variante['iva']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="variante_id" id="variante_id" value="<?=$variante['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$variante['gestore_id']?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_variante">SALVA</button><br />
                            <button type="submit" id="delete_variante">ELIMINA</button>
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