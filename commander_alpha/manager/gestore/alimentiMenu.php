<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        /* formatto la stringa ricevuta in ingresso */
        $post0 = json_encode($_POST);
        $post = str_replace('"', '',  $post0);
        $post = str_replace(',', '&', $post);
        $post = str_replace(':', '=', $post);
        $post = str_replace('{', '',  $post);
        $post = str_replace('}', '',  $post);

        $var = array("post" => $post,
                     "gestore_id" =>$menu_id,
                     "menu_id"=>'',
                     "err"  => '');

        //$var['err'] = '<br />'.$post.'<br />';

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

            $var['gestore_id'] = $gestore->id;

            $alimenti_menu = explode('&', $post);
            $menu = array_shift($alimenti_menu);//il primo elemento è l'id del menu

            $menu = explode('=', $menu);
            $menu_id = intval($menu[1]);

            foreach ($alimenti_menu as $alimento_menu){

                $alimento_menu = explode('-', $alimento_menu);
                $alimento_menu_id = intval($alimento_menu[0]);
                $menu = explode('=', $alimento_menu[1]);
                $alimento_id = intval($menu[0]);
                $menuSelezionato = $menu[1];

                //$var['err'].= '<hr/>alimento_menu_id: '.$alimento_menu_id;
                //$var['err'].= '<br />alimento_id: '.$alimento_id;
                //$var['err'].= '<br />menu_id: '.$menu_id;


                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */
                $ret = DataManager::controllo_relazione($alimento_menu_id,$alimento_id,'rel_alimentomenu_alimento');
                //$ret2 = ($ret)?'true':'false';
                //$var['err'] .= '<br />ret: '.$ret2.'<br />';
                
                if ($ret==true){
                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */
                    if ($menuSelezionato=='true'){
                        //echo "<br />non faccio nulla";
                        ;
                    }else{
                        $ret = $gestore->delAlimentoMenuAlimento($alimento_menu_id,$alimento_id);
                        if(!$ret){
                            $var['err'] .= $alimento_menu_id.'-'.$alimento_id.'false';
                        }
                        //echo "<br />Eliminno";
                    }

                } else{
                    /*
                     * la relazione non esiste, la creo se checked (true)altrimenti niente
                     */
                        if ($menuSelezionato=='true'){
                            $ret = $gestore->addAlimentoMenuAlimento($alimento_menu_id,$alimento_id);
                            if(!$ret){
                                $var['err'] .= $alimento_menu_id.'-'.$alimento_id.'false';
                            }
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