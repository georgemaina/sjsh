<?php 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <?php
        require ('myLinks.php');
        ExtIncludes();
        ?>

        <script type="text/javascript"  src="searchDrugs.js" ></script>
        <script type="text/javascript" >
            Ext.BLANK_IMAGE_URL="../../include/Extjs/resources/images/default/s.gif";
            Ext.onReady(function(){

                var viewPort=new Ext.Viewport({
                    layout: 'anchor',
                    anchorSize: {width:1024, height:720},
                    items: [{
                            title:'Drugs,Services and Other Items',
//                            html:'Content 3',
                            width:600,
                            anchor:'-100 100%',
                            items:['itemList']
                        }]
                });
                viewPort.render(document.body);
            });
        </script>
    </head>
    <body>
        <div id="itemList"></div>
    </body>
</html>

