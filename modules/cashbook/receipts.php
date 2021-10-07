<link rel="stylesheet" type="text/css" href="cashbook.css">
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path . "modules/accounting/extIncludes.php");

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
    $cashpoint = $rows[pcode];
    $shiftNo = $rows[current_shift];
    $cname = $rows[name];
    $receipt_no = $rows[next_receipt_no];
    $prefix = $rows[prefix];
}
?>
    <script type="text/javascript" src="reportFunctions.js"></script>
 <?php
echo '<table border="1" width="100%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Receipts</td></tr>';
echo '<tr class="prow"><td colspan=6>' . $cashier . ' You are using Cashpoint number ' . $cashpoint . ' and shift No ' . $shiftNo . '</td></tr>';
echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
echo '<tr><td width="30%" valign="top">' . doLinks() . '</td>';
echo '<td valign="top" align=center>';

if ($cashpoint) {
    if (isset($_POST["submit"])) {
////    $cash_point=$_REQUEST["cash_point"];
        $refno = $_REQUEST["receiptNo"];
        $paymode = $_REQUEST["paymode"];
        $payer = $_POST["payee"];
        $chequeNo = $_REQUEST["cheq_no"];
        $patientid = $_REQUEST["pid"];
        $patientname = $_REQUEST["pname"];
        $towards= $_POST["toward"];
        $total = $_REQUEST["total"];
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
            require('receiptconn.php');
//            add_data_to_database($_POST);
        }


        displayRcptForm($db, $cashpoint, $cashier);

        $heading = "AIC LITEIN HOSPITAL";
        $box = "P.O. BOX 45 -00902 LITEIN";

        $pdate1 =$_REQUEST["strDate1"];
        $inputTime=date('H:i:s');
        $date1 = new DateTime(date($pdate1));
        $rdate = $date1->format("l jS F Y").$time;
        $refno = $refno;
        $cashier = $cashier;
        $pno = $patientid;
        $PatientName = $patientname;
        $PaymentMode = $paymode;
        $cash_point = $_REQUEST["cashPoint"];

        displayGLReceipts($rdate, $refno, $cashier, $patientid, $PatientName, $PaymentMode, $cash_point, $payer, $reprint, $shiftNo,$towards,$inputTime);
    } else {
        displayRcptForm($db, $cashpoint, $cashier);
    }
} else {
    ?>
        <script type="text/javascript">
            window.location = "welcome.php?cashpoint=<?php echo $cashpoint ?>&shiftNo=<?php echo $shiftNo ?>";
        </script>
    <?php
}
echo '</td></tr></table>';

