<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'manager/DataManager.php';

$username = "cecco";
$password = "password";
$email = "a@a.a";
$nome = "francesco";
$cognome = "falanga";
$livello_cassiere = 0;

$gestore_id = 1;

$result = DataManager::inseririCassiere('NULL', 'NULL', 'NULL', $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
if ($result){
    echo "cassiere aggiunto";
}else {
    echo "an error occurred";
}


?>
