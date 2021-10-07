<link rel="stylesheet" type="text/css" href="reports.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once '../mylinks.php';
require_once 'shift_search.php';
jsIncludes();?>

<script type="text/javascript" src="select_summary.js"></script>
<?php
printform($db);

function printform($db){?>

 <div class=pgtitle>Shift Report</div>
 <table border="1" width="100%">
    <tr><td align="left" valign="top" colspan="2" width="15%"> <?php
   require_once 'reports_menus.php';
    ?>
</td><td valign="top" width="85%">

    <table><tr><td>
             <?php $sql = "Select pcode,name,next_receipt_no from care_ke_cashpoints";

                        $result=$db->Execute($sql);
                        if (!($row=$result->FetchRow())) {
                            echo 'shift Could not run query: ' . mysql_error();
                            exit;
                        }?>
                <select id="cash_point" name="cash_point" onchange="initPsearch()">
                    <?php
                        if(!isset($point)) {
                            while($row=$result->FetchRow()) { ?>
                    <option value=<?php echo $row[0]?>><?php echo $row[0]?></option>
                           <?php }
                        }
              ?>
              </select>
              <input type='text' id='shift_No' name='shift_No'>
              <input type="submit" id="getShift" value="Preview" onclick="getShiftReport(document.getElementById('cash_point').value,document.getElementById('shift_No').value)">
              
            </td></tr>
       <tr>
         <td id="txtHint"><b>Shift Report will be Displayed here.</b></td>
       </tr>
    </table>

</td>
  </tr></table>
<?php
}


?>
<script>
function invoicePdf(name){
    //alert("Hellp");
     window.open('shiftSummary_pdf.php?pid='+name ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

</script>


