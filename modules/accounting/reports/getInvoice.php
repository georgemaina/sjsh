<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
$q = $_GET["q"];
$r = $_GET["r"];
$nhif = $_GET["nhif"];
$billNumber = $_GET["billNumbers"];
require '../../../include/care_api_classes/class_ward.php';
//require('../../../include/class_ward.php');
//require('../../../include/care_api_classes/class_encounter.php');
$wrd = new Ward ();

//if (!$db)
//{
//die('Could not connect: ' . mysql_error());
//}
$sql5 = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `ip-op`=1 and bill_number=$billNumber";
//echo $sql5;
if ($result5 = $db->Execute($sql5)) {
    $row5 = $result5->FetchRow();
//echo $sql5;
    $row2 = $wrd->EncounterLocationsInfo2($row5 ['encounter_nr']);
    $bed_nr = $row2 [6];
    $room_nr = $row2 [5];
    $ward_nr = $row2 [0];
    $ward_name = $row2 [1];
    $adm_date = $row2 [7];
    $dis_date = $row2 [8];

    $sql1 = 'select * from care_ke_invoice';
    $result1 = $db->execute($sql1);
    $row = $result1->FetchRow();

    echo "<hr><table border='0' width=100%>
<tr><td valign=top><b>" . $row['CompanyName'] . "<br>" . $row['Address'] . " -" . $row['Postal'] . " <br>" . $row['Town'] . "
<br>Phone: " . $row['Tel'] . "<br>Fax: " . $row['fax'] . "<br>email: " . $row['email'] . " </b></td><td valign=top>
Invoice No:" . $row5['bill_number'] . "<br> Date:" . date('Y-m-d') . "</td>
</tr>
<tr><td colspan=2><hr></td></tr>";



    $sql = "SELECT distinct
    care_person.pid
    , care_person.name_first
    , care_person.name_2
    , care_person.name_last
    , care_person.date_birth
    , care_person.addr_zip
    , care_person.cellphone_1_nr
    , care_person.citizenship
    , care_encounter.`encounter_class_nr`
FROM
    care_encounter
    INNER JOIN care_person
        ON (care_encounter.pid = care_person.pid)
WHERE (care_encounter.`encounter_class_nr`='1' and care_encounter.pid='" . $q . "')";

    $result = $db->Execute($sql);

    while ($row = $result->FetchRow()) {
        echo "<tr>";

        echo "<td valign=top><b>Patient Name:</b> " . $row['name_first'] . " " . $row['name_2'] . " " . $row['name_last'] . "
      <br><br><b>Date: " . date("F j, Y, g:i a") . " </b></td>
        <td valign=top><b>Address:</b>P.o. Box" . $row['addr_zip'] . "<br> <b>Phone: </b>" . $row['phone_1_nr'] . "<br>
            <b>Location: </b>" . $row['district'] . " <br>
             admDate: $adm_date <br>Disc_date:$dis_date<br>
             ward_name :$ward_nr <br>room_nr:$bed_nr
             <br>Bed No:$bed_nr
</td>
        <tr><td colspan=3>";
    }
    $sql3 = "SELECT
      i.`item_Cat` AS service_type
    , sum(price) as price
    , sum(qty) as qty
    , sum(total) as total
FROM
    care_ke_billing b
LEFT JOIN care_tz_drugsandservices d ON b.`partcode`=d.`partcode`
LEFT JOIN care_tz_itemscat i ON d.`category`=i.`catID`
WHERE (pid ='" . $q . "' AND service_type not in
    ('Payment','payment adjustment','NHIF') and `ip-op`=1) and bill_number=$billNumber group by d.`category`";
//echo $sql3;
    if ($result3 = $db->Execute($sql3)) {

        echo "<table width=100% border=0>
              <tr>  <td class='tdbolder'><b>Description of Service</b></td>
                  
                    <td class='tdbolder'><b>Amount</b></td>
              </tr>";

        while ($row2 = $result3->FetchRow()) {
            if ($row2['IP-OP'] = 1) {
                echo "<tr>  <td>" . $row2['service_type'] . "</td>
                 
                    <td>" . $row2['total'] . "</td>
              <tr>";
            } else {
                echo " <tr>
                        <td class='tdbolder' colspan=2>This Patient Is not an Inpatient
                                            <br >Please Preview the Report In Outpationt Module.</td>
                   <tr><b>";
            }
        }
    } else {
        echo "<table width=100% border=0>
         <tr><td class='tdbolder' colspan=2>There are no Pending Bills for this Patient.</td>
         <tr><b>";
    }

    $sql6 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=1 and 
service_type not in('payment','payment adjustment','NHIF') and bill_number=$billNumber";

    $result6 = $db->Execute($sql6);
    if ($row6 = $result6->FetchRow()) {
        
        echo " <tr><td class=tbtopborder align=right><b>Total Bill:</b></td>
               <td class='tdbolder' align=left><b>Ksh." . number_format($row6['total'], 2) . "</b></td></tr> ";
        $total_bill = $row6['total'];
    }


    if ($r <> '') {
        echo "<tr><td class=tbtopborder colspan=2>&nbsp; </td></tr>";
        $sqli = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=1 and service_type 
in('payment','payment adjustment','nhif') and bill_number=$billNumber";

        $resulti = $db->Execute($sqli);
        while ($rowi = $resulti->FetchRow()) {

            echo "<tr><td >" . $rowi['bill_date'] . "
         " . $rowi['Description'] . "(" . intval($rowi['batch_no']) . ")</td>
       <td>" . "Ksh" . number_format(intval($rowi['total']), 2) . "</td>
   <tr>";
        }
    }

    $ntotals=0;
    if ($nhif <> '' and $r == '') {
        echo "<tr><td class=tbtopborder colspan=2>&nbsp; </td></tr>";
        $sqli = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=1 and rev_code IN('nhif','nhif2') and bill_number=$billNumber";

        $resulti = $db->Execute($sqli);
        while ($rowi = $resulti->FetchRow()) {

            echo "<tr><td >" . $rowi['bill_date'] . "
         " . $rowi['Description'] . " (" . intval($rowi['batch_no']) . ")</td>
       <td>" . "Ksh" . number_format(intval($rowi['total']), 2) . "</td>
   <tr>";
            if($rowi['rev_code']=='nhif2'){
                $nhifGainLoss=$rowi['total'];
            }
            
             if($rowi['rev_code']=='nhif'){
                $nhifDebtorAcc=$rowi['total'];
            }
        }
    }
    
//    


    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=1 and 
service_type in ('payment','payment adjustment','NHIF') AND REV_CODE<>'nhif2' and bill_number=$billNumber";

    $result2 = $db->Execute($sql2);
    if ($row2 = $result2->FetchRow()) {
    $totalPaid = $row2['total'];
        if ($r == 'ON') {
            
            echo " <tr><td align=right><b>Total Paid:</b></td>
                <td class='tdbolder' align=left><b>Kshs." . number_format($row2['total'],2) . "</b></td></tr> ";
        }
    }

    if($nhifGainLoss>0){
        $bal = intval($nhifGainLoss-$total_bill);
    }else{
        $bal = intval($total_bill-$totalPaid);
    }
    
    echo " <tr><td align=right><b>AMOUNT DUE:</b></td>
                            <td class='tdbolder' align=left><b>Ksh.";
    echo number_format($bal,2);
    echo "</b></td></tr> ";

    echo "</table>";
    ?>
    <br><br>
    <center><button onclick="invoicePdf('<?php echo $q ?>', '<?php echo "$r" ?>')" id="printInv">Print Invoice</button></center>

    <?php
} else {
    echo 'Please select bill number';
}
?>
<!--<script>
    function invoicePdf(name,receiptN){
        //alert("Hellp");
        window.open('detail_invoice_pdf.php?r=ON&pid='+name+'' ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

</script>-->
