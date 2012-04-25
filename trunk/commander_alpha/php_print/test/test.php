<?php

require_once dirname(__FILE__).'/Comm_print.php';

$ip = "192.168.1.101";
$out = chr(27)."M".chr(48).chr(29).chr(33)."0"."Prova".chr(10);

$comm_printer = new Comm_print();

echo $comm_printer->comm_print($ip, $out);

?>
