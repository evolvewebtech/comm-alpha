<?php
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        $arMenu = DataManager2::getAllMenuAsObjects();
        
        if ($arMenu){
            
            //Creazione array da passare con JSON
            $arr = array();           
            for($i=0; $i<count($arMenu); $i++) {
                //array categorie
                $arrC = array();
                for($j=0; $j<$arMenu[$i]->getNumberOfCategorie(); $j++) {
                    $cat = $arMenu[$i]->getCategoria($j);
                    
                    $arrA = array();
                    for($t=0; $t<$cat->getNumberOfAlimenti(); $t++) {
                        $alim = $cat->getAlimento($t);
                        
                        $arrV = array();
                        for($s=0; $s<$alim->getNumberOfVarianti(); $s++) {
                            $variante = $alim->getVariante($s);
                            $arrTemp3 = array(  "id"            => $variante->id,
                                                "descrizione"   => $variante->descrizione);
                            $arrV[$s] = $arrTemp3;
                        }
                        
                        $arrTemp2 = array(  "id"     => $alim->id,
                                            "nome"   => $alim->nome,
                                            "varianti" => $arrV);
                        $arrA[$t] = $arrTemp2;
                    }
                    
                    $arrTemp = array(   "id"    => $cat->id,
                                        "nome_cat"  => $cat->nome_cat,
                                        "alimenti" => $arrA);
                    $arrC[$j] = $arrTemp;
                }
                //array menu
                $menu = array(  "id"            => $arMenu[$i]->id,
                                "nome"          => $arMenu[$i]->nome,
                                "prezzo"        => $arMenu[$i]->prezzo,
                                "descrizione"   => $arMenu[$i]->descrizione,
                                "categorie"     => $arrC);
                
                $arr[$i] = $menu;
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

