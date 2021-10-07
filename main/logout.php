<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');
if (!isset($logout)||!$logout) {header('Location:'.$root_path.'/language/'.$lang.'/lang_'.$lang.'_invalid-access-warning.php'); exit;}; 

# Reset all login cookies 
$debug=false;
        
//setcookie('ck_login_pw'.$sid,'',0,'/');
//setcookie('ck_login_userid'.$sid,'',0,'/');
//setcookie('ck_login_username'.$sid,'',0,'/');
setcookie('ck_login_logged'.$sid,'',0,'/');
setcookie('ck_login_reset'.$sid,FALSE,0,'/');

$logoutTime=date('Y-m-d H:i:s');

$sql="Select * from care_accesslog where login_session='".$_SESSION['sess_login']."'";
if($debug) echo $sql;

if($db->Execute($sql)){
    $sql2="update care_accesslog set LogOutTime='$logoutTime',status='Closed' where login_session='".$_SESSION['sess_login']."'";
    if($debug) echo $sql2;
    if($db->Execute($sql2)){
        $_SESSION['sess_login']='';
    }
}

# Empty session login values
$_SESSION['sess_login_userid']='';		
$_SESSION['sess_login_username']='';
$_SESSION['sess_login_pw']='';

#
# Redirect to login page for eventual new login
#
header("location: login.php".URL_REDIRECT_APPEND."&is_logged_out=1");
exit;
?>