<?php
    try {
        require_once dirname(__FILE__).'/../manager/DataManager2.php';
        $arCat = DataManager2::getAllCategoriesAsObjects();
        
        if ($arCat){
            echo json_encode($arCat);
        }else {
            echo json_encode("an error occurred");
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
        // Note: Log the error or something
    }
?>

