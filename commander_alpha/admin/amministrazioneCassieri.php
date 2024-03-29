<?php
    require_once dirname(__FILE__).'/../manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $objSession->Impress();

    header('Content-Type: text/html; charset=utf-8');
    /*
     * todo:
     *
     * 1. visualizzza_cassa, con dialoghi
     * 2. azzera cassa,       "     "
     *
     */
?>
<link rel="stylesheet" href="../media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />

<script type="text/javascript" src="../media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.tabs.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.button.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="../media/js/ui/jquery.ui.position.js"></script>

<script src="../media/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="../media/css/main.css" type="text/css" media="screen" />


<div id="content">
    <?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

           $gestore_id = $gestore->id;
           $utente_registrato_id = $gestore->utente_registrato_id;

           $data_cassieri = DataManager::getTuttiCassieri($gestore_id);
           if ($data_cassieri){
                ;
           }else{
              $data_cassieri = array();
           }
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
        <small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">home</a> >
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
        $tab_cognome_input          = $('#tab_cognome');

    var tab_counter = <?=$numero_cassieri?>,
        max_id      = <?=$max_id?>,
        max_id_ur   = <?=$max_id_utente_registrato?>;

    tab_counter++;
    var next_id = max_id+1,
        next_ur_id = max_id_ur+1;
        
    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {

                    var tab_content_username         = $tab_username_input.val(),
                        tab_content_password         = $tab_password_input.val(),
                        tab_content_nome             = $tab_nome_input.val(),
                        tab_content_cognome          = $tab_cognome_input.val();

                    $( ui.panel ).append('<div style="min-height:150px;">'+
                        '<form id="cassiereForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<label style="margin-right: 45px;" class="tab_title" for="tab_username">Username: </label>'+
                                '<input type="text" name="tab_username" id="tab_username" value="'+tab_content_username+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 52px;" for="tab_password">Password: </label>'+
                                '<input type="text" name="tab_password" id="tab_password" value="'+tab_content_password+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 84px;" class="tab_nome" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 51px;" class="tab_cognome" for="tab_cognome">Cognome: </label>'+
                                '<input type="text" name="tab_cognome" id="tab_cognome" value="'+tab_content_cognome+'" class="ui-widget-content ui-corner-all" />'+
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

                var formSel = $("#cassiereForm-"+ui.index);

                $("#cassiereForm-"+ui.index).validate({
                    rules: {
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
     * salvo il nuovo cassiere aggiunto al premere del bottone: salva.
     *
     */
    $("#save_cassiere").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;

        if($("#cassiereForm-"+selected).valid()){

            var cassiereForm = $("#cassiereForm-"+selected).serialize();
            cassiereForm = cassiereForm+'&action=save&current_tab='+selected;

            console.log(cassiereForm);

            $.ajax({
                type: "POST",
                async: false,
                data: cassiereForm,
                url: "../manager/gestore/cassiere.php",
                dataType: 'json',
                cache: false,
                success: onCassiereSuccess,
                error: onError
            });    
        }

    });


    /*
     *
     * disconnetto il cassiere, forzo il logout
     *
     */
    $("#logout_cassiere").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        var cassiere = $("#cassiereForm-"+selected).serialize();
        cassiere = cassiere+'&action=log&current_tab='+selected;

        $.ajax({
            type: "POST",
            async: false,
            data: cassiere,
            url: "../manager/gestore/cassiere.php",
            dataType: 'json',
            cache: false,
            success: onCassiereSuccess,
            error: onError
        });

    });

    /*
     *
     * visualizzo quantità in cassa
     *
     */
    $("#saldo_cassiere").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;

        var cassiere = $("#cassiereForm-"+selected).serialize();
        cassiere = cassiere+'&action=visualizza_cassa&current_tab='+selected;

        $.ajax({
            type: "POST",
            data: cassiere,
            async: false,
            url: "../manager/gestore/cassiere.php",
            dataType: 'json',
            cache: false,
            success: onCassiereSuccess,
            error: onError
        });

    });

    /*
     *
     * visualizzo quantità in cassa
     *
     */
    $("#azzera_saldo_cassiere").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;

        var cassiere = $("#cassiereForm-"+selected).serialize();
        cassiere = cassiere+'&action=azzera_cassa&current_tab='+selected;

        $.ajax({
            type: "POST",
            async: false,
            data: cassiere,
            url: "../manager/gestore/cassiere.php",
            dataType: 'json',
            cache: false,
            success: onCassiereSuccess,
            error: onError
        });

    });


    /*
     *
     * Elimina il cassiere aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_cassiere").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Cassiere?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;

            var cassiereTavolo = $("#cassiereForm-"+selected).serialize();
            cassiereTavolo = cassiereTavolo+'&action=del&current_tab='+selected;

            $.ajax({
                async: false,
                type: "POST",
                data: cassiereTavolo,
                url: "../manager/gestore/cassiere.php",
                dataType: 'json',
                cache: false,
                success: onCassiereSuccess,
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

    function onCassiereSuccess(data, status) {
        
        if (data.action=='del'){

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'eliminazione del cassiere.');
               $dialogERR.dialog("open");
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
              
           }else{
                $('#code-ok').html('Il cassiere &egrave stato eliminato.');
                    $dialogOK.dialog( "open" );

                   //aspetto che il dialogo sia stato chiuso
                   $dialogOK.bind( "dialogclose", function(event, ui) {
                      // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                      location.reload();
                   });
           }

        }
        if (data.action=='log'){
           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante la disconnesione del cassiere.');
               $dialogERR.dialog("open");
           } else if(data.err==''){
               $('#code-ok').html('Il cassiere &egrave; stato disattivato.');
               $dialogOK.dialog( "open" );
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });
           } else{
               $('#code-err').html('Errore durante la disconnesione del cassiere.');
               $dialogERR.dialog("open");
           }
        }
        if (data.action=='visualizza_cassa'){
           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante la visualizzazione della cassa del cassiere.');
               $dialogERR.dialog("open");
           } else if(data.err==''){
               if(data.cassa==0){
                   $('#code-ok').html('Quantit&agrave; in cassa: 0 &#8364;');
               }else{
                   if (data.cassa.consegnato=="1"){
                       $('#code-ok').html('Quantit&agrave; in cassa gi&agrave; consegnata. Saldo: 0 &#8364;');
                   }else{
                $('#code-ok').html('Quantit&agrave; in cassa: '+data.cassa.saldo+ ' &#8364;');
                    }
                }
               $dialogOK.dialog( "open" );
           } else{
               $('#code-err').html('Errore durante la visualizzazione della cassa del cassiere.');
               $dialogERR.dialog("open");
           }

        }

        if (data.action=='azzera_cassa'){
           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante la visualizzazione della cassa del cassiere.');
               $dialogERR.dialog("open");
           } else if(data.err==''){
               if(data.cassa==0){
                   $('#code-ok').html('Cassa gi&agrave; azzerata. Saldo: 0 &#8364;');
               }else{
                $('#code-ok').html('Cassa azzerata con successo. Ultimo saldo: '+data.cassa.saldo+ ' &#8364;');
                }
               $dialogOK.dialog( "open" );
           } else{
               $('#code-err').html('Errore durante l\'azzeramento della cassa del cassiere.');
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
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'inserimento o aggioramento del cassiere.');
               $dialogERR.dialog("open");
           } else if(data.err==''){
               $('#code-ok').html('Il nuovo cassiere &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });
           } else{
               $('#code-err').html('Errore durante l\'inserimento o aggioramento del cassiere.');
               $dialogERR.dialog("open");
           }
        }
    }
    
    function onError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
    }


});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo cassiere">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_username">Username </label>
                    <input type="text" name="tab_username" id="tab_username" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label  for="tab_password">Password </label>
                    <input type="text" name="tab_password" id="tab_password" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="addNewTab ui-widget-content ui-corner-all" />
                    <label for="tab_cognome">Cognome </label>
                    <input type="text" name="tab_cognome" id="tab_cognome" value="" class="addNewTab ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once '../dialogs.php';?>

        <button id="add_tab"><img id="plus" src="../img/plus.png"><span id="add_span">aggiungi un cassiere</span></button>
        <div class="clearfix"></div>
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
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_cassieri as $cassiere) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:150px;">
                        <form id="cassiereForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <label style="margin-right: 45px;" class="tab_title" for="tab_username">Username: </label>
                                <input type="text" name="tab_username" id="tab_username" value="<?=$cassiere->username?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 52px;" for="tab_password">Password: </label>
                                <input type="password" name="tab_password" id="tab_password" value="<?=$cassiere->md5_pw?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 84px;" class="tab_nome" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$cassiere->first_name?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 51px;" class="tab_cognome" for="tab_cognome">Cognome: </label>
                                <input type="text" name="tab_cognome" id="tab_cognome" value="<?=$cassiere->last_name?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="cassiere_id" id="cassiere_id" value="<?=$cassiere->id?>" />
                                <input type="hidden" name="utente_registrato_id" id="utente_registrato_id" value="<?=$cassiere->utente_registrato_id?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>" />
                           </fieldset>
                        </form>

                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_cassiere">SALVA</button><br />
                            <button type="submit" id="delete_cassiere">ELIMINA</button>
                        </fieldset>

                        <button style="margin-top:5px;" type="submit" id="saldo_cassiere">VISUALIZZA CASSA</button><br />
                        <button style="margin-top: 1px; margin-bottom: 6px;" type="submit" id="azzera_saldo_cassiere">AZZERA CASSA</button><br />
                        <a href="amministrazionePermessi.php?id=<?=$cassiere->utente_registrato_id?>" style="background-color: buttonface; border: 1px solid #000; margin:2px; padding: 1px;" id="permessi_cassiere">GESTISCI I PERMESSI</a><br />
                        <button style="margin-top:5px;" type="submit" id="logout_cassiere">DISCONNETTI CASSIERE</button>
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
        <?php include_once '../footer.php';?>
</div><!-- end content -->

<?php
        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <br /><a style="color:#fff;" href="../logout.php"> <-- LOGIN</a>
            </h4>';
    }
?>