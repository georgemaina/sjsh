<?php
//require('./roots.php');
$debug=false;
$_SESSION['item_array']=NULL;

if (empty($show))
    $show="drug list"; // if there are no other values given, show the default: It is the drug list for doctors

if (!empty($show)) { // In case something goes wrong, then do nothing!

    if ($debug) echo "Show tab: ".$show."<br>";
    if ($debug) echo "DB-Filter: ".$db_drug_filter."<br>";
    if ($debug) echo "DB-Filter2: ".$filter."<br>";
    if ($debug) echo "This is external call?: ".$externalcall."<br>";
    if ($debug) echo "prescrServ: ".$_GET['prescrServ']."<br>";
    if ($debug) echo "this file: ".$thisfile."<br>";
    if ($debug) echo "the pid is".$pid ."<br>";


    if (empty($db_drug_filter))
        $db_drug_filter="drug_list";
    $drug_list = $pres_obj->getDrugList($db_drug_filter, '0'.$type,$pid);

    if ($filter=='pediadric')
        $drug_list = $pres_obj->getDrugList($db_drug_filter, "is_pediatric".$type,$pid);
    elseif ($filter=='adult')
        $drug_list = $pres_obj->getDrugList($db_drug_filter, "is_adult".$type,$pid);
    elseif ($filter=='others')
        $drug_list = $pres_obj->getDrugList($db_drug_filter, "is_other".$type,$pid);
    elseif ($filter=='consumable')
        $drug_list = $pres_obj->getDrugList($db_drug_filter, "is_consumable".$type,$pid);
}
else {
    $drug_list = $pres_obj->getDrugList("drug_list", 0,$pid);
}
?>

<script language="javascript" src="<?php echo $root_path;?>js/check_prescription_form.js"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if ($debug) echo "this file is named: ".$thisfile."<br>"; ?>
<?php if ($debug) echo "activated tab: ".$activated_tab."<br>"; ?>
<?php if ($debug) echo URL_APPEND; ?>
    <form name="prescription" method="POST" action="<?php echo $thisfile.URL_APPEND;?>&mode=new">
        <tr>
            <?php
            if (isset($externalcall))
                $EXTERNAL_CALL_PARAMETER="&externalcall=".$externalcall;
            ?>
            <td colspan="4">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr valign="middle">

                        <?php
                        $categories1 = array('druglist', 'service','physiotherapy', 'Theatre', 'Dental', 'Others', 'MEDICAL-SUPPLIES', 'smallop', 'special-others', 'eye-service');
                        $categories2 = array('Drug List','service', 'physiotherapy', 'Theatre', 'Dental', 'Others', 'MEDICAL-SUPPLIES', 'smallop', 'special-others', 'eye-service');
                        $categories3 = array('prescription_drugs.gif', 'prescription_supplies.gif', 'prescription_specialsupplies.gif', 'prescription_specialdrugs.gif', 'prescription_dental.gif',  'prescription_service.gif', 'prescription_specialdrugs.gif', 'prescription_smallop.gif', 'prescription_bigop.gif', 'eye.gif');
                        $categories4 = array('Drug List', 'Services', 'Physiotherapy', 'Theatre', 'Dental Services', 'Other Services', 'Medical Supplies', 'special-others', 'Eye Clinic');

                        //echo '$prescrServ = '.$_GET['prescrServ'];

                        if ($_GET['prescrServ']=='serv') {
                            $start = 7;
                            $end   = count($categories1);
                            $prescrServ = 'serv';
                        }
                        else {
                            $start = 0;
                            $end   = 6;
                            $prescrServ = '';

                        }

                        for ($i = $start; $i <= $end; ++$i) {
                            echo '<td ';
                            $pres_obj->DisplayBGColor($activated_tab, $categories1[$i]);
                            echo '><div align="center"><a href="#" onClick="javascript:submit_form(\''.$thisfile.URL_APPEND.'&mode=new&show='.$categories2[$i].'&disablebuttons='.$disablebuttons.''.$EXTERNAL_CALL_PARAMETER.'&prescrServ='.$prescrServ.'&backpath='.urlencode($backpath).'\')"><b>'.$categories4[$i].'</b> - <img border="0" src="../../gui/img/common/default/'.$categories3[$i].'" alt="'.$categories4[$i].'"></a></div></td>';
                        }

                        //echo '<td '; $pres_obj->DisplayBGColor($activated_tab, 'druglist'); echo '><div align="center"><a href="#" onClick="javascript:submit_form(\''.$thisfile.URL_APPEND.'&mode=new&show=Drug List&disablebuttons='.$disablebuttons.''.$EXTERNAL_CALL_PARAMETER.'&backpath='.urlencode($backpath).'\')"><img border="0" src="../../gui/img/common/default/prescription_drugs.gif" alt="Drug List"></a></div></td>';

                        ?>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        <br>
        <td colspan="4" bgcolor="#CAD3EC">
