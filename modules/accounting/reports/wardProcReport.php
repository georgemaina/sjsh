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
    echo "<table width=100% border=1>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>
        Inpatient Ward Procedures Revenue</font></td></tr>
    <tr><td align=left valign=top width=15%>";
require($root_path."modules/accounting/aclinks.php");
echo '</td>
           <td align=left valign=top width=75%>';
                 $sql = "SELECT d.partcode,d.item_description,COUNT(k.total) AS WCOUNT,SUM(k.total) AS Amount FROM care_ke_billing k
LEFT JOIN care_tz_drugsandservices d  ON d.partcode=k.partcode WHERE d.category IN(48,58)
GROUP BY d.partcode";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
                    <tr>
                        <td colspan=9 align=center class="pgtitle">Ward Procedures Revenue</td>
                     </tr>
                     <tr>
                        <td colspan=9 align=center><hr></td>
                     </tr>
                    <tr>
                        <td>Procedure Code</td>
                        <td>Description</td>
                        <td>Month to Date Count</td>
                        <td>Month to Date Revenue</td>
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
                     </tr>';
                 $rowbg='white';
    }
  echo '<tr><td colspan=10 align=center><input type="submit" id="print" name="print" value="Print Report" onclick="printReport()"/>
      <input type="submit" id="export" name="export" value="Export to Excel" /></td></tr>';
  echo '</tbody></table>
         </td>
  </tr></table>';
}



?>

<script>
function printReport(){

     window.open('wardProcReport_1.php' ,"Ward Procedures Revenue","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

</script>