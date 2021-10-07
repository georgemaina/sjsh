<link rel="stylesheet" type="text/css" href="cashbook.css">

<?php
//start session (see get.php for details)
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require_once('mylinks.php');
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
jsIncludes();
//require_once 'dhxMenus.php';
$cashier =$_SESSION['sess_login_username'];

$strmsg="";

$start_date=date("Y-m-d");
$start_time=date("H:i:s");
$end_date=date("Y-m-d");
$end_time=date("H:i:s");


Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);


function Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd) {
    $debug=false;
    print("<form method=\"POST\" action=\"\" name=\"points\">");
    print("<table>");
    print('<tr><td>'.doLinks().'</td>');
    print("<td><table class='style1'>");
 
    print("<tr><td colspan=2 align=center><b>Welcome to the Cashbook</b></td></tr>");
      
    $r_sql = "SELECT cash_point,shift_no,start_date,start_time FROM care_ke_shifts 
    WHERE cashier='$cashier' AND active=1";
    if($debug) echo $r_sql;
    $result=$db->Execute($r_sql);
    $rcount=$result->RecordCount();
    if($rcount>0){
        print("<tr><td colspan=2>You already have the following Cashpoint open</td></tr>");
        while($row=$result->FetchRow()){
            print("<tr><td><b>Cash Point:$row[0]   Shift No:$row[1]</b></td>
                    <td align=left><b>Started On:$row[2]  $row[3]</b></td></tr>");
        }
         print("<tr><td colspan=2>Please go to Cash Sale and use above Cash Point</td></tr>");
    }else{
         print("<tr><td colspan=2>Sorry, You don't have any Open Cashpoints.<br>
             Please click Start Shift to open a Cash Point.</td></tr>");
    }  
    print("</table></td></tr></table></form>");
}

//=============================================================
//edit the get last shift number
function getShiftNo($db,$cashpoint) {

    $sql="Select pcode,current_shift from care_ke_cashpoints where pcode = '$cashpoint'";
    $result=$db->Execute($sql);
    if (!($row=$result->FetchRow())) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }
    
    return $row[1];
    
}
//===============================================================
function filled_out($form_vars) {

//test that each variable has a value
    foreach ($form_vars as $key=>$value) {
        if (!isset($key) || $value=="" )
            return false;
    }
    return true;
}

//check for cashpoint is active
function checkShift($db,$cashpoint) {

    $s_sql = "Select active from care_ke_cashpoints
 where pcode='$cashpoint'";
    $result=$db->Execute($s_sql);
    if (!($row=$result->FetchRow())) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

    return $row[0];
}
         
?>
<script>
    function getPoints(){
        return document.points.cash_points.id;
    }

    function load() {
        var load = window.open('http://cash_sale.php','','');
    }
    // -->
    var xmlhttp;

    function showDesc(str)
    {
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?desc="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=points";
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);

    }

    function stateChanged()
    {
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            var str2=str.search(/,/)+1

            document.points.cpDesc.value=str.split(",",1);
            //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
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

    function popme(mtext){
        alert(mtext);
    }

</Script>
