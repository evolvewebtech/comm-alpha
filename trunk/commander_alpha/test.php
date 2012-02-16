<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/smoothness/jquery-ui-1.8.17.custom_tavoli.css" type="text/css" media="screen" />

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
    #save_sala{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
       cursor: pointer;
    }
    #delete_sala{
       width: 100px;
       height: 50px;
       background-color: red;
       text-transform: uppercase;
       cursor: pointer;
    }
    form label.tab_title{
        margin-right: 33px;
    }
</style>

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
        <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
            Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                     <a style="color:#fff; font-size: 12px;" href="amministrazioneSale.php">Sale</a> >
                     <a style="color:#fff; font-size: 14px;" href="#">Tavoli</a>
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
        $tab_posizione_input = $('#tab_posizione'), //non utilizzata per
        $tab_ntavoli_input   = $('#tab_ntavoli');

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
                    var tab_content = $tab_ntavoli_input.val(); //|| "Tab " + tab_counter + " content.";
                    var tab_content2 = $tab_title_input.val(); //|| "Tab " + tab_counter + " tite.";
                    $( ui.panel ).append('<div style="min-height:100px;"><form id="salaForm-'+tab_counter+'" style="min-height:100px; float:left;">'+
                                             '<fieldset style="float:left" class="ui-helper-reset">'+
                                                '<label class="tab_title" for="tab_title">Nome sala: </label>'+
                                                '<input type="text" name="tab_title" id="tab_title" value="'+tab_content2+'" class="ui-widget-content ui-corner-all" />'+
                                                '<br /><label for="tab_ntavoli">Numero tavoli: </label>'+
                                                '<input type="text" name="tab_ntavoli" id="tab_ntavoli" value="'+tab_content+'" class="ui-widget-content ui-corner-all" />'+
                                                '<input type="hidden" name="sala_id" id="sala_id" value="'+next_id+'" class="ui-widget-content ui-corner-all" />'+
                                             '</fieldset>'+
                                         '</form>'+
                                             '<fieldset style="float:right" class="ui-helper-reset">'+
                                                 '<button type="submit" id="save_sala">SALVA</button><br />'+
                                                 '<button type="submit" id="delete_sala">ELIMINA</button>'+
                                             '</fieldset>'
                                          );
                    $tabs.tabs('select', '#' + ui.panel.id);
            },
            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;
                $('#debug').append('<br />selected: '+ui.index);
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

});
</script>

        <!-- dialogs -->

        <!-- tabs container -->
        <div class="tavolo_tab">
            <div id="tabs">
                <div class="top" style="">
                    <div class="header-left" style="float:left; width: 700px;">
                        <ul>
                            <?php
                                $count = 1;
                                foreach ($data_tavolo as $tavolo) {
                                  echo '<li><a href="ui-tabs-'.$count.'">'.$tavolo['numero'].'</a></li>';
                                  $count++;
                                }
                            ?>
                            <!--<li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi tavolo1</span></button>-->
                        </ul>
                    </div>
                    <div class="header-right" style="float:right; width: 210px;">
                        <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi tavolo1</span></button>
                    </div>
                </div>
                    <div class="pot" style="float:left; width: 913px; background-color: #ffffff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
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
                            </fieldset>
                        </form>
                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_sala">SALVA</button><br />
                            <button type="submit" id="delete_sala">ELIMINA</button>
                        </fieldset>
                    </div>
                    <?php
                        $count++;
                        echo '</div>';
                    }
                ?>
                </div>
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