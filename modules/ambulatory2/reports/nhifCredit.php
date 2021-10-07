<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="../accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
printform();

function printform(){
    global $db;

require('roots.php');
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>NHIF Credits</font></td></tr>
    <tr><td align=left valign=top width=15%>";
require($root_path."modules/ambulatory2/aclinks.php");
echo '</td>
           <td align=left valign=top width=75%>';
                 $sql = "SELECT creditNo,inputDate,admno,NAMES,admDate,disDate,wrdDays,nhifNo,nhifDebtorNo,
	debtorDesc, totalCredit
	FROM care2x.care_ke_nhifcredits";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
                    <tr>
                        <td colspan=9 align=center class="pgtitle">NHIF Credits</td>
                     </tr>
                     <tr>
                        <td colspan=9 align=center><hr></td>
                     </tr>
                    <tr>
                        <td>Claim No</td>
                        <td>Date</td>
                        <td>Adm No</td>
                        <td>Names</td>
                        <td>Amount</td>
                        <td>Description</td>
                        <td>G/L Acc</td>
                        <td>User</td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td colspan=9 align=center><hr></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td>'.$row[1].'</td>
                        <td>'.$row[2].'</td>
                        <td>'.$row[3].'</td>
                        <td>'.$row[10].'</td>
                        <td> NHIF CARD '.$row[7].'</td>
                        <td>'.$row[8].'</td>
                        <td>Admin</td>
                        <td><a href="refreshInvoice.php?pid='.$row[0].'">refresh</a></td>
                        <td><a href="printInvoice.php?pid='.$row[0].'">print</a></td>
                     </tr>';
                 $rowbg='white';
    }
  echo '<tr><td colspan=10 align=center><input type="submit" id="print" name="print" value="Print Report" />
      <input type="submit" id="export" name="export" value="Export to Excel" /></td></tr>';
  echo '</tbody></table>
         </td>
  </tr></table>';
}



?>

