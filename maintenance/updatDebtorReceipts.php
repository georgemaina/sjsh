<?php
require('./roots.php');
require('include/inc_environment_global.php');
require_once('include/care_api_classes/class_tz_insurance.php');
require_once('include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
 $bill_obj = new Bill;
global $db;
$debug = false;
($debug) ? $db->debug = true : $db->debug = FALSE;

$dtNow = date('Y-m-d');
$disTime = date('H:i:s');
$sql = "SELECT * FROM care_ke_receipts WHERE ledger='db'";
if($debug) echo $sql;
$result = $db->Execute($sql);

while ($row = $result->FetchRow()) {

//            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($row[pid]);
//            $insuCompanyID = $insurance_obj->GetCompanyFromPID2($row[pid]);
//            if ($IS_PATIENT_INSURED) {$Cash_Point,$receiptNo,$payee
                 $bill_obj ->updateDebtorsReceipts($row[cash_point],$row[ref_no],$row[patient]);
//            }

            echo "debtor $row[patient] $row[payer] transactions updated successfully<br>";

}




?>
<script>
    window.close();
</script>