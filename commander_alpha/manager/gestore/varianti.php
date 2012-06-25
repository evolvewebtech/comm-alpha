<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';
        $objSession = new HTTPSession();
        $objSession->Impress();

        $descrizione            = mysql_real_escape_string($_POST['tab_descrizione']);
        $prezzo                 = mysql_real_escape_string($_POST['tab_prezzo']);
        $iva                    = intval(mysql_real_escape_string($_POST['tab_iva']));
        $variante_id            = intval(mysql_real_escape_string($_POST['variante_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);

        $var = array("descrizione"          => $descrizione,
                     "prezzo"               => $prezzo,
                     "iva"                  => $iva,
                     "variante_id"          => $variante_id,
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

            $ret = $gestore->delVariante($variante_id);
            if(!$ret){
                $var['err'] = $ret;
            }

	}elseif($action == 'save'){

                $nome_variante_db = $gestore->getVariante($variante_id);
                

                if($nome_variante_db==0){


                    $ret = $gestore->addVariante($variante_id, $descrizione, $prezzo, $iva, $gestore_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }


                }else{

                    $ret = $gestore->editVariante($variante_id, $descrizione, $prezzo, $iva, $gestore_id);
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