<?php
require_once dirname(__FILE__).'/manager/HTTPSession.php';
$objSession = new HTTPSession();
    
if ($objSession->IsLoggedIn()){


            $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
    if(get_class($objUser[0]) == 'Gestore') {

            //$ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utente','passProva', 'nome1', 'nome2', 'C', 1);
            $ret_aggiornaCassiere = $gestore->editCassiere(1, 'al','827ccb0eea8a706c4c34a16891f84e7b', 'Ale', 'Sarzina', 'C', 3);
            if($ret_aggiornaCassiere){
                echo "TUTTI I CASSIERI:";
                echo "<pre>";
                print_r($gestore->getAllCassiere());
                echo "</pre>";
            }else{
                echo "merda";
            }

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