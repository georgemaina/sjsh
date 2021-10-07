<?php

function getPostVals($_POST) {
    foreach($_POST as $key=>$value) {
        // returns "-2"
        if(strstr($key,"gridbox")) {

            $rest = substr($key, -2);
            switch($rest) {
                case "_0":
                    echo $key="<b>rev_code</b>";
                    break;
                case "_1":
                    echo $key="<b>rev_Desc</b>";
                    break;
                case "_2":
                    echo $key="<b>Proc_code</b>";
                    break;
                case "_3":
                    echo $key="<b>Prec_Desc</b>";
                    break;
                case "_4":
                    echo $key="<b>amount</b>";
                    break;
                case "_5":
                    echo $key="<b>proc_qty</b>";
                    break;
                case "_6":
                    echo $key="<b>total</b>";
                    break;
                default:
                    echo $key="<b>".substr($key,0, -2)."</b>";
                    break;
            }
        }
        $arr=$rest.":".$value."<br>";
        echo $arr;
    }
}

function jsIncludes() {
    ?>
<!-- dhtmlxWindows -->

<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<!--<script src='codebase/dhtmlxcommon_debug.js'></script>-->
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- Display menus-->
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">
<script  src="../../include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
<script  src="../../include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='codebase/dhtmlxgrid.css'>

<script src='../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<script src='../../include/dhtmlxGrid/codebase/dhtmlxgrid_form.js'></script>
<script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>

<script src="../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor.js'></script>
<!--<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>-->

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>

<!-- dhtmlxCombo -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCombo/codebase/dhtmlxcombo.css">
<script>window.dhx_globalImgPath="../../include/dhtmlxCombo/codebase/imgs/";</script>
<script src="../../include/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>


<script src='../../include/dhtmlxConnector/codebase/connector.js'></script>
<!-- New Grid Editor-->


    <?php
}
function doLinks() {
    echo '<table align=left >';
    echo '<tr class="tr1"><td><a href="startShift.php?command=Start">Start Shift</a></td></tr>';
    echo "<tr class='tr1'><td><a href=\"cashpoints.php\">Cashpoints</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"payment_modes.php\"> Payment Modes</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"Cash_Sale.php\">Cash Sale</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"Cash_Sale_adj.php\">Cash Sale Adjustment</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"RevenueCodes.php\">Revenue Codes</a></td>";
    echo "<tr class='tr1'><td><a href=\"PharmCodes.php\">Pharmacy Codes</a></td>";
    echo "<tr class='tr1'><td><a href=\"Payments.php\">Payments</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"Payments_adj.php\">Payments Adjustment</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"reprint.php\">Reprint</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"Receipts.php\">Receipts</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"Receipts_adj.php\">Receipts Adjustment</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"procedure_codes.php\">Procedure Codes</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"testTransfere.php\">Sales</a></td></tr>";
    echo "<tr class='tr1'><td><a href=\"startShift.php?command=End\">End Shift</a></td></tr>";
    echo "</table>";
}
function displaySaleForm($db) {

    $point=$_REQUEST[cashpoint];
    $shiftNo=$_REQUEST[shiftNo];
    require_once 'dhxMenus.php';

    echo "<form name=\"csale\" method=\"POST\" action=". $_SERVER['PHP_SELF'] . ">";

    require_once 'cashform.php';
    //echo "<div class=\"container\" style=\"width:600px\">";
    //echo "<table width=\"100%\" class=\"style4\">";
    echo "<tr><td colspan=6 align=center><br><div id=\"gridbox\" height=\"200px\" style=\"background-color:white;\"></div></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Total:<input type=\"text\" name=\"total\" id=\"total\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Paid: <input type=\"text\" name=\"paid\" id=\"paid\" onkeyup=\"getBalance(this.value)\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Balance: <input type=\"text\" name=\"bal\" id=\"bal\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><input type=\"submit\" id=\"submit\" name=\"submit\" value=\"Save\"></td><td></td></tr></table></form>";

    require_once 'gridfiles.php';
}

