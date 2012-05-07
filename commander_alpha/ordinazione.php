<?php      
    /**
     * Verifica se l'utente è loggato come "Cassiere"
     */
    require_once dirname(__FILE__).'/manager/HTTPSession.php';

    $objSession = new HTTPSession();
    $sess_id = $objSession->GetSessionIdentifier();
    $log_in = $objSession->IsLoggedIn();
    $utente_registrato = $objSession->__get('UTENTE_REGISTRATO_ID');

    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) != 'Cassiere') {
            //Utente senza diritti
            header("location: login.php");
        }
 ?>
<!-- content -->
<div id="content">
    
</div>
<?php
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <br /><a style="color:#fff;" href="logout.php"> <-- LOGIN</a>
            </h4>';
    }
?>
        
