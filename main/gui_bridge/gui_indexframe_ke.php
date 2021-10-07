<?php
/**
* Change log: 2004-07-29
* Alternate menu tree selection system was integrated.
*
* Set the default menu tree directory name here.
* Version >= 2.0.2 only "default" or "dtree". If empty or non-existing path, defaults to "default".
*/
$DefaultMenuTreeDir = 'default';

//$cfg['mainmenu_tree'] = 'dtree';

#
# Load the menu tree. Make intelligent checks. Defaults to "default" directory if nothing works.
#
//if(isset($cfg['mainmenu_tree']) && !empty($cfg['mainmenu_tree']) && file_exists('menu/'.$cfg['mainmenu_tree'] .'/mainmenu.inc.php')){
//	$LocMenuTreeDir = $cfg['mainmenu_tree'];
//}else{
//	$GlobMenuTreeDir = $gc->getConfig('theme_mainmenu_tree');
//	if(!empty($GlobMenuTreeDir) && file_exists('menu/'.$GlobMenuTreeDir .'/mainmenu.inc.php')){
//		$LocMenuTreeDir = $GlobMenuTreeDir;
//	}elseif(!empty($DefaultMenuTreeDir) && file_exists('menu/'.$DefaultMenuTreeDir .'/mainmenu.inc.php')){
//		$LocMenuTreeDir = $DefaultMenuTreeDir;
//	}else{
//		$LocMenuTreeDir = 'default';
//	}
//}
?>

<?php html_rtl($lang);  ?>
<HEAD>
<?php echo $charset; ?>
<TITLE><?php echo $wintitle; ?></TITLE>
<?php
//set the css style for a links
//require($root_path.'include/inc_css_a_sublinker_d.php');
echo '<link href="'.$root_path.'/css/indexframe.css" rel="stylesheet" type="text/css">';


?>

<script language="javascript">
function changeLanguage(lang)
{
    <?php if(($cfg['mask']==1)||($cfg['mask']=="")||$mask==1||$mask=='')  echo "window.top.location.replace(\"../index.php?lang=\"+lang+\"&mask=$cfg[mask]&sid=$sid&egal=1&_chg_lang_=1\");";
	 else echo "window.opener.top.location.replace(\"../index.php?lang=\"+lang+\"&mask=$cfg[mask]&sid=$sid&egal=1\");";
	 ?>

	return false;
}
function checkIfChanged(lang)
{
	if(lang=="<?php echo $lang; ?>") return false;
		else changeLanguage(lang);
}
</script>
</HEAD>

<?php
 # Prepare values for body template
if($boot || $_chg_lang_){
	 $TP_js_onload= 'onLoad="if (window.focus) window.focus();window.parent.CONTENTS.location.replace(\'startframe.php?sid='.$sid.'&lang='.$lang.'&egal='.$egal.'&cookie='.$cookie.'\');"';
}else{
	$TP_js_onload='onLoad="if (window.focus) window.focus();"';
}

$TP_bgcolor='bgcolor="'.$cfg['idx_bgcolor'].'"';

if(!$cfg['dhtml']){
	 $TP_link='link="'.$cfg['idx_txtcolor'].'"';
	 $TP_vlink='vlink="'.$cfg['idx_txtcolor'].'"';
	 $TP_alink='alink="'.$cfg['idx_alink'].'"';
}else{
	 $TP_link='';
	 $TP_vlink='';
	 $TP_alink='';
}
?>
<TABLE CELLPADDING=0 CELLSPACING=0 border=0 width=100%>

	<tr>
		<td></td>
		<td>


<?php
$TP_logo=createLogo($root_path,'care_logo_'.$dbtype.'.gif','0');

$tp_body=$TP_obj->load('tp_main_index_menu_body.htm');
eval("echo $tp_body;");

?>


</td>
<!--        --><?php //if($_SESSION['sess_login_username']){
//            ?>
                <td></td>
                <td align="right"><nobr><b><?php echo $LDUser .":</b>".$_SESSION['sess_login_username'] ."&nbsp|&nbsp"?></nobr>
                    <?php

                    $name = $_SESSION['sess_login_username'];

//                    echo $name."&nbsp|&nbsp";

                    $sql="SELECT name,url,name,s_url_ext FROM care_menu_main WHERE type=1";

                    $result1=$db->Execute($sql);


                    while($menu=$result1->FetchRow()){
                        if (preg_match('/LDLogin/',$menu['LD_var'])){
                            if ($_COOKIE['ck_login_logged'.$sid]=='true'){
                                $menu['url']='main/logout_confirm.php';
                                $menu['LD_var']='LDLogin';
                            }
                        }
                        $my_menu_call=$root_path.$menu['url'].URL_APPEND;
                        echo "<b><u><a href=javascript:runModul('$my_menu_call')>".$menu[name]."</a></u></b>&nbsp|&nbsp";
                    }
                    ?>

                            <tr>
                                <td colspan=4><br>
                                    <?php
                                    require("./menu/dtree/mainmenu_ke.inc.php");

                                    ?>
                                </td>
                            </tr>
            <?php
//        }else{
//            echo "";
//            }
        ?>

</TABLE>
<center><!--<a href="http://www.opensource.org/" target="_blank"><img src="<?php echo $root_path ?>gui/img/common/default/osilogo.gif" border=0></a>-->
</center>
</BODY>
</HTML>
