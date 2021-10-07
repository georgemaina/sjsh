<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="../accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');


    global $db;

require('roots.php');
require($root_path.'include/care_api_classes/class_ward.php');
$wrd = new Ward ();
    echo "<table width=100% border=1>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>
        Ward Occupancy</font></td></tr>
    <tr><td align=left valign=top width=15%>";
require($root_path."modules/accounting/aclinks.php");
echo '</td>
           <td align=left valign=top width=75%>';

   $sql = "SELECT p.pid,e.encounter_nr, p.name_first,p.name_last,p.name_2,e.encounter_date,
       e.current_ward_nr,w.name,b.`bill_number`,DATEDIFF(NOW(),e.`encounter_date`) AS BedDays 
       ,SUM(IF( b.service_type NOT IN('payment','NHIF'),total,0)) AS bill,
       SUM(IF(b.service_type IN ('payment','NHIF'),total,0)) AS payment,b.`bill_number`,c.`name` as company
       FROM care_encounter e
        LEFT JOIN care_ke_billing b ON e.encounter_nr=b.`encounter_nr`
        LEFT JOIN care_person p  ON e.pid=p.pid
        LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
        LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
        WHERE e.encounter_class_nr=1 AND e.is_discharged=0
        GROUP BY pid
        ORDER BY w.name ASC";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();
?>
  <script>
function printReport(){

     window.open('occupancy_pdf.php' ,"Ward Occupancy Report","menubar=true,toolbar=true,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

function printReport2(){
     window.open('occupancy_pdf.php' ,"Ward Occupancy Report","menubar=true,toolbar=true,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}
</script>
<?php 

echo '<table width=100%><tbody>
                    <tr>
                        <td colspan=11 align=center class="pgtitle">Ward Occupancy</td>
                     </tr>';
?>
 <tr><td colspan=11 align=center><button id="print1" name="print1" onclick="printReport2()">Print Occupancy</button>
                    <input type="submit" id="export1" name="export" value="Export to Excel" onclick="exportOccupancy()"/></td></tr>
            <?php echo ' <tr>
                        <td colspan=11 align=center><hr></td>
                     </tr>
                    <tr>
                        <td>PID</td>
                        <td>Names</td>
                        <td>Admission Date</td>
                        <td>Bill Number</td>
                        <td>BedDays</td>
                        <td>Ward</td>
                        <td>Bed</td>
                        <td align=right>Bill</td>
                        <td align=right>Deposit</td>
                        <td align=right>Balance</td>
                        <td align="right">Payment Method</td>
                     </tr>
                     <tr>
                        <td colspan=11 align=center><hr></td>
                     </tr>';
 $rowbg='white';
 $billTotal=0;
 $paymentTotal=0;
 $totalBal=0;
 $count=0;
    while($row = $result->FetchRow()){
           if($rowbg=='#ccdeeb'){
               $rowbg='white';
           }else{
               $rowbg='#ccdeeb';
           }

        if(!$row[company]){
            $paymentMethod='CASH PAYMENT';
        }else{
            $paymentMethod=$row[company];
        }
        $row2 = $wrd->EncounterLocationsInfo ($row[1] );
        $bed_nr = $row2 [6];
//		$room_nr = $row2 [5];
//		$ward_nr = $row2 [0];
//		$ward_name = $row2 [1];
                $bill=$row[bill];
                $depo=$row[payment];
                $bal=intval($bill-$depo);
                
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td><a href=# onclick="invoicePdf('.$row[0].','.$row[bill_number].')">'.$row[2].' '.$row[3].' '.$row[4].'</a></td>
                        <td>'.$row['encounter_date'].'</td>
                        <td>'.$row['bill_number'].'</td>
                        <td>'.$row['BedDays'].'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$bed_nr.'</td>
                        <td align=right>'.number_format($bill,2).'</td>
                        <td align=right>'.number_format($depo,2).'</td>
                        <td align=right>'.number_format($bal,2).'</td>
                        <td align=right>'.$paymentMethod.'</td>
                     </tr>';
          $billTotal=$billTotal+$bill;
          $paymentTotal=$paymentTotal+ $depo;
        $count=$count+1;
          
    }
    echo ' <tr><td colspan=11 align=center><hr></td></tr>';
    $totalBill=$billTotal;//getTotals('<>');
    $totalDepo=$paymentTotal;//getTotals('=');
    $totalBal=intval($totalBill-$totalDepo);
    echo ' <tr><td colspan=7 align=left><b>Total Patients in Wards are '.$count.'</b></td>
                        <td align=right><b>'.number_format($totalBill,2).'</b></td>
                        <td align=right><b>'.number_format($totalDepo,2).'</b></td>
                        <td align=right><b>'.number_format($totalBal,2).'</b></td>  <td></td></tr>';
    echo ' <tr><td colspan=11 align=center><hr></td></tr>';
  echo '<tr><td colspan=8 align=center><input type="submit" id="print" name="print" value="Print Report" onclick="printReport2()"/>
      <input type="submit" id="export" name="export" value="Export to Excel" onclick="exportOccupancy()"/></td></tr>';
  echo '</tbody></table>
         </td>
  </tr></table>';
  
function getBalance($pid,$sign,$enc_nr){
    global $db;
    
    $sql="select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.pid=$pid and e.encounter_nr=$enc_nr and b.service_type $sign'Payment' and 
    b.`IP-OP`=1 AND e.is_discharged=0 group by b.pid";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
       return $row[0];
    }else{
        return '0';
    }
    
}

function getTotals($sign){
    global $db;
    
    $sql="select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.service_type $sign'Payment' and 
    b.`IP-OP`=1 AND e.is_discharged=0";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
       return $row[0];
    }else{
        return '0';
    }
}

?>

<script>

function invoicePdf(name,bills){
  
        str2='ON';
    
      // window.open('detail_invoice_pdf.php?pid='+name+"&receipt="+str2 ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
       window.open('detail_invoice_pdf.php?pid='+name+"&receipt=ON&nhif=ON&final=1&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");

}

function exportOccupancy(){
       window.open('exportOccupancy.php',"Bed Occupancy","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes"); 
  
}


</script>