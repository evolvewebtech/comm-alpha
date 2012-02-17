<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';

        $numero_tavolo = mysql_real_escape_string($_POST['numero_tavolo']);
        $nome_tavolo = mysql_real_escape_string($_POST['tab_title']);
        $ncoperti = intval(mysql_real_escape_string($_POST['tab_nmax_coperti']));
        $sala_id = intval(mysql_real_escape_string($_POST['sala_id']));
        $tavolo_id = intval(mysql_real_escape_string($_POST['tavolo_id']));
        $current_tab = intval(mysql_real_escape_string($_POST['current_tab']));
        $action = mysql_real_escape_string($_POST['action']);

        $var = array("nome"        =>$nome_tavolo,
                     "ncoperti"    =>$ncoperti,
                     "tavolo_id"   =>$tavolo_id,
                     "sala_id"     =>$sala_id,
                     "current_tab" =>$current_tab,
                     "action"      =>$action,
                     "err"         =>'');

        if ($action == 'del'){

            $ret = DataManager::delTavolo($tavolo_id);
            if(!$ret){
                die('Error.');
            }

	}elseif($action == 'save'){

            /*
             * se nome_tavolo_db non è 0 significa che è già presente nel db,
             * quindi devo effettuare una modifica ad un tavolo già esistente
             *
             */
            $nome_tavolo_db = DataManager::getTavolo($tavolo_id);
            if($nome_tavolo_db==0){

                /*
                 * tavolo non presente, devo aggiungerlo
                 */
                $ret = DataManager::addTavolo($tavolo_id, $nome_tavolo, $numero_tavolo, $ncoperti, 'NULL', $sala_id);
            
                
            }else{
                /*
                 * aggiorno il tavolo
                 */
                $ret = DataManager::editTavolo($tavolo_id, $nome_tavolo, $numero_tavolo, $ncoperti, 'NULL', $sala_id);
                if(!$ret){
                    die();
                }

            }

        }//end del/save
        echo json_encode($var);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
