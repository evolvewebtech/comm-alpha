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

/*
$result = DataManager::aggiornaCassiere($cassiere_id, $gestore_id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
if ($result){
    echo "cassiere aggiornato2";
}else {
    echo "an error occurred";
}
*/

require_once('manager/Config.php');
require_once('manager/Logger.php');


$_GET['cassiere_id'] = false;
$log = Logger::getInstance();

if($_GET['cassiere_id']) {
    //not written to the log - the log level is too high
    $log->logMessage('A cassiere_id is present', Logger::DEBUG);
    //LOG_INFO is the default so this would get printed
    $log->logMessage('The value of cassiere_id is ' .  $_GET['cassiere_id']);

  } else {
    //This will also be written, and includes a module name
    $log->logMessage('No cassiere_id supplied', Logger::CRITICAL, "cassiere Module");
    throw new Exception('No cassiere_id!');
  }

?>
