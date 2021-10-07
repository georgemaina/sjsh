<link rel="stylesheet" type="text/css" href="cashbook.css">
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/accounting.php');
require_once($root_path.'modules/accounting/billing_ke_pharm_quote.php');

define('LANG_FILE','lang_en_billing.php');

$lang_tables[]='pharmacy.php';


require($root_path.'include/inc_front_chain_lang.php');

require_once 'myLinks.php';
jsIncludes();
echo '<html><head></head><body bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066 >';
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

    require('adjust_stock.php');

    displayAdjustStockForm($db);

}else{
    displayAdjustStockForm($db);
}
echo '</td></tr></table></body></html>';

