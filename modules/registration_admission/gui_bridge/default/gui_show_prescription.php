<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">

<tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial">Date/Adm.Nr./Days</td>
    <td><FONT SIZE=-1  FACE="Arial">Days</td>
    <td><FONT SIZE=-1  FACE="Arial">Dose</td>
    <td><FONT SIZE=-1  FACE="Arial">Times per Day</td>
  </tr>

<?php

$toggle=0;


while($row=$result->FetchRow()){
	if($toggle) $bgc='#f3f3f3';
		else $bgc='#fefefe';
	$toggle=!$toggle;

	if($row['encounter_class_nr']==1) $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_inpatient_nr_adder']; // inpatient admission
		else $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_outpatient_nr_adder']; // outpatient admission

$amount = 0;
    $notbilledyet = false;
    if ($row['bill_number'] > 0) {
        include_once($root_path . 'include/care_api_classes/class_tz_billing.php');
        if (!isset($bill_obj))
            $bill_obj = new Bill;
        $billresult = $bill_obj->GetElemsOfBillByPrescriptionNr($row['nr']);
        if ($billrow = $billresult->FetchRow()) {
            if ($billrow['amount'] != $row['dosage'])
                $amount = $billrow['amount'];
        }
        if (!$amount > 0) {
            $billresult = $bill_obj->GetElemsOfBillByPrescriptionNrArchive($row['nr']);
            if ($billrow = $billresult->FetchRow()) {
                if ($billrow['amount'] != $row['dosage'])
                    $amount = $billrow['amount'];
            }
        }
    } {
        $notbilledyet = true;
    }

    $sDate = new DateTime($row['prescribe_date']);
    $presDate = $sDate->format('d-m-Y');
?>

  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $presDate; ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['article']; ?></td>
    <td><FONT SIZE=-1  FACE="Arial" color="#006600"><?php
    if($amount>0)
    {
    	echo '<s>'.$row['dosage'].'</s> '.$amount;
  	}
  	else
  	{
    	echo $row['dosage'];
    }

    ?></td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['times_per_day']; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bgc; ?>" valign="top">

    <td><FONT SIZE=-1  FACE="Arial"><?php echo $full_en; ?></td>
    <td rowspan=2><FONT SIZE=-1  FACE="Arial"><?php echo $row['notes']; ?>

<?php

    if($row['is_disabled'])
    {
    	echo '<br><br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"> <font color=red>'.$row['is_disabled'].'</font>';
  	}
  	elseif($row['bill_number']>0)
  	{
  		echo '<br><br><img src="../../gui/img/common/default/check2.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)"> <font color=green>'.$LDAlreadyBilled.' '.$row['bill_number'].'</font>';
  		if($amount>0) echo '<br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)"> <font color="red">'.$LDTheDrugDosagehasChanged.'</font>';
  	}
  	elseif($notbilledyet)
  	{
  		echo '<br><br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"> <font color=red>'.$LDPrescriptionNotBilled.'</font>';
  	}
  	?>
    </td>
    <td><FONT SIZE=-1  FACE="Arial"><?php
    if($row['is_disabled'] || $row['bill_number']>0)
  	{
  		echo '<font color="#D4D4D4">edit</font>';
  	}
  	else
    echo '<a href="'.$thisfile.URL_APPEND.'&mode=edit&nr='.$row['nr'].'&show=insert&backpath='.urlencode($backpath).'&prescrServ='.$_GET['prescrServ'].'&externalcall='.$externalcall.'&disablebuttons='.$disablebuttons.'">'.$LDEdit.'</a>';
	?>
    </td><td><FONT SIZE=-1  FACE="Arial"><?php echo $row['order_nr']; ?></td>
  </tr>
  <tr bgcolor="<?php echo $bgc; ?>" valign="top">
    <td><FONT SIZE=-1  FACE="Arial">Days: <?php echo $row['days']; ?></td>

    <td><FONT SIZE=-1  FACE="Arial"><?php
    if($row['is_disabled'] || $row['bill_number']>0)
  	{
  		echo '<font color="#D4D4D4">'.$LDdelete.'</font>';
  	}
  	else
      echo '<a href="'.$thisfile.URL_APPEND.'&mode=delete&nr='.$row['nr'].'&show=insert&backpath='.urlencode($backpath).'&prescrServ='.$_GET['prescrServ'].'&externalcall='.$externalcall.'&disablebuttons='.$disablebuttons.'">'.$LDdelete.'</a>' ?>
    </td>
    <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['prescriber']; ?></td>

  </tr>
<?php
}
// if($row['bill_number']<1){
//            echo '<tr bgcolor="'. $bgc .'"><td colspan=4 align=center><b>
//            <a href="../accounting/billing_ke_pharm_quote.php?encounterNr='.$encounter_nr.'&target=$target">Bill this patient</a><b></td></tr>';
//          }
?>

</table>

<?php
if($parent_admit&&!$is_discharged) {
?>
<p>
<img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?>>
<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=new'; ?>">
<?php echo $LDEnterNewRecord ?>
</a>
<?php
}
?>
