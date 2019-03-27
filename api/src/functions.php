<?php

include("localDB.php");
//Get database connection
function getDB(){
    global $hostname,$dbname,$username,$password;
    $host   = $hostname;
    $dbname = $dbname;
    $user   = $username;
    $pass   = $password;
    
    $pdo = new PDO("dblib:host=$host;dbname=$dbname","$user","$pass");
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

/***** Update User Access Function *****/
function accessColumnName($db_index){
    if($db_index == 1){
        $column_name = 'member_info';
    }elseif($db_index == 2){
        $column_name = 'attendance';
    }elseif($db_index == 3){
        $column_name = 'schedule';
    }elseif($db_index == 4){
        $column_name = 'salary';
    }
    //  $column_name = 'history_mgt';
    // }
    return $column_name;
}

// ///Get Product Type/Category/Applied Area Array
// function getAllProdType($tablename, $usage){

//     $ReturnArray = array();
//     $dbRemote   = getDB();
//     $name       = "";
    
//     if ($tablename == 'ProductAppliedArea'){
//             $name   = 'product_applied_name';
//             $id     = 'product_applied_id';
//     }
//     elseif($tablename == 'ProductType'){
//             $name = 'product_type_name';
//             $id     = 'product_type_id';
//     }
//     elseif($tablename == 'ProductCategories'){
//             $name = 'product_categories_name';
//             $id     = 'product_categories_id';
//     }
//     elseif($tablename == 'ProductPreference'){
//             $name = 'product_preference_name';
//             $id     = 'product_preference_id';
//     }
//     elseif($tablename == 'ProductTextures'){
//             $name = 'product_textures_name';
//             $id     = 'product_textures_id';
//     }
//     elseif($tablename == 'GeneralSkinCondition'){
//             $name = 'general_skin_name';
//             $id     = 'general_skin_id';
//     }
//     elseif($tablename == 'SkinType'){
//             $name = 'skin_type_name';
//             $id     = 'skin_type_id';
//     }
//     elseif($tablename == 'BK_ProductLink_Category'){
//             $name = 'prodlink_category_name';
//             $id     = 'prodlink_category_id';
//     }
//     elseif($tablename == 'BK_PriceType'){
//             $name = 'pricetype_name';
//             $id     = 'pricetype_id';
//     }
//     elseif($tablename == 'BK_Language_Category'){
//             $desc = 'language_description';
//             $name = 'language_name';
//             $id     = 'language_id';
//     }
    
//     if($tablename == 'GeneralSkinCondition') $stmt = $dbRemote->query("SELECT * FROM $tablename ORDER BY general_ui_order asc");
//     else $stmt = $dbRemote->query("SELECT * FROM $tablename");
//     $importData = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $countData = count($importData);
//     $ctr = 0;
//     if($usage == 'idname' || $usage == 'mt'){
//     /* foreach($importData as $item){
//             $ReturnArray[$ctr] = array($item[$id]=>$item[$name]);
//             $ctr++;
//         } */
//         $ReturnArray = $importData;
//         if ($usage == 'mt'){
//             $ProductDetails = array();
//             $ProductDetails['TotalMatches'] = $countData;
//             $ProductDetails['item']  =  $ReturnArray;
//             $ReturnArray = $ProductDetails;
//         }
//     }
//     else{
//         foreach($importData as $item){
//             $ReturnArray[$ctr] = $item[$name];
//             $ctr++; 
//         }
    
//     }
//     return $ReturnArray;
    
//     /*$ProductDetails = array();
//     $ProductDetails['TotalMatches'] = $countData;
//     $ProductDetails['item']  =  $ReturnArray;
//     return $ProductDetails;*/ //lestercode be5022c3   maintenance_pagination
// }
// For Ignore Category ONLY
// function getAllICProdType($tablenamed, $usaged){
//     $ReturnArray = array();
//     $dbRemote   = getDB();
//     $cate       = "";
//     $lang       = "";
    
//     if($tablenamed == 'BK_IgnoreProduct_Category'){
//             $lang = 'language';
//             $cont = 'ignore_content';
//             $subj = 'ignore_subject';
//             $cate = 'ignore_category';
//             $id     = 'ignore_id';
//     }
//     // if($tablename == 'BK_IgnoreProduct_Category') $stmt = $dbRemote->query("SELECT * FROM $tablename ORDER BY ignore_id asc");
//     // else 
//     $stmt = $dbRemote->query("SELECT * FROM $tablenamed");
//     $importData = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $countData = count($importData);
//     $ctr = 0;
//     if($usaged == 'mtic'){
//      foreach($importData as $item){
//             $ReturnArray[$ctr] = array($item[$id]=>$item[$name]);
//             $ctr++;
//         } 
//         $ReturnArray = $importData;
//         if ($usaged == 'mtic'){
//             $ProdDetails = array();
//             $ProdDetails['TotalMatches'] = $countData;
//             $ProdDetails['item']  =  $ReturnArray;
//             $ReturnArray = $ProdDetails;
//         }
//     }
//     else{
//         foreach($importData as $item){
//             $ReturnArray[$ctr] = $item[$cate];
//             $ReturnArray[$ctr] = $item[$lang];
//             $ctr++; 
//         }
    
//     }
//     return $ReturnArray;
//     /*$ProductDetails = array();
//     $ProductDetails['TotalMatches'] = $countData;
//     $ProductDetails['item']  =  $ReturnArray;
//     return $ProductDetails;*/ //lestercode be5022c3   maintenance_pagination
// }

//Update Product Items
// function UpdateProductItems($tblname, $id, $body_array){
        
//         if($tblname == 'areas'){
//             $tblNew = 'ScP_ProductAppliedArea';
//             $idString = "product_applied_id";
//         }
//         elseif($tblname == 'preference'){
//             $tblNew = 'ScP_ProductPreference';
//             $idString = "product_preference_id";
//         }
//         elseif($tblname == 'skintype'){
//             $tblNew = 'ScP_SkinType';
//             $idString = "skin_type_id";
//         }
//         elseif($tblname == 'condition'){
//             $tblNew = 'ScP_GeneralSkinCondition';
//             $idString = "general_skin_id";
//         }
//         elseif($tblname == 'texture'){
//             $tblNew = 'ScP_Texture';
//             $idString = "texture_id";
//         }

//         $StringOfValues = "";
//         $dbRemote   = getDB();
        
//         foreach($body_array as $key => $value){
//             $StringOfValues = $StringOfValues."(".$id.",".$value."), ";
//         }
//         $StringOfValues = trim($StringOfValues, ", ");
        
//         $sql_query = "INSERT INTO ".$tblNew." (skpid, ".$idString.") VALUES ".$StringOfValues;
//         $dbDelete = $dbRemote->prepare("DELETE FROM ".$tblNew." WHERE skpid=$id");
//         $dbDelete->execute();
        
//         $stmt = $dbRemote->prepare($sql_query);
//         $stmt->execute();
        
//     }

// //**converts recursively all values of an array to UTF8
// function utf8_converter($array)
// {
//     array_walk_recursive($array, function(&$item, $key){
//         if(!mb_detect_encoding($item, 'utf-8', true)){
//                 $item = utf8_encode($item);
//         }
//     });
 
//     return $array;
// }

// //***Insert Mnt Table

// function  insertMnt ($tableName, $Name, $Desc){
//     $dbRemote = getDB();
    
//     if($tableName == 'ProductCategories'){
//         $name = 'product_categories_name';
//     }else if($tableName == 'ProductAppliedArea'){
//         $name = 'product_applied_name';
//     }else if($tableName == 'GeneralSkinCondition'){
//         $name = 'general_skin_name';
//     }else if($tableName == 'SkinType'){
//         $name = 'skin_type_name';
//     }else if($tableName == 'ProductPreference'){
//         $name = 'product_preference_name';
//     }else if($tableName == 'ProductTextures'){
//         $name = 'product_textures_name';
//     }else if($tableName == 'ProductType'){
//         $name = 'product_type_name';
//     }else if($tableName == 'BK_ProductLink_Category'){
//         $name = 'prodlink_category_name';
//     }else if($tableName == 'BK_PriceType'){
//         $name = 'pricetype_name';
//     }else if($tableName == 'BK_Language_Category'){
//         $name = 'language_name';
//         $desc = 'language_description';
//     }
    
//     $sql1 = "SELECT * FROM $tableName WHERE $name = '$Name'";
//     $stmt = $dbRemote->query($sql1);
//     $sql1Fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $sql1Count = count($sql1Fetch);
    
//     if($sql1Count != 0){
//         echo "Existing";
//     }else{
//         if($tableName == 'BK_Language_Category'){
//             $sql2 = "INSERT INTO $tableName ($name, $desc) VALUES ('$Name', '$Desc')";
//         }elseif($tableName == 'BK_ProductLink_Category'){
//             $sql2 = "INSERT INTO $tableName ($name) VALUES ('$Name')";
//             $stmt = $dbRemote->prepare($sql2);
//             $stmt->execute();
//             $sqlID = "SELECT TOP 1 prodlink_category_id FROM $tableName ORDER BY prodlink_category_id DESC";
//             $stmt = $dbRemote->prepare($sqlID);
//             $stmt->execute();
//             $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
//             $prodlink_id = $dbval['prodlink_category_id'];
//             $prodlink_value = $dbval['prodlink_category_id'] - 1;
//             $sql2 = "UPDATE $tableName SET prodlink_category_value = $prodlink_value WHERE prodlink_category_id = $prodlink_id";
//             $stmt = $dbRemote->prepare($sql2);
//             $stmt->execute();
//         }elseif($tableName == 'GeneralSkinCondition'){
//             $sqlID = "SELECT TOP 1 general_ui_order FROM $tableName ORDER BY general_ui_order DESC";
//             $stmt = $dbRemote->prepare($sqlID);
//             $stmt->execute();
//             $dbval = $stmt->fetch(PDO::FETCH_ASSOC);
//             $genskinNewOrder_id = $dbval['general_ui_order'] + 1;
//             $sql2 = "INSERT INTO $tableName ($name, general_ui_order) VALUES ('$Name', '$genskinNewOrder_id')";
//         }else{
//             $sql2 = "INSERT INTO $tableName ($name) VALUES ('$Name')";  
//         }
//         if($tableName != 'BK_ProductLink_Category'){
//             $stmt = $dbRemote->prepare($sql2);
//             $stmt->execute();
//         }
//         return "Success";
//     }
// }
// // FOR IGNORE CATEGORY
// function  insertMntIC ($tableNamed, $Cate, $Subj, $Cont, $Lang, $dblocal){
//     $db_local =$dblocal;
    
//     if($tableNamed == 'BK_emailContent_Category'){
//         $cate = 'ec_category';
//         $subj = 'ec_subject';
//         $cont = 'ec_content';
//         $lang = 'ec_language';
//     }
    
//     $sql8 = "SELECT * FROM $tableNamed WHERE $cate = '$Cate' AND $lang = '$Lang'";
//     $stmt = $dblocal->query($sql8);
//     $sql8Fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $sql8Count = count($sql8Fetch);
    
//     if($sql8Count != 0){
//         echo "Existing";
//     }else{
//         if($tableNamed == 'BK_emailContent_Category'){
//             $sql9 = "INSERT INTO BK_emailContent_Category (ec_category, ec_subject, ec_content, ec_language) VALUES ('$Cate', '$Subj', '$Cont', '$Lang')";
//         }
//         else{
//             $sql9 = "INSERT INTO BK_emailContent_Category (ec_category) VALUES ('$Cate')";  
//         }
//         if($tableNamed != 'BK_ProductLink_Category'){
//             $stmt =  $dblocal->prepare($sql9);
//             $stmt->execute();
//         }
//         return "Success";
//     }
// }

// //***Update Mnt Table

// function updateMnt ($tableName, $getID, $newName, $newDesc){
//     $dbRemote = getDB();
    
//     if($tableName == 'ProductCategories'){
//         $id  = 'product_categories_id';
//         $name = 'product_categories_name';
//     }else if($tableName == 'ProductAppliedArea'){
//         $id = 'product_applied_id';
//         $name = 'product_applied_name';
//     }else if($tableName == 'GeneralSkinCondition'){
//         $id = 'general_skin_id';
//         $name = 'general_skin_name';
//     }else if($tableName == 'SkinType'){
//         $id = 'skin_type_id';
//         $name = 'skin_type_name';
//     }else if($tableName == 'ProductPreference'){
//         $id = 'product_preference_id';
//         $name = 'product_preference_name';
//     }else if($tableName == 'ProductTextures'){
//         $id = 'product_textures_id';
//         $name = 'product_textures_name';
//     }else if($tableName == 'ProductType'){
//         $id = 'product_type_id';
//         $name = 'product_type_name';
//     }else if($tableName == 'BK_ProductLink_Category'){
//         $id = 'prodlink_category_id';
//         $name = 'prodlink_category_name';
//     }else if($tableName == 'BK_PriceType'){
//         $id = 'pricetype_id';
//         $name = 'pricetype_name';
//     }else if($tableName == 'BK_Language_Category'){
//         $id = 'language_id';
//         $name = 'language_name';
//         $desc = 'language_description';
//     }
    
//     $sql3 = "SELECT * FROM $tableName WHERE $name = '$newName'";
//     $stmt = $dbRemote->query($sql3);
//     $sql3Fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $sql3Count = count($sql3Fetch);

//     if($sql3Count != 0){
//         echo "Existing";
//     }else{  
//         if($tableName == 'BK_Language_Category'){
//             $sql4 = "Update $tableName SET $name = '$newName', $desc = '$newDesc' WHERE $id = '$getID' ";
//         }else{
//             $sql4 = "Update $tableName SET $name = '$newName' WHERE $id = '$getID' ";
//         }
//         $stmt = $dbRemote->prepare($sql4);
//         $stmt->execute();
//         return "Success";
//         }

// }
// // FOR IGNORE CATEGORY
// function updateMntIC ($tableNamed, $getID, $newCate, $newSubj, $newCont, $newLang, $dblocal){
//     $db_local =$dblocal;
    
//     if($tableNamed == 'BK_emailContent_Category'){
//         $id = 'ec_id';
//         $cate = 'ec_category';
//         $subj = 'ec_subject';
//         $cont = 'ec_content';
//         $lang = 'ec_language';
//     }
    
//     $sql5 = "SELECT * FROM $tableNamed WHERE $cate = '$newCate' AND $lang = '$newLang'";
//     $stmt = $dblocal->query($sql5);
//     $sql5Fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $sql5Count = count($sql5Fetch);

//     if($sql5Count != 0){
//         echo "Existing";
//     }else{  
//         if($tableNamed == 'BK_emailContent_Category'){
//             $sql6 = "Update BK_emailContent_Category SET ec_category = '$newCate', ec_subject = '$newSubj' , $cont = '$newCont' , ec_language = '$newLang' WHERE ec_id = '$getID' ";
//         }else{
//             $sql6 = "Update BK_emailContent_Category SET ec_category = '$newCate', ec_language = '$newLang' WHERE ec_id = '$getID' ";
//         }
//         $stmt = $dblocal->prepare($sql6);
//         $stmt->execute();
//         return "Success";
//         }
// }

// //***Delete Mnt Table

// function deleteMnt ($tableName, $getID){
//     $dbRemote = getDB();
    
//     if($tableName == 'ProductCategories'){
//         $id = 'product_categories_id';
//     }else if($tableName == 'ProductAppliedArea'){
//         $id = 'product_applied_id';
//     }else if($tableName == 'GeneralSkinCondition'){
//         $id = 'general_skin_id';
//     }else if($tableName == 'SkinType'){
//         $id = 'skin_type_id';
//     }else if($tableName == 'ProductPreference'){
//         $id = 'product_preference_id';
//     }else if($tableName == 'ProductTextures'){
//         $id = 'product_textures_id';
//     }else if($tableName == 'ProductType'){
//         $id = 'product_type_id';
//     }else if($tableName == 'BK_ProductLink_Category'){
//         $id = 'prodlink_category_id';
//     }else if($tableName == 'BK_PriceType'){
//         $id = 'pricetype_id';
//     }else if($tableName == 'BK_Language_Category'){
//         $id = 'language_id';
//     }
    
//     $sql = "DELETE FROM $tableName WHERE $id = '$getID'";
//         $stmt = $dbRemote->prepare($sql);
//         $stmt->execute();
//         return "Deleted";
// }
// // For Ignore Category
// function deleteMntIC ($getID,$dblocal){
//     $db_local =$dblocal;
    
    
    
//     $sql0 = "DELETE FROM BK_emailContent_Category WHERE ec_id = '$getID'";
//         $stmt = $db_local->prepare($sql0);
//         $stmt->execute();
//         return "Deleted";
// }



// function setStepId($scp_id){
//     $dbRemote = getDB();
//     $stmt = $dbRemote->query("SELECT SCP_id, product_categories_id FROM SkincareProduct WHERE scp_id = $scp_id");
//     $scp_result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $test_string = "";
//     $paa_result = array();
//     $paa_ids = array();
//     $step_id = 0;
//     $counter = 0;
//     foreach($scp_result as $item){
//         if(!empty($item['product_categories_id'])){
//             $sql = "SELECT * FROM ScP_ProductAppliedArea WHERE skpid=".$item['SCP_id'];
//             $stmt = $dbRemote->query($sql);
//             $paa_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//             $ctr=0;
//             foreach($paa_result as $paaitem){
//                 $paa_ids = array_merge($paa_ids, array($ctr=>$paaitem['product_applied_id']));
//                 $ctr++;
//             }
//             $cat_id = $item['product_categories_id'];
            
//             if($cat_id == 18){
//                 if(in_array(1, $paa_ids)){
//                     $step_id = 2;
//                 }
//                 elseif(in_array(2, $paa_ids) OR in_array(3, $paa_ids)){
//                     $step_id = 1;
//                 }
//             }
//             elseif($cat_id == 2 AND in_array(1, $paa_ids)){
//                 $step_id = 3;
//             }
//             elseif($cat_id == 16 AND (in_array(1, $paa_ids) OR (in_array(7, $paa_ids) AND in_array(4, $paa_ids)))){
//                 $step_id = 5;
//             }
//             elseif($cat_id == 8 AND in_array(1, $paa_ids)){
//                 $step_id = 7; 
//             }
//             elseif($cat_id == 24 AND in_array(1, $paa_ids)){
//                 $step_id = 8;
//             }
//             elseif($cat_id == 15 AND in_array(1, $paa_ids)){
//                 $step_id = 9;
//             }
//             elseif(($cat_id == 4 OR $cat_id == 13) AND (in_array(1, $paa_ids) OR in_array(5, $paa_ids) OR in_array(6, $paa_ids))){
//                 $step_id = 10;
//             }
//             elseif($cat_id == 22 AND in_array(3, $paa_ids)){
//                 $step_id = 11;
//             } 
//             elseif(($cat_id == 23 OR $cat_id == 22 OR $cat_id == 7 OR $cat_id == 3) AND (in_array(1, $paa_ids) OR in_array(5, $paa_ids) OR in_array(6, $paa_ids))){
//                 $step_id = 12;
//             }
//             elseif(($cat_id == 20 OR $cat_id == 25) AND in_array(1, $paa_ids)){
//                 $step_id = 13;
//             } 
            
//             //$test_string .= $step_id." - ";
//             if($step_id != 0){
//                 $new_query = "UPDATE SkincareProduct SET step_id=".$step_id." WHERE SCP_id=".$item['SCP_id'];
//                 $stmt = $dbRemote->query($new_query);
//                 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//                 $counter++;
//             }
            
//         }
//     }
// }
?>