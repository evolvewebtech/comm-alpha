<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * $ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utenteProva','passProva', 'nome1', 'nome2', 'G', 1);
 * 
 */


?>
<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__).'/../HTTPSession.php';
        $objSession = new HTTPSession();


        $username               = mysql_real_escape_string($_POST['tab_username']);
        $password               = mysql_real_escape_string($_POST['tab_password']);
        $nome                   = mysql_real_escape_string($_POST['tab_nome']);
        $cognome                = mysql_real_escape_string($_POST['tab_cognome']);
        $cassiere_id            = intval(mysql_real_escape_string($_POST['cassiere_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $utente_registrato_id   = intval(mysql_real_escape_string($_POST['utente_registrato_id']));
        $livello_cassiere       = intval(mysql_real_escape_string($_POST['tab_livello_cassiere']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);
       
        $var = array("username"             => $username,
                     "password"             => $password,
                     "nome"                 => $nome,
                     "cognome"              => $cognome,
                     "cassiere_id"          => $cassiere_id,
                     "gestore_id"           => $gestore_id,
                     "utente_registrato_id" => $utente_registrato_id,
                     "livello_cassiere"     => $livello_cassiere,
                     "current_tab"          => $current_tab,
                     "action"               => $action,
                     "err"                  => '');

    /*
     * controllo se il login sia valido
     */
/*
 * inizio login
 */
    if($objSession->IsLoggedIn()){

        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

        if ($action == 'del'){

            $ret = $gestore->delCassiere($utente_registrato_id);
            if(!$ret){
                $var['err'] = $ret;
            }

	}elseif($action == 'save'){

                /*
                 * se nome_cassiere_db non è 0 significa che è già presente nel db,
                 * quindi devo effettuare una modifica ad un cassiere già esistente
                 *
                 */
                $nome_cassiere_db = $gestore->getCassiere($utente_registrato_id);

                if($nome_cassiere_db==0){

                    /*
                     * cassiere non presente, devo aggiungerlo
                     */
                    
                    $ret = $gestore->addCassiere($cassiere_id, 'NULL', $username, $password, $nome, $cognome, 'C', $livello_cassiere);
                    if(!$ret){
                        $var['err'] = $ret;
                    }
                    

                }else{
                    /*
                     * aggiorno il cassiere
                     */

                    $ret = $gestore->editCassiere($cassiere_id, $username, $password, $nome, $cognome, 'G', $livello_cassiere);
                    if(!$ret){
                        $var['err'] = $ret;
                    }

                }

            }//end save

/*
 * fine login
 *
 */
        } else{
            $var['err'] = 'E001'; //non è un gestore
        }
    }//isLoggedin
    else {
        $var['err'] = 'E002';  //not logged in o sessione scaduta
    }

    echo json_encode($var);

    } catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }

?>