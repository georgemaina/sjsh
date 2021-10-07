<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <?php
        include "mylinks.php";
//        ExtIncludes();
        ?>

<!--        <script type="text/javascript"  src="viewSlips.js" ></script>-->

        <script src="../../../ext-4/ext-all.js"></script>
        <link rel="stylesheet" href="../../../ext-4/packages/ext-theme-classic/build/resources/ext-theme-classic-all.css">
        <script type="text/javascript" src="app.js"></script>

        <style>
            #container{width:100%;}
            #left{float:left;width:15%;}
            #itemList{float:right;width:85%;}

        </style>
    </head>
    <body>
        <div id="container">
            <div id="left">
                <?php require 'aclinks.php'; ?>
            </div>
            <div id="itemList"></div>
        </div>

    </body>
</html>

