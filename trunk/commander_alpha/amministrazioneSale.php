<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
    $objSession->Impress();

    header('Content-Type: text/html; charset=utf-8');
?>
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />

<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script src="media/js/ui/jquery.ui.core.js"></script>
<script src="media/js/ui/jquery.ui.widget.js"></script>
<script src="media/js/ui/jquery.ui.tabs.js"></script>
<script src="media/js/ui/jquery.ui.button.js"></script>
<script src="media/js/ui/jquery.ui.dialog.js"></script>
<script src="media/js/ui/jquery.ui.position.js"></script>

<script src="media/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />

<!-- content -->
<div id="content">
    <?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

           $data_sala = DataManager::getAllSala();
           $numero_sale = count($data_sala);
           $max_id = DataManager::getMAXID('cmd_sala');
           if (!$max_id){
               $max_id = 0;
           }
           /*echo '<p style="background-color:white">'.$diff.'</p>';*/
    ?>
    <h1>Gestisci le Sale
        <small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneSale.php">Sale</a></small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

       /**************************************
        * validazione form di inserimento sala
        * 
        */
       $("#addNewTab").validate({
            rules: {
                tab_ntavoli: {
                    required: true,
                    digits: true,
                    rangelength: [1, 3]
                },
                tab_title: {
                    required: true,
                    minlength: 2,
                    maxlength: 10
                }
            },
            messages: {
                tab_ntavoli:{
                    required: "Inserisci il numero di tavoli",
                    digits: "Inserisci solo cifre",
                    rangelength: "Inserisci un valore compreso tra 1 e 999"
                },
                tab_title: {
                    required: "Inserisci il nome della sala",
                    minlength: "minimo 2 caratteri",
                    maxlength: "massimo 10 caratteri"
                }
            }
        });

    //$( "#tabs" ).tabs();
    var $tab_title_input     = $("#tab_title"),
        $tab_ntavoli_input   = $('#tab_ntavoli');

    var tab_counter = <?=$numero_sale?>;
    var max_id = <?=$max_id?>;

    tab_counter++;
    var next_id = max_id+1;

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {
                    var tab_content = $tab_ntavoli_input.val();
                    var tab_content2 = $tab_title_input.val();
                    $( ui.panel ).append('<div style="min-height:120px;">'+
                                          '<form id="salaForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                                             '<fieldset style="float:left" class="ui-helper-reset">'+
                                                '<label class="tab_title" for="tab_title">Nome sala: </label>'+
                                                '<input type="text" name="tab_title" id="tab_title" value="'+tab_content2+'" class="ui-widget-content ui-corner-all" />'+
                                                '<br /><label class="tab_ntavoli" for="tab_ntavoli">Numero tavoli: </label>'+
                                                '<input type="text" name="tab_ntavoli" id="tab_ntavoli" value="'+tab_content+'" class="ui-widget-content ui-corner-all" />'+
                                                '<input type="hidden" name="sala_id" id="sala_id" value="'+next_id+'" class="ui-widget-content ui-corner-all" />'+
                                             '</fieldset>'+
                                         '</form>'+
                                         '<fieldset style="float:right" class="ui-helper-reset">'+
                                             '<button type="submit" id="save_sala">SALVA</button><br />'+
                                             '<button type="submit" id="delete_sala">ELIMINA</button>'+
                                         '</fieldset>'+
                                         '<fieldset style="float:left;width:400px;">'+
                                            '<a href="amministrazioneTavoli.php?id='+next_id+'">Gestisci i tavoli di questa sala</a>'+
                                         '</fieldset></div>'
                                          );
                    $tabs.tabs('select', '#' + ui.panel.id);
            },
            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;
                var formSel = $("#salaForm-"+ui.index);

                $("#salaForm-"+ui.index).validate({
                    rules: {
                        tab_ntavoli: {
                            required: true,
                            digits: true,
                            rangelength: [1, 3]
                        },
                        tab_title: {
                            required: true,
                            minlength: 2,
                            maxlength: 10
                        }
                    },
                    messages: {
                        tab_ntavoli:{
                            required: "Inserisci il numero di tavoli",
                            digits: "Inserisci solo cifre",
                            rangelength: "Inserisci un valore compreso tra 1 e 999"
                        },
                        tab_title: {
                            required: "Inserisci il nome della sala",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 10 caratteri"
                        }
                    }

                });

            }

    });

    //modal dialog init: custom buttons and a "close" callback reseting the form inside
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
     * salvo la nuova sala aggiunta al premere del bottone: salva.
     *
     */
    $("#save_sala").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;

        var formSala = $("#salaForm-"+selected).serialize();
        formSala = formSala+'&action=save&current_tab='+selected;

        $.ajax({
            type: "POST",
            data: formSala,
            url: "manager/gestore/sala.php",
            dataType: 'json',
            cache: false,
            success: onSalaSuccess,
            error: onError
        });
    });

    /*
     *
     * Elimina la sala aggiunta al premere del bottone: salva.
     * io farei il reload della pagina dal db dopo avere eliminato la sala.
     *
     */
    $("#delete_sala").live("click", function() {
            var answer = confirm("Sei sicuro di voler eliminare questa Sala?");

            if (answer){

            var selected = $tabs.tabs('option', 'selected');
            selected+=1;

            var formSala = $("#salaForm-"+selected).serialize();
            formSala = formSala+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: formSala,
                url: "manager/gestore/sala.php",
                dataType: 'json',
                cache: false,
                success: onSalaSuccess,
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
    function onSalaSuccess(data, status) {
        if (data.action=='del'){

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'eliminaziono della Sala.');
               $dialogERR.dialog("open");
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('La sala &egrave stata eliminata.');
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
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'inserimento o aggioramento della Sala.');
               $dialogERR.dialog("open");
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
    }

});
</script>
        <!-- dialogs -->
	<div id="dialog" title="Dati nuova sala">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                        <label for="tab_title">Nome nuova sala</label>
                        <input type="text" name="tab_title" id="tab_title" value="" class="addNewTab ui-widget-content ui-corner-all required" minlength="2"/>
                        <label for="tab_ntavoli">Numero tavoli</label>
                        <input type="text" name="tab_ntavoli" id="tab_ntavoli" class="addNewTab ui-widget-content ui-corner-all required" />
                        <!--
                        <label for="tab_posizione">Coordinate posizione</label>
                        <textarea name="tab_posizione" id="tab_posizione" class="ui-widget-content ui-corner-all"></textarea>
                        -->
                </fieldset>
            </form>
  	</div>

        <!-- dialogs -->
        <?php include_once 'dialogs.php';?>
        <!-- tabs container -->

        <button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi nuova sala</span></button>
        <div class="clearfix"></div>
        <div class="sala">
            <div id="tabs">
                    <ul>
                        <?php
                            $count = 1;
                            foreach ($data_sala as $sala) {
                              echo '<li><a href="ui-tabs-'.$count.'">'.$sala['nome'].'</a></li>';
                              $count++;
                            }
                        ?>
                        <!--<li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi nuova sala</span></button>-->
                    </ul>
            <?php
                $count = 1;
                foreach ($data_sala as $sala) {
                    $numero_tavoli = DataManager::getAllTavoloBySalaID($sala['id']);
                    $numero_tavoli = count($numero_tavoli);
                    echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                ?>
                <div style="min-height:120px;">
                    <form id="salaForm-<?=$count?>" style="min-height:60px; float:left;">
                        <fieldset style="float:left" class="ui-helper-reset">
                            <label class="tab_title" for="tab_title">Nome sala: </label>
                            <input type="text" name="tab_title" id="tab_title" value="<?=$sala['nome']?>" class="ui-widget-content ui-corner-all" />
                            <br /><label class="tab_ntavoli" for="tab_ntavoli">Numero tavoli: </label>
                            <!--<input type="text" name="tab_ntavoli" id="tab_ntavoli" value="<?//=$numero_tavoli?>" class="ui-widget-content ui-corner-all" />-->
                            <label name="tab_ntavoli" id="tab_ntavoli" ><?=$numero_tavoli?></label>
                            <input type="hidden" name="sala_id" id="sala_id" value="<?=$sala['id']?>" class="ui-widget-content ui-corner-all" />
                        </fieldset>
                    </form>
                    <fieldset style="float:right" class="ui-helper-reset">
                        <button type="submit" id="save_sala">SALVA</button><br />
                        <button type="submit" id="delete_sala">ELIMINA</button>
                    </fieldset>
                    <fieldset style="float:left; width: 400px">
                        <a style="float:left" href="amministrazioneTavoli.php?id=<?=$sala['id']?>">Gestisci i tavoli di questa sala</a>
                    </fieldset>
                </div>
                <?php
                    $count++;
                    echo '</div>';
                }
            ?>
            </div>
        </div><!-- End demo -->
        <!-- footer -->
        <?php include_once 'footer.php';?>
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
                <br /><a style="color:#fff;" href="logout.php"> <-- LOGIN</a>
            </h4>';
    }
?>