<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html>
<head>
	<title>Attaching dhtmlxGrid</title>

	<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
	<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_blue.css">


        <script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
        <script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

	<!-- dhtmlxGrid -->
	<script src="../../include/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
	<script src="../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
	<link rel="stylesheet" type="text/css" href="../../include/dhtmlxGrid/codebase/dhtmlxgrid.css">
	<link rel="stylesheet" type="text/css" href="../../include/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">

</head>
<body>
<link rel='STYLESHEET' type='text/css' href='../../include/dhtmlxGrid/common/style.css'>

<div style="height: 460px;">
	<!-- fake -->
</div>

<script>

	var dhxWins = new dhtmlXWindows();
	//dhxWins.enableAutoViewport(false);
	//dhxWins.setViewport(50, 50, 700, 400);
	//dhxWins.vp.style.border = "#909090 1px solid";
	dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

	var w1 = dhxWins.createWindow("w1", 10, 10, 400, 350);
	w1.setText("Laboratory codes");

	var grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
	grid.loadXML("grid.xml");
        grid.init();

</script>


</body>