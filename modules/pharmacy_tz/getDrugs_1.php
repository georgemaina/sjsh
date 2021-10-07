<?php
$conn = mysqli_connect('localhost', 'root', '355ewxx', 'kikuyu', '3306');
if (!$conn) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($conn, 'SET NAMES \'utf8\'');

//$sql='select categoryid from stockcategory';
echo '<table>';
echo '<tr>';
echo '<th>categoryid</th>';
echo '<th>categorydescription</th>';
echo '</tr>';

$result2 = mysqli_query($conn, 'SELECT categoryid,stockid FROM stockmaster');
while (($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) != NULL) {
    $catid=$row[1];
    $sql='SELECT categoryid, stockid FROM stockmaster2 where stockid<>"'.$catid.'"';
    $result = mysqli_query($conn, $sql);
    $row2 = mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo '<tr>';
    echo '<td>' . $row2['stockid'] . '</td>';
    echo '<td>' . $row2['categoryid'] . '</td>';
    echo '</tr>';
}
//mysqli_free_result($result);
echo '</table>';
// TODO: insert your code here.
//mysqli_close($conn);
?>