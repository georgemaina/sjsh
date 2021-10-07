<?php
require('./roots.php');
require('include/inc_environment_global.php');

global $db;
$debug = false;


updateFinalBill(15036);


function updateBillNo($encounter_nr='') {
global $db;
$debug=true;

if ($debug) echo "<br><b>Method class_tz_billing::updateBillNo()</b><br>";

$sql ="select last_Bill_nr,new_bill_nr from care_ke_invoice";
if ($debug) echo $sql;
$request = $db->Execute($sql);
$row=$request->FetchRow();
$newbillno=intval($row[1])+1;

$sql ="Update care_ke_invoice set last_Bill_nr='$row[1]',new_bill_nr='$newbillno'";
$request = $db->Execute($sql);
if ($debug) echo $sql;
return $newbillno;
}

function newBillNo($encounter_nr) {
global $db;
$debug=true;

if ($debug) echo "<br><b>Method class_tz_billing::newBillNo()</b><br>";
$sql ="select new_bill_nr from care2x.care_ke_invoice";

if ($debug) echo $sql;
$request = $db->Execute($sql);
$row=$request->FetchRow();
return $row[0];

}

function checkBillEncounter($encounter_nr) {
    global $db;
    $debug = true;
    if ($debug)
        echo "<br><b>Method class_tz_billing::updateBillNo()</b><br>";

    $sql = "select bill_number from care_ke_billing where encounter_nr='$encounter_nr'";
    if ($debug)
        echo $sql;
    $request = $db->Execute($sql);
    $row = $request->FetchRow();
    if ($row[0] != '' && !empty($row[0])) {
//$row=$this->request->FetchRow();
        return $row[bill_number];
    } else {
        $bill = newBillNo($encounter_nr);
        updateBillNo($encounter_nr);
        return $bill;
    }
}
    function updateFinalBill($encounter_no) {
        global $db, $root_path;

        $debug = true;
        if ($debug)
            echo "<b>class_tz_billing::updateFinalBill</b><br>";
        if ($debug)
            echo "encounter no: $encounter_no <br>";

        require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
        $insurance_obj = new Insurance_tz;

        $mysql3 = "SELECT c.pid,c.encounter_nr,c.encounter_class_nr,
                    b.article, b.article_item_number,b.partcode, b.price,(b.dosage*b.times_per_day*b.days) as qty,
                    b.drug_class, b.dosage, b.notes, b.prescribe_date, b.times_per_day, b.days, b.prescriber,
                    (b.dosage*b.times_per_day*b.days)*b.price as total, b.is_outpatient_prescription, b.status,
                    b.bill_number, b.bill_status,d.item_number,b.nr
                    FROM care_encounter c
                    INNER JOIN care_encounter_prescription b
                    ON c.encounter_nr=b.encounter_nr
                    INNER JOIN care_tz_drugsandservices d on d.partcode=b.partcode
                    WHERE b.encounter_nr='$encounter_no' and drug_class='drug_list' and b.prescribe_date between '2011-11-05' and '2011-11-12'";
        if ($debug)
            echo $mysql3 . "<br>";

        $result = $db->Execute($mysql3);
//                    $row=$this->result->FetchRow();
        $bill_number = checkBillEncounter($encounter_no);
        while ($row = $result->FetchRow()) {

            $sqla = 'select st_id from care_ke_stlocation where st_name like "Pharma%" and store<>1';
            $resulta = $db->Execute($sqla);
            $rowa = $resulta->FetchRow();
            if ($debug)
                echo $sqla . "<br>";
//                $insuranceid=$insurance_obj->Get_insuranceID_from_pid($row[pid]);
            $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,`ip-op`,bill_number,
                            service_type, price,`Description`,notes,prescribe_date,dosage,times_per_day,
                            days,input_user,item_number,partcode,status,qty,total,rev_code,weberpSync,insurance_id,batch_no,ledger,bill_time)
                            value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . $row['encounter_class_nr']
                    . "','" . $bill_number . "','" . $row['drug_class'] . "','" . $row['price']
                    . "','" . $row['article'] . "','" . $row['notes'] . "','" . $row['prescribe_date']
                    . "','" . $row['dosage'] . "','" . $row['times_per_day'] . "','" . $row['days']
                    . "','" . $row['prescriber'] . "','" . $row['article_item_number'] . "','" . $row['partcode'] . "','pending'"
                    . ",'" . $row['qty'] . "','" . $row['total'] . "','" . $row['item_number'] . "',0,'" . $insuranceid . "','" . $row['nr'] . "','$rowa[0]','" . date("H:m:s") . "')";
            if ($debug)
                echo $sql . "<br>";
            $db->Execute($sql);

            if ($row['encounter_class_nr'] = 1) {
                $sql = "update care_encounter_prescription set bill_number='$bill_number', bill_status='billed'
        where encounter_nr='$encounter_no' and nr='$row[nr]'";
                $db->Execute($sql);
            }

            if ($debug)
                echo $sql . "<br>";
        }
    }
    ?>
<script>
    window.close();
</script>