<?php
require_once '../../../config.php';
$q=$_GET["q"];

$link = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
$res = mysql_select_db ($mysql_db);
if (!$link)
  {
  die('Could not connect: ' . mysql_error());
  }


$sql="SELECT * FROM care_person WHERE pid = '$q'";

$result = mysql_query($sql);

echo "<hr><table border='0' width=100%>
<tr><td valign=top><b>SOWETO KAYOLE PC</b></td><td>P.O. BOX 30690 -00100<BR>NAIROBI
<br>Phone: 020 65998992<br>Fax: 020 32656666<br>email: info@chak.or.ke<br>web: www.chak.or.ke
    </td>
</tr>
<tr><td colspan=2><hr></td></tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>Patient Name: " . $row['name_first']." ".$row['name_2'] ." ".$row['name_last']. "</td>
        <td>P.o. Box" .$row['addr_zip']. "<br>".$row['phone_1_nr']. "<br>".$row['district']." <br></td>
        <tr><td>Date: ".date("F j, Y, g:i a")." </td><td>&nbsp<td><tr>
        <tr><td></td><td align=left><b>INVOICE</b><td><tr>
        <tr><td colspan=2>";

                    $link = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
                    $res = mysql_select_db ($mysql_db);
                    if (!$link)
                      {
                      die('Could not connect: ' . mysql_error());
                      }


                    $sql="SELECT * FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=1 order by service_type asc";

                    $result = mysql_query($sql);
                    echo "<table width=100% border=0>
                            <tr><td><b>Type</b></td><td><b>Description</b></td><td><b>Price</b></td><td><b>Qty</b></td><td><b>Total</b></td><tr><b>
                            <tr><td colspan=5><hr><td><tr>";
                            while($row = mysql_fetch_array($result))
                                {
                                     echo "<tr><td>" . $row['service_type']."</td>
                                               <td>" . $row['Description']."</td>
                                               <td>" . $row['price']."</td>
                                               <td>" .(($row['qty']>0)?$row['qty']:1)."</td>
                                               <td>" .intval($row['price'])*intval($row['qty'])."</td>
                                           <tr>";
                                 }
                                 
                   $sql2="SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q'";
                     echo " <tr><td></td><td></td><td></td><td></td>
                            <td>-------------</td></tr>";
                    $result2 = mysql_query($sql2);              
                    if ($row2 = mysql_fetch_array($result2)){
                        echo " <tr><td></td><td></td><td></td><td><b>Total:</b></td>
                            <td><b>". $row2['total']."</b></td></tr> ";
                    }
                    echo " <tr><td></td><td></td><td></td><td></td>
                            <td>-------------</td></tr>";
                    echo "</table>";
    }
echo "</table>";?>
<br><br>
 <center><button onclick="invoicePdf(<?php echo $q ?>)" id="printInv">Print Invoice</button></center>


<?php
mysql_close($link);
?>
