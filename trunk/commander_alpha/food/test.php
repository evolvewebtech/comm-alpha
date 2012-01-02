<?php

require '../manager/DataManager2.php';

echo "Prova estrazione da DB Categorie-Alimenti"."<BR>";


print "<hr>\n";

//*
$arContacts = DataManager2::getAllEntitiesAsObjects();

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



?>
