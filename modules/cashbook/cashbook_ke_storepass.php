<?php
error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR );
require ('./roots.php');
require ($root_path . 'include/inc_environment_global.php');
define ( 'LANG_FILE', 'stdpass.php' );
define ( 'NO_2LEVEL_CHK', 1 );
require_once ($root_path . 'include/inc_front_chain_lang.php');

require_once ($root_path . 'global_conf/areas_allow.php');
$target = $_REQUEST ['target'];

if ($target == 'cashbook') {
	$allowedarea = &$allow_area ['cashbook'];
	$fileforward = "cashbook.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Cashpoints') {
	$allowedarea = &$allow_area ['Cashpoints'];
	$fileforward = "cashpoints.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'CashSale') {
	$allowedarea = &$allow_area ['CashSale'];
	$fileforward = "cash_sale.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Reports') {
	$allowedarea = &$allow_area ['Reports'];
	$fileforward = $root_path . "modules/cashbook/reports/Shift_report.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Payments') {
	$allowedarea = &$allow_area ['Payments'];
	$fileforward = "payments.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Reprint') {
	$allowedarea = &$allow_area ['Reprint'];
	$fileforward = "reprint.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Revenue') {
	$allowedarea = &$allow_area ['Revenue_Codes'];
	$fileforward = "revenuecodes.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'procedure') {
	$allowedarea = &$allow_area ['Procedure_Codes'];
	$fileforward = "procedure_codes.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'PharmCodes') {
	$allowedarea = &$allow_area ['Pharmacy_Codes'];
	$fileforward = "pharmcodes.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Receipts') {
	$allowedarea = &$allow_area ['Receipts'];
	$fileforward = "receipts.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Receipts_adj') {
	$allowedarea = &$allow_area ['Receipts_Adjustment'];
	$fileforward = "receipts_adj.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'gh_sales') {
	$allowedarea = &$allow_area ['GH_Sales'];
	$fileforward = "gh_sales.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'GH_sales_adj') {
	$allowedarea = &$allow_area ['GH_sales_adj'];
	$fileforward = "gh_sales_adj.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Payments_adj') {
	$allowedarea = &$allow_area ['Payments_Adjustment'];
	$fileforward = "payments_adj.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'Cash_Sale_adj') {
	$allowedarea = &$allow_area ['Cash_Sale_Adjustment'];
	$fileforward = "cash_sale_adj.php?sid=" . $sid . "&lang=" . $lang;
} else if ($target == 'payment_modes') {
	$allowedarea = &$allow_area ['payment_modes'];
	$fileforward = "payment_modes.php?sid=" . $sid . "&lang=" . $lang;
}else if ($target == 'iou') {
    $allowedarea = &$allow_area ['iou'];
    $fileforward = "iou.php?sid=" . $sid . "&lang=" . $lang;
}



$thisfile = "pharmacy_ke_store_pass.php";
$breakfile = "pharmacy_tz_pass.php?sid=" . $sid . "&lang=" . $lang;

$lognote = "$LDNursingManage ok";

$userck = "ck_pflege_user";

//reset cookie;
// reset all 2nd level lock cookies
setcookie ( $userck . $sid, '' );
require ($root_path . 'include/inc_2level_reset.php');
setcookie ( 'ck_2level_sid' . $sid, '', 0, '/' );

require ($root_path . 'include/inc_passcheck_internchk.php');
if ($pass == 'check')
	include ($root_path . 'include/inc_passcheck.php');

$errbuf = $LDNursingManage;

require ($root_path . 'include/inc_passcheck_head.php');
?>
<BODY onLoad="document.passwindow.userid.focus();"
	bgcolor=<?php
	echo $cfg ['body_bgcolor'];
	?>
	<?php
	if (! $cfg ['dhtml']) {
		echo ' link=' . $cfg ['idx_txtcolor'] . ' alink=' . $cfg ['body_alink'] . ' vlink=' . $cfg ['idx_txtcolor'];
	}
	?>>
<FONT SIZE=-1 FACE="Arial">

<P><img
	<?php
	echo createComIcon ( $root_path, 'wheelchair.gif', '0', 'top' )?>> <FONT
	COLOR=<?php
	echo $cfg [top_txtcolor]?> SIZE=6 FACE="verdana"> <b><?php
	echo $LDNursingManage?></b></font>

<table width=100% border=0 cellpadding="0" cellspacing="0">

<?php
require ($root_path . 'include/inc_passcheck_mask.php')?>

<p><!--
<img <?php
echo createComIcon ( $root_path, 'varrow.gif', '0' )?>> <a href="<?php
echo $root_path;
?>main/ucons.php<?php
echo URL_APPEND;
?>"><?php
echo "$LDIntro2 $LDNursingManage"?></a><br>
<img <?php
echo createComIcon ( $root_path, 'varrow.gif', '0' )?>> <a href="<?php
echo $root_path;
?>main/ucons.php<?php
echo URL_APPEND;
?>"><?php
echo "$LDWhat2Do $LDNursingManage"?></a><br>
 -->
<?php
require ($root_path . 'include/inc_load_copyrite.php');
?>
</FONT>

</BODY>
</HTML>
