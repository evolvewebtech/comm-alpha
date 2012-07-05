
<h1>Crea un nuovo alimento</h1>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/../../manager/HTML_Form.php';

$frm = new HTML_Form();

$frmLogin = $frm->startForm('alimento_process.php', 'post', 'CreaAlimentoForm',
    array('class'=>'myFormClass',
          'onsubmit'=>'return checkBeforeSubmit(this)') );


$frmLogin .= "<SELECT NAME=alimenti><OPTION>aaa<OPTION>bbb</SELECT>";
$frmLogin .= "<BR>";


$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('nome', 'nome: ');
$frmLogin .= $frm->addInput('text', 'nome', '',
                       array('nome'=>'nome',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('prezzo', 'prezzo: ');
$frmLogin .= $frm->addInput('text', 'prezzo', '',
                       array('prezzo'=>'prezzo',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('iva', 'iva: ');
$frmLogin .= $frm->addInput('text', 'iva', '',
                       array('iva'=>'iva',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('colore_bottone', 'colore_bottone: ');
$frmLogin .= $frm->addInput('text', 'colore_bottone', '',
                       array('colore_bottone'=>'colore_bottone',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('descrizione', 'descrizione: ');
$frmLogin .= $frm->addInput('textarea', 'descrizione', '',
                       array('descrizione'=>'descrizione',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('apeso', 'apeso: ');
$frmLogin .= $frm->addInput('checkbox', 'apeso', '1',
                       array('apeso'=>'apeso',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('path_image', 'path_image: ');
$frmLogin .= $frm->addInput('text', 'path_image', '',
                       array('path_image'=>'path_image',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('codice_prodotto', 'codice_prodotto: ');
$frmLogin .= $frm->addInput('text', 'codice_prodotto', '',
                       array('codice_prodotto'=>'codice_prodotto',
                             'size'=>16));

$frmLogin .= "<BR>";
$frmLogin .= $frm->addLabelFor('quantita', 'quantita: ');
$frmLogin .= $frm->addInput('text', 'quantita', '',
                       array('quantita'=>'quantita',
                             'size'=>16));

$frmLogin .= "<BR><BR>";
$frmLogin .= $frm->addInput('submit', 'submitMyForm', 'CONFERMA');


$frmLogin .=  $frm->endForm();

echo $frmLogin;

?>
