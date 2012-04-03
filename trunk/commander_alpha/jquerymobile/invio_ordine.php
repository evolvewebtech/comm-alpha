<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
                
   
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        $next_id = DataManager2::nextIDOrdine();
        $seriale = $next_id;
        $n_coperti = $data['n_coperti'];
        $tavolo_id = $data['tavolo_id'];
        $buono_ser = $data['buono_ser'];
        $buono_cred_us = $data['buono_cred_us'];

        //Verifica se credito buono sufficiente
        $buono_ok = false;
        if ($buono_ser != "") {
            $buono = DataManager2::getBuonoPrepagatoAsObject($buono_ser);
            if ($buono->credito >= $buono_cred_us) {
                $nuovo_credito = $buono->credito - $buono_cred_us;
                $ret = DataManager::aggiornaBuonoPrepagato((int)$buono->id,
                                                           $buono->seriale,
                                                           $nuovo_credito,
                                                           $buono->nominativo,
                                                           (int)$buono->gestore_id);
                if ($ret) { $buono_ok = true; }
            }
        }
        else $buono_ok = true;
              
        //Query database
        if ($buono_ok) {
            $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);
        }
        else die();
        
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
