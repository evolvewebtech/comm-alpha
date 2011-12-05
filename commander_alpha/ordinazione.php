<?php
require_once("manager/HTTPSession.php");

$objSession = new HTTPSession();
$sess_id = $objSession->GetSessionIdentifier();
?>
<a href="login_process.php">logout</a>
<h1>Gestione ordini</h1>
<br />variabile di sessione UTENTE REGISTRATO ID: [ <?= $objSession->UTENTE_REGISTRATO_ID ?> ]
<br />variabile di sessione RUOLO: [ <?= $objSession->RUOLO ?> ]
<br />variabile di sessione LIVELLO_CASSIERE: [ <?= $objSession->LIVELLO_CASSIERE ?> ]
<br />variabile di sessione GESTORE ASSOCIATO ID: [ <?= $objSession->GESTORE_ID ?> ]

