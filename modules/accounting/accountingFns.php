<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$desc1 = $_REQUEST[desc1];
$desc2 = $_REQUEST[desc2];
$desc3 = $_REQUEST[desc3];
$desc4 = $_REQUEST[desc4];
$desc5 = $_REQUEST[desc5];
$desc6 = $_REQUEST[desc6];
$desc7 = $_REQUEST[desc7];
$desc8 = $_REQUEST[desc8];
$encNr=$_REQUEST[encNr];
$caller = $_REQUEST[caller]; //debitGetPt
$ward = $_REQUEST[ward];
$nhifAcc = $_REQUEST[nhifAcc];
$pid = $_REQUEST[pid];
$enc_nr = $_REQUEST[enc_nr];
$rates= $_REQUEST[rateID];
$bill_number=$_REQUEST[billNumber];

if ($caller == debitGetPt) {
    if ($desc3) {
        global $db;

               $sql = "SELECT b.name_first,b.name_2,b.name_last,MAX(a.encounter_nr) AS encounter_nr,a.encounter_class_nr,
        a.current_ward_nr,w.description,MAX(c.`bill_number`)
        FROM care_person b
         LEFT JOIN care_encounter a ON a.pid=b.pid 
         LEFT JOIN care_ke_billing c ON a.`encounter_nr`=c.`encounter_nr`
        INNER JOIN care_ward w ON w.nr=a.current_ward_nr WHERE b.pid='$desc3' AND a.encounter_class_nr=1";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        //echo $sql;
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();
       $firstName = $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[0]);
       $lastname = $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[1]);
       $surName= $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[2]);
        echo trim($firstName) . "," . trim($lastname) . "," . trim($surName) . "," . trim($row[encounter_nr])
                . "," . trim($row[current_ward_nr]).",".$row[bill_number]; // 42
    } else {
        echo " ";
    }
} else if ($caller == debitGetRv) {
    if ($desc4) {
        global $db;

        $sql = "SELECT item_description FROM care_tz_drugsandservices WHERE purchasing_class LIKE 'small%' and partcode like '$desc4%'";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();

        echo $row[0] . " " . $row[1] . " " . $row[2]; // 42
    } else {
        echo " ";
    }
} else if ($caller == 'creditNo') {
    if ($desc1) {
        $sql = "SELECT MAX(creditNo) as crno FROM care_ke_nhifcredits";
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();
        if ($row[0] > 1) {
            $nextIRno = intval($row[0]) + 1;
        } else {
            echo "1001";
        }
        echo "$nextIRno";
    }
} else if ($caller == 'getAdDsDate') {
    if ($desc2) {
        $sql = "SELECT DISTINCT date_from,date_to,DATEDIFF(date_to,date_from) AS days FROM care_encounter_location e 
            LEFT JOIN care_encounter c
                    ON e.encounter_nr=c.encounter_nr 
                    WHERE c.encounter_nr='" . $desc2 . "' AND e.status='discharged' and c.encounter_class_nr=1";

//        echo $sql;
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {
            $admDate = $row[0];
            $disDate = $row[1];

            echo $row[0] . ',' . $row[1] . ',' . $row[2];
////                        echo "$row[0]";
        } else {
            echo "not yet";
        }
    }
} else if ($caller == 'getNHIFRates') {
    if ($rates) {
      
        $sql = "SELECT RateValue,rateCalc FROM care_ke_rates WHERE `ratename`='$rates'";
        $result = $db->Execute($sql);
//               echo $sql;
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {

            echo $row[0].','. $row[1];
        } else {
            echo "nil";
        }
    }
}else if ($caller == 'getNHIFCredit') {
    if ($desc6) {
      
        $sql = "SELECT RateValue FROM care_ke_Rates WHERE `ratename`='$rate'";
        $result = $db->Execute($sql);
//               echo $sql;
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {

            echo $row[0];
////                        echo "$row[0]";
        } else {
            echo "nil";
        }
    }
} else if ($caller == 'getNHIFAccount') {
    if ($nhifAcc) {

        $sql = "SELECT accno,`name` FROM care_ke_debtors WHERE `accno`='NHIF'";
        $result = $db->Execute($sql);
//               echo $sql;
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {

            echo $row[0] . "," . $row[1];
////                        echo "$row[0]";
        } else {
            echo "nil";
        }
    }
} else if ($caller == 'getInvoiceno') {
    if ($desc7) {
        global $db;

        $sql = "SELECT bill_number,SUM(total) AS amount FROM care_ke_billing WHERE pid='$desc7'
         and `IP-OP`=1 and service_type NOT IN ('payment','payment adjustment','NHIF') and encounter_nr=$encNr";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {
            echo $row[0] . "," . $row[1]; // 42
        } else {
            echo 'nil';
        }
    } else {
        echo "nil";
    }
} else if ($caller == 'admDate') {
    if ($pid) {
        global $db;

        $sql = "select encounter_nr from care_encounter where pid=$pid and encounter_class_nr=1";

        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if ($row = $result->FetchRow()) {
            echo $row[0];
        } else {
            echo 'nil';
        }
    } else {
        echo "nil";
    }
} else if ($caller == 'getBills') {
    if ($desc8) {
        global $db;

        $sql = "select DISTINCT bill_number from care_ke_billing where pid=$desc8";

        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . $sql;
            exit;
        }

        echo "Invoice No's<select id='bills'>";
        while ($row = $result->FetchRow()) {
            echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
        }
        echo '<select>';
    } else {
        echo "nil";
    }
}else if ($caller == 'getNHIFcat') {
        global $db;

        $sql = "select nhifcat from care_ke_invoice";

        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . $sql;
            exit;
        }

        $row = $result->FetchRow();
            echo  $row[0];
        
}else if ($caller == 'verifyNhifCredit') {
        global $db;
        
        $sql = "SELECT COUNT(admno) AS pcount FROM care_ke_nhifcredits WHERE admno='$pid' AND bill_number='$bill_number'";

        $result = $db->Execute($sql);
        
//         echo $sql;
       
        if (!$result) {
            echo 'Could not run query: ' . $sql;
            exit;
        }
            $row = $result->FetchRow();
            if($row[0]>0){
                echo '1';
            }else{
                echo '0';
            }
        

        
        
}
?>

