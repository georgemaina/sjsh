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
        $sRow=checkShift($db,$cashier);
        
        if($sRow==0){
            $cActive=checkCashPoint($db,$_POST[cash_point]);
            if($cActive<>0 && $cActive[active]==1){
                $strmsg= "<br><b><font color=\"red\">User $cActive[cashier]  is already using 
                Cashpoint $cActive[pcode] <br>
                Current Shift No $cActive[current_shift]</font></b><br><br>";
                $strcmd='Start Shift';

                Display_Form($db,$strmsg,$cashier,$cActive[current_shift],$start_date,$start_time,$strcmd);
                exit();
            }else if($cActive<>0 && $cActive[active]==0){
                        $curr_shiftNo=getShiftNo($db,$_POST[cash_point])+1;
                        $next_shiftNo=intval($curr_shiftNo)+1;

                        $sql="update care_ke_cashpoints set active=1,next_shift_no='".$next_shiftNo."', current_shift='";
                        $sql.=$curr_shiftNo."',start_date='".$start_date."', start_time='".$start_time.
                            "', cashier='".$cashier."' where pcode='".$_POST[cash_point]."'";
                        $result=$db->Execute($sql);
                        
                        if($debug) echo $sql;
                        
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
                    ?>
                        <script type="text/javascript">
                                window.location = "Cash_Sale.php?cashpoint=<?php echo $_POST[cash_point] ?>&shiftNo=<?php echo $curr_shiftNo ?>";
                        </script>
                    <?php

                    }
            }
        }else{
            ?>
            <script type="text/javascript">
                    window.location = "welcome.php?cashpoint=<?php echo $sRow[pcode] ?>&shiftNo=<?php echo $sRow[current_shift] ?>";
            </script>
          <?php
        }
    }else if($_REQUEST[command]=="End") {
        
        $sqlE="Select cashier from care_ke_cashpoints where pcode='$_POST[cash_point]' and active=1";
        $result=$db->Execute($sqlE);
        if($sqlE) echo $sqlE;
        
        if($row=$result->FetchRow()){
            if($cashier<>$row[0] && $cashier<>'Admin'){
                   $strmsg= "<br><b><font color=\"red\">$cashier Sorry you are not allowed to Close this CashPoint<br>
                       Only $row[0] or Admin can Close this CashPoint. 
                        <br>Please Contact The System Administrator for more Assistance</font></b><br>";
                    Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
            }else if(($cashier==$row[0] || $cashier=='Admin')){
                 $strcmd='End Shift';
                    $sql="update care_ke_cashpoints set active=0";
                    $sql.=",end_date='".$end_date."', end_time='".$end_time.
                        "', cashier='' where pcode='".$_POST[cash_point]."'";

                    $result=$db->Execute($sql);
                 if($debug) echo $sql;
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
                        $strmsg= "<br><b><font color=\"blue\"> The CashPoint was Closed successfully</font></b><br>";
                        Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
            //            session_unregister("shift");
                    }
            }
        }else{
                $strmsg= "<br><b><font color=\"blue\"> The CashPoint you are trying to end is already Closed</font></b><br>";
                Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
        }
       
    }
}else {
    $strcmd='Start Shift';
    $strmsg='';
    Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd);
}

function Display_Form($db,$strmsg,$cashier,$shift_no,$start_date,$start_time,$strcmd) {
    $dtStat=$_REQUEST[command];
    print("<form method=\"POST\" action=\"\" name=\"points\">");
    print("<table>");
    print('<tr><td>'.doLinks().'</td>');
    print("<td><table class='style1'>");
   
    if($dtStat=='End') {
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
    if($debug) echo $r_sql;
    
    print("<td><select id=\"cash_point\" name=\"cash_point\" onchange=\"showDesc(this.value)\">");
    while($row=$result->FetchRow()) {
        print("<option id=". $row['pcode']." value=".$row['pcode'].">".$row['pcode']."</option>");

    }
    print("</select></td></tr>");
    print("<tr><td>Description</td><td>");

  
    print("<input type=\"text\" id=\"cpDesc\" name=\"cpDesc\" size=\"30\" /></td></tr>");
    print("<tr><td>Cashier</td><td>");
    print("<input type=\"text\" name=\"cashier\" value=".$cashier." /></td></tr>");
   

    if($dtStat=="Start") {
        $stimelbl="Start time";
        $sdatelbl="Start Date";
         print("<tr><td>Start Date</td>");
        print("<td><input type=\"text\" name=\"start_date\" value=". $start_date." />");
        print("</td></tr><tr><td>Start Time</td>");
        print("<td><input type=\"text\" name=\"start_time\" value=".$start_time." /></td>");
    }else {
        $sdatelbl="End Date";
        $stimelbl="End TimeS";
         print("<tr><td>End Date</td>");
        print("<td><input type=\"text\" name=\"start_date\" value=". $start_date." />");
        print("</td></tr><tr><td>End Time</td>");
        print("<td><input type=\"text\" name=\"start_time\" value=".$start_time." /></td>");
    }
    if($dtStat=="Start") {
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
    $debug=false;
    $sql="Select pcode,current_shift from care_ke_cashpoints where pcode = '$cashpoint'";
    if($debug) echo $sql;
    
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

//check for active cashpoint of the logged in user
function checkShift($db,$cashier) {
   $debug=false;
    $s_sql = "SELECT c.pcode,c.current_shift,c.cashier,c.active FROM care_ke_cashpoints c 
left join care_ke_shifts s on c.current_shift=s.shift_no and c.pcode=s.cash_point
where c.cashier='$cashier' and c.active=1 order by id asc";
 if($debug) echo $s_sql;
 
    $result=$db->Execute($s_sql);
    if (!($row=$result->FetchRow())) {
//        echo 'You dont have Open cashpoints';
         return 0;
    }else{
//         echo "You have the following open Cashpoints<br>$row[pcode] and shift no $row[current_shift]";
         return $row;
    }
}
   
function checkCashPoint($db,$cashpoint){
    $debug=false;
    $s_sql = "SELECT c.pcode,c.current_shift,c.cashier,c.active FROM care_ke_cashpoints c 
                left join care_ke_shifts s on c.current_shift=s.shift_no and c.pcode=s.cash_point
                where c.pcode='$cashpoint' order by id asc;";
     if($debug) echo $s_sql;
     
    $result=$db->Execute($s_sql);
    if (!($row=$result->FetchRow())) {
//        echo 'You dont have Open cashpoints';
         return 0;
    }else{
//         echo "You have the following open Cashpoints<br>$row[pcode] and shift no $row[current_shift]";
         return $row;
    }
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
