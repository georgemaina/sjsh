<?php
require_once 'roots.php';
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path . "modules/accounting/extIncludes.php");
?>
<!-- dhtmlxWindows -->
<!-- dhtmlxWindows -->

<script src='dateCalculations.js'></script>

<script>
function chkform(d) {
        if(d.crNo.value==""){
            document.getElementById('msgText').innerHTML="Please enter the Credit No";
            d.crNo.focus();
            return false;
        }else if(d.pid.value==""){
            document.getElementById('msgText').innerHTML="Please enter the Patient ID";
            d.pid.focus();
            return false;
        }else if(d.en_nr.value==""){
            document.getElementById('msgText').innerHTML="Please enter the encounter No";
            d.en_nr.focus();
            return false;
        }else if(d.pname.value==""){
            document.getElementById('msgText').innerHTML="Please enter the Patients Names";
            d.pname.focus();
            return false;
        }else if(d.admDate.value==""){
            document.getElementById('msgText').innerHTML="Please enter the Admission Date";
            d.admDate.focus();
            return false;
        }else if(d.dischargeDate.value==""){
            document.getElementById('msgText').innerHTML="Enter ther dischargeDate";
            d.dischargeDate.focus();
            return false;
        }else if(d.wrdDays.value==""){
            document.getElementById('msgText').innerHTML="Days cannot be empty";
            d.wrdDays.focus();
            return false;
        }else if(d.nhifNo.value==""){
            document.getElementById('msgText').innerHTML="Please enter the NHIF No";
            d.nhifNo.focus();
            return false;
        }else if(d.rateCalc.value==""){
            document.getElementById('msgText').innerHTML="Please enter the NHIF Credit per day";
            d.rateCalc.focus()
            return false;
        }else if(d.nhifdbac.value==""){
            document.getElementById('msgText').innerHTML="Please enter NHIF Debtor Account";
            d.nhifdbac.focus()
            return false;
        }else if(d.Total.value==""){
            document.getElementById('msgText').innerHTML="Total Credit cannot be empty";
            d.Total.focus()
            return false;
        }else if(d.invAmount.value==""){
            document.getElementById('msgText').innerHTML="Invoice Amount cannot be empty";
            d.invAmount.focus()
            return false;
        }else if(d.clientType.value==""){
            document.getElementById('msgText').innerHTML="Please Select NHIF Client Type";
            d.clientType.focus()
            return false;
        }
        else if(d.invBalance.value==""){
            document.getElementById('msgText').innerHTML="Balance cannot be Empty, Click on it to get the Balance";
            d.invBalance.focus()
            return false;
        }
    }
</script>

