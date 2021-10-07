<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
header("Pragma: public");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Transfer-Encoding: none");
header("Content-Type: application/vnd.ms-excel;");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=Invoices".date('Y-m-d').".xls");
require('roots.php');
require($root_path.'include/inc_environment_global.php');
?>
<html>
<body>
<table border="0">
<tr>
    <th>PID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>surName</th>
    <th>Total</th>
    <th>Bill Number</th>
    <th>Prescribe Date</th>
</tr>
<?php
$sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.total) as total, a.bill_number,prescribe_date
                        from care2x.care_ke_billing a
                        inner join care2x.care_person b on a.pid=b.pid where a.`IP-OP`=1
                        group by b.pid";
    $result=$db->Execute($sql);
    while($row = $result->FetchRow($result))
{
?>
<tr>
    <td><?=$row['pid']?></td>
    <td><?=$row['name_first']?></td>
    <td><?=$row['name_2']?></td>
    <td><?=$row['name_last']?></td>
    <td><?=$row['total']?></td>
    <td><?=$row['bill_number']?></td>
    <td><?=$row['prescribe_date']?></td>
</tr>
<?php
}
?>
</table>
</body>
</html>