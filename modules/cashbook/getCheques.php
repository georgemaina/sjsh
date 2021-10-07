<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$task = ($_POST['task']) ? ($_POST['task']) : ($_REQUEST['task']);


//$task="getAmount";
switch ($task) {
    case "getCheques":
        getCheques();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch



function getCheques() {
    global $db;

    $sql = 'SELECT ID,ledger,cash_point,Voucher_No,Pay_mode,pdate,cheque_no,payee,sum(amount) as total
        FROM care_ke_payments where printed<>1 group by voucher_no';
    $result = $db->Execute($sql);
    $total=$result->RecordCount();
     
     echo '{
    "unpaidCheques":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
       $payee = $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[6]);
        echo '{"ID":' . $row['ID'] . ',"Ledger":"' . $row['ledger'] . '","cash_point":"' . $row['cash_point'] . '","Voucher_No":"' . $row['Voucher_No']  . '",
            "Pay_mode":"' . $row['Pay_mode']  . '","cheque_no":"' . $row['cheque_no']. '","payee":"' . $row['payee']. '",
                "pdate":"' . $row['pdate'] . '","Total":"' . $row['total']. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
  echo ']}';
}


?>

