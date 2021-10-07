<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $storeID=$_GET[storeID];
    $countDate=$_GET[strDate];
    
    
    $cDate=new DateTime($_GET[strDate]);
    $cDate1=$cDate->format('Y/m/d');
    $countDate= $cDate1;

    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "SELECT c.id,c.`partcode`,d.`item_description`,c.`CurrQty`,c.`currCount` 
        FROM care_ke_stockcount c LEFT JOIN care_tz_drugsandservices d ON c.partcode=d.`partcode`";

      if(isset($storeID) && isset($countDate))
          $sql.=" Where c.loccode ='$storeID' and c.`stockCountDate`='$countDate'";
    
    
    $gridConn->render_sql("$sql","id", "partcode,item_description,CurrQty,currCount");

?>