<?php
            if($activated_tab=='druglist' || $activated_tab == 'Supplies' || $activated_tab == 'supplies-lab' || $activated_tab == 'special-others') {
                ?>
            <table width="100%" border="0" align="center" bordercolor="#330066" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="10">
                        Select Dispensing Store:<select name='storeID' id='storeID'>
                                    <?php if($encounterClass==2){
                                        echo "<option value='Dispens'>PHARMACY</option>";
                                    }else{
                                        echo " <option></option>";
                                    }
                                     $input_user = $_SESSION['sess_login_username'];
                                     $sql = "SELECT st_id,st_name FROM care_ke_stlocation WHERE store=1 AND Dispensing=1";
                                            $result = $db->Execute($sql);
                                            while ($row = $result->FetchRow()) {
                                                 echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';  
                                            } 
                                  ?>
                              </select>       
                   
<!--                        <font color="black"><?php echo $LDCommonItemOf; ?> </font>-->
                        
                    </td>
                    <td bgcolor="#CAD3EC" width="130">
    <?php
    if ($is_transmit_to_weberp_enable==1) {
                                ?>
                        <font color="black"><?php echo $LDType; ?></font>
                        <select
                            name="type_select"
                            onChange="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=pediadric&type='+prescription.type_select.selectedIndex+'&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                            >
                            <option <?PHP echo ($type=='0') ? 'selected' : '';?> value='All'>All</option>
                            <option <?PHP echo ($type=='1') ? 'selected' : '';?> value='Tablets'>Tablets</option>
                            <option <?PHP echo ($type=='2') ? 'selected' : '';?> value='Syrup'>Syrup</option>
                            <option <?PHP echo ($type=='3') ? 'selected' : '';?> value='Injection'>Injection</option>
                        </select>
        <?php
    } else {
                                ?>
                        &nbsp;
                                <?php
    }
                            ?>
                    </td><!-- additional filter (radiobuttons)
                    <td bgcolor="#CAD3EC" width="130">

                          <input type="radio"
                              name="peadrics_button"
                              value="<?PHP echo ($filter=='pediadric') ? '1' : '0';?>"
    <? if ($filter=='pediadric') echo 'checked';?>
                              onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=pediadric&type='+prescription.type_select.selectedIndex+'&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                          ><font color="black"><?php echo $LDPediatricItems; ?></font>
                    </td>
                    <td bgcolor="#CAD3EC" width="100">

                          <input type="radio"
                            name="adult_button"
                            value="<?PHP echo ($filter=='adult') ? '1' : '0';?>"
    <? if ($filter=='adult') echo 'checked';?>
                            onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=adult&type='+prescription.type_select.selectedIndex+'&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                          ><font color="black"><?php echo $LDAdultItems; ?></font>
                    </td>
                    <td bgcolor="#CAD3EC" width="80">

                          <input type="radio"
                            name="others_button"
                            value="<?PHP echo ($filter=='others') ? '1' : '0';?>"
    <? if ($filter=='others') echo 'checked';?>
                            onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=others&type='+prescription.type_select.selectedIndex+'&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                          ><font color="black"><?php echo $LDOthers; ?></font>
                    </td>
                    <td bgcolor="#CAD3EC">

                          <input type="radio"
                            name="conusumable"
                            value="<?PHP echo ($filter=='consumable') ? '1' : '0';?>"
    <? if ($filter=='consumable') echo 'checked';?>
                            onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&filter=consumable&type='+prescription.type_select.selectedIndex+'&mode=new&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                          >
                          <font color="black"><?php echo $LDARVDrugs; ?></font></td>-->
                </tr>
            </table>
    <?php
}
if($activated_tab=='service'){
    ?>
    <font color="black">Select the Service <?php echo $LDType; ?> </font>
    <select
        name="type_select"
        onChange="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter='+prescription.type_select.selectedIndex+'&type='+prescription.type_select.selectedIndex+'&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')">
        <option <?PHP echo ($type=='0') ? 'selected' : '';?> value='All'>All</option>
        <option <?PHP echo ($type=='1') ? 'selected' : '';?> value='Tablets'>Eye Services</option>
        <option <?PHP echo ($type=='2') ? 'selected' : '';?> value='Syrup'>Ent Services</option>
        <option <?PHP echo ($type=='3') ? 'selected' : '';?> value='Injection'>Procedures</option>
    </select>
<?php
            }
            ?>
            <?php
            if($activated_tab=='eye-service' || $activated_tab=='eye-surgery' || $activated_tab=='eye-glasses') {
                ?>
            <table  border="0" align="center" bordercolor="#330066" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="10">
                        <font color="black"><?php echo $LDCommonItemOf; ?> </font>
                    </td>

                    <td ><div align="center"><input type="radio" name='service' onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&show=eye-service&disablebuttons=<?php echo $disablebuttons;?><?php echo $EXTERNAL_CALL_PARAMETER;?>&backpath=<?php echo urlencode($backpath); ?>')">Service</div></td>
                    <td ><div align="center"><input type="radio" name='surgery' onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&show=eye-glasses&disablebuttons=<?php echo $disablebuttons;?><?php echo $EXTERNAL_CALL_PARAMETER;?>&backpath=<?php echo urlencode($backpath); ?>')">Glasses</div></td>
                    <td><div align="center"><input type="radio" name='glasses' onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&show=eye-surgery&disablebuttons=<?php echo $disablebuttons;?><?php echo $EXTERNAL_CALL_PARAMETER;?>&backpath=<?php echo urlencode($backpath); ?>')">Surgery</div></td>
           <!--        <td bgcolor="#CAD3EC" width="130">


                         <input type="radio"
                             name="peadrics_button"
                             value="<?PHP echo ($filter=='pediadric') ? '1' : '0';?>"
    <? if ($filter=='pediadric') echo 'checked';?>
                             onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=pediadric&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                         ><font color="black"><?php echo 'Glasses'; ?></font>
                   </td>DENTAL_SERVICES

                   <td bgcolor="#CAD3EC" width="100">

                         <input type="radio"
                           name="adult_button"
                           value="<?PHP echo ($filter=='adult') ? '1' : '0';?>"
    <? if ($filter=='adult') echo 'checked';?>
                           onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=adult&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                         ><font color="black"><?php echo 'Surgery'; ?></font>
                   </td>
                   <td bgcolor="#CAD3EC" width="80">

                         <input type="radio"
                           name="others_button"
                           value="<?PHP echo ($filter=='others') ? '1' : '0';?>"
    <? if ($filter=='others') echo 'checked';?>
                           onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&filter=others&show=<?php echo $show;?>&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons;?>&backpath=<?php echo urlencode($backpath); ?>')"
                         ><font color="black"><?php echo 'Service'; ?></font>
                   </td>-->
                </tr>
            </table>
    <?php
}
?>


        </td>
        </tr>
        <tr>
            <td colspan="4" bgcolor="#CAD3EC">
                <table width="100%" border="0" bgcolor="#CAD3EC" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="37%" rowspan="5">   
                            <select name="itemlist[]" size="30" style="width:330px;" onDblClick="javascript:item_add();">

                                <!-- dynamically managed content -->
