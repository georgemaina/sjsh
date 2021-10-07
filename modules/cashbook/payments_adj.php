
<!--<link rel="stylesheet" type="text/css" href="../../css/themes/default/default.css">-->
<link rel="stylesheet" type="text/css" href="cashbook.css">
<link rel="stylesheet" type="text/css" href="../../include/Extjs/resources/css/ext-all.css" />
<script type="text/javascript" src="../../include/Extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../include/Extjs/ext-all.js"></script>
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
//require($root_path . "modules/accounting/extIncludes.php");

require_once 'mylinks.php';
jsIncludes();
?>
<script type="text/javascript" src="reportFunctions.js"></script>
<?php
$debug=false;
$cashier=$_SESSION['sess_login_username'];
$sql="SELECT pcode,next_receipt_no,current_shift from care_ke_cashpoints WHERE cashier='$cashier'";
if($debug) echo $sql;
//echo $cashier;
$result=$db->Execute($sql);
//if($debug) echo $result;
if($rows=$result->FetchRow()){
    $cashpoint=$rows[pcode];
    $shiftNo=$rows[current_shift];
}
echo '<div id="hello-win" align="center"></div><table border="1" width="100%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Payments Adjustment</td></tr>';
echo '<tr class="prow"><td colspan=6>'. $cashier .' You are using Cashpoint number '. $cashpoint .' and shift No '. $shiftNo .'</td></tr>';
echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
echo '<tr><td width="30%" valign="top">'.doLinks().'</td>';
echo '<td valign="top" align=center>';


    if(isset($_POST["submit"])){
////    $cash_point=$_REQUEST["cash_point"];
        $payer=$_REQUEST["payer"];
        $chequeNo=$_REQUEST["chequeNo"];
        $cashpoint=$_REQUEST["cashPoint"];
        $voucherNo=$_REQUEST["vouchNo"];
        $payMode =$_REQUEST["paymode"];

        $total=$_REQUEST["total"];
//    $payer=$_REQUEST["payer"];

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
            require('payAdjconn.php');
//            add_data_to_database($_POST);
        }

        getVoucher();
        displayPayAdjForm();


       $rdate =date('l jS F Y h:i:s A');
        $refno=$refno;
        $cashier=$cashier;
        $pno=$patientid;
        $PatientName=$patientname;
        $PaymentMode=$paymode;


        if($pledger=='PC'){
            displayPettyADJ($cashpoint,$payMode,$voucherNo);
        }else{
            displayVoucher($cashpoint,$payMode,$voucherNo);
        }
       // displayVoucherADJ($cashpoint,$payMode,$voucherNo);


    }else{
        displayPayAdjForm();
    }

echo '</td></tr><tr><td>  </td></tr></table>';

