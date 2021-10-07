<?php
require('./roots.php');
require('../include/inc_environment_global.php');
 
global $db;
$debug = false;

$sql="SELECT * FROM care_ke_billing WHERE rev_code='NHIF' AND bill_date BETWEEN '2013-01-01' AND '2013-05-13'";

$results = $db->Execute($sql);
if($debug) echo $sql;

while ($row = $results->FetchRow()) {

            $insuCompanyID='NHIF';
            
            $transType = 2; //Invoices
            $trnsNo = getTransNo($transType);

            if ($transType == 2) {
                $amount = $row[total];
            } 

            $sql4 = "insert into `care_ke_debtortrans`
                                (`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,
                                `lasttransTime`,`settled`,encounter_nr,encounter_class_nr,reference)
                                values('$trnsNo','$transType','$insuCompanyID', '$row[pid]','" . $row[bill_date]  . "',' $row[bill_number]',
                                '$row[total]','" . $row[bill_date] . "','" . $row[bill_time] . "',0,'$row[encounter_nr] ','1','$row[id]')";
            if ($debug)
                echo $sql4;

            if ($db->Execute($sql4)) {
                $newTransNo = ($trnsNo + 1);
                $sql3 = "update care_ke_transactionNos set transNo='$newTransNo' where typeid='$transType'";
                if ($debug)
                    echo $sql3;
                $db->Execute($sql3);

                $sql5 = "update care_ke_billing set debtorUpdate=1 where pid='$row[pid]' and encounter_nr='$row[encounter_nr]'";
                if ($debug)
                    echo $sql5;
                $db->Execute($sql5);

                echo "Success sending $row[pid] , $row[bill_date], $amount , $row[bill_number]<br>";
            }else{
                echo "failure <br>$sql4 <br>";
            }
}

   function getTransNo($transType) {
        global $db;
        $debug = false;
        $sql = "select `transNo` from `care_ke_transactionNos` where typeID=$transType";
        if ($debug)
            echo $sql;
        $result = $db->Execute($sql);
        $row = $result->FetchRow();

        $currNo = $row[0];
        $nextNo = ($currNo + 1);
        return $nextNo;
    }

?>
