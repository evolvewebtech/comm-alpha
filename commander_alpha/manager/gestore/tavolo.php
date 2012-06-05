<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__).'/../HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();

        $numero_tavolo = intval(mysql_real_escape_string($_POST['tab_numero']));
        $nome_tavolo   = mysql_real_escape_string($_POST['tab_title']);
        $ncoperti      = intval(mysql_real_escape_string($_POST['tab_nmax_coperti']));
        $sala_id       = intval(mysql_real_escape_string($_POST['sala_id']));
        $tavolo_id     = intval(mysql_real_escape_string($_POST['tavolo_id']));
        $current_tab   = intval(mysql_real_escape_string($_POST['current_tab']));
        $action        = mysql_real_escape_string($_POST['action']);

        $var = array("nome"          => $nome_tavolo,
                     "numero_tavolo" => $numero_tavolo,
                     "ncoperti"      => $ncoperti,
                     "tavolo_id"     => $tavolo_id,
                     "sala_id"       => $sala_id,
                     "current_tab"   => $current_tab,
                     "action"        => $action,
                     "err"           => '');

        /*
         * inizio login
         */
        if($objSession->IsLoggedIn()){

            $objUser = $objSession->GetUserObject();
            $gestore = $objUser[0];
            if(get_class($gestore) == 'Gestore') {
                if ($action == 'del'){
                    $ret = DataManager::delTavolo($tavolo_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }
                }elseif($action == 'save'){

                    /*
                     * se nome_tavolo_db non è 0 significa che è già presente nel db,
                     * quindi devo effettuare una modifica ad un tavolo già esistente
                     *
                     */
                    $nome_tavolo_db = DataManager::getTavolo($tavolo_id);
                    if($nome_tavolo_db==0){

                        /*
                         * tavolo non presente, devo aggiungerlo
                         */
                        $ret = DataManager::addTavolo($tavolo_id, $nome_tavolo, $numero_tavolo, $ncoperti, 'NULL', $sala_id);
                        if(!$ret){
                            $var['err'] = $ret;
                        }

                    }else{
                        /*
                         * aggiorno il tavolo
                         */
                        $ret = DataManager::editTavolo($tavolo_id, $nome_tavolo, $numero_tavolo, $ncoperti, 'NULL', $sala_id);
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
