
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$limit=$_POST[limit];
$start=$_POST[start];

$sql = 'SELECT PID,FirstName,LastName,Surname,Department,JobTitle,Box,PostalCode,Phone,Town,Email,
    ID_No,Pin_No,NHIF_No,NSSF_NO,Bank_Name,Account_No,
    basicpay,nssf,nhif,paye,relief,emptype,AppointDate,AppointEndDate,
        ConfirmDate,Nationality,MaritalStatus,dob,sex,hseAllowance,`union`,
        pension,sacco,housed,unionised,riskAllowance,BankId,BranchID FROM proll_empregister';// limit '.$start .','.$limit;
$result=$db->Execute($sql);
$numRows=$result->RecordCount();

$sql2='select count(pid) from  proll_empregister';
$result2=$db->Execute($sql2);
$total=$result2->FetchRow();
$total=$total[0];
echo '{
    "total":"'.$total.'","Employees":[';
$counter=0;
while ($row = $result->FetchRow()) {
    echo '{"PID":"'. $row[0].'","FirstName":"'. $row[1].'","LastName":"'. $row[2].'",
        "SurName":"'. $row[3].'","Department":"'. $row[4].'","JobTitle":"'. $row[5].'",
        "Box":"'. $row[6].'","PostalCode":"'. $row[7].'","Phone":"'. $row[8].'",
        "Town":"'. $row[9].'","Email":"'. $row[10].'","ID_No":"'.$row[11].'",
        "Pin_No":"'. $row[12].'","NHIF_No":"'. $row[13].'","NSSF_NO":"'. $row[14].'",
        "Bank_Name":"'. $row[15].'","Account_No":"'. $row[16].'","basicpay":"'. $row[17].'",
        "nssf":"'. $row[18].'","nhif":"'. $row[19].'","paye":"'. $row[20].'","relief":"'. $row[21].'",
        "emptype":"'. $row[22].'","AppointDate":"'. $row[23].'","AppointEndDate":"'. $row[24].'",
        "ConfirmDate":"'. $row[25].'","Nationality":"'. $row[26].'", "MaritalStatus":"'. $row[27].'",
        "dob":"'. $row[28].'","sex":"'. $row[29].'","hseAllowance":"'. $row[30].'","union":"'. $row[31].'",
        "pension":"'. $row[32].'","sacco":"'. $row[33].'","housed":"'. $row[34].'","unionised":"'. $row[35].'",
        "riskAllowance":"'. $row[36].'","BankID":"'. $row[37].'","BranchID":"'. $row[38].'"}';
    
    if ($counter<$numRows){
        echo ",";
    }
    $counter++;
}
echo ']}';

?>

