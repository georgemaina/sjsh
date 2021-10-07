<link rel="stylesheet" type="text/css" href="cashbook.css">
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');

require_once 'myLinks.php';
jsIncludes();

 $debug=false;
$cashier=$_SESSION['sess_login_username'];
$sql="SELECT pcode,name,next_receipt_no,current_shift,prefix from care_ke_cashpoints WHERE cashier='$cashier' and active=1";
if($debug) echo $sql;
//echo $cashier;
$result=$db->Execute($sql);
//if($debug) echo $result;
if($rows=$result->FetchRow()){
    $cashpoint=$rows[pcode];
    $shiftNo=$rows[current_shift];
    $cname=$rows[name];
    $receipt_no=$rows[next_receipt_no];
    $prefix=$rows[prefix];
}
echo '<table border="1" width="100%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Cash Sale</td></tr>';
echo '<tr class="prow"><td colspan=6>'. $cashier .' You are using Cashpoint number '. $cashpoint .' and shift No '. $shiftNo .'</td></tr>';
echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
echo '<tr><td width="30%" valign="top">'.doLinks().'</td>';
echo '<td valign="top" align=center>';

if(isset($_POST["submit"])){
////    $cash_point=$_REQUEST["cash_point"];
    $refno=$_REQUEST["receiptNo"];
    $paymode=$_REQUEST["paymode_desc"];
    $payer=$_REQUEST["payer"];
    $chequeNo=$_REQUEST["cheq_no"];
    $patientid=$_REQUEST["patientId"];
    $patientname=$_REQUEST["patient_name"];
    $total=$_REQUEST["total"];
//    $payer=$_REQUEST["payer"];
    require('cashconn.php');

    displaySaleForm($db,$cashpoint,$cashier,$receipt_no,$cname,$prefix);

   
    //displayReceipt($cash_point,$refno,$cashier,$paymode,$payer,$chequeNo,$patientid,$patientname,$total);
//$File = "C:/Program Files/2xp/receipt.txt";
   // $Handle = fopen($File, 'w');

    //$File = "C:/Program Files/2xp/receipt.txt";
    //$Handle = fopen($File, 'w');
    $heading ="PCEA Kkuyu Hospital ";
    $box="P.O. BOX 45 -00902 Kikuyu";
    
    $rdate =date('l jS F Y h:i:s A');
    $refno=$refno;
    $cashier=$cashier;
    $pno=$patientid;
    $PatientName=$patientname;
    $PaymentMode=$paymode;


displayReceipts($rdate,$refno,$cashier,$pno,$PatientName,$PaymentMode,$cashpoint,$payer,$reprint);



}else{
    displaySaleForm($db,$cashpoint,$cashier,$receipt_no,$cname,$prefix);
}
echo '</td></tr></table>';