<style type="text/css" name="1">
    .pg1{border-style: solid;border-width:thin ;font-family: serif;font-size: large;font-weight: bold; border-color: #01699E}
    .adml{border-style: solid; border-left: solid; border-width:thin;}
    .adm2{border-style: solid; border-width:thin;}
    .tbl1{width: 100%}
    .myMessage{font-size: large;color: #ffffff;background-color: #cc0033;font-weight: bold;
               width: 60%;text-decoration: blink;}
</style>
<?php
$debug = false;
require($root_path . 'include/care_api_classes/accounting.php');
$bill_obj = new Bill;
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
$insurance_obj = new Insurance_tz;

echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>NHIF Credit</font></td></tr>
    <tr><td align=left valign=top>";
require 'aclinks.php';
echo '</td><td width=80%>';

if (!isset($_POST[submit])) {
    displayForm();
} else {

//    echo var_dump($_POST);
    $creditNo = $_POST['crNo'];
    $admno = $_POST['pid'];
    $Names = $_POST['pname'];
    $admDate = $_POST['admDate'];
    $disDate = $_POST['dischargeDate'];
    $wrdDays = $_POST['wrdDays'];
    $nhifNo = $_POST['nhifNo'];
    $nhifDesc = $_POST['nhifdbacdesc'];
    $nhifDbAc = $_POST['nhifdbac'];
    $totalCredit = $_POST['Total'];
    $invAmount = $_POST['invAmount'];
    $invBalance = $_POST['invBalance'];
    $en_nr = $_POST['en_nr'];
    $pid = $_POST['pid'];
    $username= $_SESSION['sess_login_username'];

    insertData($db, $creditNo, $admno, $Names, $admDate, $disDate, $wrdDays, $nhifNo, $nhifDbAc, $nhifDesc, 
            $totalCredit, $invAmount, $invBalance, $totalCredit,$en_nr);

     
    updateBill($db, $creditNo, $nhifNo, $Names, $admDate, $disDate, $ReleaseDate, $wrdDays, $invoiceNo, 
            $invAmount, $totalCredit, $invBalance, $nhifDbAc, $en_nr, $pid);

    $sql="SELECT count(id) as idcnt FROM care_tz_company WHERE id=(SELECT insurance_id FROM care_person WHERE pid=$pid)";
    if($insu_result = $db->Execute($sql)){
        $insu_row=$insu_result->FetchRow();
        if($insu_row[0]<1){
            updateNHIFBILL($creditNo, $nhifNo,$invAmount,$totalCredit, $invBalance, $en_nr, $pid,$new_bill_number,$username);
       }
     }
    
    updateInsuranceBill($pid,$invBalance);
    
    
    displayForm();
}
echo "</td></tr></table>";


function getNhifRates(){
    global $db;
    $sql="SELECT ratename,rateType from care_ke_rates";
      $results=$db->Execute($sql);
      $rates='<option value=""></option>';
      while($rows=$results->FetchRow()){
          $rates=$rates."<option value=$rows[0]>$rows[1]</option>";
      }
      
      return $rates;
}

function displayForm() {
    $rates=getNhifRates();
    
    echo '<form name="debit" method="post" action="' . $_SERVER['PHP_SELF'] . '" onSubmit="return chkform(this)">
            <table width=80% border="0" cellpadding="0" cellspacing="5">
                <tr><td colspan="6" class=pg1 ><center>NHIF Credit</center></td></tr>
                <tr>
                    <td id="msgText" colspan=6  class="myMessage" >
                    </td>
                </tr>
                <tr><td>
                        <table width="100%" border="0">
                            <tr><td width="15%">Credit No:</td>
                                <td><input type="text" name="crNo" id="crNo" value="" onclick="getNextCrdNo()"/>
                                    Bill Number<input type="text" name="bill_number" id="bill_number" size="15"/>
                                    Encounter No:<input type="text" name="en_nr" size="10" id="en_nr" onblur="getBalance(this.value)"/>
                                    Ward No:<input type="text" name="ward_nr" id="ward_nr" size="10"/></td>
                            </tr>
                            <tr>
                                <td>Patient No:</td>
                                <td><input type="text" name="pid" id="pid" onblur="getPatientNHIF(this.value)"/>
                                    <input type="text" name="pname" id="pname" size="40" onblur="getAdmDis(document.debit.pid.value)"/>
                                    <input type="button" id="search" value="search" /></td>
                            </tr>
                        </table>     
                    </td>
                </tr>
             
                <tr>
                    <td class="adm1">
                        <table class="tbl1">
                            <tr>
                                <td width="15%">Admission Date:</td>
                                <td width="32%"><input type="text" name="admDate" id="admDate" value=""/></td>
                                <td width="20%">Discharge Date:</td>
                                <td><input type="text" name="dischargeDate" id="dischargeDate" value=""/></td>
                               
                            </tr>
                            <tr>
                                <td>Days:</td>
                                <td><input type="text" name="wrdDays" id="wrdDays" value="" /></td>
                                <td width="20%">Release Date:</td>
                                <td><input type="text" name="releaseDt" id="releaseDt" value="" onclick="getDays()"/></td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td class="adml">
                        <table width="100%" border="0">
                            <tr><td width="15%">NHIF No:</td>
                                <td width="32%"><input type="text" name="nhifNo" id="nhifNo" /></td>
                                <td width="20%">NHIF Client Type</td>
                                <td><select id="clientType" onchange=getNHIFRates(this.value)>"'.$rates.'"</select>
                                    <input type="text" id="rateCalc" name="rateCalc" size="10" />
                                </td>
                              
                            </tr>
                            <tr>
                                <td width="15%">NHIF Debtors Ac:</td>
                                <td><input type="text" name="nhifdbac" id="nhifdbac" size="10" onclick="getNHIFAcc()"/>
                                    <input type="text" name="nhifdbacdesc" id="nhifdbacdesc" /></td>
                                <td>NHIF Credit per day:</td>
                                <td><input type="text" name="nhifCrd" id="nhifCrd" onblur="getTotalcredit()"/></td>
                            </tr>
                            <tr>
                                <td width="15%" >NHIF Category Account:</td>
                                <td><input type="text" name="NHIFCat" id="NHIFCat" size="10" onclick="getNHIFCat()"/></td>
                                <td>Total Credit:</td>
                                <td><input type="text" name="Total" id="Total"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td class="adm2">
                        <table width="100%" border="0">
                            <tr><td width="15%">Invoice Amount:</td>
                                <td width="32%"><input type="text" name="invAmount" id="invAmount" onclick="getInvoiceDetails()"/></td>
                                <td width="20%">Balance:</td>
                                <td><input type="text" name="invBalance" id="invBalance" onclick="getInvoiceBalance()"/></td>
                            </tr>
                            <tr><td></td>
                                <td colspan=4 align=center>
                                    <input type="submit" name="submit" id="submit" value="save" />
                                    <input type="button" name="cancel" id="cancel" value="cancel" /></td>
                            </tr>
                        </table>    
                    </td>
                </tr>
            </table>
        </form>';
}

function insertData($db, $creditNo, $admno, $Names, $admDate, $disDate, $wrdDays, $nhifNo, $nhifDbAc, 
        $nhifDesc, $totalCredit, $invAmount, $balance, $Premium,$en_nr) {
    require('./roots.php');
    require_once($root_path . 'include/care_api_classes/accounting.php');
    require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
    $bill_obj = new Bill;
    $acc_obj = new accounting();
    $debug = false;
//    $new_bill_number= getBillNumber($en_nr);

    $new_bill_number=$bill_obj->checkBillEncounter($en_nr);

    $user = $_SESSION['sess_login_username'];
    $sql = "INSERT INTO care_ke_nhifcredits
	    (creditNo,
        bill_number,
        inputDate,
        admno,
        NAMES,
        admDate,
        disDate,
        wrdDays,
        nhifNo,
        nhifDebtorNo,
        debtordesc,
	    totalCredit,
        invAmount,
        balance,inputUser
	)
	VALUES
	('$creditNo','$new_bill_number','" . date('Y-m-d') . "','$admno', '$Names', '$admDate', '$disDate','$wrdDays', 
            '$nhifNo','$nhifDbAc', '$nhifDesc','$totalCredit','$invAmount','$balance','$user')";

        if(!$db->Execute($sql)){
            echo 'Problem Saving the record';
        }
    if ($debug)
        echo $sql;

}

//Update the Balance after NHIF Credit

function getBillNumber($en_nr){
    global $db;
    $debug=false;
      $sqlNhif = "SELECT b.bill_number FROM care_ke_billing b INNER JOIN care_encounter e ON b.pid=e.pid WHERE 
            b.encounter_nr=$en_nr AND e.is_discharged=1 having max( b.encounter_nr)";
      
    if($debug) echo $sqlNhif;
    
    $request=$db->Execute($sqlNhif);
        $row = $request->FetchRow();
    return $row[0];
    
}

function updateBill($db, $creditNo, $nhifNo, $Names, $admDate, $disDate, $ReleaseDate, $wrdDays, $invoiceNo, $invAmount, 
        $totalCredit, $invBalance, $nhifDbAc, $en_nr, $pid) {
    require('./roots.php');
    require_once($root_path . 'include/care_api_classes/accounting.php');
    require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
    $bill_obj = new Bill;
    $acc_obj = new accounting();

    $new_bill_number=$bill_obj->checkBillEncounter($en_nr);

    $debug = false;

//    $new_bill_number= getBillNumber($en_nr);

    $user = $_SESSION['sess_user_name'];


    $sql3 = "INSERT INTO care_ke_billing (pid, encounter_nr,insurance_id,bill_date,bill_time,`ip-op`,bill_number,
        service_type, price,`Description`,notes,
        input_user,status,days,qty,total,rev_code,batch_no)
        value('" . $pid . "','" . $en_nr . "','" . $nhifDbAc . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1',
            '" . $new_bill_number . "','NHIF','$totalCredit','NHIF Credit No','NHIF Credit','$user',
     'billed','" . $wrdDays . "','1','$totalCredit','NHIF','$creditNo')";

    if ($debug)
        echo $sql3 . "<br>";
    if ($db->Execute($sql3)) {
        $sql = "SELECT accno,os_bal FROM care_ke_debtors c where name like 'NHIF%'";
        if ($result = $db->Execute($sql)) {
            $row = $result->FetchRow();
            $currBal = $row[1];
        } else {
            echo "error:" . $sql;
        }
        if ($debug)
            echo $sql . "<br>";
        $newBal = intval($currBal + $totalCredit);

        $sql1 = "update care_ke_debtors set os_bal='$newBal',last_trans='".date('Y-m-d')."' where accno='$row[0]'";
        if ($debug)
        echo $sql1 . "<br>";
        if (!$db->Execute($sql1)) {
            echo "error:" . $sql1;
        }
        
               
        if ($debug)
            echo $sql1 . "<br>";
     }else {
        
    }
}

