<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
$q=$_GET["q"];
$q2=$_GET["q2"];

if (!$link)
  {
  die('Could not connect: ' . mysql_error());
  }


echo "<hr><table border='0' width=100%>
<tr><td valign=top><b>SOWETO KAYOLE PCA</b></td><td>P.O. BOX 30690 -00100<BR>NAIROBI
<br>Phone: 020 65998992<br>Fax: 020 32656666<br>email: info@chak.or.ke<br>web: www.chak.or.ke
    </td>
</tr>
<tr><td colspan=2><hr></td></tr>
<tr><td>";

    $sql = "SELECT * FROM care_ke_receipts WHERE cash_point='$q' and Shift_no='$q'";
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
                        <td>Refno</td>
                        <td>Name</td>
                        <td>type</td>
                        <td>time</td>
                        <td>rev code</td>
                        <td>rev desc</td>
                        <td>amount</td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[Refno].'</td>
                        <td>'.$row[name].'</td>
                        <td>'.$row[type].'</td>
                        <td>'.$row[input_time].'</td>
                        <td>'.$row[rev_code].'</td>
                        <td>'.$row[rev_desc].'</td>
                        <td align=right>Ksh.<b>'.$row[amount].'</b></td>
                        
                     </tr>';
                 $rowbg='white';
    }
  echo '</tbody></table>';
                    
echo "</td></tr></table>";?>
<br><br>
 <center><button onclick="invoicePdf(<?php echo $q ?>)" id="printInv">Print Invoice</button></center>


<?php
mysql_close($link);
?>
