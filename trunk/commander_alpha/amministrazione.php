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
            //$delSala = $gestore->delSala(1);
            if ($delSala){
                echo "SALE 2:<pre>";
                echo print_r($allSala);
                echo "</pre>";
            }

            //$ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utenteProva', 'passProva', 'nome1', 'nome2', 'G', 1);
            echo "<pre>";
            print_r($gestore->getAllCassiere());
            echo "</pre>";
            
            $ret_aggiornaCassiere = $gestore->editCassiere(10, 'piero', '827ccb0eea8a706c4c34a16891f84e7b', 'piero', 'po', 'C', 3);
            if($ret_aggiornaCassiere){
                echo "<pre>";
                print_r($gestore->getAllCassiere());
                echo "</pre>";
            }
            






        }//end
    }
?>