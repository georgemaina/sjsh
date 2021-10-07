<link rel="stylesheet" type="text/css" href="cashbook.css">
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/accounting.php');
require_once($root_path.'modules/accounting/billing_ke_pharm_quote.php');
require_once 'myLinks.php';
jsIncludes();

  if(isset($_POST["submit"])){
   $req_no=$_POST[ordIrnNo];
    $status='pending';
    $req_date=date("Y-m-d");
    $req_time=date("H:i:s");
    $store_loc=$_POST[storeID];
    $store_desc=$_POST[storeDesc];
    $sup_storeId=$_POST[supStoreid];
    $supStoreDesc=$_POST[supStoredesc];
    $period='2009';
    $input_user = $_SESSION['sess_login_username'];

    require('returndrugs.php');

    displayrReturnsForm($db);

}else{
    displayrReturnsForm($db);
}
echo '</td></tr></table>';

