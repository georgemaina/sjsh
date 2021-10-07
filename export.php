<?php
header("Pragma: public");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Transfer-Encoding: none");
header("Content-Type: application/vnd.ms-excel;");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=report2_opendebitsummary".date('Ymd').".xls");
?>

<html>
<body>
<table border="1">
<tr>
    <th>Employee ID</th>
    <th>Employee Name</th>
</tr>
<?php
foreach ($data AS $row) :
?>
<tr>
    <td>< ?=$row['employee_id']?></td>
    <td>< ?=$row['employee_name']?></td>
</tr>
<?php
endforeach;
?>
</table>
</body>
</html>