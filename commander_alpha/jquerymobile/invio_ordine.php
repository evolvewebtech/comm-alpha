<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
                
   
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        $seriale = $data['seriale'];
        $n_coperti = $data['n_coperti'];
        $tavolo_id = $data['tavolo_id'];
        
        $var = array("seriale"      =>$data['seriale'],
                     "n_coperti"    =>$data['n_coperti'],
                     "tavolo_id"    =>$data['tavolo_id']);
              
        //$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
        //$phpdate = strtotime( $mysqldate );
        
        //Query database
        $ret = DataManager2::inserisciOrdine('null', $seriale, '', $n_coperti, $tavolo_id);
        
        if($ret){
            for ($i=0; $i<count($data['alimenti']); $i++) {

                $ordine_id = 1;
                $alimento_id = $data['alimenti'][$i];
                $alimento_menu_id = 0;
                $numero = 1;
                $prezzo = 0;
                $iva = 0;
                $cassire_id = 1;
                
                //Query database
                $ret = DataManager2::inserisciRigaOrdine('null', $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id);
                
                if (!$ret) {
                    die();
                }
            }
        }
        else {
            die();
        }

        echo json_encode($var);
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>
