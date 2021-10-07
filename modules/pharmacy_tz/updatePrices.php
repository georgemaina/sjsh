<?php
//echo var_dump($_POST);
$debug=false;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;

require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
if($weberp_obj = new_weberp()) {

    $sql = "select partcode as stockID, item_description,unit_price as price from care_tz_drugsandservices";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $rows=$result->RecordCount();
//$row=$result->FetchRow();
    if($rows>0) {
        $stockID='AMB002';
        $items=$weberp_obj->get_stock_price_webERP($stockID);


        $c2x_items=$result->GetArray();

        for ($i=0; $i<sizeof($c2x_items); $i++) {
            $itemlist_c2x[$c2x_items[$i]['stockID']]=$c2x_items[$i]['stockID'];
        }

        for ($i=0; $i<sizeof($items); $i++) {
            $itemlist[$i]['stockID']=$itemlist_c2x[$items[$i]['stockID']];
            $itemlist[$i]['price']=$items[$i]['price'];
        }
        // return $itemlist;
         
        if(is_array($itemlist)) {
            while(list($x,$v)=each($itemlist)) {
                //echo $v['stockID'].' - ';
                //echo $v['price'].'<br>';
                $sql = "update care_tz_drugsandservices set unit_price='".$v['price']."' where partcode='".$v['stockID']."'";
                $db->Execute($sql);
            }
        }
        echo sizeof($items);
    }

}






?>



