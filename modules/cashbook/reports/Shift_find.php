<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

$cashPoint=$_REQUEST[cashpoint];
require("../../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");
//$gridConn = new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);

$gridConn->render_sql("select * from care_ke_shifts WHERE cash_point='$cashPoint' 
    order by shift_no desc","ID", "cash_point,shift_no,cashier,start_date,start_time,end_date,end_time");

?>
