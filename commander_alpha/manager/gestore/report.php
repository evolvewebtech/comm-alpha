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


            if( $cameriere_id !="null" ){
                $cameriere_id = intval($cameriere_id);
                    $arOrdini = DataManager::getAllOrdiniByCassiereAsObjects($cameriere_id, $start_timestamp, $end_timestamp);
                } else{
                    $arOrdini = DataManager::getAllOrdiniByDateStartEndAsObjects($start_timestamp, $end_timestamp);
                    }


            //$arOrdini = DataManager::getAllOrdiniByDateStartEndAsObjects($start_timestamp, $end_timestamp);

            //Array da passare con JSON
            $var = array();

            if ($arOrdini) {
                for($i=0; $i<count($arOrdini); $i++) {

                    $tot = 0;
                    for($j=0; $j<$arOrdini[$i]->getNumberOfRigheOrdine(); $j++) {
                        $tot = $tot + ($arOrdini[$i]->getRigaOrdine($j)->prezzo * $arOrdini[$i]->getRigaOrdine($j)->numero);
                    }

                    $tavolo_id = $arOrdini[$i]->tavolo_id;
                    $tavolo = DataManager::getTavolo(intval($tavolo_id));

                    $arrTemp = array(   "id"            => $arOrdini[$i]->id,
                                        "seriale"       => $arOrdini[$i]->seriale,
                                        "timestamp"     => $arOrdini[$i]->timestamp,
                                        "n_coperti"     => $arOrdini[$i]->n_coperti,
                                        "tavolo"        => $tavolo,
                                        "tavolo_id"     => $arOrdini[$i]->tavolo_id,
                                        "totale"        => $tot);

                    $var[$i] = $arrTemp;
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