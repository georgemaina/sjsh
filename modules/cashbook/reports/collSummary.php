<link rel="stylesheet" type="text/css" href="reports.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once '../mylinks.php';
require_once 'shift_search.php';
jsIncludes();?>

<script type="text/javascript" src="selectShiftSummary.js"></script>
<?php

printform($db);

function printform($db){?>

<div class=pgtitle>Collection Summary Report</div>
<table border="0" width="100%">
        <tr><td align=left width="15%" valign="top"> <?php
   require_once 'reports_menus.php';
    ?>
</td><td valign=top width="85%">

    <table wdith="100%"><tr><td>
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
               <td id="txtHint" valign="top"><b>Shift Report will be Displayed here.</b>
                </td>
           </tr>
            </table>

</td>
  </table>
<?php
}


?>
<script>
function invoicePdf(cashpoint,shiftno){
    //alert("Hellp");
     window.open('collsummary_pdf.php?cashpoint='+cashpoint+'&shiftno='+shiftno ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

</script>


