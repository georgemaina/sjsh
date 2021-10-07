<link rel="stylesheet" type="text/css" href="accounting.css">
<?php
require_once 'roots.php';
//    require($root_path.'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');

$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;
require_once('mylinks_1.php');
//    jsIncludes();

echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Credit Slips</font></td></tr>
    <tr><td align=left valign=top>";

require 'aclinks.php';

echo '</td><td width=80% valign=top>
<table>
        <tr>
            <td>';
     echo '<input type="submit" id="search" value="search Patient" onclick="initPsearch()"/>
           <input type="text" name="pid" size="10" id="pid" onblur="getInsuredPatient(this.value)" />';
     echo '<input type="text" name="pname"  size="40" id="pname" />
                <input type="text"  disabled id="invCaller" value="'. $_REQUEST["caller"] .'">';
     echo '<input type="submit" id="getPatient" value="Print Slip" onclick="printSlip()" />';

    echo "</td>
        </tr><tr><td><span id=creditMsg style='font-size: large;color: red;font-weight: bold;'></span></td><tr>
</table>
</td></tr></table>";
?>
