<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN">
<HTML>
<HEAD>
	<TITLE> <?php echo $LDBillingInsurance; ?></TITLE>
	<meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
	<meta name="Author" content="Timo Hasselwander, Robert Meggle">
	<meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  	<script language="javascript" >
<!--
function gethelp(x,s,x1,x2,x3,x4)
{
	if (!x) x="";
	urlholder="../../main/help-router.php<?php echo URL_APPEND; ?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
	helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
	window.helpwin.moveTo(0,0);
}
// -->
</script>
<link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
<script language="javascript" src="../../js/hilitebu.js"></script>

<STYLE TYPE="text/css">
A:link  {color: #000066;}
A:hover {color: #cc0033;}
A:active {color: #cc0000;}
A:visited {color: #000066;}
A:visited:active {color: #cc0000;}
A:visited:hover {color: #cc0033;}
</style>
<script language="JavaScript">
<!--
function popPic(pid,nm){

 if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=6ac874bb63e983fd6ec8b9fdc544cab5&lang=$lang&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

}
// -->
</script>

<script language="javascript">

<!--
function closewin()
{
	location.href='startframe.php?sid=6ac874bb63e983fd6ec8b9fdc544cab5&lang=$lang';
}
// -->
</script>

<script language="javascript">
<!--
function saveData()
{
    document.forms["inputform"].submit();
}
function reset()
{
    document.forms["inputform"].submit();
}
-->
</script>

<link rel="StyleSheet" href="dtree.css" type="text/css" />
<script type="text/javascript" src="dtree.js"></script>

<script type="text/javascript">
<?php
		require($root_path.'include/inc_checkdate_lang.php');
?>
</script>
<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>


</HEAD>
<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066>
<table width=100% border=0 cellspacing=0 height=100%>
<tbody class="main">
	<tr>
		<td  valign="top" align="middle" height="35">
			 <table cellspacing="0"  class="titlebar" border=0>
          <tr valign=top  class="titlebar" >
            <td bgcolor="#99ccff" >
                &nbsp;&nbsp;<font color="#330066"><?php echo $LDPrescrWard; ?></font>
       </td>
  <td bgcolor="#99ccff" align=right><a
   href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
   href="javascript:gethelp('insurance_companies_overview.php','Administrative Companies :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
   href="pharmacy_tz_pass.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
 </tr>

 </table>		</td>
	</tr>

	</td></tr>
	</form>
	<tr>
		<td bgcolor=#ffffff valign=top>

		<table cellpadding=20>
		<tr valign=top>

		<!-- left side (list of insurances) -->
		<td>
		<form method="GET" id="inputform" action="" >
			<table cellpadding=5 ><font color=#000000>
                <th bgcolor=#add8e6>WARDS</th>
                    <?php

                        /* The following routine creates the list of insurances on the left side:  */

                        require($root_path.'include/inc_ward_lister.php');

                    ?>
			</table>

		</td>
				<!-- right side (form) -->
		<td valign="top">
                <TABLE cellSpacing=0  width=800 class="submenu_frame" cellpadding="0">
                    <TBODY>
                    <?php


                        include_once($root_path.'include/inc_date_format_functions.php');

                        if (isset($_GET['prescr_date']))
                        {
                            $prescr_date = $_GET['prescr_date'];
                            //echo 'Date set: '.$prescr_date;

                        }
                        else
                        {
                            $prescr_date = formatDate2Local(date('Y-m-d'),$date_format);


                        }

                        echo '<tr class="titlebar" bgcolor="#99ccff"><td><font color="#330066">Prescriptions for '.$prescr_date.'</font></td></tr>';
                        echo '<tr class="titlebar" bgcolor="#99ccff"><td>&nbsp;</td></tr>';
                    ?>





                    <TR>
                        <TD>
                            <TABLE cellSpacing=1 cellPadding=3 width=800>
                                <TBODY class="submenu">

                                <tr>
                                    <td class="submenu_item"><?php echo $LDPatientID?></td>
                                    <td class="submenu_item"><?php echo $LDLastName.', '.$LDName?></td>
                                    <td class="submenu_item"><?php echo $LDPrescriptions?></td>
                                    <td class="submenu_item"><?php echo $LDDosage?></td>
                                    <td class="submenu_item"><?php echo $LDTimesPerDay?></td>
                                    <td class="submenu_item"><?php echo $LDExtraNotes?></td>
                                    <td class="submenu_item"><?php echo $LDTaken?></td>
                                </tr>

								<form id="nursingform" method="GET" action="">

<?php


    $coreObjOuter = new Core;

    $sqlOuter = "select * from care_encounter where current_ward_nr=$ward_nr and is_discharged=0";

    $coreObjOuter->result = $db->Execute($sqlOuter);

    foreach ($coreObjOuter->result as $rowEncounter){

        echo '<TR  height=1>
                        <TD colSpan=7 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>';


        echo '<tr>';

        //data person
        $pid = $rowEncounter['pid'];
        echo '<td>'.$pid.'</td>';

        $sqlPerson = "select * from care_person where pid=$pid";
        $coreObjInner->result = $db->Execute($sqlPerson);
        $rowPerson = $coreObjInner->result->FetchRow();
        $name_last =  $rowPerson['name_last'];
        $name_first = $rowPerson['name_first'];

        echo '<td>'.$name_last.', '.$name_first.'</td>';


        //encounter nr
        $encounterNr = $rowEncounter['encounter_nr'];
        //echo 'Encounter nr: '.$encounterNr.'<br> ';


        //data prescription
        //$sqlInner = "select * from care_encounter_prescription where encounter_nr = $encounterNr";


        $dateSQL = substr($prescr_date, 6,4).'-'.substr($prescr_date, 3,2).'-'.substr($prescr_date, 0,2);

        $crit .= " and prescribe_date = '$dateSQL' ";

        $sqlInner = "select * from care_encounter_prescription INNER JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_encounter_prescription.article_item_number" .
            " where encounter_nr = $encounterNr $crit and purchasing_class='drug_list'";


        $coreObjInner->result = $db->Execute($sqlInner);

        $prescr = '';

        foreach ($coreObjInner->result as $rowPrescr){

            if ($prescr=='')
                $prescr .= '<td>';
            else
                $prescr .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>';

            $article = $rowPrescr['article'];
            $dosage  = $rowPrescr['dosage'];
            $times_per_day = $rowPrescr['times_per_day'];
            $notes = $rowPrescr['notes'];
            $taken = $rowPrescr['taken'];
            $nr = $rowPrescr['nr'];


            if (isset($_GET['save']))
            {

                $taken = $_GET['taken_'.$nr];

                if ($taken == 'on')
                    $takenSetter=1;
                else
                    $takenSetter=0;


                $sqlTaken = "UPDATE care_encounter_prescription SET taken='$takenSetter' WHERE nr='$nr'";
                echo $sqlTaken;
                $db->Execute($sqlTaken);


            }

            if ($taken)
                $checked = 'checked="checked"';
            else
                $checked = '';

            $prescr .= $article.'</td><td>'.$dosage.'</td><td>'.$times_per_day.'</td><td>'.$notes.'</td>
            <td><input type="checkbox" name="taken_'.$nr.'"  "'.$checked.'">';
            $prescr .= '</td></tr>';


        }
        if ($prescr == '')
            $prescr .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';

        echo $prescr;

    }
		?>

		</tr>

		</table>


		</form>
		</td>

	</tr>
	</tbody>
 </table>


</BODY>
</HTML>