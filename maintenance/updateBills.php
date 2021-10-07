<?php

require('./roots.php');
require('../include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;

        $debug =true;
        $mysql3 = "SELECT `pid`,`Encounter_nr`,`Insurance_ID`,`Bill_date`, `Partcode`,`Description`,`Service_type`,`Price`,`Qty`, `Total` 
                FROM `mmh_vaccine_bills`";
        if ($debug)
            echo $mysql3 . "<br>";

        $result = $db->Execute($mysql3);
//                    $row=$this->result->FetchRow();
       
        while ($row = $result->FetchRow()) {
             $bill_number = $bill_obj->checkBillEncounter($row['Encounter_nr'] );
            $date1 = new DateTime($row['Bill_date']);
            $billDate = $date1->format("Y-m-d");

                $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,`ip-op`,bill_number,
                            service_type, price,`Description`,notes,prescribe_date,dosage,times_per_day,
                            days,input_user,item_number,partcode,status,qty,total,rev_code,weberpSync,
                            insurance_id,ledger,bill_time)
                            value('" . $row['pid'] . "','" . $row['Encounter_nr'] . "','" . $billDate . "','2','" . $bill_number 
                            . "','" . $row['Service_type'] . "','" . $row['price']
                        . "','" . $row['Description'] . "','Hepatitis Vaccination','" . $billDate
                        . "','1','1','1','Admin','" . $row['Partcode'] . "','" . $row['Partcode'] . "','pending'"
                        . ",'1','" . $row['Price'] . "','" . $row['Partcode'] . "',0,'"
                        . $row['Insurance_ID'] . "','17','" . date("H:i:s")."')";
                if ($debug)
                    echo $sql;
                if($db->Execute($sql)){
                    echo "Update bill for". $row['pid'] ."Successfully <br>";
                }else{
                    echo "Error updating bill for Pid ".$row['pid'];
                }
            }
//        }
   

?>