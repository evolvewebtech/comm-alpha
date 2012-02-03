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
<style>
    #dialog label, #dialog input { display:block; }
    #dialog label { margin-top: 0.5em; }
    #dialog input, #dialog textarea { width: 95%; }
    #tabs { margin-top: 1em; }
    #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
<style type="text/css">
    /*Demo Styles*/
    body{ background:#e9e8e4 url('img/bg-black.png'); }
    #content{ width:920px; margin:20px auto; padding:10px 30px; }
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
    .save{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
    }
    .delete{
       width: 100px;
       height: 50px;
       background-color: red;
       text-transform: uppercase;
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
    ?>
    <h1>Configura il ristorante
        <small>
            <a style="color:#fff;text-align: right; font-size: 14px; float: right;" href="logout.php">
                <-- menu principale
            </a>
        </small>
    </h1>
<script>
$(function() {
    
    //$( "#tabs" ).tabs();
    var $tab_title_input     = $("#tab_title"),
        $tab_posizione_input = $('#tab_posizione'), //non utilizzata per
        $tab_ntavoli_input   = $('#tab_ntavoli');

    var tab_counter = 1;

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",
            add: function( event, ui ) {
                    var tab_content = $tab_ntavoli_input.val() || "Tab " + tab_counter + " content.";
                    var tab_content2 = $tab_title_input.val() || "Tab " + tab_counter + " tite.";
                    $( ui.panel ).append('<form style="min-height:100px">'+
                                             '<fieldset style="float:left" class="ui-helper-reset">'+
                                                '<label class="tab_title" for="tab_title">Nome sala: </label>'+
                                                '<input type="text" name="tab_title" id="tab_title" value="'+tab_content2+'" class="ui-widget-content ui-corner-all" />'+
                                                '<br /><label for="tab_ntavoli">Numero tavoli: </label>'+
                                                '<input type="text" name="tab_ntavoli" id="tab_ntavoli" value="'+tab_content+'" class="ui-widget-content ui-corner-all" />'+
                                             '</fieldset>'+
                                             '<fieldset style="float:right" class="ui-helper-reset">'+
                                                 '<input class="save" type="submit" value="salva" /><br />'+
                                                 '<input class="delete" type="submit" value="elimina" />'+
                                             '</fieldset>'+
                                         '</form>' );
            }
    });

    // modal dialog init: custom buttons and a "close" callback reseting the form inside
    var $dialog = $( "#dialog" ).dialog({
            position: 'center',
            autoOpen: false,
            modal: true,
            buttons: {
                    Aggiungi: function() {
                            addTab();
                            $( this ).dialog( "close" );
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
	<div id="dialog" title="Dati nuova sala">
            <form>
                <fieldset class="ui-helper-reset">
                        <label for="tab_title">Nome nuova sala</label>
                        <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
                        <label for="tab_ntavoli">Numero tavoli</label>
                        <input type="text" name="tab_ntavoli" id="tab_ntavoli" class="ui-widget-content ui-corner-all" />
                        <!--
                        <label for="tab_posizione">Coordinate posizione</label>
                        <textarea name="tab_posizione" id="tab_posizione" class="ui-widget-content ui-corner-all"></textarea>
                        -->
                </fieldset>
            </form>
  	</div>

        <div class="demo">
            <div id="tabs">
                    <ul>
                        <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi nuova sala</span></button>
                    </ul>
            </div>
        </div><!-- End demo -->


        <h4 style="margin-left: 10px;">
            <a style="color:#fff;" href="logout.php">esci</a> |
            <a style="color:#fff;" href="support.php">supporto</a> |
            <a style="color:#fff;" href="license.php">credit</a>
        </h4>
</div>
<?php
        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <a style="color:#fff;" href="logout.php"> --> LOGIN</a>
            </h4>';
    }
?>