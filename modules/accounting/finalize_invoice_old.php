<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once 'roots.php';
require($root_path . "modules/accounting/extIncludes.php"); ?>
<link rel="stylesheet" type="text/css" href="accounting.css">
<?php require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path.'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');

$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;
require_once('myLinks_1.php');
//    jsIncludes();
echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Finalize Invoices</font></td></tr>
    <tr><td align=left valign=top>";

require 'acLinks.php';
echo '</td><td width=80% valign=top>';

    Finalize();

echo "</td></tr></table>";
?>
<script>
function invoicePdf(name){
 
        window.open('finalDetail_invoice_pdf.php?pid='+name+"&receipt=1&final=1","Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    
}

</script>