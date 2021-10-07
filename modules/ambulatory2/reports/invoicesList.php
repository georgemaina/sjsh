<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Data Binding Example</title>
	    <link rel="stylesheet" type="text/css" href="../../../include/Extjs/resources/css/ext-all.css" />

	 	<script type="text/javascript" src="../../../include/Extjs/adapter/ext/ext-base.js"></script>
	    <script type="text/javascript" src="../../../include/Extjs/ext-all-debug.js"></script>
	    <script type="text/javascript" src="pendingInvoices.js"></script>
		<style type="text/css">
			body {
				padding: 15px;
			}
			.x-panel-mc {
				font-size: 12px;
				line-height: 18px;
			}
		</style>

         <link rel="stylesheet" type="text/css" href="../accounting.css">
	</head>
<body>
   
<?php

require('roots.php');?>
 <div class=pgtitle>Patient Bill Management</div>
<table border="0">
    <tr><td align=left valign="top">
<?php require($root_path."modules/ambulatory2/aclinks.php");?>
</td>
           <td align=right>
                <div id="binding-example"></div>
         </td>
  </tr></table>
	
	</body>
</html>
