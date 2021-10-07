<link rel="stylesheet" type="text/css" href="../cashbook/cashbook.css">
<!--<script type="text/javascript" src="../../include/Extjs/ext-all.js"></script>-->
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');

require_once 'myLinks.php';
jsIncludes();

?>
    <script type="text/javascript" src="reportFunctions.js"></script>
<?php
  if(isset($_POST["submit"])){
   $req_no=$_POST[ordIrnNo];
    $status='pending';
    $count_date=$_POST[storeID];
//    $req_time=date("H:i:s");
    $store_loc=$_POST[storeID];
    $store_desc=$_POST[storeDesc];
    $period='2012';
    $input_user = $_SESSION['sess_login_username'];

    require('InsertStockCount.php');

    displayOrderCountForm($db);

}else{
    displayOrderCountForm($db);
}
echo '</td></tr></table>';
?>

