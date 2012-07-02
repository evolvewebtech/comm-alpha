<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/manager/DataManager.php';
require_once dirname(__FILE__).'/manager/Config.php';
require_once dirname(__FILE__).'/manager/Logger.php';
require_once dirname(__FILE__).'/manager/HTTPSession.php';


$objSession = new HTTPSession();
$objSession->Impress();

/*
 * effettuo il logout
 */
$objSession->LogOut();

/*
 * reindirizzo alla pagina di login
 */
header("location: index.php");
?>
