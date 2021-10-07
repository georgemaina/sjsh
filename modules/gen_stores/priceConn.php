<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    //$req_no=$_GET[reqno];
     require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "select partcode, item_description,unit_price, purchasing_class from care_tz_drugsandservices
        where purchasing_class IN ('Antibiotics','Anticonvarsants','Nutriational Supplements',
        'Antimalaria-Antifungal','Cardiovascular-Antiasmatics','stationery')  ";

//      if(isset($req_no))
//          $sql.=" and req_no='$req_no'";
          
    $gridConn->render_sql("$sql","item_id","partcode,item_description,unit_price");

?>
