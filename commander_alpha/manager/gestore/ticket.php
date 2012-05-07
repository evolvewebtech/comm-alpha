<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';
        $objSession = new HTTPSession();

        $seriale                = mysql_real_escape_string($_POST['tab_seriale']);
        $credito                = mysql_real_escape_string($_POST['tab_credito']);
        $nominativo             = mysql_real_escape_string($_POST['tab_nominativo']);
        $buono_prepagato_id     = intval(mysql_real_escape_string($_POST['ticket_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);

        $var = array("seriale"              => $seriale,
                     "credito"              => $credito,
                     "nominativo"           => $nominativo,
                     "ticket_id"            => $buono_prepagato_id,
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

//            $ret = $gestore->delBuonoPrepagato($buono_prepagato_id);
//            if(!$ret){
//                $var['err'] = $ret;
//            }
            $ret = $gestore->editBuonoPrepagato($buono_prepagato_id, $seriale, $credito, $nominativo, $gestore_id, 0);
            if(!$ret){
                $var['err'] = $ret;
                echo json_encode($var);
            }

	}elseif($action == 'save'){

                //verifico se il buono esiste già
                $nome_buono_prepagato_db = $gestore->getBuonoPrepagato($buono_prepagato_id);

                if($nome_buono_prepagato_db==0){


                    $ret = $gestore->addBuonoPrepagato($buono_prepagato_id, $seriale, $credito, $nominativo, $gestore_id, 1);
                    if(!$ret){
                        $var['err'] = $ret;
                        echo json_encode($var);
                    }


                }else{

                    $ret = $gestore->editBuonoPrepagato($buono_prepagato_id, $seriale, $credito, $nominativo, $gestore_id, 1);
                    if(!$ret){
                        $var['err'] = $ret;
                        echo json_encode($var);
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