<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager.php';
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
                
   
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        //ID del nuovo "ordine"
        $next_id = DataManager2::nextIDOrdine();
        $seriale = $next_id;
        $n_coperti = $data['n_coperti'];
        $tavolo_id = $data['tavolo_id'];
        $buono_ser = $data['buono_ser'];
        $buono_cred_us = $data['buono_cred_us'];

        //Verifica se credito buono sufficiente
        $buono_ok = false;
        if ($buono_ser != "") {
            //Recupero dati "Buono prepagato"
            $buono = DataManager2::getBuonoPrepagatoAsObject($buono_ser);
            if ($buono->credito >= $buono_cred_us) {
                //Inserimento relazione "BuonoOrdine"
                $ret = DataManager2::inserisciBuonoOrdine((int)$buono->id, $next_id);               
                if ($ret) {
                    $nuovo_credito = $buono->credito - $buono_cred_us;
                    //Aggiornamento del credito del buono utilizzato
                    $ret = DataManager::aggiornaBuonoPrepagato((int)$buono->id,
                                                            $buono->seriale,
                                                            $nuovo_credito,
                                                            $buono->nominativo,
                                                            (int)$buono->gestore_id);
                }
                if ($ret) { $buono_ok = true; }
            }
        }
        else $buono_ok = true;
              
        //Inserimento del nuovo ordine nel database
        if ($buono_ok) {
            $ret = DataManager2::inserisciOrdine('null', $seriale, $n_coperti, $tavolo_id);
        }
        else die();
        
        if($ret){
            for ($i=0; $i<count($data['alimenti']); $i++) {
                
                //ID del nuovo "ordine"
                $next_id_rigaordine = DataManager2::nextIDRigaOrdine();
                $ordine_id = $next_id;
                $alimento_id = $data['alimenti'][$i][0];
                $alimento_menu_id = 0;
                $numero = $data['alimenti'][$i][1];
                $prezzo = $data['alimenti'][$i][2];
                $iva = $data['alimenti'][$i][3];
                $cassire_id = 1;
                
                //Inserimento "RigaOrdine nel database"
                $ret = DataManager2::inserisciRigaOrdine('null', $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id);
                
                if ($ret) {
                    //Inserimento relazioni "VarianteRigaOrdine" nel database
                    for ($j=0; $j<count($data['alimenti'][$i][4]); $j++) {
                        $variante_id = $data['alimenti'][$i][4][$j];
                        DataManager2::inserisciVarianteRigaOrdine($variante_id, $next_id_rigaordine);
                    }
                } 
                    
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
