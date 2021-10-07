<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');

$desc3=$_REQUEST[desc3];

if($desc3) {
  global $db;

    $sql = "SELECT name_first,name_2,name_last FROM care_person WHERE pid='$desc3'";
   // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
  $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

  $row=$result->FetchRow();

    echo trim($row[0])." ".trim($row[1])." ".trim($row[2]); // 42

} else {
    echo " ";
}

?>

