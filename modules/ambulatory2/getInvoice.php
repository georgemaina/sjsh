<?php
// we'll generate XML output

require('roots.php');
require($root_path.'include/inc_environment_global.php');
$name = $_GET['desc'];
if($name=='notinsured'){
          $sql = "SELECT b.pid,b.name_first,b.name_2,b.name_last, SUM(a.total), a.bill_number,a.insurance_id
                FROM care_ke_billing a
                INNER JOIN care_person b ON a.pid=b.pid
                WHERE a.insurance_id is null and a.`ip-op`=2
                GROUP BY b.pid";
}else{
     $sql = "SELECT b.pid,b.name_first,b.name_2,b.name_last, SUM(a.total), a.bill_number,c.name
                FROM care_ke_billing a
                INNER JOIN care_person b ON a.pid=b.pid
                INNER JOIN care_tz_company c ON b.insurance_id=c.id
                WHERE c.id='".$name."' and a.`ip-op`=2
                GROUP BY b.pid";
}
        $result=$db->Execute($sql);
        //$row=$result->FetchRow();
        
       echo '<table width=100%><tr class="prow">
                        <td>PID</td>
                        <td>First Name</td>
                        <td>Surname Name</td>
                        <td>Last Name</td>
                        <td>Bill Number</td>
                        <td>Amount</td>
                        <td>Insurance</td>
                        <td></td>
                        <td></td>
                     </tr>';
        while($row = $result->FetchRow($result)){
            $rowbg='white';
              echo '<tr bgcolor='.$rowbg.'>
                            <td>'.$row[0].'</td>
                            <td>'.$row[1].'</td>
                            <td>'.$row[2].'</td>
                            <td>'.$row[3].'</td>
                            <td>'.$row[5].'</td>
                            <td align=right>Ksh.<b>'.$row[4].'</b></td>
                            <td align=center>'.$row[6].'</a></td>
                            <td align=center><img src="../../gui/img/control/default/en/en_process.gif" width="76" height="16" alt="en_process" onclick="processInvoice('.$row[0].')"/></td>
                            <td align=center><a href="reports/refreshInvoice.php?pid='.$row[0].'">print</a></td>
                         </tr>
                          <tr><td id="patientDetails" colspan=10></td></tr>';
                     $rowbg='white';
        }
      echo '</table>';
//echo 'patient is '.$name;
?>
