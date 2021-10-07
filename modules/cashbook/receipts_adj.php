<link rel="stylesheet" type="text/css" href="cashbook.css">
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path . "modules/accounting/extIncludes.php");
$bill_obj=new Bill();
require_once 'mylinks.php';
jsIncludes();

$debug = false;
$cashier = $_SESSION['sess_login_username'];
$sql = "SELECT pcode,next_receipt_no,current_shift from care_ke_cashpoints WHERE cashier='$cashier'";
if ($debug)
    echo $sql;
//echo $cashier;
$result = $db->Execute($sql);
//if($debug) echo $result;
if ($rows = $result->FetchRow()) {
    $cashpoint = $rows[pcode];
    $shiftNo = $rows[current_shift];
}
echo '<table border="1" width="100%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Receipts Adjustment</td></tr>';
echo '<tr class="prow"><td colspan=6>' . $cashier . ' You are using Cashpoint number ' . $cashpoint . ' and shift No ' . $shiftNo . '</td></tr>';
echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
echo '<tr><td width="30%" valign="top">' . doLinks() . '</td>';
echo '<td valign="top" align=center>';

if ($cashpoint) {
    if (isset($_POST["submit"])) {
        $cashpoint = $_REQUEST["cashPoint"];
        $refno = $_REQUEST["receiptNo"];
        $paymode = $_REQUEST["paymode_desc"];
        $payer = $_REQUEST["payee"];
        $chequeNo = $_REQUEST["cheq_no"];
        $patientid = $_REQUEST["pid"];
        $patientname = $_REQUEST["pname"];
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
             require('receiptconn_adj.php');
//            add_data_to_database($_POST);
        }


        displayRcptFormADJ($db, $cashpoint, $cashier);

        $heading = "PCEA Kikuyu Hospital ";
        $box = "P.O. BOX 45 -00902 Kikuyu";

        $rdate = date('l jS F Y h:i:s A');
        $refno = $refno;
        $cashier = $cashier;
        $pno = $patientid;
        $PatientName = $patientname;
        $PaymentMode = $paymode;

        displayGLReceiptsOld($rdate, $refno, $cashier, $patientid, $patientname, $paymode, $cashpoint, $payer, $reprint);
    } else {
        displayRcptFormADJ($db, $cashpoint, $cashier);
    }
} else {
    ?>
        <script type="text/javascript">
            window.location = "welcome.php?cashpoint=<?php echo $cashpoint ?>&shiftNo=<?php echo $shiftNo ?>";
        </script>
    <?php
}
echo '</td></tr></table>';

