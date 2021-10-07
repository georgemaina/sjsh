<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
//error_reporting(E_ALL);
//require_once($root_path.'include/care_api_classes/class_core.php');
require_once($root_path . 'include/care_api_classes/class_notes.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');

class accounting {

    function updateDebit($DebitDetails) {
        global $db, $root_path;
        $debug = false;
        ($debug) ? $db->debug = FALSE : $db->debug = FALSE;
//        require_once 'roots.php';
        require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
        require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
        $insurance_obj = new Insurance_tz;
        $bill_obj = new Bill;
         $user = $_SESSION['sess_user_name'];

        $ddate=new DateTime($DebitDetails[debitDate]);
        $debitDate=$ddate->format('Y-m-d');
        
        $new_bill_number = $bill_obj->checkBillEncounter($DebitDetails[en_nr]);
        $insuranceid=$insurance_obj->Get_insuranceID_from_pid($DebitDetails[pid]);
        
//        echo var_dump($DebitDetails);
        

        $this->sql = "INSERT care_ke_billing(encounter_nr,pid,bill_number,bill_date,service_type,item_number,
            Description,price,qty,total,`status`,`IP-OP`,prescribe_date,weberpSync,partcode,
            bill_time,ledger,insurance_id,batch_no,input_user,rev_code,debtorUpdate)
            values('" . $DebitDetails[en_nr] . "','" . $DebitDetails[pid] . "','" . $new_bill_number . "','" .$debitDate 
                . "','".$DebitDetails[item_type]."','" . $DebitDetails[revcode] . "','" . $DebitDetails[Description] . "','" . $DebitDetails[Amount]
                . "','" . $DebitDetails[qty] . "','" . $DebitDetails[total] . "','pending','1','" . $debitDate
                . "',0,'" . $DebitDetails[revcode] . "','" . date('H:i:s') . "','" . $DebitDetails[ward_nr]
                . "','" . $insuranceid. "','". $DebitDetails[receiptNo]. "','".$user. "','".$DebitDetails[revcode] ."',0)";
        if ($debug)
            echo $this->sql;
        if($db->Execute($this->sql)){
            $newDebitNo=substr($DebitDetails[receiptNo],1)+1;
            $sql="Update care_ke_invoice set newDebitNo='D".$newDebitNo."'";
            if ($debug)
            echo $sql;
            $db->Execute($sql);
        }
        //           $this->sendToERP($DebitDetails[pid]);
        return TRUE;
    }

     function updateDebitOPD($DebitDetails) {
        global $db, $root_path;

        $debug = false;
        ($debug) ? $db->debug = FALSE : $db->debug = FALSE;
//        require_once 'roots.phplse';
        require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
        require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
        $insurance_obj = new Insurance_tz;
        $bill_obj = new Bill;

        $ddate=new DateTime($DebitDetails[debitDate]);
        $debitDate=$ddate->format('Y-m-d');
         $user = $_SESSION['sess_user_name'];

        $new_bill_number = $bill_obj->checkBillEncounter($DebitDetails[en_nr]);
         $insuranceid=$insurance_obj->Get_insuranceID_from_pid($DebitDetails[pid]);
         
        $this->sql = "INSERT care_ke_billing(encounter_nr,pid,bill_number,bill_date,service_type,item_number,
            Description,price,qty,total,`status`,`IP-OP`,prescribe_date,weberpSync,partcode,
            bill_time,ledger,insurance_id,batch_no,input_user,rev_code,debtorUpdate)
            values('" . $DebitDetails[en_nr] . "','" . $DebitDetails[pid] . "','" . $new_bill_number . "','" 
                . $debitDate . "','".$DebitDetails[item_type]."','" . $DebitDetails[revcode] . "','" . $DebitDetails[Description] . "','" . $DebitDetails[Amount]
                . "','" . $DebitDetails[qty] . "','" . $DebitDetails[total] . "','pending','2','" .$debitDate
                . "',0,'" . $DebitDetails[revcode] . "','" . date('H:i:s') . "','17','" . $insuranceid .  "','" 
                . $DebitDetails[receiptNo] . "','" . $user . "','".$DebitDetails[revcode] ."',0)";
        if ($debug)
            echo $this->sql;
        $db->Execute($this->sql);
        
        $newDebitNo=substr($DebitDetails[receiptNo],2)+1;
            $sql="Update care_ke_invoice set OPDebitNo='DT".$newDebitNo."'";
            if ($debug)
            echo $sql;
            $db->Execute($sql);
        //           $this->sendToERP($DebitDetails[pid]);

        return TRUE;
    }

    
    function getEncounter($pid) {
        global $db;
        $debug = false;
        ($debug) ? $db->debug = FALSE : $db->debug = FALSE;

        $this->sql = "select * from care_encounter where pid='$pid'  AND is_discharged=0";
        if ($debug)
            echo $this->sql;
        $this->request = $db->Execute($this->sql);
        $row = $this->request->FetchRow();
        $encounter_nr = $row[0];
        return $encounter_nr;
    }

    function getEncounterDetails($pid) {
        global $db;
        $debug = false;
        ($debug) ? $db->debug = FALSE : $db->debug = FALSE;
        if ($debug)
            echo 'getEncounterDetails';
        $this->sql = "select * from care_encounter where pid='$pid'  AND is_discharged=0";
        if ($debug)
            echo $this->sql;
        $this->request = $db->Execute($this->sql);

        return $this->request;
    }

    function convert_number($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 1000000);  /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */

        $res = "";

        if ($Gn) {
            $res .= convert_number($Gn) . " Million";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") .
                    convert_number($kn) . " Thousand";
        }

        if ($Hn) {
            $res .= (empty($res) ? "" : " ") .
                    convert_number($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
            "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
            "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }

}

?>
