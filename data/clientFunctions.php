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

$sql="SELECT MAX(clientno) as clientNo FROM mmc_clientregister";

if($result = mysqli_query($conn1, $sql)){
     echo '{
    "ClientNos":[';
   
    if(($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        $newNo=intval($row['clientNo']+1);
        echo '{"clientNo":"' . $newNo.'"}';
    }
    mysqli_free_result($result);
    echo ']}';
}else{
    echo '{success:false,msg:"Problem with Client No"}';
}

mysqli_close($conn1);
?>
