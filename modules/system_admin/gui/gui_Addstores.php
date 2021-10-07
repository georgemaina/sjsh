<?php
require_once 'roots.php';
require($root_path.'include/inc_environment_global.php');

$debug=false;
require('myLinks.php');
jsIncludes();

echo ' <div class=pgtitle>Store Management</div>';
echo "<table width=100% border=1>
    <tr><td align=left valign=top>";
//require 'acLinks.php';
echo '</td><td width=80%>';

if(!isset($_POST[submit])) {
    displayStoresForm($db);
}else {

    echo var_dump($_POST);
    $storeID= $_POST['storeID'];
    $storeName= $_POST['storename'];
    insertData($db,$storeID,$storeName);

    displayStoresForm($db);

}
echo "</td></tr></table>";



function  insertData($db,$storeID,$storeName) {
    $debug=false;
    $sql="INSERT INTO care2x.care_ke_stlocation
	(st_id,
	st_name
	)
	VALUES
	('$storeID','$storeName')";

    $result=$db->Execute($sql);
    if($debug) echo $sql;
}


?>

