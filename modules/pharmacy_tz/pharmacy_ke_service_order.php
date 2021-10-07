
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once 'myLinks.php';
jsIncludes();
define('LANG_FILE','pharmacy.php');
$local_user='ck_pflege_user';


require_once($root_path.'include/inc_front_chain_lang.php');

  if(isset($_POST["submit"])){

         // $is_new_post = true;
        // is a previous session set for this form and is the form being posted
        //if (isset($_SESSION["myform_key"]) && isset($_POST["myform_key"])) {
            // is the form posted and do the keys match
          //  if($_POST["myform_key"] == $_SESSION["myform_key"] ){
            //    $is_new_post = false;
           // }
       // }
      //  if($is_new_post){
            // register the session key variable
         //   $_SESSION["myform_key"] = $_POST["myform_key"];
            // do what ya gotta do
            require('serviceOrder.php');
//            add_data_to_database($_POST);
       // }
    

    displayServiceOrderForm($db);

}else{
    displayServiceOrderForm($db);
}
echo '</td></tr></table>';