function displaySaleAdjForm($db) {

    $point=$_REQUEST[cashpoint];
    $shiftNo=$_REQUEST[shiftNo];
    require_once 'dhxMenus.php';

    echo "<form name=\"csale\" method=\"POST\" action=". $_SERVER['PHP_SELF'] . ">";

    require_once 'cashform.php';
    //echo "<div class=\"container\" style=\"width:600px\">";
    //echo "<table width=\"100%\" class=\"style4\">";
    echo "<tr><td colspan=6 align=center><br><div id=\"gridbox\" height=\"200px\" style=\"background-color:white;\"></div></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Total:<input type=\"text\" name=\"total\" id=\"total\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Paid: <input type=\"text\" name=\"paid\" id=\"paid\" onkeyup=\"getBalance(this.value)\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Balance: <input type=\"text\" name=\"bal\" id=\"bal\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><input type=\"submit\" id=\"submit\" name=\"submit\" value=\"Save\"></td><td></td></tr></table></form>";

    require_once 'gridfiles.php';
}
function displayPayForm($db) {

    $point=$_REQUEST[cashpoint];
    $shiftNo=$_REQUEST[shiftNo];
    require_once 'dhxMenus.php';

    echo "<form name=\"csale\" method=\"POST\" action=". $_SERVER['PHP_SELF'] . ">";

    require_once 'cashform.php';
    //echo "<div class=\"container\" style=\"width:600px\">";
    //echo "<table width=\"100%\" class=\"style4\">";
    echo "<tr><td colspan=6 align=center><br><div id=\"gridbox\" height=\"200px\" style=\"background-color:white;\"></div></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Total:<input type=\"text\" name=\"total\" id=\"total\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Paid: <input type=\"text\" name=\"paid\" id=\"paid\" onkeyup=\"getBalance(this.value)\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><b>Balance: <input type=\"text\" name=\"bal\" id=\"bal\"><b/></td><td></td></tr>";
    echo "<tr><td colspan=6 align=right><input type=\"submit\" id=\"submit\" name=\"submit\" value=\"Save\"></td><td></td></tr></table></form>";

    require_once 'gridfiles_1.php';
}

function displayRcptForm() {
    $point=$_REQUEST[cashpoint];
    $shiftNo=$_REQUEST[shiftNo];
    require_once 'dhxMenus.php';

    echo "<br>You are using Cashpoint number $point and shift No $shiftNo<br>";
    echo "<form name=\"csale\" method=\"POST\">";
    require_once 'receipt_form.php';

    echo "<table width=\"100%\">";
    echo "<tr><td colspan=2><div id=\"txtHint\" height=\"50px\" style=\"background-color:white;\"></div></td></tr>";
    echo "<tr><td colspan=2><div id=\"gridbox\" height=\"150px\" style=\"background-color:white;\"></div></td></tr>";
    echo "<tr><td width=70% align=right><b>Total:<input type=\"text\" name=\"total\" id=\"total\"><b/></td><td></td></tr>";
    echo "<tr><td width=70% align=right><b>Paid: <input type=\"text\" name=\"paid\" id=\"paid\" onkeyup=\"getBalance(this.value)\"><b/></td><td></td></tr>";
    echo "<tr><td width=70% align=right><b>Balance: <input type=\"text\" name=\"bal\" id=\"bal\"><b/></td><td></td></tr>";
    echo "<tr><td align=right><input type=\"submit\" id=\"submit\" name=\"submit\" value=\"Save\"></td><td></td></tr></table></form>";

    require_once 'gridfiles.php';

}
function displayReceipt($refno,$cashier,$paymode,$payer,$chequeNo,$patientid,$patientname,$total,$rtext) {
    ?>
<script>
    window.open('receipt.php?refno=<?php echo $refno ?>&cashier=<?php echo $cashier?>&paymode=<?php echo $paymode?>\
&payer=<?php echo $payer?>&patientid=<?php echo $patientid?>&patientname=<?php echo $patientname?>&total=<?php echo $total?>&rtext=<?php echo $rtext?>'
    ,"receipt","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");

</script>
    <?php
}

function displayMenus() {
    require_once 'dhxMenus.php';
    ?><script> initMenu(); </script><?php

}

function printcashbook($page) {
    require_once $page;
}

?>
<script>
    function getBalance(str){
        var paid=str;
        var total=document.csale.total.value;
        var bal=paid-total;
        document.csale.bal.value=bal;
    }
</script>