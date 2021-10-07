<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT pcode,name,prefix,next_receipt_no,next_voucher_no,next_shift_no 
    FROM care_ke_cashpoints","code_id", "pcode,name,prefix,next_receipt_no,next_voucher_no,next_shift_no ");

?>
