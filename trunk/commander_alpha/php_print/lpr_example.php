<?php
/*
Example of use of PrintSendLPR
@author Mick Sear
eCreate 2005
LGPL
*/

error_reporting(E_ALL);

include("PrintSend.php");
include("PrintSendLPR.php");

echo "<h1>PrintSendLPR example</h1>";
$lpr = new PrintSendLPR();
$lpr->setHost("192.168.1.101");//Put your printer IP here
//$lpr->setData("C:\\wampp2\\htdocs\\print\\test.txt");//Path to file, OR string to print.
$lpr->setData("Prova stampa da PHP");//Path to file, OR string to print.

echo $lpr->printJob("Prova stampa da PHP");//If your printer has a built-in printserver, it might just accept anything as a queue name.
echo "<h3>Debug</h3><pre>".$lpr->getDebug()."</pre>";

?>