function updateDbtErp($db, $pn) {
//global $db, $root_path;
    $debug = false;
    if ($debug)
        echo "<b>class_tz_billing::updateDbtErp()</b><br>";
    if ($debug)
        echo "encounter no: $pn <br>";
    ($debug) ? $db->debug = TRUE : $db->debug = FALSE;
    $sql = 'SELECT b.pid, c.unit_price AS price,c.partcode,c.item_Description AS article,a.prescribe_date,a.qty AS amount,a.bill_number
    FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c
    ON a.item_number=c.partcode
    INNER JOIN care_encounter b
    ON a.pid=b.pid and b.pid="' . $pn . '" and service_type="ward procedure"';
    $result = $db->Execute($sql);
    if ($weberp_obj = new_weberp()) {
        //$arr=Array();
        while ($row = $result->FetchRow()) {
            //$weberp_obj = new_weberp();
            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                echo 'success<br>';
                echo date($weberp_obj->defaultDateFormat);
            } else {
                echo 'failed';
            }
            destroy_weberp($weberp_obj);
        }
    } else {
        echo 'could not create object: debug level ';
    }
}

function updateInsuranceBill($pid,$newBal) {
    global $db;
    $debug=false;
    $sql = "SELECT d.accno,d.os_bal FROM care_ke_debtors d LEFT JOIN care_tz_company c ON d.accno=c.accno
            LEFT JOIN care_person p ON c.id=p.insurance_ID WHERE p.pid=$pid";

    if ($debug)
        echo $sql . "<br>";
    $result = $db->Execute($sql);
   
    $row = $result->FetchRow();
        $bal=intval($row[1]+$newBal);

        $sql = "update care_ke_debtors set os_bal='$bal' where accno='$row[0]'";
        if ($debug)
            echo $sql . "<br>";
        $db->Execute($sql);
    
}

