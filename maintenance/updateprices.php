<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');

global $db;

//updatePaymentErp($payMode,$cashpoint);
//function updatePaymentErp($payMode,$cashpoint) {
     
    $sql1="select Roles from users where username='Admin'";
    if($debug) echo $sql1;
    $result=$db->Execute($sql1);
    $row1=$result->FetchRow();
    $roles=$row1[0];
   // echo $roles.'<br>';
    $arrRoles=count(explode(',',$roles));
    //echo $arrRoles.'<br>' ;

    for($x=0;$x<$arrRoles;$x++)
    {
        echo $roles[$x];
        echo ",";
    }
?>
