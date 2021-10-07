<link rel="stylesheet" type="text/css" href="button.css">
 <?php
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/accounting.php');
require_once($root_path.'modules/accounting/billing_ke_pharm_quote.php');


 require_once 'myLinks.php';
 jsIncludes();
?>
<script language="JavaScript">
    function IsNumeric(strString) //  check for valid numeric strings
    {
        if(!/\D/.test(strString)) return true;//IF NUMBER
        else if(/^\d+\.\d+$/.test(strString)) return true;//IF A DECIMAL NUMBER HAVING AN INTEGER ON EITHER SIDE OF THE DOT(.)
        else return false;
    }

    function chkform(d) {
        if(d.issDate.value==""){
//            alert("Please enter the Date");
            document.getElementById('message').innerHTML="Please enter the Date";
            d.issDate.focus();
            return false;
        }else if(d.pid.value==""){
//            alert("Please enter the PID");
            document.getElementById('message').innerHTML="Please enter the PID";
            d.pid.focus();
            return false;
        }else if(d.pname.value==""){
//            alert("Please enter the Payment Patient name");
            document.getElementById('message').innerHTML= "Please enter the Payment Patient name";
            d.pname.focus();
            return false;
        }else if(d.storeID.value==""){
//            alert("Please Select the Store");
            document.getElementById('message').innerHTML="Please Select the Store";
            d.storeID.focus();
            return false;
        }else if(d.supStoredesc.value==""){
//            alert("Please enter the Store description");
            document.getElementById('message').innerHTML="Please enter the Store description";
            d.supStoredesc.focus();
            return false;
        }
        // else if(d.issType.value==2 && d.pmode.value==1){
        //     if(d.receiptAmount.value=="" &&  document.getElementById('submit').value=='Send'){
        //         var pmode= document.getElementById('paymode').innerHTML
        //         document.getElementById('message').innerHTML="The Prescription has not been paid for," +
        //             "<br>Please send the patient to the Cashier.";//+ document.getElementById('submit').value;
        //         d.pname.focus();
        //         return false;
        //     }
        // }

    }

    function printPrescription(){
        alert("Hallo");
    }
</script>
<?php
  if(isset($_POST["submit"])){
   $req_no=$_POST[ordIrnNo];
    $status='pending';
    $req_date=date("Y-m-d");
    $req_time=date("H:i:s");
    $store_loc=$_POST[storeID];
    $store_desc=$_POST[storeDesc];
    $sup_storeId=$_POST[supStoreid];
    $supStoreDesc=$_POST[supStoredesc];
    $period='2009';
    $input_user = $_SESSION['sess_login_username'];

    require('issuedrugs.php');

    displayIssueDrugsForm($db);

}else{
    displayIssueDrugsForm($db);
}
echo '</td></tr></table>';

