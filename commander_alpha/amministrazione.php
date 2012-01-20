<?php
require_once dirname(__FILE__).'/manager/HTTPSession.php';

$objSession = new HTTPSession();

$sess_id = $objSession->GetSessionIdentifier();
$log_in = $objSession->IsLoggedIn();
$utente_registrato = $objSession->__get('UTENTE_REGISTRATO_ID');

?>
<a href="logout.php">logout</a>
<h1>Pannello amministrazione</h1>
<br />ISLOGGEDIN:[ <?= var_dump($log_in) ?> ]
<br />SESSION ID: [ <?= $sess_id ?> ]
<br />variabile di sessione UTENTE_REGISTRATO_ID: [ <?= $utente_registrato ?> ]
<br />variabile di sessione RUOLO: [ <?= $objSession->RUOLO ?> ]

<h4>Aggiungo sala nel db</h4>
<?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

            $ret_addSala = $gestore->addSala('NULL','sala1','NULL');
            if ($ret_addSala){
                echo "<p>Sala aggiunta correttamente</p>";
            }
            $ret_addSala = $gestore->addSala('NULL','sala2','NULL');
            if ($ret_addSala){
                echo "<p>Sala aggiunta correttamente</p>";
            }
            $allSala = $gestore->getAllSala();
            if ($allSala){
                echo "<pre>";
                echo print_r($allSala);
                echo "</pre>";
            }
            






        }//end
    }
?>