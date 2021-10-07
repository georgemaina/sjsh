<link rel="stylesheet" href="laboratory.css" type="text/css">
<link rel="stylesheet" type="text/css" href="../../../ext-4/resources/css/ext-all-neptune-debug.css"/>
<script src="../../../ext-4/ext-all.js"></script>

<script type="text/javascript" src="laboratoryJS.js"></script>
<?php

define('ROW_MAX',15); # define here the maximum number of rows for displaying the groups

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('chemlab_groups.php','chemlab_params.php');
define('LANG_FILE','lab.php');
$local_user='ck_lab_user';
require_once($root_path.'include/inc_front_chain_lang.php');

$thisfile=basename($_SERVER['PHP_SELF']);

///$db->debug=true;

# Create lab object
require_once($root_path.'include/care_api_classes/class_lab.php');
$lab_obj=new Lab();

# Load the date formatter */
include_once($root_path.'include/inc_date_format_functions.php');
if(isset($mode) && !empty($mode)) {
	$lab_obj->moveUp($_GET['nrFirst'],$_GET['sortnrFirst']);
	$lab_obj->moveDown($_GET['nrSecond'],$_GET['sortnrSecond']);
}

# Get the test test groups
$tResultParams=$lab_obj->GetResultParams();

$breakfile="labor.php".URL_APPEND;

// echo "from table ".$linecount;
# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme


?>

<script language="javascript" name="j1">
<!--

function editGroup(nr){
	urlholder="<?php echo $root_path ?>modules/laboratory/labor_test_group_edit.php?sid=<?php echo "$sid&lang=$lang" ?>&nr="+nr;
	editgroup_<?php echo $sid ?>=window.open(urlholder,"editgroup_<?php echo $sid ?>","width=510,height=390,menubar=no,resizable=yes,scrollbars=yes");
}

function newGroup() {
	urlholder="<?php echo $root_path ?>modules/laboratory/labor_test_group_edit.php?sid=<?php echo "$sid&lang=$lang" ?>&mode=new";
	editgroup_<?php echo $sid ?>=window.open(urlholder,"editgroup_<?php echo $sid ?>","width=510,height=390,menubar=no,resizable=yes,scrollbars=yes");
}

function moveUp(nrFirst ,  sortnrFirst , nrSecond , sortnrSecond) {
	urlholder="<?php echo $root_path ?>modules/laboratory/labor_test_group_admin.php?sid=<?php echo "$sid&lang=$lang" ?>&mode=sort&nrFirst="+nrFirst+"&nrSecond="+nrSecond+"&sortnrFirst="+sortnrFirst+"&sortnrSecond="+sortnrSecond;
	document.location = urlholder;
}

// -->
</script>

<?php
echo "<table id='labTitles'><tr><th class=heading>$LDTestResults</th><th><script>gethelp('lab_group_config.php')</script></th></tr></table>";
echo "<button id='lab-btn'>New Result Parameter</button>";
echo '<form action="'. $thisfile .'" method="post" name="group_admin">';
$toggle=0;
if(isset($tResultParams) && !empty($tResultParams)) {
	$max_rows = $tResultParams->NumRows();
	$array_groups = $tResultParams->GetArray();
}
$i = 0;
if(is_object($tResultParams)){

    echo "<table id='laboratory'>";
    echo " <th align='center' class=titles>item_number</th>
            <th align='center' class=titles>Description</th>
            <th align='center' class=titles>Input Type</th>
            <th align='center' class=titles>Unit of Measure</th>
            <th align='center' class=titles>Normal</th>
            <th align='center' class=titles>Ranges</th>
            <th align='center' class=titles>Result Values</th>
            <th align='center' class=titles>Edit Parameter</th>
            <th align='center' class=titles>Delete Parameter</th></tr>";

	for($i = 0; $i < $max_rows; $i++) {

        if($array_groups[$i]['input_type']=='TITLE'){
            echo "<tr><td colspan=9 id=subTitles>".$array_groups[$i]['name']." - ".$array_groups[$i]['results']."</td></tr>";
        }else{
            echo "<tr><td>".$array_groups[$i]['item_id']."</td><td>".$array_groups[$i]['results']."</td><td>".$array_groups[$i]['input_type']."</td>
		        <td>".$array_groups[$i]['unit_msr']."</td><td>".$array_groups[$i]['normal']."</td>
		        <td>".$array_groups[$i]['ranges']."</td><td>".$array_groups[$i]['result_values']."<div id='lab-win'></div></td>";

//            echo "<td><a href='javascript:editGroup(".$array_groups[$i]['nr'].")'><img ".createLDImgSrc($root_path,'edit_sm.gif','0')."></a></td>
//                <td><button id='-btn'>Delete</button></td>";
                  echo "<td><a href='javascript:checkButton(".$array_groups[$i]['resultID'].")'>
                          <img ".createLDImgSrc($root_path,'edit_sm.gif','0')."></a></td>
                        <td><a href='javascript:checkButton(".$array_groups[$i]['nr'].")'><img ".createLDImgSrc($root_path,'delete.gif','0')."></a></td>";
            echo '<td>';
            if(isset($array_groups[$i-1]['nr']))
                echo '<a href="javascript:moveUp('.$array_groups[$i]['nr'].",".$array_groups[$i-1]['sort_nr'].','.$array_groups[$i-1]['nr'].','.$array_groups[$i]['sort_nr'].')"><img '.createLDImgSrc($root_path,'uparrow.png','0').'></a></td>';
            else echo '&nbsp;</td>';
            echo '<td>';
            if(isset($array_groups[$i+1]['nr']))
                echo '<a href="javascript:moveUp('.$array_groups[$i]['nr'].",".$array_groups[$i+1]['sort_nr'].','.$array_groups[$i+1]['nr'].','.$array_groups[$i]['sort_nr'].')"><img '.createLDImgSrc($root_path,'downarrow.png','0').'></a></td>';
            else echo "&nbsp;</td>" ;
            echo '
			</tr>';
        }

 }
    echo "</table>";
}
echo '</form>';

?>
