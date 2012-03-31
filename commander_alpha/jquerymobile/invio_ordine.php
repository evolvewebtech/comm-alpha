<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
                
   
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        $next_id = DataManager2::nextIDOrdine();
        $seriale = $next_id;
        $n_coperti = $data['n_coperti'];
        $tavolo_id = $data['tavolo_id'];
        
        //Query database
        $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);
        
        if($ret){
            for ($i=0; $i<count($data['alimenti']); $i++) {

                $ordine_id = $next_id;
                $alimento_id = $data['alimenti'][$i][0];
                $alimento_menu_id = 0;
                $numero = $data['alimenti'][$i][1];
                $prezzo = $data['alimenti'][$i][2];
                $iva = $data['alimenti'][$i][3];
                $cassire_id = 1;
                
                //Query database
                $ret = DataManager2::inserisciRigaOrdine('null', $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id);
                
                if (!$ret) {
                    die();
                }
            }
            //Query database
            $ret = DataManager2::inserisciOrdineChiuso('null', $next_id);
            if (!$ret) {
                die();
            }
        }
        else {
            die();
        }

        echo json_encode($next_id);
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
