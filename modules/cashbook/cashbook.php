<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<script type="text/javascript">

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
}

document.onkeypress = stopRKey;

</script> 
<?php require_once 'mylinks.php'?>

        <?php
//            global $shift_no;
            
            $pg=$_REQUEST[page];
            switch($pg) {
                case "startShift":
                    $page="startShift.php";
                    break;
                case "cashpoints":
                    $page="cashpoints.php";
                    break;
                case "PayModes":
                    $page="payment_modes.php";
                    break;
                case "Cash_Sale":
                    $page="cash_sale.php";
                    break;
                case "RevenueCodes":
                    $page="revenuecodes.php";
                    break;
                case "Payments":
                    $page="payments.php";
                    break;
                case "Receipts":
                    $page="receipts.php";
                    break;
                case "GH_Sales":
                    $page="GH_Sales.php";
                    break;
                case "GH_sales_adj":
                    $page="GH_sales_adj.php";
                    break;
                 case "endShift":
                    $page="cashpoints.php";
                    break;
                case "PharmCodes":
                    $page="pharmcodes.php";
                    break;
                case "proccodes":
                    $page="procedure_codes.php";
                    break;
                case "shiftReport":
                    $page="reports/Shift_report.php";
                    break;
                case "collSummary":
                    $page="reports/collSummary.php";
                    break;
                 case "shiftSummary":
                    $page="reports/shiftSummary.php";
                    break;
                 case "writer":
                    $page="testTransfere.php";
                    break;
                default:
                    $page="welcome.php";
                    break;
        }
        
       ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <td align="center" valign="top">
                    <?php
                     
                    printcashbook($page);

                     ?></td>
                </tr>
                

            </tbody>
        </table>


