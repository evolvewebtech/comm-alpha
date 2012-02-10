<?php
    try
    {
        require_once dirname(__FILE__) . '/../DataManager.php';

        $nome_sala = mysql_real_escape_string($_POST['tab_title']);
        $n_tavoli = mysql_real_escape_string($_POST['tab_ntavoli']);
        $sala_id = mysql_real_escape_string($_POST['sala_id']);
        $current_tab = mysql_real_escape_string($_POST['current_tab']);
        $action = mysql_real_escape_string($_POST['action']);

        $var = array("nome"=>$nome_sala,
                     "n_tavoli"=>$n_tavoli,
                     "id"=>$sala_id,
                     "current_tab"=>$current_tab,
                     "action"=>$action);

        if ($action == 'del'){
            
            $ret = DataManager::delSala($sala_id);
            if(!$ret){
                die('Error.');
            }
            
        }elseif($action == 'save'){
            $ret = DataManager::addSala($sala_id, $nome_sala, 'NULL');
            if(!$ret){
                die('Error.');
            }
        }
        echo json_encode($var);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