<?php	$pres_obj->DisplayDrugs($drug_list);?>

                                <!-- dynamically managed content -->

                            </select>
                        </td>
                        <td height="5">&nbsp;</td>
                        <td width="38%" rowspan="5"><div align="center">
                                <select name="selected_item_list[]" size="30" style="width:315px;" onDblClick="javascript:item_delete();">

                                    <!-- dynamically managed content -->
<?php $pres_obj->DisplaySelectedItems($item_no); ?>
                                    <!-- dynamically managed content -->

                                </select>
                            </div></td>
                    </tr>
                    <tr>
                        <td height=50" valign="top"><div align="center">&nbsp;
                                <input type="button" name="Del" value="<?php echo $LDadd; ?> >>" onClick="javascript:item_add();">
                            </div></td>
                    </tr>
                    <tr>
                        <td width="25%" height="60" valign="top"> <div align="center">
                                <input type="button" name="Add" value="<< <?php echo $LDdel; ?>" onClick="javascript:item_delete();">
                            </div></td>
                    </tr>
                    <tr>
                        <td height="20" align="center">
<?
if (isset($externalcall)) {
    ?>
                            <input type="button" name="show" value="<?php echo $LDPrescribe; ?>" onClick="javascript:submit_form('<?php echo $thisfile.URL_APPEND;?>&mode=new&prescrServ=<?php echo $_GET['prescrServ']?>&show=insert&externalcall=<?php echo $externalcall;?>&disablebuttons=<?php echo $disablebuttons; ?>&backpath=<?php echo urlencode($backpath); ?>')">
                                <?
                            } //else {
    ?>
                                                           <?
                            //}
?></td>
                    </tr>
                </table>
        </tr>
    </form> 
</table>