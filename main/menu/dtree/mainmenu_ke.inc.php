<?php
/**
* Copyright Joachim Mollin <mollin@hccgmbh.com> & Healthcare Consulting GmbH
*
* This is the javascript drop down menu tree developed by J. Mollin.
* It uses the dtree.js script Copyright (c) 2002-2003 Geir Landr�
* This script was slightly modified by Elpidio Latorilla to accomodate user selectable menu tree style system
* This file will not run alone. This file is included by /main/gui_bridge/gui_indexframe.php.
*/
#
# Mod: 2004-07-29 EL
#
# Lese neue Menuestruktur.
# Read new menu structure

//	 $sql="SELECT a.name, a.LD_var AS \"LD_var\",a.url, b.s_main_nr, b.s_name, b.s_LD_var AS \"s_LD_var\", b.s_url, b.s_image,b.s_open_image, b.s_status, b.s_ebene, b.s_url_ext " .
//	 			"FROM care_menu_main as a " .
//				"LEFT  JOIN care_menu_sub as b on a.nr=b.s_main_nr ".
//				"INNER JOIN care_users_menu AS um ON um.m_nr = a.nr " .
//				"INNER JOIN care_users AS u ON u.login_id = um.u_login_id " .
//				"WHERE a.is_visible=1  OR LD_var='LDEDP' OR LD_var='LDLogin'  ORDER BY a.sort_nr, b.s_sort_nr";



	$sql="SELECT nr, a.name, a.LD_var AS \"LD_var\",a.url,s_image,s_open_image,s_url_ext FROM care_menu_main a
			WHERE a.is_visible=1 ORDER BY a.sort_nr";

$result1=$db->Execute($sql);

#
# If sql query ok, generate the javascript menu tree
#
?>
<script language="javascript">
<!--

function runModul (ziel) {
   //alert (ziel);
   //parent.frames[1].location.href=ziel;
	 window.parent.CONTENTS.location.href=ziel;
}
-->
</script>
<link rel="stylesheet" href="menu/dtree/menus.css" type="text/css" />
<?php
if($result1){


  	while($menu=$result1->FetchRow()){
    if (preg_match('LDLogin',$menu['LD_var'])){
			if ($_COOKIE['ck_login_logged'.$sid]=='true'){
				$menu['url']='main/logout_confirm.php';
				$menu['LD_var']='LDLogin';
			}
		}
  	if ($menu['s_ebene']!=0) {
		
		$my_menu_call=$menu['s_url'].URL_APPEND.$menu['s_url_ext'];
        $my_menu_LDvar=$menu['s_LD_var'];
        $my_menu_name=$menu['s_name'];
        $my_menu_img=$menu['s_image'];
        $my_menu_open_img=$menu['s_open_image'];
        //$i=$ip;
        //$i=$p_last;
        }
        else {
        $my_menu_call=$root_path.$menu['url'].URL_APPEND;
        $my_menu_LDvar=$menu['LD_var'];
        $my_menu_name=$menu['name'];
        $my_menu_img=$menu['s_image'];
        $my_menu_open_img=$menu['s_open_image'];
        //$ip=$j;
        //$i=0;
        }
		
		//$my_menu_call=$menu['url'].URL_APPEND.$menu['s_url_ext'];
		$v="blue_aqua";
		$menu_img;
		
		if($pageid=$menu['nr']){
			$menu_img=$menu['s_image'];
		}else{
			$menu_img=$menu['s_open_image'];
		}
		
		echo "<a href=javascript:runModul('$my_menu_call')><img src=".$root_path ."gui/img/control/".$v."/".$lang."/".$menu_img." 
		alt=".$menu['name']." border=1 class='button'></a>";
	}
//

}else{
		include('menu/default/mainmenu.inc.php');
	}
?>
