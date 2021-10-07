<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');

printform();


function printform(){
global $db;
echo ' <div class=pgtitle>Patient Bill Management</div>';
echo '<table border="0">
        <tr><td align=left>';
echo '</td>
           <td align=right>
                <table border=0><tr>
                    <td>';
require_once 'reports/psearch.php';
echo ' </td></tr>
                    <tr>
                        <td id="txtHint">
            <b>Person Invoice will be Displayed here.</b>

  </td>
                    </tr>

              </table>
         </td>
  </tr></table>';
}
function getInvoice($pid) {
    global $db;

    $sql = "SELECT name_first,name_2,name_last FROM care_person WHERE pid='$desc3'";

     $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

   $row=$result->FetchRow();

    echo $row[0]." ".$row[1]." ".$row[2]; // 42

    echo '<table border="1">
                                 <tbody>
                                    <tr>
                                        <td></td>
                                     </tr>
                                </tbody>
                            </table>
                                ';
}
?>
<script>
function invoicePdf(name){
    //alert("Hellp");
     window.open('summary_invoice_pdf.php?pid='+name ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

</script>