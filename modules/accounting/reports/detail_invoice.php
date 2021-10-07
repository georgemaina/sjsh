<?php 
require('roots.php');
require( "../extIncludes.php"); ?>
<link rel="stylesheet" type="text/css" href="../accounting.css">

<script type="text/javascript" src="../reportFunctions.js"></script>
<?php
printform();
    require_once 'myLinks_1.php';

function printform() {
    require('roots.php');

    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Detailed Invoice</font></td></tr>
    <tr><td align=left valign=top>";
    require("../aclinks.php");
    echo '</td>
           <td align=left>
                <table border=0><tr class="tr2">
                    <td>';
    require_once 'psearch.php';
    echo ' </td></tr>
                    <tr>
                        <td><font size="4" color="blue"><b>' . $_REQUEST['caller'] . ' Invoice</b></font><br>
             <div id="loader"></div>
            <div class="style5" id="txtHint">Person Invoice will be Displayed here.</b></div>
  </td>
                    </tr>
              </table>
         </td>
  </tr></table>';
}
?>
