<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT p.pid,p.name_first,p.name_last,p.name_2 FROM care_person p
    LEFT JOIN care_encounter e ON p.pid=e.pid
WHERE e.encounter_date>DATE_SUB(NOW(),INTERVAL 2 DAY) or e.encounter_class_nr=1 and e.is_discharged=0","pid", "pid,name_first,name_2,name_last");

?>
