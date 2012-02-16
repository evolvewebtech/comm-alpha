<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';

        $nome_sala = mysql_real_escape_string($_POST['tab_title']);
        $n_tavoli = intval(mysql_real_escape_string($_POST['tab_ntavoli']));
        $sala_id = intval(mysql_real_escape_string($_POST['sala_id']));
        $current_tab = intval(mysql_real_escape_string($_POST['current_tab']));
        $action = mysql_real_escape_string($_POST['action']);

        $var = array("nome"        =>$nome_sala,
                     "n_tavoli"    =>$n_tavoli,
                     "id"          =>$sala_id,
                     "current_tab" =>$current_tab,
                     "action"      =>$action,
                     "err"         =>'');

        if ($action == 'del'){
            
            $ret = DataManager::delSala($sala_id);
            if(!$ret){
                die('Error.');
            }
            
	}elseif($action == 'save'){

            /*
             * cerco l'id con valore massimo nella
             * tabella cmd_tavolo. Poi aggiungo nuovi tavoli a partire da queell'id
             *
             * ogni tavolo è inserito nella seguente forma (vedi "for" succesivi):
             * id:           incrementale a partitee da max_id
             * nome:         numero da 1 a n_tavoli
             * numero:       numero da 1 a n_tavoli
             * nmax_coperti: 4 (valore di defolut, modificabile in seguito)
             * posizione:    null
             * sala_id:      id della sala associata
             *
             */            

            /*
             * se nome_sala_db non è null significa che è già presente nel db,
             * quindi devo effettuare una modifica ad una sala già esistente
             *
             */
            $nome_sala_db = DataManager::getSala($sala_id);
            if($nome_sala_db==0){

		$ret = DataManager::addSala($sala_id, $nome_sala, 'NULL');
                $max_id_table = DataManager::getMAXID('cmd_tavolo');
                $max_id_table++;
                for ($i = 1; $i <= $n_tavoli; $i++) {
                    $ret2 = DataManager::addTavolo($max_id_table, 'NULL', $i, 4, 'NULL', $sala_id);
                    $max_id_table++;
                }

            }else{
                /*
                 * aggiorno la sala
                 */
                $ret = DataManager::editSala($sala_id, $nome_sala, 'NULL');
                if(!$ret){
                    die();
                }
                
                /*
                 * aggiorno il numero di tavoli
                 */
                $tavoli = DataManager::getAllTavoloBySalaID($sala_id);
                $num_tavoli = count($tavoli);
                $num_tavoli = intval($num_tavoli);
                $diff = $n_tavoli - $num_tavoli;
                $diff = intval($diff);
                if ($diff < 0){
                    /*
                     * il numero dei tavoli è diminuito. Azione illegale.
                     * Devo sapere quali eliminare.
                     */
                    $var['err'] = 'E005';

                }elseif ($diff == 0){
                    /*
                     * il numero dei tavoli è invariato, non faccio niente
                     */
                    ;
               }elseif ($diff > 0) {
                     /*
                      * il numero dei tavoli è aumentato, aggiorno.
                      * Li inserisco in fondo alla tabella.
                      */
                     $max_id_table2 = DataManager::getMAXID('cmd_tavolo');
                     $max_id_table2++;
                     for ($i = 1; $i <= $diff; $i++) {
                         $ret2 = DataManager::addTavolo($max_id_table2, 'NULL', $i, 4, 'NULL', $sala_id);
                         $max_id_table2++;
                      }

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
