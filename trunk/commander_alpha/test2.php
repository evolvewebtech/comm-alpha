<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'manager/DataManager2.php';

$nome = "abc";
$prezzo = 1;
$colore_bottone = "xxx";
$descrizione = "abc";
$apeso = 0;
$path_image = "prova_path";
$codice_prodotto = rand();
$gestore_id = 1;
$categoria_id = 1;
$alimento_id = 1;


$result = DataManager2::inserisciAlimento('NULL', $nome, $prezzo, $colore_bottone,
        $descrizione, $apeso, $path_image, $codice_prodotto, $gestore_id,
        $categoria_id, $alimento_id); //*/

/*
$result = DataManager2::aggiornaAlimento(5, "nome_mod", 2, $colore_bottone,
        $descrizione, $apeso, $path_image, $codice_prodotto, $gestore_id,
        $categoria_id, $alimento_id); //*/

//$result = DataManager2::cancellaAlimento(5);



$nome = "stampante1";
$posizione = "1";
$indirizzo = "2";
$gestore_id = 1;
$alimento_id = 1;
$stampante_id = 1;
$colore_pred = "aaa";
$menu_id = 111;
$data_esaurito = "2011/03/20 07:16:17" ;


//$result = DataManager2::inserisciStampante('NULL', $nome, $posizione, $indirizzo, $gestore_id);

//$result = DataManager2::aggiornaStampante(1, "stampante yyy", $posizione, $indirizzo, $gestore_id, 1);

//$result = DataManager2::cancellaStampante(1);


//$result = DataManager2::inserisciAlimentoStampante('NULL', $nome, $alimento_id, $stampante_id);

//$result = DataManager2::aggiornaAlimentoStampante(1, "aggiornato", $alimento_id, $stampante_id);

//$result = DataManager2::cancellaAlimentoStampante(1);


//$result = DataManager2::inserisciCategoria('NULL', $colore_pred, $nome, $gestore_id);

//$result = DataManager2::aggiornaCategoria(1, $colore_pred, "cat_01", $gestore_id);

//$result = DataManager2::cancellaCategoria(1);


//$result = DataManager2::inserisciMenuFisso('NULL', $nome, $prezzo, $descrizione, $gestore_id);

//$result = DataManager2::aggiornaMenuFisso(1, "menu02", 2, $descrizione, $gestore_id);

//$result = DataManager2::cancellaMenuFisso(1);


//$result = DataManager2::inserisciAlimentoMenu('NULL', $alimento_id, $menu_id);

//$result = DataManager2::aggiornaAlimentoMenu(1, 2, 2);

//$result = DataManager2::cancellaAlimentoMenu(1);


//$result = DataManager2::inserisciAlimentoEsaurito('NULL', $alimento_id, $data_esaurito);

//$result = DataManager2::aggiornaAlimentoEsaurito(1, 2, $data_esaurito);

//$result = DataManager2::cancellaAlimentoEsaurito(1);


//$result = DataManager2::inserisciVariante('NULL', $descrizione, $prezzo, $gestore_id);

//$result = DataManager2::aggiornaVariante(1, "descr_var", 2, 2);

//$result = DataManager2::cancellaVariante(1);


if ($result){
    echo "fatto!!!";
}else {
    echo "an error occurred";
}


?>
