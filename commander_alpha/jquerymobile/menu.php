<?php
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        //Query database
        $arCat = DataManager2::getAllCategoriesAsObjects();
        $arMenu = DataManager2::getAllMenuAsObjects();
        
        //Array da passare con JSON  
        $arr = array(); 
        
        
        //Creazione array Categorie
        if ($arCat){          
            $arrTempCat = array();
            
            for($i=0; $i<count($arCat); $i++) {
                //array alimenti
                $arrA = array();
                for($j=0; $j<$arCat[$i]->getNumberOfAlimenti(); $j++) {
                    $alim = $arCat[$i]->getAlimento($j);
                    
                    $arrV = array();
                    for($t=0; $t<$alim->getNumberOfVarianti(); $t++) {
                        $variante = $alim->getVariante($t);
                        $arrTemp2 = array(  "id"            => $variante->id,
                                            "descrizione"   => $variante->descrizione,
                                            "prezzo"        => $variante->prezzo);
                        $arrV[$t] = $arrTemp2;
                    }
                    
                    $arrTemp = array(   "id"    => $alim->id,
                                        "nome"  => $alim->nome,
                                        "prezzo"  => $alim->prezzo,
                                        "varianti" => $arrV);
                    $arrA[$j] = $arrTemp;
                }
                //array categorie
                $cat = array(   "id"                    => $arCat[$i]->id,
                                "nome"                  => $arCat[$i]->nome,
                                "colore_bottone_predef" => $arCat[$i]->colore_bottone_predef,
                                "alimenti"              => $arrA);
                
                $arrTempCat[$i] = $cat;
                $arr[0] = $arrTempCat;
            }
        }
        
        
        //Creazione array MenuFisso
        if ($arMenu){
            $arrTempMenu = array();  
            
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
                
                $arrTempMenu[$i] = $menu;
                $arr[1] = $arrTempMenu;
            }            
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

