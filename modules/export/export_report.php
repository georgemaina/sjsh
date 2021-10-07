<?php session_start(); ob_start();

require('roots.php');
require($root_path.'include/inc_environment_global.php');

    $sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.total) as total, a.bill_number,prescribe_date
                        from care2x.care_ke_billing a
                        inner join care_person b on a.pid=b.pid where a.`IP-OP`=1
                        group by b.pid";
    $result=$db->Execute($sql);
    
    //$row=$result->FetchRow();
	unset($_SESSION['report_header']);
	unset($_SESSION['report_values']);
	//note that the header contain the three columns and its a array
	$_SESSION['report_header']=array("FirstName","LastName","Surname","total","bill_number");

   // now the excel data field should be two dimentational array with three column
        $rownum= $result->RecordCount();
   //loop through the needed cycle
//  for($i=0;$i<=$rownum;$i++) //loop through the needed cycle
//   {
//      $row = $result->FetchRow($result);
//   		echo $_SESSION['report_values'][$i][0]='"'.$row[0].'"'." ";
//		echo $_SESSION['report_values'][$i][1]='"'.$row[1].'"'." ";
//		echo $_SESSION['report_values'][$i][2]='"'.$row[2].'"'." ";
//                echo $_SESSION['report_values'][$i][3]='"'.$row[3].'"'." ";
//                echo $_SESSION['report_values'][$i][4]='"'.$row[4].'"'." ";
//		echo "<br>";
//  }
          for($i=0;$i<=4;$i++) //loop through the needed cycle
   {
   		echo $_SESSION['report_values'][$i][0]="Michael"." ";
		echo $_SESSION['report_values'][$i][1]="michael@yahoo.com"." ";
		echo $_SESSION['report_values'][$i][2]="Nepal"." ";
		echo "<br>";
  }

	$fn="Invoices.xls";
	include_once("class.export_excel.php");
	//create the instance of the exportexcel format
	$excel_obj=new ExportExcel("$fn");
	//setting the values of the headers and data of the excel file 
	//and these values comes from the other file which file shows the data
	$excel_obj->setHeadersAndValues($_SESSION['report_header'],$_SESSION['report_values']);
	//now generate the excel file with the data and headers set
	$excel_obj->GenerateExcelFile();
	//print_r($_SESSION['report_values']);
	
	
?>
