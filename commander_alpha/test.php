<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'manager/DataManager.php';

$username = "cecco3";
$password = "password3";
$email = "a@a.a";
$nome = "francesco3";
$cognome = "falanga3";
$livello_cassiere = 2;

$gestore_id = 1;
$cassiere_id = 2;
$utente_registrato_id = 6;
$gestore_id = 1;
$result = DataManager::aggiornaCassiere($cassiere_id, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
if ($result){
    echo "cassiere aggiornato2";
}else {
    echo "an error occurred";
}


?>
