<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
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
    #save_cassiere{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
       cursor: pointer;
    }
    #delete_cassiere{
       width: 100px;
       height: 50px;
       background-color: red;
       text-transform: uppercase;
       cursor: pointer;
    }
    form label.tab_title{
        margin-right: 38px;
    }
    form label.tab_ncoperti{
        margin-right: 5px;
    }
    form label.tab_numero{
        margin-right: 52px;
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

           $data_cassieri = DataManager::getTuttiCassieri($gestore_id);
           $numero_cassieri = count($data_cassieri);
           $max_id = DataManager::getMAXID('cmd_cassiere');
           if (!$max_id){
               $max_id=0;
           }
           $max_id_utente_registrato = DataManager::getMAXID('cmd_utente_registrato');
           if (!$max_id_utente_registrato){
               $max_id_utente_registrato=0;
           }
//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
    <h1>Gestisci i cassieri
        <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
            Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                     <a style="color:#fff; font-size: 14px;" href="amministrazioneCassieri.php"><b>Cassieri</b></a>
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
                        tab_livello_cassiere: {
                            required: true,
                            digits: true,
                            rangelength: [1, 1]
                        },
                        tab_username: {
                            required: true,
                            minlength: 2,
                            maxlength: 10
                        },
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_cognome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_password: {
                            required: true,
                            minlength: 2,
                            maxlength: 40
                        }
                    },
                    messages: {
                        tab_livello_cassiere:{
                            required: "Inserisci il livello del cassiere",
                            digits: "Inserisci solo cifre",
                            rangelength: "Inserisci un valore compreso tra 1 e 3"
                        },
                        tab_username: {
                            required: "Inserisci l'username del cassiere.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        },
                        tab_password: {
                            required: "Inserisci una password per il cassiere",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 40 caratteri"
                        },
                        tab_nome: {
                            required: "Inserisci il nome del cassiere.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        },
                        tab_cognome: {
                            required: "Inserisci il cognome del cassiere",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        }
                    }
        });

    //$( "#tabs" ).tabs();
    var $tab_username_input         = $("#tab_username"),
        $tab_password_input         = $('#tab_password'),
        $tab_nome_input             = $('#tab_nome'),
        $tab_cognome_input          = $('#tab_cognome'),
        $tab_livello_cassiere_input = $('#tab_livello_cassiere');

    var tab_counter = <?=$numero_cassieri?>,
        max_id      = <?=$max_id?>,
        max_id_ur   = <?=$max_id_utente_registrato?>;

    tab_counter++;
    var next_id = max_id+1,
        next_ur_id = max_id_ur+1;
        
    $('#debug').append('<br />Numero: '         +tab_counter+
                       '<br />ID max: '         +max_id+
                       '<br />ID ur max: '      +max_id_ur+
                       '<br />ID max next: '    +next_id+
                       '<br />ID ur max next: ' +next_ur_id);

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {

                    var tab_content_username         = $tab_username_input.val(),
                        tab_content_password         = $tab_password_input.val(),
                        tab_content_nome             = $tab_nome_input.val(),
                        tab_content_cognome          = $tab_cognome_input.val(),
                        tab_content_livello_cassiere = $tab_livello_cassiere_input.val();

                    $( ui.panel ).append('<div style="min-height:175px;">'+
                        '<form id="cassiereForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<br /><label style="margin-right: 45px;" class="tab_title" for="tab_username">Username: </label>'+
                                '<input type="text" name="tab_username" id="tab_username" value="'+tab_content_username+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 52px;" for="tab_password">Password: </label>'+
                                '<input type="text" name="tab_password" id="tab_password" value="'+tab_content_password+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 84px;" class="tab_nome" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 51px;" class="tab_cognome" for="tab_cognome">Cognome: </label>'+
                                '<input type="text" name="tab_cognome" id="tab_cognome" value="'+tab_content_cognome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label for="tab_livello_cassiere">Livello cassiere: </label>'+
                                '<input type="text" name="tab_livello_cassiere" id="tab_livello_cassiere" value="'+tab_content_livello_cassiere+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="cassiere_id" id="cassiere_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="utente_registrato_id" id="utente_registrato_id" value="'+next_ur_id+'"  />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_cassiere">SALVA</button><br />'+
                             '<button type="submit" id="delete_cassiere">ELIMINA</button>'+
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

                var formSel = $("#cassiereForm-"+ui.index);

                $("#cassiereForm-"+ui.index).validate({
                    rules: {
                        tab_livello_cassiere: {
                            required: true,
                            digits: true,
                            rangelength: [1, 1]
                        },
                        tab_username: {
                            required: true,
                            minlength: 2,
                            maxlength: 10
                        },
                        tab_nome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_cognome: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        },
                        tab_password: {
                            required: true,
                            minlength: 2,
                            maxlength: 40
                        }
                    },
                    messages: {
                        tab_livello_cassiere:{
                            required: "Inserisci il livello del cassiere",
                            digits: "Inserisci solo cifre",
                            rangelength: "Inserisci un valore compreso tra 1 e 3"
                        },
                        tab_username: {
                            required: "Inserisci l'username del cassiere.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        },
                        tab_password: {
                            required: "Inserisci una password per il cassiere",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 40 caratteri"
                        },
                        tab_nome: {
                            required: "Inserisci il nome del cassiere.",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        },
                        tab_cognome: {
                            required: "Inserisci il cognome del cassiere",
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
                    $tab_username_input.focus();
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
            var tab_title = $tab_username_input.val() || "Tab " + tab_counter;
            //creo la nuova tab
            $tabs.tabs( "add", "#tabs-" + tab_counter, tab_title );
            
            //aggiorno le variabili per la prossima nuova tab
            tab_counter++;
            next_id++;
            next_ur_id++;
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
    $("#save_cassiere").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#cassiereForm-"+selected).valid()){
            
            var cassiereForm = $("#cassiereForm-"+selected).serialize();
            cassiereForm = cassiereForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: cassiereForm,
                url: "manager/gestore/cassiere.php",
                dataType: 'json',
                cache: false,
                success: onCassiereSuccess,
                error: onCassiereError
            });    
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_cassiere").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Cassiere?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var cassiereTavolo = $("#cassiereForm-"+selected).serialize();
            cassiereTavolo = cassiereTavolo+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: cassiereTavolo,
                url: "manager/gestore/cassiere.php",
                dataType: 'json',
                cache: false,
                success: onCassiereSuccess,
                error: onCassiereError
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

    function onCassiereSuccess(data, status) {
        
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
               $('#code-err').html('Errore durante l\'eliminazione del cassiere.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('Il cassiere &egrave stato eliminato.');
               $dialogOK.dialog( "open" );

               //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });
              
           }

        }
        /*
        else{

           $dialogOK.dialog( "open" );
           $('#debug').append('<br />data saved:<br />'+ 'ID: '+data.id +' NOME:'+ data.nome +' N: '+ data.n_tavoli + ' Current: '+data.current_tab);
        }
        */
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento del cassiere.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('Il nuovo cassiere &egrave stat aggiunto.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+
                                   'ID_cassiere: ' + data.cassiere_id+
                                   ' ID_gestore: ' + data.gestore_id+
                                   ' ID_ut_reg: '  + data.gestore_id+
                                   ' Nome:'        + data.nome +
                                   ' Cognome: '    + data.cognome+
                                   ' Username: '   + data.username+
                                   ' Password: '   + data.password+
                                   ' Livello: '    + data.livello_cassiere+
                                   ' Current: '    + data.current_tab+
                                   ' Err: '        + data.err );
           }
        }
    }
    function onCassiereError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }


});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo cassiere">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_username">Username </label>
                    <input type="text" name="tab_username" id="tab_username" value="" class="ui-widget-content ui-corner-all" />
                    <label  for="tab_password">Password </label>
                    <input type="text" name="tab_password" id="tab_password" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_cognome">Cognome </label>
                    <input type="text" name="tab_cognome" id="tab_cognome" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_livello_cassiere">Livello cassiere: </label>
                    <input type="text" name="tab_livello_cassiere" id="tab_livello_cassiere" value="" class="ui-widget-content ui-corner-all" />
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
                        foreach ($data_cassieri as $cassiere) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$cassiere->username.'</a></li>';
                          $count++;
                        }
                    ?>
                    <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi un cassiere</span></button>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_cassieri as $cassiere) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:175px;">
                        <form id="cassiereForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <br /><label style="margin-right: 45px;" class="tab_title" for="tab_username">Username: </label>
                                <input type="text" name="tab_username" id="tab_username" value="<?=$cassiere->username?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 52px;" for="tab_password">Password: </label>
                                <input type="text" name="tab_password" id="tab_password" value="<?=$cassiere->md5_pw?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 84px;" class="tab_nome" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$cassiere->first_name?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 51px;" class="tab_cognome" for="tab_cognome">Cognome: </label>
                                <input type="text" name="tab_cognome" id="tab_cognome" value="<?=$cassiere->last_name?>" class="ui-widget-content ui-corner-all" />
                                <br /><label for="tab_livello_cassiere">Livello cassiere: </label>
                                <input type="text" name="tab_livello_cassiere" id="tab_livello_cassiere" value="<?=$cassiere->livello_cassiere?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="cassiere_id" id="cassiere_id" value="<?=$cassiere->id?>" />
                                <input type="hidden" name="utente_registrato_id" id="utente_registrato_id" value="<?=$cassiere->utente_registrato_id?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>" />
                           </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_cassiere">SALVA</button><br />
                            <button type="submit" id="delete_cassiere">ELIMINA</button>
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