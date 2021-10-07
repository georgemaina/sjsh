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

if(isset($_REQUEST["submit"])) {

    if($_REQUEST[command]=="Start") {
        

        if(checkShift($db,$_REQUEST[cash_point])==1) {
            $strmsg= "<br><b><font color=\"red\">That cash point is already in use.</font></b><br><br>";
           $strcmd='Start Shift';
            
            Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
            exit();

        }

        $curr_shiftNo=getShiftNo($db,$_POST[cash_point])+1;
        $next_shiftNo=intval($curr_shiftNo)+1;

        $sql="update care_ke_cashpoints set active=1,next_shift_no='".$next_shiftNo."', current_shift='";
        $sql.=$curr_shiftNo."',start_date='".$start_date."', start_time='".$start_time.
            "', cashier='".$cashier."' where pcode='".$_POST[cash_point]."'";
        $result=$db->Execute($sql);
        //$GLOBALS[$shift_no] = $curr_shiftNo;
        //session_register('sess_shift');
        $sql2="INSERT INTO care_ke_shifts
                    (shift_no,
                    cash_point,
                    cashier,
                    start_date,
                    start_time,
                    active
                    )
                    VALUES
                    ('".$curr_shiftNo."',
                    '".$_POST[cash_point]."',
                    '".$cashier."',
                    '".$start_date."',
                    '".$start_time."',
                    '1'
                    )";
        $db->Execute($sql2);
       // echo $sql2;
        if(!$result) {
            die('Problem with insert statement.Error: ' . mysql_error());
            Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
        //$shift=$GLOBALS[$shift_no] ;
        }else {

//        $db->Execute($sql2);
            ?>
        }

<script type="text/javascript">
    <!--
    window.location = "Cash_Sale.php?cashpoint=<?php echo $_POST[cash_point] ?>&shiftNo=<?php echo $curr_shiftNo ?>"
    //-->
</script>


        <?php

        // printcashbook("cash_sale.php");
        // header("http://localhost/care2x/modules/cashbook/cashbook.php?page=Cash_Sale",true);

        }
    }elseif($_REQUEST[command]=="End") {
       $strcmd='End Shift';
        $sql="update care_ke_cashpoints set active=0";
        $sql.=",end_date='".$end_date."', end_time='".$end_time.
            "', cashier='".$cashier."' where pcode='".$_POST[cash_point]."'";

        $result=$db->Execute($sql);
       // echo $sql;
        $sql2="Update care_ke_shifts set
                    end_time='".$end_time
                    ."', end_date='".$end_date
                    ."', active=0 where cash_point='".$_POST[cash_point]."' and active=1";

        $db->Execute($sql2);
//       echo $sql2;
        if(!$result) {
            die('Problem with Update statement.desc: ' . mysql_error());
            Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
        }else {
            $strmsg= "<br><b><font color=\"blue\"> The shift was Stopped successfully</font></b><br>";
            Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
//            session_unregister("shift");
        }
    }
}else {
    $strcmd='Start Shift';
    $strmsg='';
    Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
}

function Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd) {

    print("<form method=\"POST\" action=\"\" name=\"points\">");
    print("<table>");
    print('<tr><td>'.doLinks().'</td>');
    print("<td><table class='style1'>");
   
    if($_REQUEST[command]=='End') {
        print("<tr class='pgtitle'><td colspan=2>End Shift</td></tr>");
       print("<tr><td colspan=2><b>Warning! <font color=\"red\">You are about to end a shift</font><b></td></tr>");
    }else {
         print("<tr class='pgtitle'><td colspan=2>Start Shift</td></tr>");
        print('<tr><td colspan=2><b><font color=\"blue\">You are about to Start a shift</font><b></td></tr>');
    }
    print("<tr><td colspan=2>".$strmsg."</td></tr>");
    print("<tr><td>Cash Point</td>");
   
    $r_sql = "Select code_id,pcode,name from care_ke_cashpoints";
    $result=$db->Execute($r_sql);
    print("<td><select id=\"cash_point\" name=\"cash_point\" onchange=\"showDesc(this.value)\">");
    while($row=$result->FetchRow()) {
        print("<option id=". $row['pcode']." value=".$row['pcode'].">".$row['pcode']."</option>");

    }
    print("</select></td></tr>");
    print("<tr><td>Description</td><td>");

    if($_REQUEST[command]=="start") {
        $stimelbl="Start time";
        $sdatelbl="Start Date";
    }else {
        $sdatelbl="End Date";
        $stimelbl="End Time";
    }
    print("<input type=\"text\" id=\"cpDesc\" name=\"cpDesc\" size=\"30\" /></td></tr>");
    print("<tr><td>Cashier</td><td>");
    print("<input type=\"text\" name=\"cashier\" value=".$cashier." /></td></tr>");
    print("<tr><td>Start Date</td>");
    //print("<td><input type=\"text\" name=\"shift_no\" value=\"0\" />");
    // print("</td></tr><tr><td>".$sdatelbl."</td>");
    print("<td><input type=\"text\" name=\"start_date\" value=". $start_date." />");
    print("</td></tr><tr><td>".$stimelbl."</td>");
    print("<td><input type=\"text\" name=\"start_time\" value=".$start_time." /></td>");
    if($_REQUEST[command]=="Start") {
        print("</tr><tr><td colspan=\"2\" align=center><input type=\"submit\"  value=\"Start Shift\" name=\"submit\" id=\"submit\" ></td></tr>");
    }else{
        print("</tr><tr><td colspan=\"2\" align=center><input type=\"submit\"  value=\"End Shift\" name=\"submit\" id=\"submit\" ></td></tr>");
    }
    //print("<tr><td colspan=\"2\">".$_REQUEST[command] .",".$shift."</td></tr>");
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
