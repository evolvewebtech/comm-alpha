<?php

require dirname(__FILE__).'/../manager/DataManager2.php';

echo "Prova query da DB Categorie-Alimenti"."<BR>";


print "<hr>\n";

//*
$arContacts = DataManager2::getAllCategoriesAsObjects();

foreach($arContacts as $objEntity) {
    if(get_class($objEntity) == 'Categoria') {
        print "<h2>Categoria - {$objEntity->__toString()}</h2>";
        
        $numAlmt = $objEntity->getNumberOfAlimenti();
        echo "Numero alimenti: ".$numAlmt."<br>";
        for($i=0; $i<$numAlmt; $i++) {
            $Almnt = $objEntity->getAlimento($i);
            echo "<h3>Alimento - {$Almnt}</h3>";
            
            echo "Numero stampanti: ".$Almnt->getNumberOfStampanti()."<br>";
            for($j=0; $j<$Almnt->getNumberOfStampanti(); $j++) {
                echo "Stampante - {$Almnt->getStampante($j)}<br>";
            }
            echo "Numero varianti: ".$Almnt->getNumberOfVarianti()."<br>";
            for($j=0; $j<$Almnt->getNumberOfVarianti(); $j++) {
                echo "Variante - {$Almnt->getVariante($j)}<br>";
            }
        }
    }
  print "<hr>\n";

}//*/   //End foreach





print "<hr>\n";
print "<hr>\n";
print "<hr>\n";
print "<hr>\n";

print "Prova query da DB"."<BR>";

$arContacts = DataManager2::getAllStampantiAsObjects();
        
foreach($arContacts as $objEntity) {
    if(get_class($objEntity) == 'Stampante') {
        print "<h3>Stampante - {$objEntity->__toString()}</h3>";
    }
}


?>
