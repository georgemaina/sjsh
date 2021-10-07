<?php
require '../../../config.php';

printform();


function printform(){
echo ' <div class=pgtitle>Patient Bill Management</div>';
echo '<table border="0">
        <tr><td align=left>';
require_once "rpt_accounting_menu.php";
echo '</td>
           <td align=right>
                <table class="style3" border=0><tr>
                    <td>';
require_once 'psearch.php';
echo ' </td></tr>
                    <tr>
                        <td>
            <div id="txtHint"><b>Person Invoice will be Displayed here.</b></div>

  </td>
                    </tr>

              </table>
         </td>
  </tr></table>';
}
function getInvoice($pid) {
    $link = mysql_pconnect($mysql_host, $mysql_user, $mysql_pass);
    $db = mysql_select_db ($mysql_db);
    $result = mysql_query("SELECT name_first,name_2,name_last FROM care_person WHERE pid='$desc3'");

    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

    $row = mysql_fetch_row($result);

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