<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (preg_match('save_admission_data.inc.php',$_SERVER['PHP_SELF']))
	die('<meta http-equiv="refresh" content="0; url=../">');	
	
   unset($_POST[bp1]);
   unset($_POST[bp2]);
   unset($_POST[height]);
   unset($_POST[weight]);
   unset($_POST[BMI]);
   unset($_POST[innitialMode]);
   unset($_POST[innitialMode]);
   unset($_POST[htnKnown]);
$obj->setDataArray($_POST);
	
switch($mode){	
		case 'create': 
                            if($obj->insertDataFromInternalArray()) {
                                    if(!$no_redirect){
                                           header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&pid=".$_SESSION['sess_pid']);
                                            //echo "$obj->getLastQuery<br>$LDDbNoSave";
                                            exit;
                                    }
                            } else{
                                   echo "$obj->getLastQuery<br>$LDDbNoSave";
                                   $error=TRUE;
                                }
                break;
                case 'update':
                                    $obj->where=' nr='.$nr;
                                    if($obj->updateDataFromInternalArray($nr)) {
                                            if(!$no_redirect){
                                                    header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&pid=".$_SESSION['sess_pid']);
                                                    //echo "$obj->sql<br>$LDDbNoUpdate";
                                                    exit;
                                            }
                                    } else{
                                      echo "$obj->getLastQuery<br>$LDDbNoUpdate";
                                      $error=TRUE;
                                    }
                break;
}// end of switch

?>