<link rel="stylesheet" type="text/css" href="cashbook.css">
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$billObj=new Bill();

require_once 'mylinks.php';
jsIncludes();

$debug = false;
$cashier = $_SESSION['sess_login_username'];
$sql = "SELECT pcode,name,next_receipt_no,current_shift,prefix from care_ke_cashpoints 
WHERE cashier='$cashier' and active=1";
if ($debug)
    echo $sql;
//echo $cashier;
$result = $db->Execute($sql);
//if($debug) echo $result;
if ($rows = $result->FetchRow()) {
    $cashpoint = $rows['pcode'];
    $shiftNo = $rows['current_shift'];
    $cname = $rows['name'];
    $receipt_no = $rows['next_receipt_no'];
    $prefix = $rows['prefix'];
}
echo '<table border="1" width="100%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Cash Sale</td></tr>';
echo '<tr class="prow"><td colspan=6>' . $cashier . ' You are using Cashpoint number ' . $cashpoint . ' and shift No ' . $shiftNo . '</td></tr>';
echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
echo '<tr><td width="30%" valign="top">' . doLinks() . '</td>';
echo '<td valign="top" align=center>';

if ($cashpoint) {
    if (isset($_POST["submit"])) {
////    $cash_point=$_REQUEST["cash_point"];
        $refno = $_REQUEST["salesReceiptNo"];
        $paymode = $_REQUEST["paymode_desc"];
        $payer = $_REQUEST["payer"];
        $chequeNo = $_REQUEST["cheq_no"];
        $patientid = $_REQUEST["patientId"];
        $patientname = $_REQUEST["patient_name"];
        $total = $_REQUEST["total"];
        $cash_point = $_REQUEST["cash_point"];
        $shiftNo = $_REQUEST["shift_no"];
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
            require('cashconn.php');
//            add_data_to_database($_POST);
        }


        displaySaleForm($db, $cashpoint, $cashier, $receipt_no, $cname, $prefix);


        $rdate = date('l jS F Y h:i:s A');
        $input_time=date('H:i:s');
        $refno = $refno;
        $cashier = $cashier;
        $pno = $patientid;
        $PatientName = $patientname;
        $PaymentMode = $paymode;

        $patientNames=$billObj->getPatientNames($patientid);
        displayReceipts($rdate, $ref_no, $cashier, $pno, $patientNames, $PaymentMode, $cash_point, $payer, $reprint, $shiftNo,$input_time);
    } else {
        displaySaleForm($db, $cashpoint, $cashier, $receipt_no, $cname, $prefix);
    }
} else {
    ?>
    <script type="text/javascript">
        window.location = "welcome.php?cashpoint=<?php echo $cashpoint ?>&shiftNo=<?php echo $shiftNo ?>";
    </script>
    <?php
}
echo '</td></tr></table>';

