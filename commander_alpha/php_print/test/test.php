<?php

require_once dirname(__FILE__).'/PosPrint.php';
require_once dirname(__FILE__).'/EscPos.php';

$ip = "192.168.1.101";
$out = chr(27)."M".chr(48).chr(29).chr(33)."0"."Prova".chr(10);

$esc=new Comm_print("it",858,"àèìòù","\x7B\x7D\x7E\x7C\x60\xD5");	// initialize and select country, codepage and extra char trasformer string
$esc->align("c");			// central align
$esc->font(false,true,false,true,true);	// select bold, tall and large font
$esc->text("S.ANNA");
$esc->font();
$esc->align();				// left align
$esc->text("Tavolo: 1");
$esc->cut(30);				// 30 spaces and paper cut
$to_printer=$esc->out();

var_dump($to_printer);
echo PosPrint::comm_print($ip_address, $to_printer);

?>
