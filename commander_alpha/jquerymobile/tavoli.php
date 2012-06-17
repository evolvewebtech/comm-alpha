<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();
        
        //Array da passare con JSON  
        $arr = array(   "sale"   => array(),
                        "err"   => ''); 
        
        /*
         * Inizio login
         */
        if($objSession->IsLoggedIn()){
            $objUser = $objSession->GetUserObject();
            $user = $objUser[0];
            if(get_class($user) == 'Cassiere') {
            
            $arr['sale'] = array(   "id"        => 0,
                                    "nome"      => '',
                                    "tavoli"    => array() );
            
            $arSale = DataManager2::getAllSaleAsObjects();            
            $saleTemp = array();
            
            for($i=0; $i<count($arSale); $i++) {
                
                $tavoli = array();               
                for($j=0; $j<$arSale[$i]->getNumberOfTavoli(); $j++) {
                    
                    $tavoloData = $arSale[$i]->getTavolo($j);
                    
                    $tavolo = array(    "id"            => $tavoloData->id,
                                        "nome"          => $tavoloData->nome,
                                        "numero"        => $tavoloData->numero,
                                        "nmax_coperti"  => $tavoloData->nmax_coperti,
                                        "posizione"     => $tavoloData->posizione,
                                        "sala_id"       => $tavoloData->sala_id);
                    
                    $tavoli[$j] = $tavolo;
                }
                
                $sala = array(  "id"        => $arSale[$i]->id,
                                "nome"      => $arSale[$i]->nome,
                                "tavoli"    => $tavoli);
                
                $saleTemp[$i] = $sala;
            }
            
            $arr['sale'] = $saleTemp;
            
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
