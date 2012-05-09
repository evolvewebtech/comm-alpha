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

           $menu_fisso_id = intval(mysql_real_escape_string($_GET['id']));

           $menu_fisso = DataManager::getMenufisso($menu_fisso_id);
           if ($menu_fisso==0){
               die('<p style="background-color:white;color:black;">Err</p>');
           }

           $data_categorie = DataManager::getAllCategoriaByMenuID($menu_fisso_id);
           $numero_categorie = count($data_categorie);


           $data_alimento = DataManager::getAllAlimentoByGestoreID($gestore_id);//($gestore_id);
           /*
           echo '<pre style="background-color:white">';
           print_r($data_categorie);
           echo '</pre>';
            * 
            */
           $data_alimento_menu  = DataManager::getAllAlimentoMenu();
           

    ?>
    <h1 style="margin-bottom: 20px;">Menu: <?=$menu_fisso['nome']?><small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 12px;" href="amministrazioneMenu.php">Men&ugrave;</a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazioneAlimentiMenu.php?id=<?=$menu_fisso_id?>"><b>Alimenti</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

    var $tab_nome_input  = $("#tab_nome");

    /*
     * Creo l'array che contiene l'id delle checkbox che devono essere
     * selezionate
     */
    var id_alimento_menu = new Array();
    <? foreach ($data_alimento_menu as $al_ID_var_ID) { ?>
        id_alimento_menu.push("<?=$al_ID_var_ID['alimento_menu_id']?>-<?=$al_ID_var_ID['alimento_id']?>");
    <? } ?>

    /*
     *  metto a checked=true le checkbox che rappresentano relazioni nel db
     */
    $.each(id_alimento_menu, function(key, value){
        $('#'+value).prop('checked', true);
    });

    var tab_counter = <?=$numero_categorie?>,
        gestore_id  = <?=$gestore_id?>;

    tab_counter++;

    $('#debug').append('<br />Numero: '         +tab_counter+
                       '<br />Gestore ID: '     +gestore_id);

    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $( "#tabs").tabs({
            tabTemplate: "<li><a href='#{href}'>#{label}</a></li>",

            add: function( event, ui ) {

            },

            remove: function( event, ui ) {

            },

            select: function( event, ui ) {

                ui.index+=1;

                $('#debug').append('<br />selected: '+ui.index);

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

    
    /*
     *
     * salvo il nuovo tavolo aggiunto al premere del bottone: salva.
     *
     */
    $("#save_menu").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        /*
         * prelevo tutti i valori delle checkbox Categorie e ritorno una stringa
         * formattata json così costruita:
         *
         * tabID-NomeCat=bool&tabID-NomeCat=bool&tabID-NomeCat=bool...
         * dove bool = true se la checkbox è selezionata, false altrimenti
         *
         */
        var params_v = $.param($('#alimentoMenuForm-'+selected+' input:checkbox').map(function() {
           return { name: this.id, value: !!this.checked };
        }));
        params_v = 'id='+<?=$menu_fisso_id?>+'&'+params_v;
        $('#debug').append('<br />PARAM cat-Menu: '+params_v);
        $.ajax({
            type: "POST",
            data: params_v,
            url: "manager/gestore/alimentiMenu.php?menu_id=<?=$menu_fisso_id?>",
            dataType: 'json',
            cache: false,
            success: alimentiMenuSuccess,
            error: onError
        });


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

    function alimentiMenuSuccess(data, status) {
        /*
         *  verico che l'operazione di salvataggio sia andata a buon fine.
         */
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
           $('#code-ok').html('Il men&ugrave; &egrave stato aggiornato.');
           $('#debug').append( '<br />DATA SAVED:<br />'+
                               ' ID_gestore: '    + data.gestore_id+
                               ' ID_menu: '       + data.menu_id+
                               ' <br />err'       + data.err
                                );
           $dialogOK.dialog( "open" );
           //aspetto che il dialogo sia stato chiuso
           $dialogOK.bind( "dialogclose", function(event, ui) {
              // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
              location.reload();
           });

       }else{
           $('#debug').append( '<br /><br />ERR:'+ data.err);      
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
        <?php include_once 'dialogs.php';?>
        <div class="clearfix"></div>
        <!-- tabs container -->
        <div class="tavolo_tab">
            <div id="tabs">
                <ul>
                    <?php
                        $count = 1;
                        foreach ($data_categorie as $cat_menu) {
                          echo '<li><a href="ui-tabs-'.$count.'">'.$cat_menu['nome_cat'].'</a></li>';
                          $count++;
                        }
                    ?>
                </ul>
                <?php
                    $count = 1;
                    foreach ($data_categorie as $cat_menu) {
                        echo '<div id="ui-tabs-'.$count.'" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
                    ?>
                    <div style="min-height:230px; width: 550px; display: table;">
                        <form id="menuForm-<?=$count?>">
                            <fieldset style="" class="ui-helper-reset">
                                <!--
                                <br /><label style="" class="tab_title" for="tab_nome">Menu: <?//=$menu_fisso['nome']?> - <?//=$menu_fisso['descrizione']?></label>
                                -->
                                <input type="hidden" name="tab_nome" id="tab_nome" value="<?=$cat_menu['nome_cat']?>" class="ui-widget-content ui-corner-all" />
                                <input type="hidden" name="menu_id" id="menu_id" value="<?=$cat_menu['id']?>" />
                                <input type="hidden" name="gestore_id" id="gestore_id" value="<?=$gestore_id?>" />
                            </fieldset>
                        </form>
                        <form class="alimento" style="float: left; width: 440px;" id="alimentoMenuForm-<?=$count?>">
                        <fieldset>
                                <p>Seleziona gli alimenti da inserire in questa categoria del menu <i><?=$menu_fisso['nome']?></i>.
                                   Se il cliente non ha possibilit&agrave; di scelta nella categoria <i><?=$cat_menu['nome_cat']?></i> seleziona soltanto una voce.
                                </p>
                                <?php
                                    foreach ($data_alimento as $alimento) {
                                        echo '<input type="checkbox" name="alimenti[]" id="'.$cat_menu['id'].'-'.$alimento['id'].'" value="'.$alimento['id'].'" />'.$alimento['nome'].'<br />';
                                    }
                                ?>
                        </fieldset>
                        </form>

                        <fieldset style="float:right; width: 100px;" class="ui-helper-reset">
                            <button type="submit" id="save_menu">SALVA</button><br />
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