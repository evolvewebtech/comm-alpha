<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();
        
        //Array da passare con JSON  
        $arr = array(   "livelli"   => array(),
                        "cassiere" => '',
                        "sconti" => array(),
                        "id" => 0,
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
            
            $arr['livelli'] = $user->getLivelli();
            $arr['cassiere'] = $user->first_name;
            $arr['id'] = $user->id;
            $arr['sconti'] = DataManager2::getAllScontiCassiere($user->id);
                
            //Invio array con Ajax
            if ($arr){
                echo json_encode($arr);
            }else {
                //echo json_encode("an error occurred");
                echo "an error occurred";
            }    
                
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
