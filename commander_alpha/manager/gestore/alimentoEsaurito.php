<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        $finito       = mysql_real_escape_string($_POST['finito']);
        $action       = mysql_real_escape_string($_POST['action']);
        $alimento_id  = intval(mysql_real_escape_string($_POST['id']));

        // prende la data di oggi e la formatta
        $timestamp = time();
        $today     = mktime(date("H", $timestamp), date("i", $timestamp), date("s", $timestamp), date("m", $timestamp), date("d", $timestamp), date("Y", $timestamp));
        $today     = date("Y-m-d H:i:s", $today);
        $data      = $today;

        $var = array("finito"      => $finito,
                     "alimento_id" => $alimento_id,
                     "err"         => '');

    /*
     * inizio login
     */
    if($objSession->IsLoggedIn()){

        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

            $var['err'] .= '<hr />';

                if ($action=='finito'){

                    $finito = false;

                }else{

                    if ($finito==true){

                        $var['err'] .= 'TRUE:ret: ';

                        $ret = $gestore->addAlimentoEsaurito('NULL', $alimento_id, $data);
                        if (!$ret){
                            $var['err'] .= $ret;
                        }


                    }elseif($finito==false) {

                        $var['err'] .= 'FALSE:ret: ';

                        $ret = $gestore->delAlimentoEsaurito($alimento_id);
                        if (!$ret){
                            $var['err'] .= $ret;
                        }
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