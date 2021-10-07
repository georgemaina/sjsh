<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("select b.pid,a.name_first,a.name_2,a.name_last from care_person a inner join
    care_personell b on a.pid=b.pid","pid", "pid,name_first,name_2,name_last");

?>

