<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$dte = date('Y-m-d');
$age = $_GET["age"];
$mnts = $_GET["months"];

if(!isset($_GET["months"])||empty($_GET["months"])){
    $mnts=0;
}
if(!isset($_GET["age"])||empty($_GET["age"])){
    $age=0;
}
$date = new DateTime($dte);
date_sub($date, new DateInterval("P" . $age . "Y" . $mnts . "M"));
$newdate = $date->format("d/m/Y");

echo $newdate;
?>
