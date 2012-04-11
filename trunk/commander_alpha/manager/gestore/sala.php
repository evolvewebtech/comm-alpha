<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__).'/../HTTPSession.php';
        $objSession = new HTTPSession();

        $nome_sala = mysql_real_escape_string($_POST['tab_title']);
        if (isset($_POST['tab_ntavoli']))
            $n_tavoli = intval(mysql_real_escape_string($_POST['tab_ntavoli']));
        else
            $n_tavoli = 0;
        $sala_id = intval(mysql_real_escape_string($_POST['sala_id']));
        $current_tab = intval(mysql_real_escape_string($_POST['current_tab']));
        $action = mysql_real_escape_string($_POST['action']);

        $var = array("nome"        =>$nome_sala,
                     "n_tavoli"    =>$n_tavoli,
                     "id"          =>$sala_id,
                     "current_tab" =>$current_tab,
                     "action"      =>$action,
                     "err"         =>'');
        /*
         * inizio login
         */
        if($objSession->IsLoggedIn()){

            $objUser = $objSession->GetUserObject();
            $gestore = $objUser[0];
            if(get_class($gestore) == 'Gestore') {

                if ($action == 'del'){

                    $ret = DataManager::delSala($sala_id);
                    $ret2 = DataManager::delAllTavoloBySalaID($sala_id);
                    if(!$ret & !$ret2){
                        $var['err'] = $ret;
                    }

                }elseif($action == 'save'){

                    /*
                     * cerco l'id con valore massimo nella
                     * tabella cmd_tavolo. Poi aggiungo nuovi tavoli a partire da queell'id
                     *
                     * ogni tavolo è inserito nella seguente forma (vedi "for" succesivi):
                     * id:           incrementale a partitee da max_id
                     * nome:         numero da 1 a n_tavoli
                     * numero:       numero da 1 a n_tavoli
                     * nmax_coperti: 4 (valore di defolut, modificabile in seguito)
                     * posizione:    null
                     * sala_id:      id della sala associata
                     *
                     */

                    /*
                     * se nome_sala_db non è null significa che è già presente nel db,
                     * quindi devo effettuare una modifica ad una sala già esistente
                     *
                     */
                    $nome_sala_db = DataManager::getSala($sala_id);
                    if($nome_sala_db==0){

                        //la sala non esiste, la creo e aggiungo i tavoli
                        $ret = DataManager::addSala($sala_id, $nome_sala, 'NULL');
                        if(!$ret){
                            $var['err'] = $ret;
                        }
                        $max_id_table = DataManager::getMAXID('cmd_tavolo');
                        $max_id_table++;
                        for ($i = 1; $i <= $n_tavoli; $i++) {
                            $ret2 = DataManager::addTavolo($max_id_table, $i, intval($i), 4, 'NULL', $sala_id);
                            $max_id_table++;
                        }

                    }else{
                        /*
                         * aggiorno il nome sala
                         */
                        $ret = DataManager::editSala($sala_id, $nome_sala, 'NULL');
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
