<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
$q = $_GET["q"];
$r = $_GET["r"];
$nhif = $_GET["nhif"];
$final = $_GET["Final"];
$billNumber = $_GET["billNumbers"];
    $debug=false;
require '../../../include/care_api_classes/class_ward.php';
//require('../../../include/class_ward.php');
//require('../../../include/care_api_classes/class_encounter.php');
$wrd = new Ward ();
  $nhifdebited=false;
//if (!$db)
//{
//die('Could not connect: ' . mysql_error());
//}
$sql5 = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 and bill_number=$billNumber";
    if($debug) echo $sql5;
if ($result5 = $db->Execute($sql5)) {
    $row5 = $result5->FetchRow();

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
        <br>Phone: " . $row['Tel'] . "<br>Fax: " . $row['fax'] . "<br>email: " . $row['email'] . " </b>
    </td><td valign=top>Invoice No:" . $row5['bill_number'] . "<br> Date:" . date('Y-m-d') . "</td>
</tr>";
    $sql = "SELECT id,accno,`name` FROM care_tz_company WHERE id=(SELECT insurance_id FROM care_person WHERE pid=$q)";
    if ($insu_result = $db->Execute($sql)) {
        $insu_row = $insu_result->FetchRow();
        if ($insu_row) {
            echo "<tr><td align=left><b>Account No<b> $insu_row[1] </td></tr>
               <tr><td align=left><b>Account Name</b>  $insu_row[2]";

        }
    }


    echo "<br><div class='billCloseRpt' id='closebill'></div>
    </td></tr><tr><td colspan=2><hr></td></tr>";



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
WHERE (care_encounter.`encounter_class_nr`='2' and care_encounter.pid='" . $q . "')";
//echo $sql;
    $result = $db->Execute($sql);

    if ($row = $result->FetchRow()) {
        echo "<tr>";

        echo "<td valign=top><b>Patient Name:</b> " . $row['name_first'] . " " . $row['name_2'] . " " . $row['name_last'] . "
      <br><br><b>Date: " . date("F j, Y, g:i a") . " </b></td>
        <td valign=top><b>Address:</b>P.o. Box" . $row['addr_zip'] . "<br> <b>Phone: </b>" . $row['phone_1_nr'] . "<br>
            <b>Location: </b>" . $row['district'] . " <br>
             admDate: $adm_date <br>Disc_date:$dis_date<br>
             ward_name :$ward_nr <br>room_nr:$bed_nr
             <br>Bed No:$bed_nr
</td>
        <tr><td colspan=2>";
    }
    $sqlS = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 and 
service_type NOT IN ('payment','payment adjustment','NHIF') and bill_number=$billNumber order by bill_date asc";

    if ($resultS = $db->Execute($sqlS)) {

        echo "<table width=100% border=0>
                            <tr><td class='tdbolder'><b>Date</b></td><td class='tdbolder'><b>Type</b></td><td class='tdbolder'>
                            <b>Description</b></td><td class='tdbolder'><b>P-Price</b></td><td class='tdbolder'><b>Qty</b></td>
                            <td class='tdbolder'><b>Total</b></td><tr><b>";

        while ($rowS = $resultS->FetchRow()) {
            if ($rowS['IP-OP'] == 2) {

                echo "<tr>  <td>" . $rowS['prescribe_date'] . "</td>
                        <td>" . $rowS['service_type'] . "</td>
                        <td>" . $rowS['Description'] . "</td>
                        <td>" . $rowS['price'] . "</td>
                        <td>" . (($rowS['qty'] > 0) ? $rowS['qty'] : 0) . "</td>
                        <td>" . intval($rowS['total']) . "</td>
                  <tr>";
            } else {
                echo " <tr><td class='tdbolder' colspan=6>This Patient Is not an Outpatient
                                            <br >Please Preview the Report In Outpationt Module.</td><tr><b>";
            }
        }
    } else {
        echo "<table width=100% border=1>
          <tr><td class='tdbolder' colspan=5>There are no Pending Bills for this Patient.</td><tr><b>";
    }

    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 
and service_type NOT IN ('payment','payment adjustment','NHIF') and bill_number=$billNumber";

    $result2 = $db->Execute($sql2);
    if ($row2 = $result2->FetchRow()) {
        $totalBill = $row2['total'];
        echo " <tr><td colspan=5 class=tbtopborder align=right><b>Total Bill:</b></td>
                            <td class='tdbolder' align=left><b>Ksh." . number_format($totalBill,2) . "</b></td></tr> ";
    }

    if ($r <> '') {
        echo "<tr><td class=tbtopborder colspan=6>&nbsp; </td></tr>";
        $sqli = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 and service_type
in ('payment','payment adjustment','NHIF') and bill_number=$billNumber";

        $resulti = $db->Execute($sqli);
        $ntotals=0;
        while ($rowi = $resulti->FetchRow()) {

            echo "<tr><td >" . $rowi['bill_date'] . "</td>
        <td>Bill </td>
       <td>" . $rowi[service_type] . "</td>
       <td>" . $rowi[Description] . "(" . intval($rowi['batch_no']) . ")</td>
       <td>Ksh</td>
       <td>" . number_format(intval($rowi['total']),2) . "</td>
   <tr>";
            if($rowi['rev_code']<>'nhif2')  {  
                $ntotals=$ntotals+$rowi['total'];
            } 
        }
        $totalPaid=$ntotals;
    }

    if ($nhif <> '' and $r=='') {
    $nhifdebited=true;
        echo "<tr><td class=tbtopborder colspan=6>&nbsp; </td></tr>";
        $sqli = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 and rev_code IN('NHIF','NHIF2') and bill_number=$billNumber";

        $resulti = $db->Execute($sqli);
        $ntotal=0;
        while ($rowi = $resulti->FetchRow()) {

            echo "<tr><td >" . $rowi['bill_date'] . "</td>
                        <td>Bill </td>
                       <td>" . $rowi[service_type] . "</td>
                       <td>" . $rowi[Description] . "(" . intval($rowi['batch_no']) . ")</td>
                       <td>Ksh</td>
                       <td>" . intval($rowi['total']) . "</td>
                <tr>";
             if($rowi['rev_code']<>'nhif2')  {  
                $ntotal=$ntotal+$rowi['total'];
            } 
        }
        $totalPaid=$ntotal;
    }


    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `ip-op`=2 and service_type in 
('payment','payment adjustment','NHIF') and bill_number=$billNumber";

    $result2 = $db->Execute($sql2);
    if ($row2 = $result2->FetchRow()) {
       
        //if ($r <> '') {
            echo " <tr><td colspan=5 class=tbtopborder align=right><b>Total Paid:</b></td>
                <td class='tdbolder' align=left><b>Ksh." . number_format($totalPaid,2). "</b></td></tr> ";
//            echo " <tr><td colspan=5 class=tbtopborder align=right><b>Amount Due:</b></td>
//                <td class='tdbolder' align=left><b>Ksh." . $bal . "</b></td></tr> ";
        //}
    }
    
        if($nhifdebited){
            $bal=$totalPaid;
         }else{
            $bal=$totalBill-$totalPaid;
         }
    
    echo " <tr><td colspan=5  align=right><b>AMOUNT DUE:</b></td>
      <td class='tdbolder' align=left><b>Ksh." . number_format($bal,2) . "</b></td></tr> ";
    echo "</table>";
    ?>
    <br><br>
    <center><button onclick="invoicePdf('<?php echo $q ?>', '<?php echo "$r" ?>')" id="printInv">Print Invoice</button></center>
        <?php
    } else {
        echo 'Please select the bill number';
    }
    ?> 
