<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/../../manager/DataManager2.php';
require_once dirname(__FILE__).'/../../manager/Config.php';

$nome = $_POST['nome'];
$prezzo = $_POST['prezzo'];
$iva = $_POST['iva'];
$colore_bottone = $_POST['colore_bottone'];
$descrizione = $_POST['descrizione'];
$apeso = $_POST['apeso'];
if ($apeso > 0) $apeso = 1;
else $apeso = 0;
$path_image = $_POST['path_image'];
$codice_prodotto = $_POST['codice_prodotto'];
$quantita = $_POST['quantita'];

$gestore_id = 1;
$categoria_id = 1;
$alimento_id = 1;


$result = DataManager2::inserisciAlimento('NULL', $nome, $prezzo, $iva, $colore_bottone,
        $descrizione, $apeso, $path_image, $codice_prodotto, $quantita, $gestore_id,
        $categoria_id, $alimento_id);


if ($result){
    echo "Alimento aggiunto!!!";
}else {
    echo "an error occurred";
}


?>
