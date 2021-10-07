<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$clientNo=$_POST[clientNo];
$contacts=$_POST[contacts];	
$firstName=$_POST[firstName];	
$hospNo	=$_POST[hospNo];
$surname=$_POST[surname];

$regDate1=new DateTime($_POST[regDate]);
$regDate=$regDate1->format('Y/m/d');

require 'config.php';
$conn1 = mysqli_connect($server, $user, $pass, $database, $port);
if (!$conn1) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($conn1, 'SET NAMES \'utf8\'');

$sql="INSERT INTO `mmc`.`mmc_clientregister`
            (`clientNo`,
             `FirstName`,
             `surName`,
             `contact`,
             `hospitalNo`,
             `regDate`)
VALUES ('$clientNo','$firstName','$surname',
        '$contacts','$hospNo', '$regDate')";

if($result = mysqli_query($conn1, $sql)){
    echo '{success:true,msg:"Form Successfully Saved"}';
}else{
    echo '{success:false,msg:"Form could not be saved please check you entries",sql:'.$sql.'}';
}

mysqli_close($conn1);
?>