function updateNHIFBILL($creditNo, $nhifNo,$invAmount,$totalCredit, $invBalance, $en_nr, $pid,$new_bill_number,$username) {
    global $db;
     require('./roots.php');
//    require_once($root_path . 'include/care_api_classes/accounting.php');
    require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
    $bill_obj = new Bill;
//    $acc_obj = new accounting();
    $debug=false;
    
//    $new_bill_number= getBillNumber($en_nr);
    $new_bill_number=$bill_obj->checkBillEncounter($en_nr);
        $invBal=($totalCredit-$invAmount);
        
    $sql2="INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode,item_number,
                    weberpsync,debtorUpdate,insurance_id)
                    VALUES('$pid','$en_nr','1','".date('Y-m-d')."','$new_bill_number','NHIF',
                    'NHIF GAIN/LOSS','$invBal','1','$invBal','$username','NHIF Debtor Account','Paid','$creditNo',
                    '".date('H:i:s')."','nhif2','nhif2','nhif2',1,1,'NHIF2')";

        if ($debug)
            echo $sql2 . "<br>";
        $result = $db->Execute($sql2);
        
        $trnsNo = $bill_obj->getTransNo(2);
        
        $sql4 = "insert into `care_ke_debtortrans`
                                (`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,
                                `lasttransTime`,`settled`,encounter_nr,accountNo)
                                values('$trnsNo','2','NHIF', '$pid','" . date('Y-m-d') . "','$new_bill_number',
                                '$totalCredit','" . date('Y-m-d') . "','" . date('H:i:s') . "',0,'$en_nr','$nhifNo')";
            if ($debug)
                echo $sql4;
            if ($db->Execute($sql4)) {
                $newTransNo = ($trnsNo + 1);
                $sql3 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
                if ($debug)
                    echo $sql3;
                $db->Execute($sql3);

            }
            
        $sql5 = "insert into `care_ke_debtortrans`
                                (`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,
                                `lasttransTime`,`settled`,encounter_nr,accountNo)
                                values('$trnsNo','2','NHIF2', '$pid','" . date('Y-m-d') . "','$new_bill_number',
                                '$invBalance','" . date('Y-m-d') . "','" . date('H:i:s') . "',0,'$en_nr','$nhifNo')";
            if ($debug)
                echo $sql5;

            if ($db->Execute($sql5)) {
                $newTransNo = ($trnsNo + 1);
                $sql6 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
                if ($debug)
                    echo $sql6;
                $db->Execute($sql6);

            }
}

