<link rel="stylesheet" type="text/css" href="styles.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');

/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('departments.php');
define('LANG_FILE','ambulatory.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');

// reset all 2nd level lock cookies
require($root_path.'include/inc_2level_reset.php');

if(!$_SESSION['sess_path_referer']) $_SESSION['sess_path_referer'];
$breakfile=$root_path.'modules/news/start_page.php'.URL_APPEND;
$_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
$_SESSION['sess_user_origin']='amb';
$_SESSION['sess_parent_mod']='';
/* Create department object and load all medical depts */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$medical_depts=&$dept_obj->getAllMedical();
require_once('toolbar.php');


