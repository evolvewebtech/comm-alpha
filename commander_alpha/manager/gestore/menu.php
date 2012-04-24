<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';
        $objSession = new HTTPSession();

        $nome                   = mysql_real_escape_string($_POST['tab_nome']);
        $descrizione            = mysql_real_escape_string($_POST['tab_descrizione']);
        $prezzo                 = mysql_real_escape_string($_POST['tab_prezzo']);
        $iva                    = intval(mysql_real_escape_string($_POST['tab_iva']));
        $menu_id                = intval(mysql_real_escape_string($_POST['menu_id']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);

        $var = array("nome"                 => $nome,
                     "descrizione"          => $descrizione,
                     "prezzo"               => $prezzo,
                     "iva"                  => $iva,
                     "menu_id"              => $menu_id,
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

//        $action = 'del';
//        $menu_id = 2;

        if ($action == 'del'){

            $menu = new MenuFisso($menu_id);
            $num_cat = $menu->getNumberOfCategorie();

            //elimino la relazione con le stampanti
            for($j=0; $j<$num_cat; $j++) {
                $categoria = $menu->getCategoria($j);
                $categoria_nome = $categoria->nome_cat;
                //devo eliminare anche le relazioni con le categorie
                $ret = DataManager::cancellaNomeCatMenu($menu_id, $categoria_nome);
                if(!$ret){
                    $var['err'] = $ret;
                }
            }


            //elimino il menu
            //$ret = $gestore->delMenufisso($menu_id);
            if(!$ret){
                $var['err'] = $ret;
            }

	}elseif($action == 'save'){

                $nome_menu_db = $gestore->getMenufisso($menu_id);

                if($nome_menu_db==0){


                    $ret = $gestore->addMenufisso($menu_id, $nome, $prezzo, $iva, $descrizione, $gestore_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }


                }else{

                    $ret = $gestore->editMenufisso($menu_id, $nome, $prezzo, $iva, $descrizione, $gestore_id);
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