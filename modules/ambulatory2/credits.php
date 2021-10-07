<?php
    require_once 'roots.php';
//    require($root_path.'include/inc_environment_global.php');
    require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path.'include/inc_init_xmlrpc.php');
    require_once($root_path.'include/care_api_classes/class_tz_billing.php');
    require($root_path.'include/care_api_classes/accounting.php');
    require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
    $insurance_obj = new Insurance_tz;
    $bill_obj = new Bill;
    require_once('mylinks_1.php');
//    jsIncludes();


    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Credit</font></td></tr>
    <tr><td align=left valign=top>";
    require 'aclinks.php';
    echo '</td><td width=80%>';

            if(!isset($_POST[submit])){
                    displayForm();
            }else{

              //echo var_dump($_POST);

            $new_bill_number=$bill_obj->checkBillEncounter($_POST['en_nr']);

            $obj_acconts=new accounting();

            $DebitDetails[bill_number]=$new_bill_number;
            $DebitDetails[en_nr]=$_POST[en_nr];
            $DebitDetails[pid]=$_POST[pid];
            $DebitDetails[debitDate]=$_POST[debitDate];

           $added_rows=$_POST[gridbox_rowsadded];

            $arr_rows= explode(",", $added_rows);
            for($i=0;$i<count($arr_rows);$i++) {
                  $DebitDetails[revcode]=$_POST["gridbox_".$arr_rows[$i]."_0"];
                  $DebitDetails[item_type]=$_POST["gridbox_".$arr_rows[$i]."_1"];
                  $DebitDetails[Description]=$_POST["gridbox_".$arr_rows[$i]."_2"];
		          $DebitDetails[Amount] = -$_POST["gridbox_".$arr_rows[$i]."_3"];;
                  $DebitDetails[qty]=$_POST["gridbox_".$arr_rows[$i]."_4"];
                  $DebitDetails[total]=-$_POST["gridbox_".$arr_rows[$i]."_5"];

                  $obj_acconts->updateDebitOPD($DebitDetails);
                  updateDbtErp($db,$_POST['pid']);
            }



                displayForm();

            }
        echo "</td></tr></table>";

     function updateDbtErp($db,$pn) {
      //global $db, $root_path;
      $debug=false;;
        if ($debug) echo "<b>class_tz_billing::updateDbtErp()</b><br>";
        if ($debug) echo "encounter no: $pn <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
        $sql='SELECT b.pid, c.unit_price AS price,c.partcode,c.item_Description AS article,a.prescribe_date,a.qty AS amount,a.bill_number
    FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c
    ON a.item_number=c.partcode
    INNER JOIN care_encounter b
    ON a.pid=b.pid and b.pid="'.$pn.'" and weberpSync=0';
        if($debug) echo $sql;
        $result=$db->Execute($sql);
        if($weberp_obj = new_weberp()) {
        //$arr=Array();
            while($row=$result->FetchRow()) {
            //$weberp_obj = new_weberp();
                if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                    $sql='update care_ke_billing set weberpSync=1';
                    $db->Execute($sql);
                }
                else {
                    echo 'failed';
                }
                destroy_weberp($weberp_obj);
            }
        }else {
            echo 'could not create object: debug level ';
        }
    }

 //If patient is insured transmit to weberp
 function updateinsuredDbt($db,$pn){
      global $root_path;

  $sql='SELECT a.insurance_id as pid, c.unit_price AS price,a.partcode,c.item_Description AS article,a.prescribe_date,a.qty AS amount,a.bill_number
    FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c
    ON a.item_number=c.partcode
    INNER JOIN care2x.care_encounter b
    ON a.pid=b.pid AND b.pid="'.$pn.'" AND a.partcode="INSSERV"';
$result=$db->Execute($sql);
$rows=$result->FetchRow();

$result=$db->Execute($sql);

//$arr=Array();
 if($weberp_obj = new_weberp()) {
while($row=$result->FetchRow()){
 echo $row[1];
        if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row))
        {
                echo 'success member transmission<br>';
                echo date($weberp_obj->defaultDateFormat);
        }
        else
        {
                echo 'failed member transmission';
        }
        destroy_weberp($weberp_obj);
}
   }else{
        echo 'could not create object: debug level ';
   }
   }



    ?>
