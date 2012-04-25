<?php

    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        
        $id = intval(mysql_real_escape_string($_POST['id']));
        
        //Array da passare con JSON  
        $arr = array(); 
        
        if ($id > 0) {
            $ordine = DataManager2::getOrdineAsObject($id);
            
            $arrRighe = array();
            
            for ($i=0; $i<$ordine->getNumberOfRigheOrdine(); $i++) {
                $rigaOrd = $ordine->getRigaOrdine($i);
                $riga = array();
                
                //Recupero nome alimento
                $alimTemp = DataManager2::getAlimentoAsObject($rigaOrd->alimento_id);
                
                //Recupero varianti alimento
                $arrVar = array();
                for ($j=0; $j<$rigaOrd->getNumberOfVarianti(); $j++) {
                    $variante = array(
                                "descrizione" => $rigaOrd->getVariante($j)->descrizione
                                //"prezzo" => $rigaOrd->getVariante($j)->prezzo 
                                );
                    $arrVar[$j] = $variante;
                }
                
                //Riga_ordine
                if ($alimTemp) {
                    $riga = array (
                        "nome"          => $alimTemp->nome,
                        "numero"        => $rigaOrd->numero,
                        "prezzo"        => $rigaOrd->prezzo,
                        "iva"           => $rigaOrd->iva,
                        "cassiere_id"   => $rigaOrd->cassiere_id,
                        "arrVar"        => $arrVar );
                }
                
                $arrRighe[$i] = $riga;
            }
            
            $arr = $arrRighe;
        }

        //Invio array con Ajax
        if ($arr){
            echo json_encode($arr);
        }else {
            echo json_encode("an error occurred");
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
    
?>
