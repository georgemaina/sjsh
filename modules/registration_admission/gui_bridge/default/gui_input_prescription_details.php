

<?php

require_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($pres_obj)) $pres_obj=new Prescription;
require_once($root_path.'include/care_api_classes/class_person.php');

// added by mrisho

require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
$bill = new Bill();
//end of the addition

$person_obj = new Person;
$enc_obj = new Encounter;
if (empty($encounter_nr) and !empty($pid))
	$encounter_nr = $person_obj->CurrentEncounter($pid);

$encounterClass=$enc_obj->EncounterClass($encounter_nr);
//echo $encounterClass;
$debug=false;
if ($debug) {
	if (!empty($back_path)) $backpath=$back_path;

	echo "file: show_prescription<br>";
    if (!isset($externalcall))
      echo "internal call<br>";
    else
      echo "external call<br>";

    echo "mode=".$mode."<br>";

	echo "show=".$show."<br>";

    echo "nr=".$nr."<br>";

    echo "breakfile: ".$breakfile."<br>";

    echo "backpath: ".$backpath."<br>";

    echo "pid:".$pid."<br>";

    echo "encounter_nr:".$encounter_nr."<br>";

    echo "Session-ecnounter_nr: ".$_SESSION['sess_en'];
}
$pres_types=$pres_obj->getPrescriptionTypes();

