<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__).'/../HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();

        $nome                   = mysql_real_escape_string($_POST['tab_nome']);
        $colore_bottone         = mysql_real_escape_string($_POST['tab_colore_bottone']);
        $categoria_id           = intval(mysql_real_escape_string($_POST['categoria_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);
        $id_livello             = intval(mysql_real_escape_string($_POST['id_livello']));

        $var = array("nome"                 => $nome,
                     "colore_bottone"       => $colore_bottone,
                     "categoria_id"         => $categoria_id,
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
                
                $cassieri = $gestore->getAllCassiere();
                foreach ($cassieri as $cassiere) {
                    $cassiere_id = $cassiere->id;
                    $ret = DataManager2::aggiornaMenuAggiornato($cassiere_id, 0);
                    }

                if ($action == 'del'){

                    foreach ($cassieri as $cassiere) {
                        $livelli = $cassiere->getLivelli();
                        $cassiere_id = $cassiere->id;
                        if ($livelli==0)
                            $livelli = array();
                        //print_r($livelli);
                        foreach ($livelli as $livello) {
                            if ($livello == $id_livello){
                                //elimino qusto permesso al cassiere
                                $ret = DataManager::eliminaPermessoCassiere('rel_livello_cassiere', $livello, $cassiere_id);
                                //elimino il permesso dalla tabella cmd_lvello
                                $ret = DataManager::eliminaPermessoById($id_livello);
                                }
                            }
                        }

                    $ret = DataManager::eliminaPermessoCategoria('rel_livello_categoria',$categoria_id);

                    $ret = $gestore->delCategoria($categoria_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }

                }elseif($action == 'save'){

                        /*
                         * se nome_categoria_db non è 0 significa che è già presente nel db,
                         * quindi devo effettuare una modifica ad una categoria già esistente
                         *
                         */
                        $nome_categoria_db = $gestore->getCategoria($categoria_id);

                        if($nome_categoria_db==0){

                            /*
                             * categoria non presente, devo aggiungerlo
                             */
                            $ret = $gestore->addCategoria($categoria_id, $nome, $colore_bottone, $gestore_id);
                            if(!$ret){
                                $var['err'] = $ret;
                            }
                                                    

                        }else{

                            /*
                             * aggiorno la categoria
                             */
                            $ret = $gestore->editCategoria($categoria_id, $nome, $colore_bottone, $gestore_id);
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