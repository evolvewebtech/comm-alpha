<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        /* formatto la stringa ricevuta in ingresso */
        $post = json_encode($_POST);

        $cassiere_id = intval(mysql_real_escape_string($_POST['cassiere_id']));
        $permessi = array();
        $permessi    = json_decode((($_POST['permessi'])));
        $action      = (mysql_real_escape_string($_POST['action']));

        $var = array("post"       => $post,
                     "permessi"   => $permessi,
                     "gestore_id" => '',
                     "cassiere"   => $cassiere_id,
                     "action"     => $action,
                     "err"        => '');

        $var['gestore_id'] = $gestore->id;
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

            foreach ($permessi as $livello => $permesso){

                /*
                 * se la relazione esiste già non faccio controllo se devo eliminarla (false) oppure non far nulla,
                 * altrimenti se non esiste controllo se true la aggiungo mentre se false non faccio nulla
                 */
                $ret = DataManager::controllo_relazione($livello,$cassiere_id,'rel_livello_cassiere');
                if ($ret==true){
                    $var['err'] = '';
                    /*
                     * la relazione esiste già. se checked (true) non faccio niente altrimenti la elimino.
                     */
                    if ($permesso){
                        $var['err'] = '';
                        ;
                    }else{
                        $ret = $gestore->eliminaPermesso($livello,$cassiere_id);
                        if(!$ret){
                            $var['err'] = '';
                        }
                    }
                } else{
                    /*
                     * la relazione non esiste, la creo se checked (true)altrimenti niente
                     */
                    if ($permesso){
                        $ret = $gestore->aggiungiPermesso($livello,$cassiere_id);
                        if(!$ret){
                            $var['err'] = '';
                        }
                      }else{
                        ;
                    }
                }
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