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

            $menu_nomeCat = explode('&', $post);

            foreach ($menu_nomeCat as $menu_nomeCategoria){

                /*
                 * 2-Primi=true
                 *
                 * menuID=2
                 * nomeCat=Primi
                 * nomeCatSelezionata=true
                 */
                $menu = explode('-', $menu_nomeCategoria);
                $menuID = $menu[0];
                $nomeCategoria = explode('=', $menu[1]);
                $nomeCat = $nomeCategoria[0];
                $nomeCatSelezionata = $nomeCategoria[1];

                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */
                $ret = DataManager::controllo_relazione($menuID,$nomeCat,'cmd_alimento_menu');
                //$vr = var_dump($ret);
                $var['err'] = $var['err'].'<br />'.$nomeCat.' --- '."RET: [ $ret ] var selezionata: $nomeCatSelezionata";

                if ($ret){

                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */

                    if ($nomeCatSelezionata=='true'){
                        //echo "<br />non faccio nulla";
                       $var['err'] = $var['err']." - Nulla";
                        ;
                    }else{
                /*
                 *
                 *   il problema è qui
                 */
                        //$ret = $gestore->delAlimentoVariante($alimentoID,$varianteID);
                        $ret = DataManager::cancellaNomeCatMenu($menuID, $nomeCat);
                        $var['err'] = $var['err']." - Eliminno - return: $ret";
                        //echo "<br />Eliminno";
                    }
                }else{

                    /*
                     * la relazione non esiste: la creo se checked (true) altrimenti niente
                     */
                        if ($nomeCatSelezionata=='true'){
                            //$ret = $gestore->addNomeCatMenu($menufisso_id,$nomeCat);
                            $ret = DataManager::inseriscinomeCatMenu('NULL',$menuID, $nomeCat);
                            //$var['err'] = $var['err']." - aggiungo: -$menuID-$nomeCat- return: $ret";
                            
                        }else{
                            //echo "<br />non faccio nulla";
                            $var['err'] = $var['err']." - Nulla2";
                            ;
                        }

                }


                //echo "<br />alimento ID: $alimentoID<br />variante ID: $varianteID<br/>selezionata: $StamapanteSelezionata<br />";
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