<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        require_once('manager/DataManager.php'); //everything gets included by it


        /*

        //carico tutti gli utenti registrati presenti nel db
        //e stampo a video alcune info.

        $arContacts = DataManager::getAllEntitiesAsObjects();

        foreach($arContacts as $objEntity) {
            if(get_class($objEntity) == 'Gestore') {
                print "<h1>Gestore - {$objEntity->__toString()}</h1>";
            } else {
                print "<h1>Cassiere - {$objEntity->__toString()}</h1>";
            }
          print "<hr>\n";

        }//End foreach
        */
        
        ?>
    </body>
</html>
