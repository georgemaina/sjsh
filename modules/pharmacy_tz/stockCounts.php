<?php
require_once('roots.php');
require($root_path . 'include/inc_environment_global.php');

require_once 'myLinks.php';
jsIncludes();
?>
<style>
    .stockMessage {
        background-color: #ffffff;
        font-size: large;
        font-weight: bold;
        text-decoration: blink;
        color: #ff0007;
        height: 60px;
        width: 800px
}
    
</style>
<html>
    <link rel="stylesheet" type="text/css" href="../accounting/accounting.css">
    <body>
        <table width=100% border=0 cellspacing=0 height=100%>
            <tbody class="main">
                <tr>
                    <td  valign="top" align="middle" height="35">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td bgcolor="#99ccff" >
                                    &nbsp;&nbsp;<font color="#330066">Pharmacy Imported Counts</font>

                                </td>
                                <td bgcolor="#99ccff" align=right><a
                                        href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
                                        href="javascript:gethelp("pharmacy_menu.php","Pharmacy :: Main Menu")"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
                                        href="../../modules/news/start_page.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
                            </tr>
                            <tr>
                                <td>These imported stocks have been added to the Temporary Table</td>
                            </tr>
                        </table>
                        <div id="myContent" class="stockMessage"> Check the figures and click Confirmed to move the stocks to the stock counts table<br>
                           
                        </div>      
                        <div> <button id='Confirmed' onclick="confirmCounts()">Submit Confirmed Counts</button> </div>              
                        <div id="hello-win" align="center"></div>   
                        <div id="gridbox" height="500px" style="background-color:white;"></div>
                        <button  id="processCounts" onclick="processCounts()">Process Stock Count</button>

                        </body>
                        </html>

                        <script>
                            var revgrid = new dhtmlXGridObject('gridbox');
                            revgrid.setImagePath("codebase/imgs/");
                            revgrid.setHeader("Item ID,location,Date,Qty at hand,Current Count,Input User");
                            revgrid.attachHeader("#connector_text_filter,#connector_text_filter")
                            revgrid.setInitWidths("80,80,100,80,80,100");
                            revgrid.setSkin("light");
                            revgrid.setColSorting("str,str,str,int,int,str");
                            revgrid.setColTypes("ed,ed,ed,ed,ed,ed");
                            revgrid.enableSmartRendering(true);
                            revgrid.enableMultiselect(true);
                            revgrid.init();
                            revgrid.loadXML("stockCountDB.php")

                            var revDP=new dataProcessor("stockCountDB.php");
                            revDP.init(revgrid);

                            function addRowR(){
                                var newId = (new Date()).valueOf();
                                revgrid.addRow(newId,"",revgrid.getRowsNum())
                                revgrid.selectRow(revgrid.getRowIndex(newId),false,false,true);
                            }
            
                             function processCounts(str){
                                xmlhttp=GetXmlHttpObject();
                                if (xmlhttp==null)
                                {
                                    alert ("Browser does not support HTTP Request");
                                    return;
                                }
                                var url="pharmVals.php?desc="+str;
                                url=url+"&sid="+Math.random();
                                url=url+"&callerID=processCounts";
                                
                                show_progressbar('myContent');
                                xmlhttp.onreadystatechange=stateChanged3;
                                xmlhttp.open("POST",url,true);
                                xmlhttp.send(null);
                            }
                            
                            function stateChanged3()
                            {
                                //get payment description
                                if (xmlhttp.readyState==4)
                                {
                                    var str=xmlhttp.responseText;
                                    if(str==0){
                                        document.getElementById('myContent').innerHTML='Stock Count Counts Updated Successfully, Check Stock levels for Confirm the Quantities';
                                    }
                                    
                                }
                            }
                            function confirmCounts(str){
                                xmlhttp=GetXmlHttpObject();
                                if (xmlhttp==null)
                                {
                                    alert ("Browser does not support HTTP Request");
                                    return;
                                }
                                var url="pharmVals.php?desc="+str;
                                url=url+"&sid="+Math.random();
                                url=url+"&callerID=confirmCounts";
                                
                                show_progressbar('myContent');
                                xmlhttp.onreadystatechange=stateChanged;
                                xmlhttp.open("POST",url,true);
                                xmlhttp.send(null);
                            }

                            function show_progressbar(id) {
                                document.getElementById('myContent').innerHTML='<img src="../ajax-loader2.gif" border="0" alt="Loading, please wait..." />';
                            }
                            function stateChanged()
                            {
                                //get payment description
                                if (xmlhttp.readyState==4)
                                {
                                    var str=xmlhttp.responseText;
                                   if(str==0){
                                        document.getElementById('myContent').innerHTML='Stock Count Confirmed Click Process Stock Count to Update Stocks';
                                    }
                                    
                                }
                            }
                            
                           
                        </script>
