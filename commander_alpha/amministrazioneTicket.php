<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<!--
todo: 1. Creare tutti i controlli del form
      2. Tgliere debug
      3. Sistemare title tab
-->
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
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
    #save_ticket{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
       cursor: pointer;
    }
    #delete_ticket{
       width: 100px;
       height: 50px;
       background-color: red;
       text-transform: uppercase;
       cursor: pointer;
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

           $data_ticket = DataManager::getAllBuonoPrepagatoByGestoreID($gestore_id);//($gestore_id);
           $numero_ticket = count($data_ticket);
           $max_id = DataManager::getMAXID('cmd_buoni_prepagati');
           if (!$max_id){
               $max_id=0;
           }
           //echo '<p style="background-color:white">'.$numero_tavolo.'</p>';

    ?>
    <h1>Gestisci i buoni
        <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
            Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                     <a style="color:#fff; font-size: 14px;" href="amministrazioneTicket.php"><b>Buoni pasto</b></a>
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
                    tab_nominativo: {
                        required: true,
                        minlength: 2,
                        maxlength: 20
                    }
                },
                messages: {
                    tab_nominativo: {
                        required: "Inserisci il nominativo dell'intestatario del buono",
                        minlength: "minimo 2 caratteri",
                        maxlength: "massimo 20 caratteri"
                    }
                }
            });

    var //$tab_nome_input                     = $("#tab_nome"),
        $tab_credito_input              = $('#tab_credito'),
        $tab_nominativo_input           = $('#tab_nominativo'),
        $tab_seriale_input              = $('#tab_seriale');

    var tab_counter = <?=$numero_ticket?>,
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
                        tab_content_credito      = $tab_credito_input.val(),
                        tab_content_nominativo   = $tab_nominativo_input.val(),
                        tab_content_seriale      = $tab_seriale_input.val();

                    $( ui.panel ).append('<div style="min-height:175px;">'+
                        '<form id="ticketForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<br /><label style="margin-right: 80px;" class="tab_seriale" for="tab_seriale">Seriale: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_seriale" id="tab_seriale" value="'+tab_content_seriale+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 76px;" class="tab_credito" for="tab_iva">Credito: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_credito" id="tab_credito" value="'+tab_content_credito+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 40px;" class="tab_title" for="tab_nominativo">Nominativo: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_nominativo" id="tab_nominativo" value="'+tab_content_nominativo+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="ticket_id" id="ticket_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_ticket">SALVA</button><br />'+
                             '<button type="submit" id="delete_ticket">ELIMINA</button>'+
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

                var formSel = $("#ticketForm-"+ui.index);

                $("#ticketForm-"+ui.index).validate({
                    rules: {
                        tab_nominativo: {
                            required: true,
                            minlength: 2,
                            maxlength: 20
                        }
                    },
                    messages: {
                        tab_nominativo: {
                            required: "Inserisci il nominativo dell'intestatario del buono",
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
                    $tab_seriale_input.focus();
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
            var tab_title = $tab_seriale_input.val() || "Tab " + tab_counter;
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
     * salvo il nuovo buono aggiunto al premere del bottone: salva.
     *
     */
    $("#save_ticket").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#ticketForm-"+selected).valid()){

            var ticketForm = $("#ticketForm-"+selected).serialize();
            ticketForm = ticketForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: ticketForm,
                url: "manager/gestore/ticket.php",
                dataType: 'json',
                cache: false,
                success: onBuonoSuccess,
                error: onBuonoError
            });
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_ticket").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Buono?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var ticketForm = $("#ticketForm-"+selected).serialize();
            ticketForm = ticketForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: ticketForm,
                url: "manager/gestore/ticket.php",
                dataType: 'json',
                cache: false,
                success: onBuonoSuccess,
                error: onBuonoError
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

    function onBuonoSuccess(data, status) {

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
               $('#code-err').html('Errore durante l\'eliminaziono del Buono.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('Il buono &egrave stato eliminato.');
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento del Buono.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+ data.err);
           } else if(data.err==''){
               $('#code-ok').html('Il nuovo Buono &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />' +
                                   ' ID_gestore: '    + data.gestore_id +
                                   ' ID_ticket: '     + data.ticket_id +
                                   ' Seriale:'        + data.seriale +
                                   ' Credito: '       + data.credito +
                                   ' Nominativo: '    + data.nominativo +
                                   ' Current: '       + data.current_tab +
                                   ' Err: '           + data.err );
           }
        }
    }
    function onBuonoError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }


});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo ticket">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_seriale">Seriale </label>
                    <input type="text" name="tab_seriale" id="tab_seriale" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_credito">Credito: </label>
                    <input type="text" name="tab_credito" id="tab_credito" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_nominativo">Nominativo: </label>
                    <input type="text" name="tab_nominativo" id="tab_nominativo" value="" class="ui-widget-content ui-corner-all" />
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
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_ticket as $ticket) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$ticket['seriale'].'</a></li>';
                          $count++;
                        }
                    ?>
                    <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi un ticket</span></button>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_ticket as $ticket) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:175px;">
                        <form id="ticketForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <!--class="tab_descrizione"-->
                                <br /><label style="margin-right: 80px;" class="tab_title" for="tab_seriale">Seriale: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_seriale" id="tab_seriale" value="<?=$ticket['seriale']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 76px;" class="tab_prezzo" for="tab_credito">Credito: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_credito" id="tab_credito" value="<?=$ticket['credito']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 40px;" class="tab_nominativo" for="tab_nominativo">Nominativo: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_nominativo" id="tab_nominativo" value="<?=$ticket['nominativo']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="ticket_id" id="ticket_id" value="<?=$ticket['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$ticket['gestore_id']?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:left" class="ui-helper-reset">
                            <button type="submit" id="save_alimento">STAMPA BUONO</button>
                        </fieldset>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_ticket">SALVA</button><br />
                            <button type="submit" id="delete_ticket">ELIMINA</button>
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