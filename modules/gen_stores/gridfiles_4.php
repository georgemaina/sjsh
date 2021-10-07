<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("No,prescribe_date,item_id,Item_desc,dosage,times_per_day,days,qty in store,price,qty prescribed,issued,total");
    sgrid.setInitWidths("30,120,100,200,60,100,60,100,60,120,120,120");
    sgrid.setSkin("light");
    sgrid.setColTypes("ch,ro,ro,ro,ro,ro,ro,ro,ro,ro,ed,ro");
    sgrid.setColSorting("int,str,str,str,int,int,int,int,str,str,int,int,int");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.init();
//    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    var dhxWins, w1, grid;
//    function myRowIds(){
//        var rId='';
//        for(var i=0;i<=sgrid.getRowsNum();i++){
//            rId += parseFloat(sgrid.getRowId(i))+','; // get row id by its index
//        }
//        //document.getElementById('numRows').value=rId;
//    }

    function getPaymentMethod(pid){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="pharmVals.php?pid="+pid;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getPaymentMethod";
        xmlhttp.onreadystatechange=statePaymentMehod;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }


    function statePaymentMehod()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");

            document.getElementById("paymode").innerHTML=str2[0];
            document.getElementById("pmode").value=str2[1];
        }
    }

    function getOrders(){
        if(document.order.issType[0].checked){
            getPnames(document.getElementById("pid").value);
            applyFilter1();
            getDrugTotals(10);

           // myRowIds();

        }else if(document.order.issType[1].checked){
            getPnames(document.getElementById("pid").value);
            applyFilter2();
            getDrugTotals(10);

//           document.getElementById('totalCost').value=totals;
        }
    }


    function getReceiptNo(pid){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="pharmVals.php?pid="+pid;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getReceiptNo";
        xmlhttp.onreadystatechange=stateChangedReceipt;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }


    function stateChangedReceipt()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");

            document.order.receiptNo.value=str2[0];
            document.order.receiptAmount.value=str2[1];

            getPaymentMethod(document.getElementById("pid").value);
        }
    }
function applyFilter1(){

        sgrid.clearAll(); //remove all data
        gridQString = "issue_conn.php?patientId="+document.getElementById("pid").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

        var revDP=new dataProcessor(gridQString);
        revDP.init(sgrid);

//        getDrugTotals(6);

    }

    function applyFilter2(){
        //alert('help');
        sgrid.clearAll(); //remove all data
        gridQString = "issue_conn2.php?patientId="+document.getElementById("pid").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

        var revDP=new dataProcessor(gridQString);
        revDP.init(sgrid);

//        getDrugTotals(6);
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
            str2=str.split(",");

            
             document.order.pname.value=str2[0];
             document.order.enc_no.value=str2[1];
//            document.order.docName.value=str2[2];
            document.order.age.value=str2[3];

            getPrescriber(document.getElementById("enc_no").value);

        }
    }

    function getPrescriber(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }

        var url="pharmVals.php?enc_no="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getPrescriber";
        xmlhttp.onreadystatechange=stateChanged12;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged12()
    {
     //   alert('Test test');
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");
//            alert(str);
            document.order.docName.value=str2[0];

            getReceiptNo(document.getElementById("pid").value);
        }
    }



    function doOnCellEdit(stage,rowId,cellInd){
        //        var rev=sgrid.cells(sgrid.getSelectedId(),0).getValue();
        var qty=sgrid.cells(sgrid.getSelectedId(),10).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),8).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==10){
            sgrid.cells(rowId,11).setValue(total);
            document.getElementById('totalCost').value=sumColumn(9);
        }
        return true;
    }


    function getDrugTotals(ind){
         var out = 0;
         var sIds='';

        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,11).getValue());

        }
        var selectedRow = sgrid.getCheckedRows(0);

        document.getElementById('totalCost').value='Ksh:'+out;
        document.getElementById('numRows').value=selectedRow;// sgrid.getRowsNum();

