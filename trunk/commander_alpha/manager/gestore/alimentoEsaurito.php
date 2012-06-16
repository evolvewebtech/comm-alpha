<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();
        $objSession->Impress();

        $action       = mysql_real_escape_string($_POST['action']);
        $alimento_id  = intval(mysql_real_escape_string($_POST['id']));

        // prende la data di oggi e la formatta
        $timestamp = time();
        $today     = mktime(date("H", $timestamp), date("i", $timestamp), date("s", $timestamp), date("m", $timestamp), date("d", $timestamp), date("Y", $timestamp));
        $today     = date("Y-m-d H:i:s", $today);
        $data      = $today;

        $var = array("finito"      => '',
                     "alimento_id" => $alimento_id,
                     "err"         => '');
    /*
     * inizio login
     */
    if($objSession->IsLoggedIn()){

        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {

            //$var['err'] .= '<hr />';

                //$action = 'finito';
                if ($action=='finito'){

                    //$alimento_id = intval(21);
                    $finito = Datamanager::controlloAlimentoEsaurito($alimento_id);
                    //print_r($finito);
                    if ($finito==FALSE || $finito['record_attivo']==0){
                        /*
                         * l'alimento non è presente nella tabella oppure il record è disattivo.
                         * Quindi è da segnalare come finito
                         */
                        //echo "1 - sono qui";
                        $ret = $gestore->addAlimentoEsaurito('NULL', $alimento_id, $data);
                        if (!$ret){
                            $var['err'] .= '<br />aggiunto'.$ret;
                        }
                        $var['finito'] = true;

                    } else{
                        /*
                         * l'alimento è tornato a disposizione. Il record è da disattivare
                         */
                        //echo "2 - sono qui";
                        $ret = $gestore->delAlimentoEsaurito($alimento_id);
                        //var_dump($ret);
                        if (!$ret){
                            $var['err'] .= '<br />eliminato - '.$ret;
                        }
                        $var['finito'] = false;
                    }
                    /*
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
                    */
                }else if($action=='controllo'){
                    $finito = Datamanager::controlloAlimentoEsaurito($alimento_id);
                    if ($finito==FALSE || $finito['record_attivo']==0){
                        $var['finito'] = false;
                        }else{
                            $var['finito'] = true;
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