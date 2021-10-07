<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");
$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT distinct p.pid,p.name_first,p.name_last,p.name_2,e.`encounter_nr`,e.`encounter_class_nr` FROM care_person p
            LEFT JOIN care_encounter e ON p.pid=e.pid
          WHERE e.encounter_date>date(now()) or e.encounter_class_nr=2
          and e.is_discharged=0 order by e.create_time desc","pid",
        "p.pid,p.name_first,p.name_2,p.name_last,e.encounter_nr,e.encounter_class_nr");

?>
