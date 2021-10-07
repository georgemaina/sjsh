<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
$desc2=$_REQUEST['q'];
$desc3=$_REQUEST['q2'];

printform($desc2,$desc3);

function printform($desc2,$desc3){

echo ' <div class=pgtitle>Shift Report for '.$desc2.' and Shift No '.$desc3.'</div>';
echo '<table border="0"  width="100%">
        <tr>
           <td align=right>
                '.getInvoices($desc2,$desc3).'
         </td>
  </tr></table>';
}

function getInvoices($desc2,$desc3) {
global $db;

echo '<table width=100%><tbody>';

    // report summary grouped by revenue codesSELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$desc2' and shift_no='$desc3'";
    echo '<tr><td colspan=7>
              <center>MASENO HOSPITAL</center>
              <center>CASHIERS SUMMARY REPORT</center>
               <center> SHIFT NO <B>'.$desc3.'</B> DATE <B>'.date("F j, Y, g:i a").'</B></center></td>
          </tr>';
    echo '<tr><td colspan=4><hr></td><tr><td colspan=4>
                  <table>
                      <tr class="prow"><td colspan=4>Breakdown of Cash Sale</td></tr>
                      <tr class="prow">
                                <td>rev code</td>
                                <td>rev desc</td>
                                <td>pay mode</td>
                                <td>amount</td>
                    </tr>';
            $sql = "select  rev_code,rev_desc, pay_mode, sum(total) as total from care2x.care_ke_receipts
                    WHERE cash_point='$desc2' and shift_no='$desc3'
                    group by rev_code";
            $result=$db->Execute($sql);
            while ($row = $result->FetchRow($result)){
                   echo '<tr bgcolor='.$rowbg.'>
                                <td>'.$row[0].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td></tr>';
                          $rowbg='white';
            }
            echo "</table></td></tr>";

            $sql = "SELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$desc2' and shift_no='$desc3'";
            $result=$db->Execute($sql);
            $row = $result->FetchRow($result);
            echo "<tr><td colspan=4 align=center><br> <b>Total ".$row[0]."</b></td><tr>";
            
             
  echo '</tbody></table>';
}
?>

