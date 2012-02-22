<?php
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        $arCat = DataManager2::getAllCategoriesAsObjects();
        
        if ($arCat){
            
            //Creazione array da passare con JSON
            $arr = array();           
            for($i=0; $i<count($arCat); $i++) {
                
                $arrAlim = array();
                for($j=0; $j<$arCat[$i]->getNumberOfAlimenti(); $j++) {
                    $alim = $arCat[$i]->getAlimento($j);
                    $arrTemp = array(   "id"    => $alim->id,
                                        "nome"  => $alim->nome,
                                        "prezzo"  => $alim->prezzo);
                    $arrAlim[$j] = $arrTemp;
                }
                
                $var = array(   "id"                    => $arCat[$i]->id,
                                "nome"                  => $arCat[$i]->nome,
                                "colore_bottone_predef" => $arCat[$i]->colore_bottone_predef,
                                "alimenti"              => $arrAlim);
                
                $arr[$i] = $var;
            }
            
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

