
<script> //display datagrid
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('codebase/imgs/');
    sgrid.setHeader("issue_no,presc_nr,item_id,item_desc,price,qty,unit_cost");
    sgrid.setInitWidths("80,80,80,180,80,80");
    sgrid.setSkin("light");
    sgrid.setColTypes("ro,ro,ro,ro,ro,ed,ro");
    //sgrid.myCombo(0).setValue("con");
    sgrid.setColSorting("str,str,str,str,str,str,str");
    sgrid.setColumnColor("white,grey,grey");
    //sgrid.attachEvent("onRowSelect",doOnRowSelected);
    //sgrid.attachEvent("onMouseOver",doOnMouseover);
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.enableKeyboardSupport(true);

    sgrid.init();
    sgrid.enableSmartRendering(true);
    //gridQString = "cashsale_conn.php?patientId="+document.getElementById("patientId").value;//save query string in global variable (see step 5 for details)
    //sggrid.loadXML(gridQString)
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    var dhxWins, w1, grid;

 

    function myRowIds(){
       var rId='';
        for(var i=0;i<=sgrid.getRowsNum();i++){
              rId += parseFloat(sgrid.getRowId(i))+','; // get row id by its index
        }
         document.getElementById('txtIDs').value=rId;
    }

    function applyFilter1(){

        sgrid.clearAll(); //remove all data
        gridQString = "returnsConn.php?issueId="+document.getElementById("issNo").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

         var revDP=new dataProcessor(gridQString);
         revDP.init(sgrid);

         
    }

    function applyFilter2(){
        //alert('help');
        sgrid.clearAll(); //remove all data
        gridQString = "returnsConn2.php?issueId="+document.getElementById("issNo").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

        var revDP=new dataProcessor(gridQString);
        revDP.init(sgrid);


    }

function getPrescriptions(str){
        //alert('help');
        sgrid.clearAll(); //remove all data
        gridQString = "Prescription_conn_1.php?prescNo="+document.getElementById("prescNO").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

        var revDP=new dataProcessor(gridQString);
        revDP.init(sgrid);


    }


    function closeWindow() {
        parent.dhxWins.window("w1").close();
    }
    //function doOnCellEdit(){
      //  parent.dhxWins.window("w1").close();
    //}

    function doOnEnter(rowId,cellInd){
        var rev= sgrid.cells(sgrid.getSelectedId(),sgrid.getSelectedCellIndex()).getValue();
        // protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        if(cellInd==0){
            initRevPop();}

    }

    function doOnRowSelected(id,ind){

        // var qty=grid.cells(grid.getSelectedId(),1).getValue();
//        alert("User pressed Enter on row with id "+id+" and index "+ sgrid.cells(id,ind).getValue());
        getGridRecords(document.getElementById("patientId").value)

    }

    function doOnCellEdit(stage,rowId,cellInd){
        var rev=sgrid.cells(sgrid.getSelectedId(),0).getValue();
        var qty=sgrid.cells(sgrid.getSelectedId(),5).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),4).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==5){
            sgrid.cells(rowId,6).setValue(total);
            document.getElementById('total').value=sumColumn(6);
        }else if(stage==2 && cellInd==0){
            //sgrid.cells(sgrid.getSelectedId(),1).setValue('rev');//split(",",1));
           getDesc(rev,rowId)
        }


        return true;
    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
    }


    function initRevPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 940, 220, 340, 450);
        w1.setText("revenue codes");

        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("rev_code,rev_desc,amount");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("80,180,60");
        grid.loadXML("revconn_pop.php");
        // grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }



    function doOnEnter2(rowId,cellInd){
        //var qty=grid.cells(grid.getSelectedId(),1).getValue();
        // protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
//        alert(grid.cells2(rowId,cellInd).getValue());

    }

    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            sgrid.addRow(newId,"",sgrid.getRowsNum())
        }
        sgrid.selectRow(mygrid.getRowIndex(newId),false,false,true);

    }


    function showPrescriptions(str){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 940, 220, 540, 450);

        w1.setText("Prescription");
        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("PID,order_no,presc_nr,item_id,item_desc,price,qty,unit_cost");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
        grid.setInitWidths("80,60,60,60,180,60,60,60");
        grid.loadXML("Prescription_conn.php?issDate="+str);
        grid.attachEvent("onRowSelect",doOnRowSelected1);
        grid.attachEvent("onEnter",doOnEnter1);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


    function initLabPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 940, 220, 340, 450);
        w1.setText("Laboratory codes");

        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("code,Test Desc,Price");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("80,180,60");
        grid.loadXML("labconn_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function initProcPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 940, 220, 340, 450);
        w1.setText("Procedure codes");

        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("code,Procedure,Price");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("80,180,60");
        grid.loadXML("proc_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function initPsearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 300, 235, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80");
        grid.loadXML("pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowSelected1(id,ind){
        document.getElementById('prescNO').value=grid.cells(id,2).getValue();
        document.getElementById('patientId').value=grid.cells(id,0).getValue();
        getPrescriptions(grid.cells(id,2).getValue())
//        document.getElementById("pname").value=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
    }

     function doOnEnter1(rowId,cellInd){
        document.getElementById('prescNO').value=grid.cells(rowId,1).getValue();
        closeWindow();
    }
    
    function doOnRowSelected3(id,ind){
        document.getElementById('patientId').value=grid.cells(id,0).getValue();
        document.getElementById("pname").value=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
//        document.getElementById("storeID").value=sgrid.cells(id,3).getValue();
//        document.getElementById("storeDesc").value=sgrid.cells(id,4).getValue();

    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('patientId').value=+rowId;
        closeWindow();
    }

    function closeWindow() {
        dhxWins.window("w1").close();
    }

 function getIssues(){
        if(document.order.issType[0].checked){
            getPnames(document.getElementById("patientId").value);
            applyFilter1();
        }else if(document.order.issType[1].checked){
            getPnames(document.getElementById("patientId").value);
            applyFilter2();
        }
    }

function getDesc(rev,rowId){
    xmlhttp5=GetXmlHttpObject();
    if (xmlhttp5==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url="gridVals.php?rev="+rev;
    url=url+"&sid="+Math.random();
    url=url+"&rowID="+rowId;
    xmlhttp5.onreadystatechange=stateChanged5;
    xmlhttp5.open("POST",url,true);
    xmlhttp5.send(null);
}

   function stateChanged5()
    {
     //get payment description
    if (xmlhttp5.readyState==4)
    {
        var str=xmlhttp5.responseText;
         //str2=str.search(/,/)+1;
        splitString(str, ",");
    }
}

 function getPnames(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="pharmVals.php?desc9="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getNames";
        xmlhttp.onreadystatechange=stateChanged9;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }
    
    
    function stateChanged9()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
             document.order.pname.value=str;

        }
    }
	

function splitString(stringToSplit,separator) {
    var arrayOfStrings = stringToSplit.split(separator);

    for (var i=0; i < arrayOfStrings.length; i++) {
        sgrid.cells(arrayOfStrings[2],1).setValue(arrayOfStrings[0]);
        sgrid.cells(arrayOfStrings[2],4).setValue(arrayOfStrings[1]);
    //document.write(arrayOfStrings[i] + " / ");
    }
}

//var tempestString = "Oh brave new world that has such people in it.";
//var monthString = "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec";




</script>



