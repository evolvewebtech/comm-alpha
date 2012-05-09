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

           $utente_registrato_id = intval(mysql_real_escape_string($_GET['id']));
           $cassiere = DataManager::getUserAsObject($utente_registrato_id);
           $cassiere = $cassiere[0];

            /*
             * prelevo i livelli già assegnati al cassiere
             */
           $livelli = $cassiere->getLivelli();
           if ($livelli==0) $livelli=array();

           /*
            * prelevo tutti i permessi disponibili
            */
           $permessi = DataManager::getAllPermessi();


           echo '<div style="background-color:white">';
               echo "<pre>";
//               print_r($cassiere);
               echo "</pre>";

               echo "<pre>";
//               print_r($livelli);
               echo "</pre>";
           echo '</div>';
    ?>
    <h1>Gestisci i permessi
        <small class="breadcrumb">Sei qui:
            <a style="color:#fff; font-size: 12px;" href="amministrazione.php">menu principale</a> >
            <a style="color:#fff; font-size: 12px;" href="amministrazioneCassieri.php"><b>Cassieri</b></a> >
            <a style="color:#fff; font-size: 14px;" href="amministrazionePermessi.php"><b>Permessi</b></a>
        </small>
    </h1>

<script>
/*
 * Tabs & validation.
 *
 */
$(function() {

    /*
     * Creo l'array che contiene l'id delle checkbox che devono essere
     * selezionate
     */
    var id_livelli = new Array();
    <? foreach ($livelli as $livello) { ?>
        id_livelli.push("<?=$livello?>");
    <? } ?>

    /*
     *  metto a checked=true le checkbox che rappresentano relazioni nel db
     */
    $.each(id_livelli, function(key, value){
        //alert(key + ': ' + value);
        $('#'+value).prop('checked', true);
    });


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
                             $( this ).dialog( "close" );
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


    /*
     *
     * salvo il nuovo cassiere aggiunto al premere del bottone: salva.
     *
     */
    $("#save_permesso").live("click", function() {

        var selected = $tabs.tabs('option', 'selected');
        selected+=1;
        $('#debug').append('<br />selected: '+selected);

        /*
         * prelevo tutti i valori delle checkbox "permessi" e ritorno una stringa
         * formattata json così costruita:
         *
         * id_permesso = bool
         * dove bool = true se la checkbox è selezionata, false altrimenti
         *
         */
//        var permessi = $.param($('#permessiForm input:checkbox').map(function() {
//           return { name: this.id, value: !! this.checked };
//        }));

        var matches = {};
        $("#permessiForm input:checkbox").map(function() {
            //matches.push(this.checked);
            matches[this.value] = this.checked;
        });
        console.log(matches);
        var permessi = JSON.stringify(matches);
        console.log(permessi);
        var permessiForm = $("#permessiForm-"+selected).serialize();
        permessiForm = permessiForm+'&action=save&cassiere_id='+<?=$cassiere->id?>+'&'+'permessi='+permessi;

        $.ajax({
            type: "POST",
            data: permessiForm,
            url: "manager/gestore/permessi.php",
            dataType: 'json',
            cache: false,
            success: onPermessoSuccess,
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

    function onPermessoSuccess(data, status) {

        console.log(data);
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
               $('#code-ok').html('Il nuovo cassiere &egrave stato aggiunto.');
               $dialogOK.dialog( "open" );
               console.log(data);

               $dialogOK.bind( "dialogclose", function(event, ui) {
                  // rinfresco la pagina per rendere effettiva l'eliminazione del cassiere
                  location.reload();
               });
           } else{
               alert(data);
               console.log(data);
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
        <?php include_once 'dialogs.php';?>

        <div class="clearfix"></div>
        <!-- tabs container -->
        <div class="permessi_tab" style="margin-top: 20px;">
            <div id="tabs">
                <ul>
                    <li><a href="ui-tabs-1">Permessi</a></li>
                </ul>
                <div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
                    <div style="min-height:230px; width: 550px; display: table;">
                        <form class="permesso" style="float: left; width: 440px;" id="permessiForm">
                        <fieldset>
                                <p>Seleziona i permessi da associare al cassiere <i><?=$cassiere->username?></i>.</p>
                                <?php
                                    foreach ($permessi as $permesso) {
                                        echo '<input type="checkbox" name="permessi[]" id="'.$permesso['id'].'" value="'.$permesso['id'].'" />'.$permesso['nome'].'<br />';
                                    }
                                ?>
                        </fieldset>
                        </form>

                        <fieldset style="float:right; width: 100px;" class="ui-helper-reset">
                            <button type="submit" id="save_permesso">SALVA</button><br />
                        </fieldset>

                    </div>
               </div>
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