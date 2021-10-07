<?php
require('roots.php');
require($root_path.'include/inc_environment_global.php');

    $sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.total) as total, a.bill_number,prescribe_date
                        from care2x.care_ke_billing a
                        inner join care_person b on a.pid=b.pid where a.`IP-OP`=1
                        group by b.pid";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

	//note that the header contain the three columns and its a array
	$_SESSION['report_header']=array("FirstName","LastName","Surname","total","bill_number");
	
   // now the excel data field should be two dimentational array with three column
   while($row = $result->FetchRow($result)){ //loop through the needed cycle
   
   		echo $exportdata['FirstName'][$i][0]=$row[0]." ";
		echo $exportdata['LastName'][$i][1]=$row[1]." ";
		echo $exportdata['Surname'][$i][2]=$row[2]." ";
                echo $exportdata['total'][$i][3]=$row[3]." ";
                echo $exportdata['bill_number'][$i][4]=$row[4]." ";
		echo "<br>";
  }
  
  ?>
<a href="export_report.php?fn=member_report">Click here to generate report</a>
<!--the export_report.php takes one variable called fn as GET parameter which is name of the file to be generated, if you pass member_report as a value, then the name of the generated file would be member_report.php
 -->