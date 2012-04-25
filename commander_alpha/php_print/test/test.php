
<?php
$fp = fsockopen("192.168.1.101", 9100, $errno, $errstr, 10);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = chr(27)."M".chr(48)."aaaa".chr(10);
    //$out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
  //  while (!feof($fp)) {
  //      echo fgets($fp, 128);
  //  }
    fclose($fp);
}
?>

