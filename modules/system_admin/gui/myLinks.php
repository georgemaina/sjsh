<?php

function jsIncludes() {
    ?>
<!-- dhtmlxWindows -->

<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxcommon_debug.js'></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- Display menus-->
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">
<script  src="../../../include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
<script  src="../../../include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgrid_form.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
<script  src="../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>

<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor.js'></script>
<script src='../../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>

<!-- dhtmlxCombo -->
<link rel="STYLESHEET" type="text/css" href="../../../include/dhtmlxCombo/codebase/dhtmlxcombo.css">
<script>window.dhx_globalImgPath="../../../include/dhtmlxCombo/codebase/imgs/";</script>
<script src="../../../include/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../../../include/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>


<script src='../../../include/dhtmlxConnector/codebase/connector.js'></script>
                               <!-- New Grid Editor-->
<link rel="STYLESHEET" type="text/css" href="SystemAdmin.css">

<?php
}

function displayStoresForm($db) {

   echo "";

   echo '<table border="0" width="70%">
                <tbody>
                <form name="order" method="POST" action="'. $_SERVER['PHP_SELF'] . '">
                    <tr>
                        <td class="pgtitle" colspan=2>Stores</td>
                   
                    </tr>
                    <tr>
                        <td>Store ID</td>
                        <td><input type="text" id="storeID" name="storeID" value="" /></td>
                    </tr>
                    <tr>
                        <td>Store Name</td>
                        <td><input type="text" name="storename" id="storename" value="" /></td>
                    </tr>
                    <tr><td colspan=2><input type="submit" name="submit" id="submit" value="Save" /> <input type="submit" value="Cancel" /></td></tr>
</form>
<tr><td colspan=2><div id="gridbox" height="100px" style="background-color:white;"></div></td></tr>
                </tbody>
            </table>';
  
    require_once 'gridfiles.php';
}


?>

