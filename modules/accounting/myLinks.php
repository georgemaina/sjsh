<?php

function jsIncludes() {
    ?>
    <!-- dhtmlxWindows -->

    <link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
    <link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
    <!--<script src='../../include/dhtmlxGrid/codebase/dhtmlxcommon_debug.js'></script>-->
    <script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
    <script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

    <!-- Display menus-->
    <link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
    <link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
    <link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">
    <script  src="../../include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
    <script  src="../../include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>
    <!-- dhtmlxGrid -->
    <link rel='STYLESHEET' type='text/css' href='../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

    <script src='../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
    <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_form.js'></script>
    <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
    <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
    <script src='../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
    <script src="../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>

    <script src="../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

    <script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxDataProcessor.js'></script>
    <!--<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>

     dhtmlxCalendar -->
    <link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
    <script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
    <script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
    <script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>

    <!-- dhtmlxCombo -->
    <link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCombo/codebase/dhtmlxcombo.css">
    <script>window.dhx_globalImgPath="../../include/dhtmlxCombo/codebase/imgs/";</script>
    <script src="../../include/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
    <script src="../../include/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
    <script src='../../include/dhtmlxConnector_php/codebase/connector.js'></script>

    <?
}

function displayForm() {
    echo '<form name="debit" method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<table width=90% border="0" cellpadding="0" cellspacing="5">';

    echo '<tr><td colspan="5" class=pgtitle>Debit</td></tr>';
//                echo ' <tr><td>Revenue Codes:</td>';
//                echo '<td colspan=3><input type="text" name="revcode" id="revcode" ondblclick="initRsearch()"/>
//                            </td></tr>';
    echo '<tr><td>Patient No:</td><td colspan=3>
                <input type="text" size="10" name="pid" id="pid" ondblclick="initPtsearch()" onblur="getPatient(this.value)"/>';
    echo '<input type="text" name="pname" id="pname" size="36" "/>
                        <input type="button" id="search" value="search" onclick="initPtsearch()"/></td></tr>';
    echo '<tr><td>ref No:</td>';
    echo '<td><input type="text" name="receiptNo" id="receiptNo"/>
                    <input type="hidden" name="en_nr" id="en_nr" size=5/>
                    <input type="hidden" name="ward_nr" id="ward_nr" size=5"/>
                    <input type="hidden" name="ward_name" id="ward_name" size=10/>
                    <input type="hidden" name="room_nr" id="room_nr" size=5/>
                    <input type="text" name="bed_nr" id="bed_nr" size=5/></td></tr>';
    echo '<tr><td>date:</td';
    echo '<td colspan=4><input type="text" name="calInput" id="calInput" value="' . date("d-m-y") . '"/></td></tr>';
    echo '<tr><td colspan="5">
                           <div id="gridbox" height="200px" style="background-color:white;"></div>
                       </td></tr>';
    echo '<tr><td colspan=5 align=right>Total:<input type="text" size="15" name="total" id="total" /></td></tr>';
    echo '<tr><td></td><td colspan=4 align=center>';
    echo '<input type="submit" name="submit" id="submit" value="save" />&nbsp&nbsp';
    echo '<input type="button" name="cancel" id="cancel" value="cancel" /></td></tr>';

    echo '</table>';
    echo '</form>';
    echo '<button onclick=addRows(1)>Add Row</button>
                    <button onclick=deleteRow()>Delete</button>
                    <button onclick=initKSearch()>Get Products List</button>';
    require_once 'gridfiles.php';
}
?>
