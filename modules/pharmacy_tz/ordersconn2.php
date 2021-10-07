<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];
$suppStore=$_REQUEST['supstore'];

$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(50);


    $sql = "select * from care_ke_internalreq where`status`='pending'";

       if(isset($suppStore))
          $sql = $sql ." and sup_storeid like '$suppStore%'"  ;
           $sql = $sql ." group by req_no";
//echo $sql;
           
    $gridConn->render_sql("$sql","id","req_date,req_time,req_no,store_loc,Store_desc,sup_storeId,sup_storeDesc,status,input_user");

?>
