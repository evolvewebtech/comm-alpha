<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();
        $objSession->Impress();

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

            $cassieri = $gestore->getAllCassiere();
            foreach ($cassieri as $cassiere) {
                $cassiere_id = $cassiere->id;
                $ret = DataManager2::aggiornaMenuAggiornato($cassiere_id, 0);
                }

            $alimenti_varianti = explode('&', $post);

            foreach ($alimenti_varianti as $alimento_variante){

                //$var['err'] = $var['err']."<hr />STAMPA: $alimento_variante<br />";

                $alimento_variante = str_replace('av', '', $alimento_variante);
                $alimento = explode('-', $alimento_variante);
                $alimentoID = $alimento[0];
                $variante = explode('=', $alimento[1]);
                $varianteID = $variante[0];
                $varianteSelezionata = $variante[1];


                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */

                $ret = DataManager::controllo_relazione($varianteID,$alimentoID,'rel_variante_alimento');
                //$vr = var_dump($ret);
                //$var['err'] = $var['err']."RET: [ $ret ] var selezionata: $varianteSelezionata";

                if ($ret){

                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */
                    if ($varianteSelezionata=='true'){
                        //echo "<br />non faccio nulla";
                        //$var['err'] = $var['err']." - Nulla";
                        ;
                    }else{
                        $ret = $gestore->delAlimentoVariante($alimentoID,$varianteID);
                        //$var['err'] = $var['err']." - Eliminno";
                        //echo "<br />Eliminno";
                        if(!$ret){
                            $var['err'] = $ret;
                        }
                    }
                }else{

                    /*
                     * la relazione non esiste: la creo se checked (true) altrimenti niente
                     */
                        if ($varianteSelezionata=='true'){
                            $ret = $gestore->addAlimentoVariante($alimentoID,$varianteID);
                            //$var['err'] = $var['err']." - aggiungo: -$alimentoID-$varianteID-";
                            //echo "<br />aggiungo";
                            if(!$ret){
                                $var['err'] = $ret;
                            }
                        }else{
                            //echo "<br />non faccio nulla";
                            //$var['err'] = $var['err']." - Nulla2";
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