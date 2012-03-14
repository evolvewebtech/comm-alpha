<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<!--
todo: 1. Creare tutti i controlli del form
      2. gestisci relazioni fra alimento menu e menu e attento ai menu "semi fissi"

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
    #save_menu{
       height: 50px;
       width: 100px;
       background-color: green;
       text-transform: uppercase;
       cursor: pointer;
    }
    #delete_menu{
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

           $data_menu = DataManager::getAllMenuByGestoreID($gestore_id);//($gestore_id);
           $numero_menu = count($data_menu);
           $max_id = DataManager::getMAXID('cmd_menu_fisso');
           if (!$max_id){
               $max_id=0;
           }

           $data_categoria_menu  = DataManager::getAllNomeCategoria();
           //echo '<p style="background-color:white">'.$numero_tavolo.'</p>';

    ?>
    <h1>Gestisci i men&ugrave;
        <small style="color:#fff;text-align: right; font-size: 12px; float: right;">
            Sei qui: <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
                     <a style="color:#fff; font-size: 14px;" href="amministrazioneMenu.php"><b>Men&ugrave;</b></a>
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
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descrizione del men&ugrave;",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        }
                    }
        });

    var $tab_nome_input                     = $("#tab_nome"),
        $tab_prezzo_input                   = $('#tab_prezzo'),
        $tab_iva_input                      = $('#tab_iva'),
        $tab_descrizione_input              = $('#tab_descrizione');

    var tab_counter = <?=$numero_menu?>,
        max_id      = <?=$max_id?>,
        gestore_id  = <?=$gestore_id?>;


       // Categoria-------------------------------------------------------------
       /*
        * Creo l'array che contiene l'id delle checkbox che devono essere
        * selezionate
        */
       var id_categoria_menu = new Array();
       <? foreach ($data_categoria_menu as $al_ID_var_ID) { ?>
                id_categoria_menu.push("<?=$al_ID_var_ID[menu_fisso_id]?>-<?=$al_ID_var_ID[nome_cat]?>");
       <? } ?>

       /*
        *  metto a checked=true le checkbox che rappresentano relazioni nel db
        */
       $.each(id_categoria_menu, function(key, value){
           //alert(key + ': ' + value);
           $('#'+value).prop('checked', true);
       });


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

                    var tab_content_nome                    = $tab_nome_input.val(),
                        tab_content_prezzo                  = $tab_prezzo_input.val(),
                        tab_content_iva                     = $tab_iva_input.val(),
                        tab_content_descrizione             = $tab_descrizione_input.val();

                    $( ui.panel ).append('<div style="min-height:175px;">'+
                        '<form id="menuForm-'+tab_counter+'" style="min-height:60px; float:left;">'+
                            '<fieldset style="float:left" class="ui-helper-reset">'+
                                '<br /><label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>'+
                                '<input type="text" name="tab_nome" id="tab_nome" value="'+tab_content_nome+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="'+tab_content_prezzo+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="'+tab_content_iva+'" class="ui-widget-content ui-corner-all" />'+
                                '<br /><label style="margin-right: 89px;" class="tab_descrizione" for="tab_descrizione">Descrizione: </label>'+
                                '<input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="'+tab_content_descrizione+'" class="ui-widget-content ui-corner-all" />'+
                                '<input type="hidden" name="menu_id" id="menu_id" value="'+next_id+'" />'+
                                '<input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>"  />'+
                           '</fieldset>'+
                        '</form>'+
                         '<fieldset style="float:right" class="ui-helper-reset">'+
                             '<button type="submit" id="save_menu">SALVA</button><br />'+
                             '<button type="submit" id="delete_menu">ELIMINA</button>'+
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

                var formSel = $("#menuForm-"+ui.index);

                $("#menuForm-"+ui.index).validate({
                    rules: {
                        tab_descrizione: {
                            required: true,
                            minlength: 2,
                            maxlength: 15
                        }
                    },
                    messages: {
                        tab_descrizione: {
                            required: "Inserisci la descriizone del men&ugrave;",
                            minlength: "minimo 2 caratteri",
                            maxlength: "massimo 15 caratteri"
                        }
                    }
                });
            }

    });


    /*
     * visualizzo o nascondo il pannello con tutte le categorie
     * disponibili
     *
     */
    $("button").click(function () {
        $(".categoria").slideToggle("slow");
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
    $("#save_menu").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        if($("#menuForm-"+selected).valid()){


            /*
             * prelevo tutti i valori delle checkbox Categorie e ritorno una stringa
             * formattata json così costruita:
             *
             * tabID-NomeCat=bool&tabID-NomeCat=bool&tabID-NomeCat=bool...
             * dove bool = true se la checkbox è selezionata, false altrimenti
             *
             */
            var params_v = $.param($('#nomeCatMenuForm-'+selected+' input:checkbox').map(function() {
               return { name: this.id, value: !!this.checked };
            }));
            $('#debug').append('<br />PARAM cat-Menu: '+params_v);
            $.ajax({
                type: "POST",
                data: params_v,
                url: "manager/gestore/nomeCatMenu.php",
                dataType: 'json',
                cache: false,
                success: nomeCatMenuSuccess,
                error: onMenuError
            });


            var menuForm = $("#menuForm-"+selected).serialize();
            menuForm = menuForm+'&action=save&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: menuForm,
                url: "manager/gestore/menu.php",
                dataType: 'json',
                cache: false,
                success: onMenuSuccess,
                error: onMenuError
            });
        }

    });

    /*
     *
     * Elimina il tavolo aggiunto al premere del bottone: elimina.
     *
     */
    $("#delete_menu").live("click", function() {

        var answer = confirm("Sei sicuro di voler eliminare questo Men&ugrave;?");

        if (answer){
            var selected = $tabs.tabs('option', 'selected');
            selected+=1;
            $('#debug').append('<br />selected: '+selected);

            var menuForm = $("#menuForm-"+selected).serialize();
            menuForm = menuForm+'&action=del&current_tab='+selected;

            $.ajax({
                type: "POST",
                data: menuForm,
                url: "manager/gestore/menu.php",
                dataType: 'json',
                cache: false,
                success: onMenuSuccess,
                error: onMenuError
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

    function onMenuSuccess(data, status) {

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
               $('#code-err').html('Errore durante l\'eliminazione del men&ugrave;.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){

               var current_tab = parseInt(data.current_tab,10);
               current_tab -= 1;

               $tabs.tabs( "option", "disabled",[ current_tab ] );
               $tabs.tabs( "remove", data.current_tab );

               $('#code-ok').html('Il men&ugrave; &egrave stata eliminata.');
               $dialogOK.dialog( "open" );

               //aspetto che il dialogo sia stato chiuso
               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione
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
               $('#code-err').html('Errore durante l\'inserimento o aggioramento del men&ugrave;.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               $('#code-ok').html('Il nuovo men&ugrave; &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+
                                   ' ID_gestore: '    + data.gestore_id+
                                   ' ID_menu: '       + data.menu_id+
                                   ' Nome:'           + data.nome +
                                   ' Prezzo: '        + data.prezzo +
                                   ' IVA: '           + data.iva +
                                   ' descrizione: '   + data.descrizione +
                                   ' Current: '       + data.current_tab+
                                   ' Err: '           + data.err );
           }
        }
    }


    function nomeCatMenuSuccess(data, status){
        $('#debug').append('<br />ajax nomeCat: success');
        $('#debug').append('<br /><br />'+data.err+'<br />');

           if (data.err=='E002'){
               $('#code-err').html('Sessione scaduta o login non valido.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='E001'){
               $('#code-err').html('Non hai i permessi necessari per eseguire questa operazione. Contatta il gestore.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if (data.err=='false'){
               $('#code-err').html('Errore durante l\'aggiornamento delle categorie.');
               $dialogERR.dialog("open");
               $('#debug').append(' ERR: '+data.err);
           } else if(data.err==''){
               //$('#code-ok').html('Le varianti sono state aggiornate correttamente.');
               //$dialogOK.dialog( "open" );
               $('#debug').append( '<br />DATA SAVED:<br />'+data.post+'<br />ERR: '+data.err+'<br />');
           }
           return;
    }

    function onMenuError(data, status) {
        $('#code-err').html('Errore nel file. Contatta l\'amministratore. ');
        $dialogERR.dialog( "open" );
        $('#debug').append(data);

    }


});
</script>

        <!-- dialogs -->
	<div id="dialog" title="Dati nuovo men&ugrave;">
            <form id="addNewTab">
                <fieldset class="ui-helper-reset">
                    <label for="tab_nome">Nome </label>
                    <input type="text" name="tab_nome" id="tab_nome" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_prezzo">Prezzo: </label>
                    <input type="text" name="tab_prezzo" id="tab_prezzo" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_iva">Iva: </label>
                    <input type="text" name="tab_iva" id="tab_iva" value="" class="ui-widget-content ui-corner-all" />
                    <label for="tab_descrizione">Descrizione: </label>
                    <input type="text" name="tab_descrizione" id="tab_descrizione" value="" class="ui-widget-content ui-corner-all" />
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
                        foreach ($data_menu as $menu) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$menu['nome'].'</a></li>';
                          $count++;
                        }
                    ?>
                    <li style="float:right"><button id="add_tab"><img id="plus" src="img/plus.png"><span id="add_span">aggiungi un men&ugrave;</span></button>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_menu as $menu) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:250px;">
                        <form id="menuForm-<?=$count?>" style="min-height:60px; float:left;">
                            <fieldset style="float:left" class="ui-helper-reset">
                                <br /><label style="margin-right: 139px;" class="tab_title" for="tab_nome">Nome: </label>
                                <input type="text" name="tab_nome" id="tab_nome" value="<?=$menu['nome']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 89px;" class="tab_descrizione" for="tab_descrizione">Descrizione: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_descrizione" id="tab_descrizione" value="<?=$menu['descrizione']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 133px;" class="tab_prezzo" for="tab_prezzo">Prezzo: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_prezzo" id="tab_prezzo" value="<?=$menu['prezzo']?>" class="ui-widget-content ui-corner-all" />
                                <br /><label style="margin-right: 162px;" class="tab_iva" for="tab_iva">Iva: </label>
                                <input style="margin-right: 9px;" type="text" name="tab_iva" id="tab_iva" value="<?=$menu['iva']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="menu_id" id="menu_id" value="<?=$menu['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$menu['gestore_id']?>" />
                                
                                <fieldset style="margin-top:10px; width:300px;">
                                <a href="amministrazioneAlimentiMenu.php?id=<?=$menu['id']?>">Inserisci gli alimenti al menu.</a>
                                </fieldset>
                            </fieldset>
                        </form>
                        <!--
                            Area nomi categorie
                        -->
                        <button style="float:left;width:235px;">Scegli le categorie da inserire nel menu fisso</button>
                        <form class="categoria" style="float:left; display:none;width:235px;" id="nomeCatMenuForm-<?=$count?>">
                        <fieldset>
                                <?php
                                    $data_categoria = DataManager::getAllCategoriaByGestoreID($gestore_id);
                                    foreach ($data_categoria as $categoria) {
                                        echo '<input type="checkbox" name="categorie[]" id="'.$menu['id'].'-'.$categoria['nome'].'" value="'.$categoria['id'].'" />'.$categoria['nome'].'<br />';
                                    }
                                ?>
                        </fieldset>
                        </form>

                        <fieldset style="float:right" class="ui-helper-reset">
                            <button type="submit" id="save_menu">SALVA</button><br />
                            <button type="submit" id="delete_menu">ELIMINA</button>
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