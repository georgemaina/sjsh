<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'config.php';
$conn1 = mysqli_connect($server, $user, $pass, $database, $port);
if (!$conn1) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($conn1, 'SET NAMES \'utf8\'');
$param=$_POST['clientNo'];

$sql="SELECT `clientNo`,`FirstName`,`surName`,`contact`,`hospitalNo`,`regDate`
FROM `mmc`.`mmc_clientregister` where clientNo='$param'";

//if(isset($param)){
//    $sql=$sql." where clientNo='$param'";
//}
if($result = mysqli_query($conn1, $sql)){
//    echo var_dump($_POST);
     echo '{
    "total":"' . $total . '","Clients":[';
    $counter = 0;
   
    while(($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {

        echo '{"clientNo":"' . $row['clientNo'] . '","firstName":"' . $row['FirstName'] . '",
       "surname":"' . $row['surName'] . '","contact":"' . $row['contact'] . '","hospitalNo":"' . $row['hospitalNo'] . '",
       "regDate":"' . $row['regDate'] . '"},';

        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    mysqli_free_result($result);
    echo ']}';
}else{
    echo '{success:false,msg:"Form could not be saved please check you entries",sql:'.$sql.'}';
}

mysqli_close($conn1);
?>
