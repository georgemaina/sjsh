
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');

printform();

function printform(){

echo ' <div class=pgtitle>Patient Bill Management</div>';
echo '<table border="0">
        <tr><td align=left>';
require_once "rpt_accounting_menu.php";
echo '</td>
           <td align=right>
                '.getInvoices().'
         </td>
  </tr></table>';
}

function getInvoices() {
global $db;
    $sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.price), a.bill_number
                        from care2x.care_ke_billing a
                        inner join care_person b on a.pid=b.pid where a.`IP-OP`=1
                        group by b.pid";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();
                        
echo '<table class="style3"><tbody>
                    <tr>
                        <td colspan=7 align=center class="pgtitle">Pending Invoices</td>
                     </tr>
                     <tr>
                        <td colspan=7 align=center><hr></td>
                     </tr>
                    <tr class="prow">
                        <td>PID</td>
                        <td>First Name</td>
                        <td>Surname Name</td>
                        <td>Last Name</td>
                        <td>Bill Number</td>
                        <td>Amount</td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td>'.$row[1].'</td>
                        <td>'.$row[2].'</td>
                        <td>'.$row[3].'</td>
                        <td>'.$row[5].'</td>
                        <td align=right>Ksh.<b>'.$row[4].'</b></td>
                        <td><a href="view.php">View</a></td>
                     </tr>';
                 $rowbg='white';
    }
  echo '</tbody></table>';
}
?>

