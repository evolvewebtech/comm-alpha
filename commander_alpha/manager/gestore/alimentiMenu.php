<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        $post = json_encode($_POST);
        $post = str_replace('"', '', $post);
        $post = str_replace(',', '&', $post);
        $post = str_replace(':', '=', $post);
        $post = str_replace('{', '', $post);
        $post = str_replace('}', '', $post);

        $var = array("post" => $post,
                     "err"  => '');

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

            $alimenti_menu = explode('&', $post);

            foreach ($alimenti_menu as $alimento_menu){

                $alimento_menu = explode('-', $alimento_menu);
                $alimento_menu_id = $alimento_menu[0];
                $menu = explode('=', $alimento_menu[1]);
                $menu_id = $menu[0];
                $menuSelezionato = $menu[1];

                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */
                $ret = DataManager::controllo_relazione($alimento_menu_id,$menu_id,'rel_alimento_stampante');

                if ($ret){
                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */
                    if ($menuSelezionato=='true'){
                        //echo "<br />non faccio nulla";
                        ;
                    }else{
                        $ret = $gestore->delAlimentoMenuAlimento($alimento_menu_id,$menu_id);
                        //echo "<br />Eliminno";
                    }

                }else{
                    /*
                     * la relazione non esiste, la creo se checked (true)altrimenti niente
                     */
                        if ($menuSelezionato=='true'){
                            $ret = $gestore->addAlimentoMenuAlimento($alimento_menu_id,$menu_id);
                            //echo "<br />aggiungo";
                        }else{
                            //echo "<br />non faccio nulla";
                            ;
                        }

                }

                //echo "<br />alimento ID: $alimentoID<br />stampante ID: $stampanteID<br/>selezionata: $StamapanteSelezionata<br />";
            }//end foreach

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