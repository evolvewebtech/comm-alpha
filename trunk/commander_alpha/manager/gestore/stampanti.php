<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__).'/../HTTPSession.php';
        $objSession = new HTTPSession();

        $nome                   = mysql_real_escape_string($_POST['tab_nome']);
        $indirizzo              = mysql_real_escape_string($_POST['tab_indirizzo']);
        $posizione              = "NULL";//mysql_real_escape_string($_POST['tab_posizione']);
        $stampante_id           = intval(mysql_real_escape_string($_POST['stampante_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);
        
        $var = array("nome"                 => $nome,
                     "indirizzo"            => $indirizzo,
                     "stampante_id"         => $stampante_id,
                     "gestore_id"           => $gestore_id,
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

            
            $ret = $gestore->delStampante($stampante_id);
            if(!$ret){
                $var['err'] = $ret;
            }
            

	}elseif($action == 'save'){

                /*
                 * se nome_stampante_db non è 0 significa che è già presente nel db,
                 * quindi devo effettuare una modifica ad una stampante già esistente
                 *
                 */

                $nome_stampante_db = $gestore->getStampante($stampante_id);
                
                if($nome_stampante_db==0){

                    /*
                     * stampante non presente, devo aggiungerla
                     */
                    $ret = $gestore->addStampante($stampante_id, $nome, $posizione, $indirizzo, $gestore_id);
                    //$ret = $gestore->addStampante(3, 'Cucina', 'NULL', '192.168.1.20', 2);
                    if(!$ret){
                        $var['err'] = $ret;
                    }
                    
                }else{

                    /*
                     * aggiorno la stampante
                     */
                    $ret = $gestore->editStampante($stampante_id, $nome, $posizione, $indirizzo, $gestore_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }
                    
                }//add or edit

            }//end del/save

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