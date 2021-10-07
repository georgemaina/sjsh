<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT p.pid,p.name_first,p.name_2,p.name_last FROM care_person p LEFT JOIN care_encounter e ON p.pid=e.pid
WHERE e.encounter_class_nr=1","pid", "pid,name_first,name_2,name_last");

?>
