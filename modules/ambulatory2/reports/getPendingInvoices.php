<?php
require('roots.php');
require($root_path.'include/inc_environment_global.php');

    $sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.total), a.bill_number,prescribe_date
                        from care2x.care_ke_billing a
                        inner join care_person b on a.pid=b.pid where a.`IP-OP`=2
                        group by b.pid";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
                     <tr>
                        <td colspan=9 align=center><hr></td>
                     </tr>
                    <tr class="prow">
                        <td>PID</td>
                        <td>Date</td>
                        <td>First Name</td>
                        <td>Surname Name</td>
                        <td>Last Name</td>
                        <td>Bill Number</td>
                        <td>Amount</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td>'.$row[6].'</td>
                        <td>'.$row[1].'</td>
                        <td>'.$row[2].'</td>
                        <td>'.$row[3].'</td>
                        <td>'.$row[5].'</td>
                        <td align=right>Ksh.<b>'.$row[4].'</b></td>
                        <td><a href="refreshInvoice.php?pid='.$row[0].'">View</a></td>
                        <td><a href="refreshInvoice.php?pid='.$row[0].'">refresh</a></td>
                        <td><button onclick="invoicePdf('.$row[0].')" id="printInv">Print Invoice</button></td>
                     </tr>';
                 $rowbg='white';
    }
  echo '</tbody></table>';


?>
