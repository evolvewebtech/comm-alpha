<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        $finito       = mysql_real_escape_string($_POST['finito']);
        $alimento_id  = intval(mysql_real_escape_string($_POST['id']));
        $data   = 'now()';

        $var = array("finito"      => $finito,
                     "alimento_id" => $alimento_id,
                     "err"         => '');
        /*
        $finito = false;
        $alimento_id = 19;
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

                if ($finito==true){

                    $ret = $gestore->addAlimentoEsaurito('NULL', $alimento_id, "now()");
                    if (!$ret){
                        $var['err'] = $ret;
                    }

                }elseif($finito==false) {

                    //$alimento_id = 22;
                    //$id = DataManager::getIDbyAlimentoID($alimento_id);
                    //print_r($id['id']);

                    $ret = $gestore->delAlimentoEsaurito($alimento_id);
                    if (!$ret){
                        $var['err'] = $ret;
                    }
                }
            
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