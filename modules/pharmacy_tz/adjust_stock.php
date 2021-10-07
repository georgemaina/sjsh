<?php
//echo var_dump($_POST);
$debug=false;;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$AdjDate=$_POST[adjDate];
$adjDesc='Stock Adjustent number '.$_POST[adjNO].' on '.$_POST[adjDate];
$Adjdesc2=$_POST[adjDesc];
$storeId=$_POST[storeID];


//echo var_dump($_POST);
//echo 'rows '.$_POST[gridbox_rowsadded];

function insertData($db,$rowid,$AdjDate,$adjDesc,$Adjdesc2,$storeId) {
    $debug=false;

    $itemId=$_POST["gridbox_".$rowid."_0"];
    $item_Desc=$_POST["gridbox_".$rowid."_1"];
    $qty=$_POST["gridbox_".$rowid."_3"];
    $price=$_POST["gridbox_".$rowid."_2"];
    $total=$_POST["gridbox_".$rowid."_4"];

    $csql="update care_ke_locstock set
	quantity='$qty',
        comment='$adjDesc',
        comment2='$Adjdesc2' where stockid='$itemId' and loccode='$storeId'";

    if($debug) echo $csql;
    $result2=$db->Execute($csql);
    
}


if(strstr($_POST[gridbox_rowsadded],",")) {
    $added_rows=$_POST[gridbox_rowsadded];
    $arr_rows= explode(",", $added_rows);
    for($i=0;$i<count($arr_rows);$i++) {
        insertData($db,$arr_rows[$i],$AdjDate,$adjDesc,$Adjdesc2,$storeId);
        }
}else{
     insertData($db,$_POST[gridbox_rowsadded],$AdjDate,$adjDesc,$Adjdesc2,$storeId);

     }













?>



