<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
 global $db;
require("../../cashbook/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT  a.pid,a.name_first,a.name_2,a.name_last,b.encounter_nr FROM care_person a
INNER JOIN care_encounter b ON a.pid=b.pid","a.pid", "pid,name_first,name_2,name_last,encounter_nr");

?>
