<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$limit = $_REQUEST[limit];
$start = $_REQUEST[start];

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

$ID=$_POST[ID];
$tableName=$_POST[tableName];

switch ($task){
    case "deleteDepartment":
        deleteDepartment($ID);
        break;
    case "deleteMethod":
        deleteMethod($ID,$tableName);
        break;
    default:
        echo "{failure:true}";
        break;
}


function deleteDepartment($ID){
    global $db;
    $debug=false;
    
    $sql="Delete from proll_departments where ID='$ID'";
    if($debug){echo $sql; }
 
    if($db->Execute($sql)){
        echo '{Success:true}';
    }else{
        echo '{Failure:true}';
    }
}

function deleteMethod($ID,$table){
    global $db;
    $debug=true;
    
    $sql="Delete from $table where ID='$ID'";
    if($debug){echo $sql; }
 
    if($db->Execute($sql)){
        echo '{Success:true}';
    }else{
        echo '{Failure:true}';
    }
}