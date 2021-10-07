 <?php
    echo "<table><tr><td align=left>" . require_once 'acLinks.php';
    echo '</td><td align=right>';
    echo '<table border="0" cellpadding="0" cellspacing="5" class="style1">
     <tr>
        <td colspan="5" class=pgtitle>Debit</td>
             </tr>';
       echo " <tr><td>Revenue Codes:</td>";
       echo "<td colspan=3><input type=\"text\" name=\"revcode\" id=\"revcode\" value=" .$row[2] . "/></td></tr>";
        echo "<tr><td>Patient No:</td><td colspan=3><input type=\"text\" size=\"10\"  name=\"cashpoint_desc\" id=\"cashpoint_desc\" value=".$row[1] ."/>";
        echo "<input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" size=\"36\" value=" .$row[2] . "/></td></tr>";
        echo "<tr><td>ref No:</td>";
        echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . "/></td></tr>";
        echo "<tr><td>Description:</td>";
        echo "<td><input type=\"text\" size=\"36\" name=\"cashpoint_desc\" id=\"cashpoint_desc\" value=".$row[1] . "  /></td>";
        echo "<td>date:</td>";
        echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . " /></td></tr>";
        echo "<tr><td><td><td>Quantity:</td>";
        echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . " /></td><tr>";
        echo "<tr><td><td><td>Amount:</td>";
        echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . " /></td></tr>";
        echo "<tr><td colspan=5><br><br></td></tr>";
        echo "<tr><td colspan=5 align=center>
                                <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"save\" />&nbsp&nbsp
                                <input type=\"button\" name=\"cancel\" id=\"cancel\" value=\"cancel\" /></td></tr>";
        echo '</table>';
        echo '';

        echo "</td></tr></table>"
    ?>
