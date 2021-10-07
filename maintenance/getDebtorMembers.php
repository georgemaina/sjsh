<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$sql="SELECT ID,category FROM sheet3";
$result=$db->Execute($sql);

//while($row=$result->FetchRow()){
    $user = $_SESSION['sess_login_username'];
    $sql1="INSERT INTO `care_ke_debtormembers`
            (`accno`,`PID`,`MemberNames`, `memberType`,`OP_Usage`,`IP_Usage`,`DOB`,`inputDate`,`inputUser`)
            SELECT c.`accno`,p.pid,CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS pnames,'Both','0','0',p.`date_birth`,p.`date_reg`,'$user' FROM care_person p
            LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
            WHERE  p.`insurance_ID`<>'-1'";
    
    if($result1=$db->Execute($sql1)){
          echo "Success <br>";
    }else{
        echo "error:".$sql1.'<br>';
    }
    
//}

?>