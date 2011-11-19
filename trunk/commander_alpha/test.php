<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'manager/DataManager.php';

$username = "cecco2";
$password = "password2";
$email = "a@a.a";
$nome = "francesco2";
$cognome = "falanga2";
$livello_cassiere = 1;

$gestore_id = 1;
$cassiere_id = 2;
$utente_registrato_id = 6;
$gestore_id = 1;
$result = DataManager::aggiornaCassiere($cassiere_id, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
if ($result){
    echo "cassiere aggiornato";
}else {
    echo "an error occurred";
}


?>
