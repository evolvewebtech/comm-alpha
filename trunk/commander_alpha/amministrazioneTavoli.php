<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom_tavoli.css" type="text/css" media="screen" />
<!--
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />
-->
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

           $sala_id = intval(mysql_real_escape_string($_GET['id']));
           $data_sala = DataManager::getSala($sala_id);
           if ($data_sala==0){
               die('<p style="background-color:white;color:black;">Err</p>');
           }

           $data_tavolo = DataManager::getAllTavoloBySalaID($sala_id);
           $numero_tavolo = count($data_tavolo);
           $max_id = DataManager::getMAXID('cmd_tavolo');
//           echo '<p style="background-color:white">'.$numero_tavolo.'</p>';
    ?>
    <h1>Gestisci i tavoli di <?=$data_sala['nome']?>
        <small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 12px;" href="amministrazioneSale.php">Sale</a> >
            <a style="color:#fff; font-size: 14px;" href="#">Tavoli</a></small>
    </h1>
<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

       $("#addNewTab").validate({
            rules: {
                tab_ncoperti: {
                    required: true,
                    digits: true,
                    rangelength: [1, 2]
                },
                tab_title: {
                    required: true,
                    minlength: 2,
                    maxlength: 10
                },
                tab_numero: {
                    required: true,
                    minlength: 1,
                    digits: true
                }
            },
            messages: {
                tab_ncoperti:{
                    required: "Inserisci il numero di coperti",
                    digits: "Inserisci solo cifre",
                    rangelength: "Inserisci un valore compreso tra 1 e 99"
                },
                tab_title: {
                    required: "Inserisci il nome del tavolo",
                    minlength: "minimo 2 caratteri",
                    maxlength: "massimo 10 caratteri"
                },
               tab_numero: {
                    required: "Inserisci il numero del tavolo",
                    digits: "Inserisci solo cifre",
                    minlength: "minimo 1 cifra"
                }
            }
        });

    //$( "#tabs" ).tabs();
    var $tab_title_input     = $("#tab_title"),
        $tab_posizione_input = $('#tab_posizione'), //non utilizzata per
        $tab_ncoperti_input   = $('#tab_ncoperti');

    var tab_counter = <?=$numero_tavolo?>;
    var max_id = <?=$max_id?>;

    tab_counter++;
    var next_id = max_id+1;
    $('#debug').append('<br />Numero: '+tab_counter);
    $('#debug').append('<br />ID max: '+max_id);

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {
                    var tab_content = $tab_ncoperti_input.val(); //|| "Tab " + tab_counter + " content.";
                    var tab_content2 = $tab_title_input.val(); //|| "Tab " + tab_counter + " tite.";
                    $( ui.panel ).append('<div style="min-height:100px;"><form id="tavoloForm-'+tab_counter+'" style="min-height:100px; float:left;">'+
                                             '<fieldset style="float:left" class="ui-helper-reset">'+
                                                '<label style="margin-right: 57px;" class="tab_numero" for="tab_numero">Numero tavolo: </label>'+
                                                '<input type="text" name="tab_numero" id="tab_numero" value="'+tab_counter+'" class="ui-widget-content ui-corner-all" />'+
                                                '<br /><label style="margin-right: 43px;" class="tab_title" for="tab_title">Nome del tavolo: </label>'+
                                                '<input type="text" name="tab_title" id="tab_title" value="'+tab_content2+'" class="ui-widget-content ui-corner-all" />'+
                                                '<br /><label style="margin-right: 5px;" for="tab_nmax_coperti">Numero max coperti: </label>'+
                                                '<input type="text" name="tab_nmax_coperti" id="tab_nmax_coperti" value="'+tab_content+'" class="ui-widget-content ui-corner-all" />'+
                                                '<input type="hidden" name="tavolo_id" id="tavolo_id" value="'+next_id+'" class="ui-widget-content ui-corner-all" />'+
                                                '<input type="hidden" name="sala_id" id="sala_id" value="<?=$sala_id?>" class="ui-widget-content ui-corner-all" />'+
                                                '<input type="hidden" name="numero_tavolo" id="numero_tavolo" value="'+tab_counter+'" class="ui-widget-content ui-corner-all" />'+
                                             '</fieldset>'+
                                         '</form>'+
                                             '<fieldset style="float:right" class="ui-helper-reset">'+
                                                 '<button type="submit" id="save_tavolo">SALVA</button><br />'+
                                                 '<button type="submit" id="delete_tavolo">ELIMINA</button>'+
                                             '</fieldset>'
                                          );
                    $tabs.tabs('select', '#' + ui.panel.id);
            },
            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;
                $('#debug').append('<br />selected: '+ui.index);
                var formSel = $("#tavoloForm-"+ui.index);

                $("#tavoloForm-"+ui.index).validate({
                    rules: {
                        tab_ncoperti: {
                            required: true,
                            digits: true,
                            rangelength: [1, 3]
                        },
                        tab_title: {
                            required: true,
                            minlength: 2,
                            maxlength: 10
                        },
                        tab_numero: {
                            required: true,
                            minlength: 1,
                            digits: true
                        }
                    },
                    messages: {
                        tab_ncoperti:{
                            required: "Inserisci il numero di coperti",
                            digits: "Inserisci solo cifre",
                            rangelength: "Inserisci un valore compreso tra 1 e 99"
                        },
                        tab_title: {
                            required: "Inserisci il nome del tavolo",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        },
                       tab_numero: {
                            required: "Inserisci il numero del tavolo",
                            digits: "Inserisci solo cifre",
                            minlength: "minimo 1 cifra"
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
                    $tab_title_input.focus();
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
            var tab_title = $tab_title_input.val() || "Tab " + tab_counter;
            $tabs.tabs( "add", "#tabs-" + tab_counter, tab_title );
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
    $("#save_tavolo").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#tavoloForm-"+selected).validate()){

            $('#debug').append('<br />valid!!!??');

            var formTavolo = $("#tavoloForm-"+selected).serialize();
            formTavolo = formTavolo+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: formTavolo,
                url: "manager/gestore/tavolo.php",
                dataType: 'json',
                cache: false,
                success: onTavoloSuccess,
                error: onError
            });
        }
    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_tavolo").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Tavolo?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var formTavolo = $("#tavoloForm-"+selected).serialize();
            formTavolo = formTavolo+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: formTavolo,
                url: "manager/gestore/tavolo.php",
                dataType: 'json',
                cache: false,
                success: onTavoloSuccess,
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
    function onTavoloSuccess(data, status) {

        console.log(data);
        $('#debug').append(data.nome);
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
               $('#code-err').html('Errore durante l\'eliminaziono del Tavolo.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('Il tavolo &egrave stato eliminato.');
               $dialogOK.dialog( "open" );

               //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });

           }
        }
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento della Sala.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+ data.err);
           } else if(data.err==''){
               $('#code-ok').html('La nuova sala &egrave stata aggiunta.');
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
	<div id="dialog" title="Dati nuovo tavolo">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                        <label style="color:white" for="tab_title">Nome tavolo</label>
                        <input type="text" name="tab_title" id="tab_title" value="" class="addNewTab ui-widget-content ui-corner-all required" minlength="2"/>
                        <label style="color:white" for="tab_ntavoli">Numero coperti</label>
                        <input type="text" name="tab_ncoperti" id="tab_ncoperti" class="addNewTab ui-widget-content ui-corner-all required" />
                        <!--
                        <label for="tab_posizione">Coordinate posizione</label>
                        <textarea name="tab_posizione" id="tab_posizione" class="ui-widget-content ui-corner-all"></textarea>
                        -->
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>

        <!-- bottone per aggiungere di un nuovo tab -->
        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi un tavolo</span></button>

        <!-- tabs container -->
        <div class="clearfix"></div>
        
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_tavolo as $tavolo) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$tavolo['numero'].'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_tavolo as $tavolo) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:120px;">
                        <form id="tavoloForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <label class="tab_numero" for="tab_numero">Numero tavolo: </label>
                                <input type="text" name="tab_numero" id="tab_numero" value="<?=$tavolo['numero']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label class="tab_title" for="tab_title">Nome del tavolo: </label>
                                <input type="text" name="tab_title" id="tab_title" value="<?=$tavolo['nome']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label for="tab_nmax_coperti">Numero max coperti: </label>
                                <input type="text" name="tab_nmax_coperti" id="tab_nmax_coperti" value="<?=$tavolo['nmax_coperti']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="tavolo_id" id="tavolo_id" value="<?=$tavolo['id']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="sala_id" id="sala_id" value="<?=$sala_id?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="numero_tavolo" id="numero_tavolo" value="<?=$tavolo['numero']?>" class="ui-widget-content ui-corner-all" />
                            </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_tavolo">SALVA</button><br />
                            <button type="submit" id="delete_tavolo">ELIMINA</button>
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