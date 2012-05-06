<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/manager/DataManager.php';
require_once dirname(__FILE__).'/manager/Config.php';
require_once dirname(__FILE__).'/manager/Logger.php';
require_once dirname(__FILE__).'/manager/HTTPSession.php';



$username = mysql_real_escape_string($_POST['username']);
$password = mysql_real_escape_string($_POST['password']);

$objSession = new HTTPSession();
$objSession->Impress();

/*
 * effettuo l'accesso o il logout
 */
if (isset ($username) && isset ($password)) {
    $objSession->Login($username, $password);
} else {
    $objSession->LogOut();
    die("LOGOUT");
}

if ($objSession->IsLoggedIn()){

    $objUser = $objSession->GetUserObject();
    $objSession->UTENTE_REGISTRATO_ID = $objSession->GetUserID();

    if(get_class($objUser[0]) == 'Gestore') {
        $objSession->RUOLO = 'GESTORE';

        /*
         * reindirizzo al pannello amministratore
         * per il gestore
         *
         */
        header("location: amministrazione.php");

    } elseif (get_class($objUser[0]) == 'Cassiere') {
        $objSession->RUOLO = 'CASSIERE';
        $livello_cassiere = $objUser[0]->getLivelloCassiere();
        $objSession->LIVELLO_CASSIERE = $livello_cassiere;

        $gestore_id = $objUser[0]->getGestoreID();
        $objSession->GESTORE_ID = $gestore_id;

        /*
         * reindirizzo alla pagina delle ordinazioni
         * del cassiere
         *
         */
        //echo '<a href="ordinazione.php">inizia a gestire gli ordini</a>';
        header("location: jquerymobile/index.php");
    }
} else {
    //login fallito
    header("location: login.php");
    }
?>