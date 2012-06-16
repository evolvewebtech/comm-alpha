<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();

        $start_timestamp = mysql_real_escape_string($_POST['start_timestamp']);
        $end_timestamp   = mysql_real_escape_string($_POST['end_timestamp']);

        $cameriere_id    = mysql_real_escape_string($_POST['cameriere_id']);

        $var = array("start_timestamp" => $start_timestamp,
                     "end_timestamp"   => $end_timestamp,
                     "cameriere_id"    => $cameriere_id,
                     "err"             => '');

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


            if( $cameriere_id == "null" || $cameriere_id=="0" ){
                $totali = DataManager::getTotaliAlimentiConsumati($start_timestamp, $end_timestamp);
                } else{
                    $cameriere_id = intval($cameriere_id);
                    $totali = DataManager::getTotaliAlimentiConsumatiByCameriere($start_timestamp, $end_timestamp, $cameriere_id);
                    }

           $var['s1'] = array();
           $var['ticks'] = array();
           if ($totali){
                    foreach ($totali as $totale) {
                        $var['s1'][] = intval($totale['quantita_consumata']);
                        $var['ticks'][] = $totale['nome'];
                  }}

            
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
