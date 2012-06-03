<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
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

           $data_stampante = DataManager::getAllStampanteByGestoreID($gestore_id);//($gestore_id);
           $numero_stampante = count($data_stampante);
           $max_id = DataManager::getMAXID('cmd_stampante');
           if (!$max_id){
               $max_id=0;
           }
//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
    <h1>Gestisci le stampanti
        <small  class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneStampanti.php"><b>Stampanti</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

        $.validator.addMethod('IP4Checker', function(value) {
             var ip = "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$";
             return value.match(ip);
            }, 'Inserisci un indirizzo valido, es: 192.168.1.x');

        $("#addNewTab").validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        ip: {
                            required: true,
                            IP4Checker: true
                        }
                    },
                    messages: {
                        ip: {
                            required: "Inserisci un indirizzo IP."
//                            IP4Checker: true
                        },
                        tab_nome: {
                            required: "Inserisci il nome della stampante",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        }
                    }
        });

    var $tab_nome_input       = $("#tab_nome"),
        $tab_indirizzo_input  = $('#ip');

    var tab_counter = <?=$numero_stampante?>,
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

                    var tab_content_nome      = $tab_nome_input.val(),
                        tab_content_indirizzo = $tab_indirizzo_input.val();

                    $( ui.panel ).append('<div style="min-height:100px;">'+
                        '<form id="stampanteForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<label style="margin-right: 160px;" class="tab_title" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 10px;" class="tab_indirizzo" for="tab_indirizzo">Indirizzo IP stampante: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="ip" id="ip" value="'+tab_content_indirizzo+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="stampante_id" id="stampante_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_stampante">SALVA</button><br />'+
                             '<button type="submit" id="delete_stampante">ELIMINA</button>'+
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

                var formSel = $("#stampanteForm-"+ui.index);

                $("#stampanteForm-"+ui.index).validate({
                    rules: {
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        ip: {
                            required: true,//url: true
                            IP4Checker: true
                        }
                    },
                    messages: {
                        ip: {
                            required: "Inserisci un indirizzo IP."
//                            //url: "Inserisci un indirizzo valido (es: http://100.100.100.100)"
//                            IP4Checker: true
                        },
                        tab_nome: {
                            required: "Inserisci il nome della stampante",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
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
    $("#save_stampante").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#stampanteForm-"+selected).valid()){

            $('#debug').append('<br />form valid');
            var stampanteForm = $("#stampanteForm-"+selected).serialize();
            stampanteForm = stampanteForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: stampanteForm,
                url: "manager/gestore/stampanti.php",
                dataType: 'json',
                cache: false,
                success: onStampanteSuccess,
                error: onError
            });
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_stampante").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questa stampante?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);
            $('#debug').append('<br />deleting ...');

            var stampanteForm = $("#stampanteForm-"+selected).serialize();
            stampanteForm = stampanteForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: stampanteForm,
                url: "manager/gestore/stampanti.php",
                dataType: 'json',
                cache: false,
                success: onStampanteSuccess,
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

    function onStampanteSuccess(data, status) {

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
               $('#code-err').html('Errore durante l\'eliminazione della stampante.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('La stampante &egrave stata eliminata.');
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento della stampante.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('La nuova stampante &egrave stata aggiunta.');
               $dialogOK.dialog( "open" );

                //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });

//               $('#debug').append( '<br />DATA SAVED:<br />'+
//                                   ' ID_gestore: '    + data.gestore_id+
//                                   ' ID_stampante: '  + data.stampante_id+
//                                   ' Nome:'           + data.nome +
//                                   ' Indirizzo: '     + data.indirizzo+
//                                   ' Current: '       + data.current_tab+
//                                   ' Err: '           + data.err );
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
	<div id="dialog" title="Dati nuova stampante">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label for="tab_indirizzo">Indirizzo </label>
                    <input type="text" name="ip" id="ip" value="" class="addNewTab ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>
        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>

        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi una stampante</span></button>
        <div class="clearfix"></div>

        <!-- tabs container -->
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_stampante as $stampante) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$stampante['nome'].'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_stampante as $stampante) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:100px;">
                        <form id="stampanteForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <label style="margin-right: 160px;" class="tab_title" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$stampante['nome']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 10px;" class="tab_indirizzo" for="tab_indirizzo">Indirizzo IP stampante: </label>
                                <input style="margin-right: 9px;" type="text" name="ip" id="ip" value="<?=$stampante['indirizzo']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="stampante_id" id="stampante_id" value="<?=$stampante['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$stampante['gestore_id']?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_stampante">SALVA</button><br />
                            <button type="submit" id="delete_stampante">ELIMINA</button>
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