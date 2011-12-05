<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('manager/HTML_Form.php');

$frm = new HTML_Form();

$frmLogin = $frm->startForm('login_process.php', 'post', 'LoginForm',
    array('class'=>'myFormClass',
          'onsubmit'=>'return checkBeforeSubmit(this)') );

$frmLogin .= $frm->addLabelFor('username', 'username: ');
$frmLogin .= $frm->addInput('text', 'username', 'ed',
                       array('id'=>'username',
                             'size'=>16, 'username'=>15));

$frmLogin .= $frm->addLabelFor('password', 'password: ');
$frmLogin .= $frm->addInput('password', 'password', '12345',
                       array('id'=>'password',
                             'size'=>16, 'maxlength'=>15));

$frmLogin .= $frm->addInput('submit', 'submitMyForm', 'ENTRA');

$frmLogin .=  $frm->endForm();

echo $frmLogin;
?>
