<?php
require_once dirname(__FILE__).'/manager/HTTPSession.php';

$objSession = new HTTPSession();

$sess_id = $objSession->GetSessionIdentifier();
$log_in = $objSession->IsLoggedIn();
$utente_registrato = $objSession->__get('UTENTE_REGISTRATO_ID');

?>
<a href="logout.php">logout</a>
<h1>Pannello amministrazione</h1>
<br />ISLOGGEDIN:[ <?= var_dump($log_in) ?> ]
<br />SESSION ID: [ <?= $sess_id ?> ]
<br />variabile di sessione UTENTE_REGISTRATO_ID: [ <?= $utente_registrato ?> ]
<br />variabile di sessione RUOLO: [ <?= $objSession->RUOLO ?> ]

<?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

            //prova sala
            /*
            //$ret_addSala = $gestore->addSala('NULL','sala343','NULL');
            if ($ret_addSala){
                echo "<p>Sala aggiunta correttamente!</p>";
            }
            $allSala = $gestore->getAllSala();
            if ($allSala){
                echo "<br />TUTTE LE SALE: <pre>";
                echo print_r($allSala);
                echo "</pre>";
            }

            //$ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utenteProva', 'passProva', 'nome1', 'nome2', 'G', 1);
            echo "<pre>";
            print_r($gestore->getAllCassiere());
            echo "</pre>";
            
            $ret_aggiornaCassiere = $gestore->editCassiere(10, 'piero', '827ccb0eea8a706c4c34a16891f84e7b', 'piero', 'po', 'C', 3);
            if($ret_aggiornaCassiere){
                echo "TUTTI I CASSIERI:";
                echo "<pre>";
                print_r($gestore->getAllCassiere());
                echo "</pre>";
            }

            $el = $gestore->delCassiere(18);
            var_dump($el);
             * 
             */

            //prova tavoli
            /*
            echo "<br />aggiungo tavolo... ";
            $addT = $gestore->addTavolo('NULL','A1',1,4,'NULL',32);
            var_dump($addT);

            echo "<br />get tavolo... ";
            $getT = $gestore->getTavolo(2);
            var_dump($getT);

            echo "<br />modifico tavolo... ";
            $editT = $gestore->editTavolo(3,'A2',2,8,'NULL',31);
            var_dump($editT);
            
            echo "<br />TAVOLI:<pre>";
            print_r($gestore->getAllTavolo());
            echo "</pre>";
            
            echo "<br />Eliminazione tavolo... ";
            //$delT = $gestore->delTavolo(1);
            var_dump($delT);

            echo "<br />TAVOLI:<pre>";
            print_r($gestore->getAllTavolo());
            echo "</pre>";
            */

            //prova alimento
            /*
            echo "<br />aggiungo alimento... ";
            $addT = $gestore->addAlimento('NULL', 'pasta al pomodoro', 5, 0, 'yellow',
                                'tagliatelle al pomodoro', 0, 'images/', '00345F',
                                10000, 2, 1, 1);
            var_dump($addT);

            
            echo "<br />get Alimento... ";
            $getT = $gestore->getAlimento(18);
            var_dump($getT);

            echo "<br />modifico Alimento... ";
            $editT = $gestore->editAlimento(18, 'pasta al pesto', 4, 0, 'red',
                                'pennette al pesto', 0, 'images/', '00345G',
                                10000, 2, 1, 1);
            var_dump($editT);

            echo "<br />ALIMENTI:<pre>";
            print_r($gestore->getAllAlimento());
            echo "</pre>";
            
            echo "<br />Eliminazione Alimento... ";
            $delT = $gestore->delAlimento(18);
            var_dump($delT);
            */


        }//end
    }
?>