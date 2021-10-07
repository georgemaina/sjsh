
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

    require('serviceOrder.php');

    displayServiceOrderForm($db);

}else{
    displayServiceOrderForm($db);
}
echo '</td></tr></table>';

