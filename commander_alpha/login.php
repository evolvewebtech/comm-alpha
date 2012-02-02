<link href="media/css/login.css" rel="stylesheet" type="text/css" />
<link href="media/css/main.css" rel="stylesheet" type="text/css" />

<div id="content" class="login">
<header>
    <h1>Commander</h1>
</header>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require dirname(__FILE__).'/manager/HTML_Form.php';

$frm = new HTML_Form();

$frmLogin = $frm->startForm('login_process.php', 'post', 'LoginForm',
    array('class'=>'myFormClass',
          'onsubmit'=>'return checkBeforeSubmit(this)') );
$frmLogin .= '<fieldset>';
$frmLogin .= $frm->addLabelFor('username', 'username: ');
$frmLogin .= $frm->HTML5addInput('text', 'username', 'ed',
                       array('id'=>'username'),'required autofocus');

$frmLogin .= '<br />';
$frmLogin .= $frm->addLabelFor('password', 'password: ');
$frmLogin .= $frm->HTML5addInput('password', 'password', '12345',
                       array('id'=>'password'),'required');
$frmLogin .= '</fieldset>';

$frmLogin .= '<fieldset class=login>';
$frmLogin .= $frm->addInput('submit', 'submitMyForm', 'ENTRA',array('id'=>'submitLogin'));
$frmLogin .= '</fieldset>';

$frmLogin .=  $frm->endForm();

echo $frmLogin;
?>

    <small>&copy; commander</small>
</div>