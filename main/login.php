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
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');
// reset all 2nd level lock cookies
require($root_path.'include/inc_2level_reset.php');

$fileforward=$root_path.'portal-master/index.html'.URL_REDIRECT_APPEND;
$thisfile='login.php';
$breakfile='startframe.php'.URL_APPEND;

if(!isset($pass)) $pass='';
if(!isset($keyword)) $keyword='';
if(!isset($userid)) $userid='';

if(!$_SESSION['sess_login_userid']) $_SESSION['sess_login_userid'];
if(!$_SESSION['sess_login_username']) $_SESSION['sess_login_username'];
if(!$_SESSION['sess_login_pw']) $_SESSION['sess_login_pw'];

function logentry($userid,$key,$report){
	$logpath='logs/access/'.date('Y').'/';
        //echo "<br> Log path=".$logpath;
	if (file_exists($logpath)){
		$logpath=$logpath.date('Y_m_d').'.log';
		$file=fopen($logpath,'a');
		if ($file){	
			if ($userid=='') $userid='blank';
			$line=date('Y-m-d H:i:s').' '.'Main Login: '.$report.'  Username='.$userid.'  UserID='.$key;
			fputs($file,$line);fputs($file,"\r\n");
			fclose($file);
		}
	}
}

function logAccess($userid,$username,$password,$fileforward,$REMOTE_ADDR){
    global $db;
    $debug=false;
    
    $logTime=date('Y-m-d h:i:s');
    $lognote="";
    $_SESSION['sess_login']=md5(date('Y-m-d h:i:s'));
    $loginSess=$_SESSION['sess_login'];
    //$ip= gethostbyname(gethostname());
    
    $sql="INSERT INTO `care_accesslog` (
          `loginTime`,`ip`,`lognote`,`userid`,`username`,`password`,`thisfile`,`fileforward`,`login_success`,`status`,login_session) 
         VALUES ('$logTime','$REMOTE_ADDR','$lognote','$userid','$username','$password','login.php','$fileforward','1','active','$loginSess')";
    if($debug) echo $sql;
    
    $db->Execute($sql);
}

if ((($pass=='check')&&($keyword!=''))&&($userid!=''))
{
	include_once($root_path.'include/care_api_classes/class_access.php');
	$user = new Access($userid,$keyword);

	if($user->isKnown() && $user->hasValidPassword())
	{
		if($user->isNotLocked())
		{
			$_SESSION['sess_login_userid']=$user->LoginName();
			$_SESSION['sess_login_username']=$user->Name();
			# Init the crypt object, encrypt the password, and store in cookie
    			$enc_login = new Crypt_HCEMD5($key_login,makeRand());

			$cipherpw=$enc_login->encodeMimeSelfRand($keyword);

			$_SESSION['sess_login_pw']=$cipherpw;

			# Set the login flag
			setcookie('ck_login_logged'.$sid,'true',0,'/');
                        
                        $myIp = getHostByName(getHostName());
                       // echo "Remote Address ".$REMOTE_ADDR." LoginName=".$user->LoginName." Name=".$user->Name()." UserID=".$userid." name=".$keyword.' Host is'.$myIp .' Password is '.md5($keyword).' ';

                        logAccess($user->Name(),$userid,md5($keyword),$fileforward,$REMOTE_ADDR);
			logentry($user->Name(),$user->LoginName(),$REMOTE_ADDR." OK'd","","");
                        
			header("location: $fileforward");
            exit;

		}else { $passtag=3;}
	}else {$passtag=1;}
}

$errbuf='Log in';
$minimal=1;
require($root_path.'include/inc_passcheck_head.php');
?>

<?php echo setCharSet(); ?>

<BODY onLoad="<?php if(isset($is_logged_out) && $is_logged_out) echo "window.parent.STARTPAGE.location.href='indexframe.php?sid=$sid&lang=$lang';"; ?>document.passwindow.userid.focus();" bgcolor=<?php echo $cfg['body_bgcolor']; ?>
<?php if (!$cfg['dhtml']){ echo ' link='.$cfg['idx_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['idx_txtcolor']; } ?>>
&nbsp;
<p>
<?php
if(isset($is_logged_out) && $is_logged_out) {
	echo '<div align="center"><FONT  FACE="Arial" SIZE=+4 ><b>'.$LDLoggedOut.'</b></FONT><p><font size=4>'.$LDNewLogin.':</font></p></div>';
}
?>
<p>
<table width=100% border=0 cellpadding="0" cellspacing="0">
<tr>
<td colspan=3><img <?php echo createLDImgSrc($root_path,'login-b.gif') ?>></td>
</tr>

<?php require($root_path.'include/inc_passcheck_mask.php') ?>

<!--<img src="../img/small_help.gif" >dfdf <a href="--><?php //echo $root_path; ?><!--main/ucons.php--><?php //echo URL_APPEND; ?><!--">Was ist login?</a><br>-->
<!--<img src="../img/small_help.gif" > <a href="--><?php //echo $root_path; ?><!--main/ucons.php--><?php //echo URL_APPEND; ?><!--">Wieso soll ich mich einloggen?</a><br>-->
<!--<img src="../img/small_help.gif" > <a href="--><?php //echo $root_path; ?><!--main/ucons.php--><?php //echo URL_APPEND; ?><!--">Was bewirkt das einloggen?</a><br>-->


<?php
require($root_path.'include/inc_load_copyrite.php');
?>
	</table>
</FONT>
</BODY>
</HTML>
