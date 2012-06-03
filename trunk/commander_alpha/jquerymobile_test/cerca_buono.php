<?php
    
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession();
        
        //Array da passare con JSON  
        $arr = array(   "buono"   => array(),
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
                
                //ID gestore
                $gestore_id = $user->getGestoreID();
                
                //Ricerca buono
                $seriale = mysql_real_escape_string($_POST['buonoSer']);
                $buono = DataManager2::getBuonoPrepagatoAsObject($seriale, $gestore_id);        

                $var = array();
                $var[0] = $buono->credito;
                $var[1] = $buono->nominativo;
                
                $arr['buono'] = $var;

                echo json_encode($arr);

            /*
             * fine login
             *
             */
            } else{
                $arr['err'] = 'E001'; //non è un cassiere
                echo json_encode($arr);
            }
        }//isLoggedin
        else {
            $arr['err'] = 'E002';  //not logged in o sessione scaduta
            echo json_encode($arr);
        } 
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }

?>
