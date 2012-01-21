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

            $ret_addSala = $gestore->addSala('NULL','sala343','NULL');
            if ($ret_addSala){
                echo "<p>Sala aggiunta correttamente!</p>";
            }
            $allSala = $gestore->getAllSala();
            if ($allSala){
                echo "SALE: <pre>";
                echo print_r($allSala);
                echo "</pre>";
            }
            $delSala = $gestore->delSala(1);
            if ($delSala){
                echo "SALE 2:<pre>";
                echo print_r($allSala);
                echo "</pre>";
            }

            $ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utenteProva', 'passProva', 'email@email.email', 'nome', 'cognome', 1);
            if($ret_addCassiere){
                echo "<pre>";
                print_r($gestore->getAllCassieri());
                echo "</pre>";
            }
            /*
            $ret_aggiornaCassiere = $gestore->editCassiere($cassiere_id, $username, $password, $email, $nome, $cognome, $livello_cassiere);
            if($ret_editCassiere){
                echo "<pre>";
                print_r($gestore->getAllCassieri());
                echo "</pre>";
            }
            */
            echo "ciao";






        }//end
    }
?>