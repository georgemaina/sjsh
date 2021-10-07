<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
$obj=new Measurement;

if (preg_match('save_admission_data.inc.php',$_SERVER['PHP_SELF']))
	die('<meta http-equiv="refresh" content="0; url=../">');	
	
/*    unset($_POST['bp1']);
   unset($_POST['bp2']);
   unset($_POST['height']);
   unset($_POST['weight']);
   unset($_POST['BMI']); */
$obj->setDataArray($_POST);
$mode='create';
	
switch($mode){
    case 'create':
        if($obj->insertDataFromInternalArray()) {
            $error=false;
        } else{
            $error=TRUE;
        }
        break;
    case 'update':
        $obj->where=' nr='.$nr;
        if($obj->updateDataFromInternalArray($nr)) {
            $error=false;
        } else{
            $error=TRUE;
        }
        break;
}// end of switch



?>