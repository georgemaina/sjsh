<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Grid Example (MySQL/php)</title>

    <link rel="stylesheet" type="text/css" href="../ext/resources/css/ext-all.css" />

     <!-- GC -->
     <!-- LIBS -->
     <script type="text/javascript" src="../ext/adapter/ext/ext-base.js"></script>
     <!-- ENDLIBS -->

    <script type="text/javascript" src="../ext/ext-all-debug.js"></script>




    <script type="text/javascript" src="ux/RowExpander.js"></script>

     <!-- Extensions - Paging Toolbar -->
    <script type="text/javascript" src="ux/paging/pPageSize.js"></script>

     <!-- Extensions - Filtering -->
	<script type="text/javascript" src="ux/menu/EditableItem.js"></script>
	<script type="text/javascript" src="ux/menu/RangeMenu.js"></script>

	<script type="text/javascript" src="ux/grid/GridFilters.js"></script>
	<script type="text/javascript" src="ux/grid/filter/Filter.js"></script>
	<script type="text/javascript" src="ux/grid/filter/StringFilter.js"></script>
	<script type="text/javascript" src="ux/grid/filter/DateFilter.js"></script>
	<script type="text/javascript" src="ux/grid/filter/ListFilter.js"></script>
	<script type="text/javascript" src="ux/grid/filter/NumericFilter.js"></script>
	<script type="text/javascript" src="ux/grid/filter/BooleanFilter.js"></script>

     <!-- Page Specific -->
    <script type="text/javascript" src="grid-editor-mysql-php.js"></script>


    <link rel="stylesheet" type="text/css" href="grid-examples.css" />

    <!-- Common Styles for the examples -->

    <link rel="stylesheet" type="text/css" href="../ext/shared/examples.css" />

    <style type="text/css">
        body .x-panel {
            margin-bottom:20px;
        }
        .icon-grid {
            background-image:url(../ext/shared/icons/fam/grid.png) !important;
        }
        #button-grid .x-panel-body {
            border:1px solid #99bbe8;
            border-top:0 none;
        }
        .add {
            background-image:url(ext/shared/icons/fam/add.gif) !important;
        }
        .option {
            background-image:url(ext/shared/icons/fam/plugin.gif) !important;
        }
        .remove {
            background-image:url(ext/shared/icons/fam/delete.gif) !important;
        }
        .refresh {
            background-image:url(ext/shared/icons/fam/table_refresh.png) !important;
        }
        .save {
            background-image:url(ext/shared/icons/save.gif) !important;
        }

        .red { color: red;}
    	.redcell { background-color:#FFE5E5 !important;}

		.greenrow { background-color:#C3FF8F !important;}
		.yellowrow { background-color:#FFFF66 !important;}
		.pinkrow { background-color:#FFE6CC !important;}
    	.x-grid3-col-classCompanyID { background-color:#F2F2F2 !important;}
 /*  	.x-grid3-col-classCompany { background-color:#FEFFE5 !important;} this shades the column but covers the red triangles*/

    	.stars1 {
           background-image:url(ext/shared/icons/fam/user_suit.png) !important;
		   background-repeat: no-repeat;
		   background-position: center;
 		}
    	.stars2 {
           background-image:url(ext/shared/icons/fam/user_red.png) !important;
		   background-repeat: no-repeat;
		   background-position: center;
 		}
    	.stars3 {
           background-image:url(ext/shared/icons/fam/user_orange.png) !important;
		   background-repeat: no-repeat;
		   background-position: center;
 		}
    	.stars4 {
           background-image:url(ext/shared/icons/fam/user_green.png) !important;
		   background-repeat: no-repeat;
		   background-position: center;
 		}
    	.stars5 {
           background-image:url(ext/shared/icons/fam/user_gray.png) !important;
		   background-repeat: no-repeat;
		   background-position: center;
 		}

		/*for filter extension*/
		/*visual feedback of a filtered column, filtered columns will show bold/italic*/
		.x-grid3-hd-row td.ux-filtered-column {
        font-style: italic;
        font-weight: bold;
    	}

    </style>
</head>
<body>
<script type="text/javascript" src="../ext/examples.js"></script><!-- EXAMPLES -->
<h1>Grid Example using MySQL and php</h1>
<p>This example shows how to create a grid from MySQL data with php backend.</p>
<p>Note that the js is not minified so it is readable. See <a href="grid-mysql-php.js" target="_blank">grid-mysql-php.js</a> and the <a href="../../docs/" target="_blank">API Docs</a>.</p>

<p><!--the progress bar is rendered in here-->
    <b>Progress</b><br />
    <div id="p2" style="width:50%;"></div>
</p>

<!-- you must define the select box here, as the custom editor for the 'Light' column will require it -->
<select name="riskName" id="riskID" style="display: none;">
	<option value="low">Low</option>
	<option value="medium">Medium</option>
	<option value="high">High</option>
</select>

<!--when right clicking, the properties get rendered in here-->
<div id="property-win" class="x-hidden"></div>

<!--the grid gets rendered in here-->
<div id="grid-company"></div>

 <!---->
</body>
</html>