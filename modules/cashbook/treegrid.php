<html>
    <head><title></title>
        <link rel='STYLESHEET' type='text/css' href='../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

        <script src='../../include/dhtmlxGrid/codebase/dhtmlfunctions.js'></script>
        <script src='../../include/dhtmlxGrid/codebase/dhtmlxcommon.js'></script>
        <script src='../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
        <script src='../../include/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor.js'></script>
        <!--<script src='../../include/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor_debug.js'></script>-->
        <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
        <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
        <script src='../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
        <script src='../../include/dhtmlxConnector/codebase/connector.js'></script>
        <script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_form.js'></script>
            </head>
    <body>

         <div id="cash_tree" width="680px" height="150px" style="background-color:white;overflow:hidden"></div>

        <script>
            var mygrid = new dhtmlXGridObject("cash_tree")
            mygrid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("rev_code,rev_desc,proc_code,prec_desc,qty,amount");
           // mygrid.attachHeader("#connector_text_filter,#connector_text_filter")
            mygrid.setInitWidths("80,180,80,180,50,100");
            mygrid.setSkin("light");
            mygrid.setColSorting("str,str,str,int,int,int");
            mygrid.setColTypes("ed,ed,ed,ed,ed,ed");
            mygrid.enableSmartRendering(true);
            mygrid.enableMultiselect(true);


            mygrid.init();
            mygrid.loadXML("treegrid.xml");


           // var myDP=new dataProcessor("cashconn.php");
            //myDP.setUpdateMode("off");

            //myDP.init(mygrid);

        </script>
        <button onclick="addRow()">Add Row</button>
        <button onclick="InsertmyData()">Save</button>
        <button onclick="removeRow()">Cancel</button>

    </body>
</html>