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
echo "<br />SESS ID: ";
var_dump($objSession->GetSessionIdentifier());
echo "<br />Logged in: ";
var_dump($objSession->IsLoggedIn());
echo "<br />User ID: ";
var_dump($objSession->GetUserID());

$ret = $objSession->LogOut();
echo "<br />LogOUT: ";
var_dump($ret);
echo "<br />";
*/
/*
 * reindirizzo alla pagina di login
 */
header("location: login.php");
?>
