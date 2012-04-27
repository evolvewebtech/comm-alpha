<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';
        require_once dirname(__FILE__) . '/../HTTPSession.php';

        $objSession = new HTTPSession();

        $nome                   = mysql_real_escape_string($_POST['tab_nome']);
        $prezzo                 = mysql_real_escape_string($_POST['tab_prezzo']);
        $iva                    = mysql_real_escape_string($_POST['tab_iva']);
        $colore_bottone         = mysql_real_escape_string($_POST['tab_colore_bottone']);
        $descrizione            = mysql_real_escape_string($_POST['tab_descrizione']);
        $apeso                  = intval(mysql_real_escape_string($_POST['tab_apeso']));
        $path_image             = "";
        $codice_prodotto        = mysql_real_escape_string($_POST['tab_codice_prodotto']);
        $quantita               = intval(mysql_real_escape_string($_POST['tab_quantita']));
        $gestore_id             = intval(mysql_real_escape_string($_POST['gestore_id']));
        $categoria_id           = intval(mysql_real_escape_string($_POST['tab_categoria_id']));
        $id                     = intval(mysql_real_escape_string($_POST['alimento_id']));
        $alimento_id            = intval(mysql_real_escape_string($_POST['tab_secondo_alimento_id']));
        $current_tab            = intval(mysql_real_escape_string($_POST['current_tab']));
        $action                 = mysql_real_escape_string($_POST['action']);
        
        //$ritardo                 = mysql_real_escape_string($_POST['ritardo']); //futuro
        
        $var = array("id"                   => $id,
                     "nome"                 => $nome,
                     "prezzo"               => $prezzo,
                     "iva"                  => $iva,
                     "colore_bottone"       => $colore_bottone,
                     "descrizione"          => $descrizione,
                     "apeso"                => $apeso,
                     "path_image"           => $path_image,
                     "codice_prodotto"      => $codice_prodotto,
                     "quantita"             => $quantita,
                     "gestore_id"           => $gestore_id,
                     "categoria_id"         => $categoria_id,
                     "alimento_id"          => $alimento_id,//alimento_composto
                     "current_tab"          => $current_tab,
                     "action"               => $action,
                     //"ritardo"              => $ritardo, //futuro
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

        if ($action == 'del'){

            /*
             * controllo chen l'alimento non sia stato associato a dei menu fissi
             */
            $ret = DataManager::appartieneMenuFisso($id);
            if (!$ret){//l'alimento non appartiene a un menu


                $alimento = new Alimento($id);

                $num_stampanti = $alimento->getNumberOfStampanti();
                //elimino la relazione con le stampanti
                for($j=0; $j<$num_stampanti; $j++) {
                    $stampante = $alimento->getStampante($j);
                    $stampante_id = $stampante->id;
                    $ret = $gestore->delAlimentoStampante($id,$stampante_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }                    
                }
                $variante = $alimento->getNumberOfVarianti();
                //elimino la relazione con le varianti
                for($j=0; $j<$alimento->getNumberOfVarianti(); $j++) {
                    $variante = $alimento->getVariante($j);
                    $variante_id = $variante->id;
                    $ret = $gestore->delAlimentoVariante($id,$variante_id);
                        if(!$ret){
                            $var['err'] = $ret;
                        }
                    
                }

                //elimino l'alimento
                $ret = $gestore->delAlimento($id);
                if(!$ret){
                    $var['err'] = $ret;
                }

            }else{
              /*
               * l'alimento appartiene ad un menù
               * NON POSSO ELIMINARLO
               */
               $var['err'] = 'E008';

            }


	}elseif($action == 'save'){

                /*
                 * se nome_alimento_db non è 0 significa che è già presente nel db,
                 * quindi devo effettuare una modifica ad un alimento già esistente
                 *
                 */
                $nome_alimento_db = $gestore->getAlimento($id);
              
                if($nome_alimento_db==0){
                
                    /*
                     * alimento non presente, devo aggiungerlo
                     *
                     */
                    $ret = $gestore->addAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                 $descrizione, $apeso, 'images/', $codice_prodotto,
                                 $quantita, $gestore_id, $categoria_id, $alimento_id);
                    if(!$ret){
                        $var['err'] = "false";
                    }

                }else{

                    /*
                     * aggiorno l'alimento
                     */
                    //var_dump("<br />EDIT");
                    $ret = $gestore->editAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                 $descrizione, $apeso, 'images/', $codice_prodotto,
                                 $quantita, $gestore_id, $categoria_id, $alimento_id);
                    if(!$ret){
                        $var['err'] = $ret;
                    }

                }//add or edit


            }//end del/save

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