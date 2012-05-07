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
        }//l'utente non è un cassiere
    else {
        //Utente non loggato
        header("location: login.php");
    }//l'utente non è loggato
?>
        