function GetTimeStamp($MySqlDate) {
    /*
      Take a date in yyyy-mm-dd format and return it to the user
      in a PHP timestamp
      Robin 06/10/1999
     */
    $date_array = explode("/", $MySqlDate); // split the array

    $var_year = $date_array[2];
    $var_month = $date_array[0];
    $var_day = $date_array[1];

    $var_timestamp = $var_year . '-' . $var_month . '-' . $var_day;
    return($var_timestamp); // return it to the user
}
?>


<script>
    var xmlhttp7;
   
    function getBalance(str){
        xmlhttp2=GetXmlHttpObject();
        if (xmlhttp2==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var enc_nr=document.getElementById('en_nr').value
        var url="accountingFns.php?desc2="+str;
        url=url+"&caller=getAdDsDate";
        url=url+"&enc_nr="+enc_nr;
        url=url+"&sid="+Math.random();
        xmlhttp2.onreadystatechange=stateChangedNHIF2;
        xmlhttp2.open("POST",url,true);
        xmlhttp2.send(null);

    }

    function stateChangedNHIF2()
    {
        //get payment description
        if (xmlhttp2.readyState==4)//show point desc
        {
            str=xmlhttp2.responseText;
            str2=str.split(",");

            document.getElementById('admDate').value=str2[0];
            document.getElementById('dischargeDate').value=str2[1];
            document.getElementById('releaseDt').value=str2[1];
            document.getElementById('wrdDays').value=str2[2];

            
        }
    }


    function getNHIFRates(rate){
        xmlhttp1=GetXmlHttpObject();
        if (xmlhttp1==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        currWard= document.getElementById('ward_nr').value;
         
        var url="accountingFns.php?rateID="+rate;
        url=url+"&caller=getNHIFRates";
        url=url+"&sid="+Math.random();
        xmlhttp1.onreadystatechange=stateChangedRates;
        xmlhttp1.open("POST",url,true);
        xmlhttp1.send(null);
    }
    
    
    function stateChangedRates()
    {
        //get payment description
        if (xmlhttp1.readyState==4)//show point desc
        {
            str=xmlhttp1.responseText;
            str2=str.split(",");

            document.getElementById('nhifCrd').value=str2[0];
            document.getElementById('rateCalc').value=str2[1];
//            getTotalcredit();
            
            getTotalcredit();
//        rateCalc=str2[1];
//        credit=document.getElementById('nhifCrd').value;
//        days=document.getElementById('wrdDays').value;
//        
//        if(str2[1]=='2'){
//             strdays=credit*days;
//        }else{
//             strdays=credit*1;
//        }
//       
//
//        document.getElementById('Total').value=strdays;


        }
    }

    function getNHIFCredit(){
        xmlhttp1=GetXmlHttpObject();
        if (xmlhttp1==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        currWard= document.getElementById('ward_nr').value;
         
        var url="accountingFns.php?desc6=nhif";
        url=url+"&caller=getNHIFCredit";
        url=url+"&ward="+currWard;
        url=url+"&sid="+Math.random();
        xmlhttp1.onreadystatechange=stateChanged1;
        xmlhttp1.open("POST",url,true);
        xmlhttp1.send(null);
    }

    function stateChanged1()
    {
        //get payment description
        if (xmlhttp1.readyState==4)//show point desc
        {
            str=xmlhttp1.responseText;
            str2=str.split(",");

            document.getElementById('nhifCrd').value=str2[0];
            //            document.getElementById('nhifdbac').value=str2[0];
            //            document.getElementById('nhifdbacdesc').value=str2[1];


        }
    }


    function getNHIFAcc(){
        xmlhttp1=GetXmlHttpObject();
        if (xmlhttp1==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        currWard= document.getElementById('ward_nr').value;
         
        var url="accountingFns.php?nhifAcc=nhif";
        url=url+"&caller=getNHIFAccount";
        url=url+"&sid="+Math.random();
        xmlhttp1.onreadystatechange=stateChanged8;
        xmlhttp1.open("POST",url,true);
        xmlhttp1.send(null);
    }

    function stateChanged8()
    {
        //get payment description
        if (xmlhttp1.readyState==4)//show point desc
        {
            str=xmlhttp1.responseText;
            str2=str.split(",");

            document.getElementById('nhifdbac').value=str2[0];
            document.getElementById('nhifdbacdesc').value=str2[1];
            getNHIFCat();

        }
    }
    
    function getNHIFCat(){
        xmlhttp1=GetXmlHttpObject();
        if (xmlhttp1==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        currWard= document.getElementById('ward_nr').value;
         
        var url="accountingFns.php?nhifAcc=nhif";
        url=url+"&caller=getNHIFcat";
        url=url+"&sid="+Math.random();
        xmlhttp1.onreadystatechange=stateChangeNHIFcat;
        xmlhttp1.open("POST",url,true);
        xmlhttp1.send(null);
    }

    function stateChangeNHIFcat()
    {
        //get payment description
        if (xmlhttp1.readyState==4)//show point desc
        {
            str=xmlhttp1.responseText;
            str2=str.split(",");

            document.getElementById('NHIFCat').value=str;

        }
    }


    function getTotalcredit(){

        rateCalc=document.getElementById('rateCalc').value;
        credit=document.getElementById('nhifCrd').value;
        days=document.getElementById('wrdDays').value;
        
        if(rateCalc==2){
             strdays=credit*days;
        }else if(rateCalc==3){
            strdays=getInvoiceAmount();
        }else{
             strdays=credit*1;
        }
       

        document.getElementById('Total').value=strdays;
        


    }

    function getAdmDis(str) {
        xmlhttp6=GetXmlHttpObject();
        if (xmlhttp6==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        
            var enc_nr=document.getElementById('en_nr').value
        
            var url="accountingFns.php?desc5="+str;
            url=url+"&caller=getAdDsDate";
            url=url+"&enc_nr="+enc_nr;
            url=url+"&sid="+Math.random();
            xmlhttp6.onreadystatechange=stateChanged6;
            xmlhttp6.open("POST",url,true);
            xmlhttp6.send(null);

        }
    }
    
    function stateChanged6()
    {
        //get payment description
        if (xmlhttp6.readyState==4)//show point desc
        {
            var str=xmlhttp6.responseText;
            
            str2=str.split(",");
            
            document.getElementById('admDate').value=str2[0];
            document.getElementById('dischargeDate').value=str2[1];
            document.getElementById('wrdDays').value=str2[2];
           
        }
    }


    function getDays(){

        firstdate=document.getElementById('admDate').value;
        seconddate=document.getElementById('dischargeDate').value;
        strdays=dateDiff(seconddate,firstdate);

        document.getElementById('wrdDays').value=strdays;

        getNHIFCredit();
        getTotalcredit();
        getInvoiceDetails();
    }

    function getInvoiceBalance(){
        nhifCrd=document.getElementById('Total').value;
        invAmount=document.getElementById('invAmount').value;
        strBalance=nhifCrd-invAmount;

        document.getElementById('invBalance').value=strBalance;
    }

    function getInvoiceAmount(){
        xmlhttp7=GetXmlHttpObject();
        if (xmlhttp7==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }

        var url="accountingFns.php?desc7="+document.getElementById('pid').value;
        url=url+"&caller=getInvoiceno";
        url=url+"&encNr="+document.getElementById('en_nr').value;
        url=url+"&sid="+Math.random();
        xmlhttp7.onreadystatechange=stateChanged20;
        xmlhttp7.open("POST",url,true);
        xmlhttp7.send(null);

    }

    function stateChanged20()
    {
        //get payment description
        if (xmlhttp7.readyState==4)//show point desc
        {
            var str=xmlhttp7.responseText;
            str3=str.split(",");
//            document.getElementById('invNo').value=str3[0];
            document.getElementById('Total').value=str3[1];
            //        alert(str);
        }
    }



    function getInvoiceDetails(){
        xmlhttp7=GetXmlHttpObject();
        if (xmlhttp7==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var url="accountingFns.php?desc7="+document.getElementById('pid').value;
        url=url+"&caller=getInvoiceno";
        url=url+"&encNr="+document.getElementById('en_nr').value;
        url=url+"&sid="+Math.random();
        xmlhttp7.onreadystatechange=stateChanged7;
        xmlhttp7.open("POST",url,true);
        xmlhttp7.send(null);

    }

    function stateChanged7()
    {
        //get payment description
        if (xmlhttp7.readyState==4)//show point desc
        {
            var str=xmlhttp7.responseText;
            str3=str.split(",");
            //            document.getElementById('invNo').value=str3[0];
            document.getElementById('invAmount').value=str3[1];
            //        alert(str);
        }
    }

    

    function getPatientNHIF(str)
    {

        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc3="+str;
        url=url+"&caller=debitGetPt";
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChangedNHIF;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

    function stateChangedNHIF()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            str3=str.split(",");
            document.getElementById('pname').value=str3[0]+' '+str3[1]+' '+str3[2];//str.split(",",3);
            document.getElementById('en_nr').value=str3[3];
            document.getElementById('ward_nr').value=str3[4];
            document.getElementById('bill_number').value=str3[5];
            var pid= document.getElementById('pid').value;
            
            checkNhifCredit(pid,str3[5]);
            
            getBalance(document.getElementById('en_nr').value);
            getNextCrdNo();
            //            getDisDate(document.debit.pid.value);
            //            getDays();
        }
    }

    function checkNhifCredit(pid,billNumber){
        xmlhttp3=GetXmlHttpObject();
        
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?pid="+pid;
        url=url+"&caller=verifyNhifCredit";
        url=url+"&billNumber="+billNumber;
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateValidateCredit;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
    }
    
    function stateValidateCredit()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            str3=str.split(",");
            if(str3[0]==1){
            document.getElementById('msgText').innerHTML="This NHIF Credit has already been Posted, Check NHIF Credits Report";
//            document.getElementById('pname').value='';
//            document.getElementById('en_nr').value='';
//            document.getElementById('ward_nr').value='';
//            document.getElementById('bill_number').value='';
////            document.getElementById('pid').value='';
//            document.getElementById('admDate').value='';
//            document.getElementById('dischargeDate').value='';
//            document.getElementById('wrdDays').value='';
            
            }else{
                document.getElementById('msgText').innerHTML='';
            }
            
        }
    }

    function getCodeDesc(str)
    {

        xmlhttp4=GetXmlHttpObject();
        if (xmlhttp4==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc4="+str;
        url=url+"&caller=debitGetRv";
        url=url+"&sid="+Math.random();
        xmlhttp4.onreadystatechange=stateChanged4;
        xmlhttp4.open("POST",url,true);
        xmlhttp4.send(null);

    }

    function stateChanged4()
    {
        //get payment description
        if (xmlhttp4.readyState==4)//show point desc
        {
            var str=xmlhttp4.responseText;
            var str2=str.search(/,/)+1
            document.getElementById('Description').value=str //.split(",",1);
            //applyFilter();

        }
    }


    function getNextCrdNo(){
        xmlhttp5=GetXmlHttpObject();
        if (xmlhttp5==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc1=credit";
        url=url+"&sid="+Math.random();
        url=url+"&caller=creditNo";
        xmlhttp5.onreadystatechange=stateChanged5;
        xmlhttp5.open("POST",url,true);
        xmlhttp5.send(null);
    }

    function stateChanged5()
    {
        //get payment description
        if (xmlhttp5.readyState==4)
        {
            var str=xmlhttp5.responseText;
            //str2=str.search(/,/)+1;
            document.debit.crNo.value=str;
        }
    }

 
    function GetXmlHttpObject()
    {
        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        }
        if (window.ActiveXObject)
        {
            // code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
        return null;
    }
</script>
