<link rel="stylesheet" type="text/css" href="cashbook.css">
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
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

     $is_new_post = true;
        // is a previous session set for this form and is the form being posted
        if (isset($_SESSION["myform_key"]) && isset($_POST["myform_key"])) {
            // is the form posted and do the keys match
            if($_POST["myform_key"] == $_SESSION["myform_key"] ){
                $is_new_post = false;
            }
        }
        if($is_new_post){
            // register the session key variable
            $_SESSION["myform_key"] = $_POST["myform_key"];
            // do what ya gotta do
            require('InsertOrder.php');
//            add_data_to_database($_POST);
        }


    displayOrderForm($db);

}else{
    displayOrderForm($db);
}
echo '</td></tr></table>';
?>

