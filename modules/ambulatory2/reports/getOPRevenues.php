<?php
require('roots.php');
require($root_path.'include/inc_environment_global.php');

    $sql = "select rev_code,rev_desc,type,debit,sum(total) as total from care2x.care_ke_receipts group by rev_code";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
                     <tr>
                        <td colspan=9 align=center><hr></td>
                     </tr>
                    <tr class="prow">
                        <td>Revenue Code</td>
                        <td>Description</td>
                        <td>Debits</td>
                        <td>Cash Sales</td>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td>'.$row[1].'</td>
                        <td>'.$row[3].'</td>
                        <td>'.$row[2].'</td>
                        <td align=right>Ksh.<b>'.$row[4].'</b></td>
                        <td><a href="refreshInvoice.php?pid='.$row[0].'">View</a></td>
                        <td><a href="refreshInvoice.php?pid='.$row[0].'">refresh</a></td>
                        <td><button onclick="invoicePdf('.$row[0].')" id="printInv">Print Invoice</button></td>
                     </tr>';
                 $rowbg='white';
    }
  echo '</tbody></table>';


?>
