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

            $alimenti_stampanti = explode('&', $post);

            foreach ($alimenti_stampanti as $alimento_stampante){

                $alimento = explode('-', $alimento_stampante);
                $alimentoID = $alimento[0];
                $stampante = explode('=', $alimento[1]);
                $stampanteID = $stampante[0];
                $stamapanteSelezionata = $stampante[1];

                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */
                $ret = DataManager::controllo_relazione($alimentoID,$stampanteID,'rel_alimento_stampante');

                if ($ret){
                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */
                    if ($stamapanteSelezionata=='true'){
                        //echo "<br />non faccio nulla";
                        ;
                    }else{
                        $ret = $gestore->delAlimentoStampante($alimentoID,$stampanteID);
                        if(!$ret){
                            $var['err'] = $ret;
                        }
                        //echo "<br />Eliminno";
                    }

                }else{
                    /*
                     * la relazione non esiste, la creo se checked (true)altrimenti niente
                     */
                        if ($stamapanteSelezionata=='true'){
                            $ret = $gestore->addAlimentoStampante($alimentoID,$stampanteID);
                            if(!$ret){
                                $var['err'] = $ret;
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