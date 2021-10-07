
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');
$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];


$rptType = ($_REQUEST['rptType']) ? ($_REQUEST['rptType']) : '';
switch ($rptType) {
    case "statement":
         getStatement();
        break;
    
    default:
        echo "{failure:true}";
        break;
}//end switch


function getStatement(){
    global $db;
    $accno=$_REQUEST[acc1];

    $sql = "SELECT * FROM care_ke_invoice";
	$global_result = $db->Execute ( $sql );
	if ($global_result) {
		while ( $data_result = $global_result->FetchRow () ) {
			$company = $data_result ['CompanyName'];
			$address = $data_result ['Address'];
			$town = $data_result ['Town'];
			$postal = $data_result ['Postal'];
			$tel = $data_result ['Tel'];
			$invoice_no = $data_result ['new_bill_nr'];
		}
		$global_config_ok = 1;
	} else {
		$global_config_ok = 0;
	}
	
    
        $sql="select d.accno,d.name,c.id,d.address1,d.address2,d.phone from care_ke_debtors d left join care_tz_company c
ON d.accno=c.accNo where c.id is not NULL and c.id='$accno'";
    
 
    $request=$db->Execute($sql);
$row=$request->FetchRow();
              
    echo "<table width=100% height=14>
        <tr><td colspan=6 align=center>STATEMENT OF ACCOUNT</td></tr>
        <tr><td colspan=3 align=left>$row[0] <br>  $row[1] <br>  $row[3] <br>  $row[4] <br>  $row[5]</td>
        <td colspan=3 align=left>$company <br>$address - $postal<br>$town <br>$tel <br></td></tr>
        <tr><td colspan=3 align=left> Period: ".date("Y-m-d")."</td>
            <td colspan=3 align=left>Date : ".date("Y-m-d")."</td></tr>
            <tr bgcolor=#6699cc>
                    <td align='center'>pid</td>
                    <td align='center'>Names</td>
                    <td align='center'>Bill Date</td>
                    <td align='center'>Bill Number</td>
                    <td align='center'>Total</td>
                    <td align='center'>Running Total</td>
                 </tr>";
        $bg='';
    $sql="select b.`ip-op`,p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total from care_person p 
        left join care_ke_billing b
on p.pid=b.pid where b.`IP-OP`=2 and p.insurance_ID='$accno'";
    
 
    $request=$db->Execute($sql);
    while ($row = $request->FetchRow()) {
          if ($bg == "silver")
                    $bg = "white";
                else
                    $bg="silver";
$total=intval($row[total]+$total);
        echo '<tr bgcolor='.$bg.' height=16>
                    <td>'.$row[ip-op].'</td>
                    <td>'.$row[pid].'</td>
                    <td>'.$row[name_first].' '.$row[name_last].' '.$row[name_2].'</td>
                    <td>'.$row[bill_date].'</td>
                    <td>'.$row[bill_number].'</td>
                    <td>'.$row[total].'</td>
                        
                    <td>'.$total.'</td>    
                   
             </tr>';
        
        $rowbg='white';
    }
    echo '</table>';
    
}