?>
<script language="JavaScript">

        function GetXmlHttpObject()
            { 
                var objXMLHttp=null;
                if (window.XMLHttpRequest)
                {
                    objXMLHttp=new XMLHttpRequest();
                }
                else if (window.ActiveXObject)
                {
                    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                return objXMLHttp;
            }
            
         function getQuantity(storeId,partcode,rowNo){
//                alert(storeId +" , "+partcode);
                xmlhttp = GetXmlHttpObject();
                  if (xmlhttp === null)
                  {
                      alert("Browser does not support HTTP Request");
                      return;
                  }
                  var url = "myFunctions.php?task=getQuantityByItem";
                  url = url + "&storeID=" + storeId;
                  url = url + "&partCode=" + partcode;
                  url = url + "&rowNo=" + rowNo;
                  url = url + "&sid=" + Math.random();
                  xmlhttp.onreadystatechange = stateChanged9;
                  xmlhttp.open("POST", url, true);
                  xmlhttp.send(null);
          }

          function stateChanged9()
          {
              if (xmlhttp.readyState == 4)
              {
                  var str = xmlhttp.responseText;
                  str2=str.split(",");
document.getElementById('quantities'+str2[1]).innerHTML="There are<input type=text id='qty"+str2[1]+"' name='qty"+str2[1]+"' value="+str2[0]+" size=6 readonly/> unit(s) currently in stock.";
//                  alert(str);
              }
          }
         
function chkform(d,i) {

	if(d.arr_timesperday+i.value == "") {
    	alert("Please enter Total dose.");
    	return false;
	}

   // alert(document.getElementById('storeQty').value);

//        if( (isNaN(d.value)) || (d.value < 0) ) {
//		alert("Please enter a positive numeric value format like '1234', '2' or '2.5'");
//		return false;
//	}

}

function reCalculate(t1,s,t,d){
    alert(t1);
    document.getElementById(t1).value= document.getElementById(s).value*document.getElementById(t).value*document.getElementById(d).value;
}

function getTotals(t1,cost,t3,s,t,d,totalCost) {
    // alert('dosage='+s);

    totalQty=s * t * d;

    storeQty=document.getElementById('storeQty').value;
    // if(storeQty<totalQty){
    //     alert("Quantity Prescribed cannot be greater that Quantity in Store");
    //     document.getElementById('timesperday0').value='';
    // }
    document.getElementById(t1).value = totalQty;
    document.getElementById(t3).innerHTML = "  This Dose Costs Ksh." + cost * s * t * d;
    document.getElementById(totalCost).value = cost * s * t * d;
    document.getElementById(hiddenTotals).value = cost * s * t * d;

    document.getElementById(TotalCost).innerHTML = "The Total Cost of the Prscription is Ksh.";
    totalCost = totalCost * cost * s * t * d;
   

}



</script>
<form method="POST" name="reportform<?PHP echo $i;?>"  onSubmit='return chkform(this,<?php echo $i; ?>)'>

<input type="hidden" name="backpath" value="<?php echo $backpath; ?>">
<?PHP
/*
if($_GET['mode']=='edit')
{
	echo 'Sie sind im edit-Modus';
	echo 'nummer: '.$_GET['nr'];
}
*/

if(!$nr)
{

	//new entry
	$item_array=$_SESSION['item_array'];
//echo $item_array[0];
}
else
{
	
	//edit entry
	$prescriptionitem = $pres_obj->GetPrescritptionItem($nr);
	$item_array='';
        
	$item_array[0]= $prescriptionitem['item_id'];
	echo '<input type="hidden" value="'.$nr.'" name="nr">';

}

$cost=0;
//echo "-->items in array: ".count($item_array)."<br>";#
for ($i=0 ; $i<count($item_array) ; $i++) {

	$class = $pres_obj->GetClassOfItem($item_array[$i]);
	$stockID = $pres_obj->GetItemNumberByID($item_array[$i]);
    $cost=$pres_obj->GetPriceOfItem($stockID);
//	if ($is_transmit_to_weberp_enable==1) {
//		$weberp_obj=new_weberp();
//		$balance=$weberp_obj->get_stock_balance_webERP($item_array[$i]);
//	}
if($nexttime)
{
	$prescriptionitem['dosage']="";
	$nexttime=false;
}
if($class=='supplies' || $class=='drug_list' || $class=='special_others_list' || $class=='supplies_laboratory')
{
	$caption_dosage = 'Single dose';
}
else
{
	$caption_dosage = 'Amount';
	if(!$prescriptionitem['dosage']) $prescriptionitem['dosage']=1;
	$nexttime=true;
}

?>

<font class="adm_div"><?php echo $stockID ." - ". $pres_obj->GetNameOfItem($item_array[$i]);  ?></font>
 <table border=0 cellpadding=2 width=100%>

   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo 'Dose'; ?></td>
     <td>
     <!--  <input type="text" name="arr_dosage[<?PHP echo $i; ?>]" size=5 maxlength=5 value="<?php echo $prescriptionitem['dosage'];?>" onChange="chkform(this)"> -->


	<?php

		 //select "dosage"
//		if ($is_transmit_to_weberp_enable==1) {
//		$weberp_obj=new_weberp();
//		$property=$weberp_obj->get_stock_items_from_category_property('1',$item_array[$i]);
//		}

		 if ($property == 'Tablets')
		 {

	     	echo '<select id="dosage'.$i.'" name="arr_dosage['.$i.']" required> ';

	     			 $dosageUnits = array (	"" => "",
										"0.1"  =>  "1 / 10",
										"0.25" => "1 / 4",
										"0.5"  => "1 / 2",
										"0.75" => "3 / 4",
										"1"    => "1",
                                                                                "1.25" => "1 + 1 / 4",
                                                                                "1.5"  => "1 + 1 / 2",
                                                                                "1.75" => "1 + 3 / 4",
										"2"    => "2",
										"3"    => "3",
										"4"    => "4",
										"5"    => "5",
										"6"    => "6",
										"7"    => "7",
										"8"    => "8",
										"9"    => "9",
										"10"   => "10"	);

			foreach($dosageUnits as $dec => $fract)
			{
				//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['dosage'] == $dec)||((!$nr)&&($dec == "")))
					$selected = 'selected="selected"';
				else
					$selected = '';

				echo '<option value="'.$dec.'" '.$selected.'>'.$fract.'</option>';

			}

	       echo '</select><FONT SIZE=-1  FACE="Arial" color="#000066">'.$property.'</font>';
	       if (isset($nr)&&($prescrServ!='serv')) echo '('.$dosageUnits[$prescriptionitem['dosage']].')&nbsp;&nbsp;&nbsp;';

		 } elseif ($property == 'Injections') {
	     	echo '<input type=text id="dosage"'.$i.' size=6 name="arr_dosage['.$i.']" min=1 max=2>
	     	<FONT SIZE=-1  FACE="Arial" color="#000066">'.$property.'</font>' ;
		 } elseif ($property == 'Syrups')
		 {

	     	echo '<select id="dosage'.$i.'" name="arr_dosage['.$i.']" required> ';

	     			 $dosageUnits = array (	"" => "",
										"0.1" =>  "1 / 10",
										"0.25" => "1 / 4",
										"0.5"  => "1 / 2",
										"0.75" => "3 / 4",
										"1"    => "1",
										"1.25"    => "1 + 1 / 4",
										"1.5"     => "1 + 1 / 2",
										"1.75"    => "1 + 3 / 4",
										"2"    => "2",
										"3"    => "3",
										"4"    => "4",
										"5"    => "5",
										"6"    => "6",
										"7"    => "7",
										"8"    => "8",
										"9"    => "9",
										"10"   => "10"	);

			foreach($dosageUnits as $dec => $fract)
			{
				//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['dosage'] == $dec)||((!$nr)&&($dec == "")))
					$selected = 'selected="selected"';
				else
					$selected = '';

				echo '<option value="'.$dec.'" '.$selected.'>'.$fract.'</option>';

			}

	       echo '</select><FONT SIZE=-1  FACE="Arial" color="#000066">'.$property.'</font>';
	       if (isset($nr)&&($prescrServ!='serv')) echo '('.$dosageUnits[$prescriptionitem['dosage']].')&nbsp;&nbsp;&nbsp;';

		 }else
		 {
		 	
			echo '<input type="text" id="dosage'.$i.'" name="arr_dosage['.$i.']" value="1" size=5>';
         }
   
      	 //select "times_per_day"

		 if ($caption_dosage == 'Single dose')
		 {

    		echo '<FONT SIZE=-1  FACE="Arial" color="#000066">Times per day : </FONT>';
     		echo "<select id='timesperday$i' name='arr_timesperday[$i]' onchange=getTotals('total$i','$cost','cost$i',document.getElementById('dosage$i').value,document.getElementById('timesperday$i').value,this.value,'totalCost$i') required>";
//             echo '<option value=""></option>';
      		$timesperdayUnits = array('', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

     		foreach ($timesperdayUnits as $unit)
     		{
     			//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['times_per_day'] == $unit)||((!$nr)&&($unit == "")))
					$selected = 'selected="selected"';
				else
					$selected = '';

				echo '<option value="'.$unit.'" '.$selected.'>'.$unit.'</option>';
     		}

			echo '</select>';
		 }
		 else
		 {
		 	//echo '<input type="hidden" id="timesperday" name="arr_timesperday['.$i.']" value="1">';
                     echo '<FONT SIZE=-1  FACE="Arial" color="#000066">Times per day : </FONT>';
     		echo "<select id='timesperday$i' name='arr_timesperday[$i]' onchange=getTotals('total$i','$cost','cost$i',document.getElementById('dosage$i').value,document.getElementById('timesperday$i').value,this.value,'totalCost$i') required>";
//             echo '<option value=""></option>';
      		$timesperdayUnits = array('', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

     		foreach ($timesperdayUnits as $unit)
     		{
     			//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['times_per_day'] == $unit)||((!$nr)&&($unit == "")))
					$selected = 'selected="selected"';
				else
					$selected = '';

				echo '<option value="'.$unit.'"'.$selected.'>'.$unit.'</option>';
     		}

			echo '</select>';
		 }

		 if (isset($nr)&&($prescrServ!='serv')) echo '('.$prescriptionitem['times_per_day'].')&nbsp;&nbsp;&nbsp;';

		//select "days"



		if ($caption_dosage == 'Single dose')
		{
                        
			echo '<FONT SIZE=-1  FACE="Arial" color="#000066">Days : </FONT>';
			echo "<select id='days$i' name='arr_days[$i]' onchange=getTotals('total$i','$cost','cost$i',document.getElementById('dosage$i').value,document.getElementById('timesperday$i').value,this.value,'totalCost$i') required>";
//			$dayUnits = array('', '1', '2', '3', '4', '5');
			$dayUnits[0]='';
			for ($daycounter=1;$daycounter<91;$daycounter++) {
				$dayUnits[$daycounter]=$daycounter;
			}
			foreach ($dayUnits as $unit)
			{
					//preselect "1" in case of a new entry or the old value in case of an edit
					if (($prescriptionitem['days'] == $unit)||((!$nr)&&($unit == "")))
						$selected = 'selected="selected"';
					else
						$selected = '';

                if($unit=="")
                    $unit="";
                else
                    $unit=$unit;

					echo '<option value="'.$unit.'" '.$selected.'>'.$unit.'</option>';
			}

			 echo '</select>';
		}
		else
		{
			echo '<FONT SIZE=-1  FACE="Arial" color="#000066">Days : </FONT>';
            echo "<select id='days$i' name='arr_days[$i]' onchange=getTotals('total$i','$cost','cost$i',document.getElementById('dosage$i').value,document.getElementById('timesperday$i').value,this.value,'totalCost$i') required>";
//			$dayUnits = array('', '1', '2', '3', '4', '5');
			$dayUnits[0]='';
			for ($daycounter=1;$daycounter<91;$daycounter++) {
				$dayUnits[$daycounter]=$daycounter;
			}
			foreach ($dayUnits as $unit)
			{
					//preselect "1" in case of a new entry or the old value in case of an edit
					if (($prescriptionitem['days'] == $unit)||((!$nr)&&($unit == "")))
						$selected = 'selected="selected"';
					else
						$selected = '';

					if($unit=="")
					    $unit="";
					else
                        $unit=$unit;

					echo '<option value="'.$unit.'"'.$selected.'>'.$unit.'</option>';
			}

			 echo '</select>';
		}
		echo '<FONT SIZE=-1  FACE="Arial" color="#000066">  Total dosage : ';
		echo '<input type="text" id="total'.$i.'" name="total"'.$i.'" size=5 value="">';
        echo '<input type="hidden" id="totalCost'.$i.'" name="totalDoseCost[]" size=5 value="">';

//		if ($is_transmit_to_weberp_enable==1) {
//			echo "There are <strong>".$balance[3]['quantity']."</strong> unit(s) currently in stock</FONT>";

//        echo" Select Dispensing Store:<select name='arr_storeID[$i]' id='storeID$i' onchange=getQuantity(this.value,'$stockID','$i')>";
////                    if($encounterClass==2){
////                        echo "<option value='Dispens'>PHARMACY</option>";
////                    }else{
////                        echo " <option></option>";
////                    }
//           echo " <option></option>";
//                     $input_user = $_SESSION['sess_login_username'];
//                     $sql = "SELECT st_id,st_name FROM care_ke_stlocation WHERE store=1  AND Dispensing=1";
//                            $result = $db->Execute($sql);
//                            while ($row = $result->FetchRow()) {
//                                 echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';  
//                            } 
//         echo "</select>";       
       // if($pharmLoc<>"care2x"){
                    $sql='select quantity from care_ke_locstock where loccode="DISPENS" and stockid="'.$stockID.'"';
              //  }else{
               //     $sql="select quantity from $pharmLoc.locstock where loccode='MAIN' and stockid='$stockID'";
               // }
                //echo $sql;

                 $result2=$db->Execute($sql);
                 if($debug) echo $sql;
                $row=$result2->FetchRow();
         echo "<span id='quantities$i' style='color:blue; font-weight: bold; font-size: medium'>Quantity in Store is $row[0] </span><input type='hidden' id='storeQty' value='$row[0]'>";
        
//                $accDB=$_SESSION['sess_accountingdb'];
//                $pharmLoc=$_SESSION['sess_pharmloc'];
//                if($pharmLoc<>"care2x"){
//                    $sql="select quantity from $accDB.locstock where loccode='MAIN' and stockid='$stockID'";
//                }else{
//                    $sql='select quantity from care_ke_locstock where loccode="DISPENS" and stockid="'.$stockID.'"';
//                }
//                //echo $sql;
//
//                 $result2=$db->Execute($sql);
//                 if($debug) echo $sql;
//                $row=$result2->FetchRow();

               // $stockQty=$weberp_obj->get_stock_balance_webERP($stockID);
//                echo "There are <strong>".$row[0]."</strong> unit(s) currently in stock    ";

                echo " <span style='color:red; font-weight: bold; font-size: medium' id='cost".$i."'></span></FONT>";
//		if (isset($nr)&&($prescrServ!='serv')) echo '('.$prescriptionitem['days'].')&nbsp;&nbsp;&nbsp;';
 
	?>

     </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDApplication.' '.$LDNotes; ?></td>
     <!--<td><textarea name="arr_notes[<?PHP echo $i; ?>]" cols=40 rows=3 wrap="physical"><?php echo $prescriptionitem['notes'];?></textarea></td>-->
	 <td><input type="text" name="arr_notes[<?PHP echo $i; ?>]" size="120"><?php echo $prescriptionitem['notes'];?></td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPrescribedBy; ?></td>
     <td><input type="text" name="prescriber" size=50 maxlength=60 value="<?php echo $_SESSION['sess_user_name']; ?>" readonly></td>
   </tr>
 </table>


<input type="hidden" name="arr_item_number[<?PHP echo $i; ?>]" value="<?PHP echo $i; ?>">
<input type="hidden" name="arr_article_item_number[<?PHP echo $i; ?>]" value="<?php echo $item_array[$i];?>">
<input type="hidden" name="arr_price[<?PHP echo $i; ?>]" value="<?php echo $pres_obj->GetPriceOfItem($item_no[$i]);?>">
<input type="hidden" name="arr_article[<?PHP echo $i; ?>]" value="<?php echo $pres_obj->GetNameOfItem($item_array[$i]);?>">
<input type="hidden" name="arr_history[<?PHP echo $i; ?>]" value="<?php echo $pres_obj->GetNameOfItem($item_array[$i])."\n";?>Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">
<?php

} // end of loop
?>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<?php
if(!$nr)
	echo '<input type="hidden" name="mode" value="create">';
else
	echo '<input type="hidden" name="mode" value="update">';
?>

<input type="hidden" name="target" value="<?php echo $target; ?>">


        <?
        if (isset($externalcall)) {
        ?>
          <input type="hidden" name="externalcall" value="<?php echo $externalcall;?>">
        <?}?>
    <input type="hidden" name="myform_key" value="<?php echo md5(date('Y:m:d H:i:s')); ?>">
<input type="hidden" name="is_outpatient_prescription" value="1">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
  <span id="TotalCost" style="color: red; font-size: larger; font-weight: bold; width: 70%; text-align: right"></span>
</form>


<?php
/**
* Second part: Show all prescriptions for this encounter no. since now.
*/
?>

<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">


<tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial">Date/Adm.Nr./Days</td>
    <td><FONT SIZE=-1  FACE="Arial">Article</td>
    <td><FONT SIZE=-1  FACE="Arial">Dose</td>
    <td><FONT SIZE=-1  FACE="Arial">Times Per Day</td>
  </tr>

<?php

$toggle=TRUE;
while($row=$result->FetchRow()){
	if($toggle) $bgc='#f3f3f3';
		else $bgc='#fefefe';
	if ($toggle)
		$toggle=FALSE;
	else $toggle=TRUE;

	if($row['encounter_class_nr']==1) $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_inpatient_nr_adder']; // inpatient admission
		else $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_outpatient_nr_adder']; // outpatient admission

?>

  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial"><?php echo formatDate2Local($row['prescribe_date'],$date_format,FALSE,FALSE,"-"); ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['article']; ?></td>
    <td><FONT SIZE=-1  FACE="Arial" color="#006600"><?php echo $row['dosage']; ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['times_per_day']; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $full_en; ?></td>
    <td rowspan=2><FONT SIZE=-1  FACE="Arial"><?php echo $row['notes']; ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['drug_class']; ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['order_nr']; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['prescription_type_nr']; ?></td>

    <td><FONT SIZE=-1  FACE="Arial">&nbsp;</td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['prescriber']; ?></td>
  </tr>
<?php
}
?>

</table>