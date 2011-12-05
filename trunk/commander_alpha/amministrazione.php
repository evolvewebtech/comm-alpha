<?php
require_once("manager/HTTPSession.php");

$objSession = new HTTPSession();
$sess_id = $objSession->GetSessionIdentifier();

$utente_registrato = $objSession->__get('UTENTE_REGISTRATO_ID');
?>
<a href="login_process.php">logout</a>
<h1>Pannello amministrazione</h1>
<br />variabile di sessione UTENTE_REGISTRATO_ID: [ <?= $utente_registrato ?> ]
<br />variabile di sessione RUOLO: [ <?= $objSession->RUOLO ?> ]