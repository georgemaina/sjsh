<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');

require($root_path.'include/inc_environment_global.php');

if(!empty($encounter)) $_SESSION['sess_en']=$encounter;

/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
* elpidio@care2x.org, meggle@merotech.de
*
* See the file "copy_notice.txt" for the licence notice
*/

//define('NO_2LEVEL_CHK',1);
//require($root_path.'include/inc_front_chain_lang.php');

$lang_tables[]='diagnoses_ICD10.php';
require($root_path.'include/inc_front_chain_lang.php');

//Load the diagnstics-class:
require_once($root_path.'include/care_api_classes/class_tz_diagnostics.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require($root_path.'include/care_api_classes/class_tz_billing.php');

$diagnostic_obj = new Diagnostics;
$encObj=new Encounter();
$bill_obj = new Bill;

//$consultation=$bill_obj->checkConsultationPayment($_SESSION['sess_en']);
   // if($consultation==1){
        if($_GET['backpath_diag']) $_SESSION['backpath_diag'] = urldecode($_GET['backpath_diag']);
        if($_GET['ispopup']) $_SESSION['ispopup'] = $_GET['ispopup'];

        if ($mode=="search") {
          if (!empty($keyword)) {
            $diagnostic_obj->get_array_search_results($keyword);
            /*
            if ($number_of_search_results=count($rs=$diagnostic_obj->get_array_search_results($keyword))) {
              echo $diagnostic_obj->get_icd10_description_from_array($rs);
            }
            */
          }
        }

        $encounterClass=$encObj->EncounterClass($_SESSION['sess_en']);

        require ("gui/gui_icd10_quicklist.php");
//    }else{
//         header("location:../validateFile.php?target=Diagnosis&allow_update=1&pid=".$_SESSION['sess_pid']);
//	    exit;
//    }
?>