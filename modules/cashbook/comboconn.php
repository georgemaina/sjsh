<?php
//require_once ("config.php");
//$res = mysql_connect ( $mysql_server, $mysql_user, $mysql_pass );
//mysql_select_db ( $mysql_db );

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require ("../../include/dhtmlxconnector_php/codebase/combo_connector.php");
$combo = new ComboConnector ( $db );
$combo->enable_log ( "temp.log",true);
$combo->render_sql( "select * from care_ke_cashpoints", "code_id", "pcode,prefix" );