//        return out;
    }
    
    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            sgrid.addRow(newId,"",sgrid.getRowsNum())
        }
        sgrid.selectRow(mygrid.getRowIndex(newId),false,false,true);

    }

    function deleteRow(){
        //sgrid.deleteSelectedItem();
        var ind1 = sgrid.getSelectedId();
        if (ind1 === null)
            return;

        if(window.prompt('Are you sure you want to delete this record ', sgrid.getSelectedId())){
            sgrid.deleteRow(ind1);
            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="pharmVals.php?callerID=delete";
            url=url+"&sid="+Math.random();
            url=url+"&prescNr="+ind1;
            xmlhttp.onreadystatechange=stateChangedDelete;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        }else{
            return;
        }
    }

    function stateChangedDelete()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            //alert("Delete was "+str)
            if(document.order.issType[0].checked){
                applyFilter1();
            }else if(document.order.issType[1].checked){
                applyFilter2();
            }
        }
    }

    function initPSearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 400, 150, 450, 450);
        w1.setText("Products List");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Item_ID,Description,price,qty");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ed,ro,ro");
        grid.setInitWidths("80,180,60,60,60");
        grid.loadXML("issueconn_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        grid.enableDragAndDrop(true);

        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }



    function doOnEnter2(rowId,cellInd){
        //        var qty=grid.cells(grid.getSelectedId(),1).getValue();
        //         protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        alert(grid.cells2(rowId,cellInd).getValue());
        //
    }

    function doOnRowSelected(id,ind){
        //        addRows(1);
        //        numrows=sgrid.getRowsNum();
        //        var itemid=grid.cells(id,1).getValue();
        //        var description=grid.cells(id,3).getValue();
        //        var units=grid.cells(id,2).getValue();


    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,10).getValue())
        }
        return out;
    }

    function initPtsearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 335, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name,enc_no");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,80");
        grid.loadXML("pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);

    }

    function doOnRowSelected3(id,ind){
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        var names=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue()
        document.getElementById('pname').value=names;
        document.getElementById('enc_no').value=grid.cells(id,4).getValue();
    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        var names=sgrid.cells(id,1).getValue()+' '+grid.cells(id,3).getValue()
        document.getElementById('pname').value=names;
        document.getElementById('enc_no').value=grid.cells(id,4).getValue();
        closeWindow();
    }

    function getDoc(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 335, 340, 250);
        w1.setText("Search Doctors");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");
        grid.setHeader("DocID,first Name,Surname,Last name");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80");
        grid.loadXML("docList.php");
        grid.attachEvent("onRowSelect",doOnRowSelected4);
        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);

    }

    function doOnRowSelected4(id,ind){
        //        document.getElementById('docName').value=grid.cells(id,0).getValue();
        var names=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
        document.getElementById('docName').value=names;
        //        document.getElementById('enc_no').value=grid.cells(id,4).getValue();
    }

    function doOnEnter4(rowId,cellInd){
        var names=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
        document.getElementById('docName').value=names;
        closeWindow();
    }

</script>
<script language="JavaScript">
    <!--
    function popPic(pid,nm){

        if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=6ac874bb63e983fd6ec8b9fdc544cab5&lang=$lang&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

    }
    // -->
</script>

<script language="javascript">

    <!--
    function closewin()
    {
        location.href='startframe.php?sid=6ac874bb63e983fd6ec8b9fdc544cab5&lang=$lang';
    }
    // -->
</script>

<script language="javascript">
    <!--
    function saveData()
    {
        document.forms["inputform"].submit();
    }
    function reset()
    {
        document.forms["inputform"].submit();
    }
    -->
</script>
<script language="javascript" >
    <!--
    function gethelp(x,s,x1,x2,x3,x4)
    {
        if (!x) x="";
        urlholder="../../main/help-router.php<?php echo URL_APPEND; ?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
        helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
        window.helpwin.moveTo(0,0);
    }
    // -->
</script>
