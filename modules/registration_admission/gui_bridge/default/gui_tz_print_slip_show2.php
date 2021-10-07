<style type="text/css">
    <!--
    .style1 {
        font-family:serif;
        font-size: 14px;
        color:black;
    }
    -->
</style>

<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

$debug=false;
($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$patientid=$_REQUEST["patientid"];

if(isset($_POST["submit"])) {
     $r_sql = "select a.company_id, a.`PID`,b.`name`,d.name_first,name_last
from care_tz_insurance a
inner join care_tz_company b on a.company_id=b.id
inner join care_person d on d.pid=a.`PID` where a.pid='".$_POST['pid']."'";
        $result=$db->Execute($r_sql);
        echo $r_sql;
        if($row3=$result->FetchRow()){

//                $File = "C:/Program Files/2xp/receipt.txt";
//                $Handle = fopen($File, 'w');
                $Data ="\tPCEA Kikuyu Hospital\n P.O. BOX 30690 -00100\n NAIROBI\n";
                $Data =$Data."--------------------------------\n";
                $Data =$Data."\tCREDIT SLIP ACCEPTANCE\n";
                $Data =$Data."--------------------------------\n";
                $Data =$Data."VALID TILL".date('d/m/Y')."\n";
                $Data =$Data."--------------------------------\n";
                $Data =$Data."To All departments:\n";
                $Data =$Data."Please accord medical services to:\n";
                $Data =$Data."Patient No:\t".$patientid."\n";
                $Data =$Data."Patient Name:\t".$row[name_first]." ".$row[name_last]."\n\n";
                $Data =$Data."whose bill is payable by\n";
                $Data =$Data."Insurance:\t".$row[name].".\n\n";
                $Data =$Data."A/c ".$row[company_id].". Thereafter, please raise a Debit Note in respect of the Patient\n\n";
                $Data =$Data."\tFor ADMINISTRATOR\n";
                $Data =$Data."--------------------------------\n";

                $Data =$Data."\tThank You\n\n\n\n\n";
                $File = "c:/Temp/receipt.txt";
                $Handle = fopen($File, 'w');
                fwrite($Handle, $Data);
                print "Data Written";
                
                exec('cmd.exe /C C:/Temp/receipt.bat');
                fclose($Handle);
        }
}else{
$r_sql = "SELECT a.pid, 
	b.encounter_nr,
	a.name_first, 
	a.name_2, 
	a.name_last, 
	a.date_birth, 
	a.addr_zip, 
	a.citizenship,  
	a.phone_1_nr, 
	a.civil_status, 
	a.sex
	FROM 
	care_person a,care_encounter b
	WHERE a.pid=b.pid
        And b.encounter_nr='$patientid'";
$result=$db->Execute($r_sql);
if($debug) echo $r_sql;
echo'<form method="POST" action='. $_SERVER['PHP_SELF'] . '>';
     echo "<table border=\"0\" class=style1 width=100%>";
     while($row=$result->FetchRow()){
     echo "<tr>
            <td colspan=2 align=center class=style1>PCEA Kikuyu <br> Hospital Treatment Slip<br>P.O. BOX 45 - 00902 <br>Kikuyu</td>
            <tr><td colspan=2><hr></td></tr>
            <tr><td>Date:</td><td>".date('l jS \of F Y h:i:s A')."</td></tr>
            <tr><td>patient Name:</td><td><b>".$row[2]." ".$row[3]." " .$row[4]."<b></td></tr>
            <tr><td>DOB:</td><td>".$row[5]."</td></tr>
            <tr><td>Address:</td><td>".$row[6]."</td></tr>
            <tr><td>Residence</td><td>".$row[7]."</td></tr>
            <tr><td>Phone</td><td>".$row[8]."</td> </tr>
            <tr><td>Status</td><td>".$row[9]."<input type='hidden' id='pid' name='pid' value='$row[0]'></td> </tr>";
    }
          
        echo "</tbody>
    </table>";

//echo "records returned".mysql_affected_rows();
echo "<br><center><input type=\"submit\" name=\"submit\" value=\"Print Receipt\"><br></form>";


}
   ?>
<script>
function printOut()
    {
    	this.window.print()
        <?php
        $result=$db->Execute($r_sql);
         while($row=$result->FetchRow()){
            $sql='insert into care_ke_billing(pid,encounter_nr) values("'.$row[0].'","'.$row[1].'")';
             $result=$db->Execute($sql);
         }
        ?>
        this.window.close();
    }

    </script>