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