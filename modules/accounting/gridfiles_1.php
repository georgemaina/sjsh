<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("item_Number,item_type,Description,price,qty,total");
    sgrid.setInitWidths("80,200,270,100,60,100");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ro,ed,ed,ed,ed");
    sgrid.setColSorting("str,str,str,str,str,str");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    //    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    var dhxWins, w1, grid;

    function doOnCellEdit(stage,rowId,cellInd){
        var rev=sgrid.cells(sgrid.getSelectedId(),0).getValue();
        var qty=sgrid.cells(sgrid.getSelectedId(),4).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),3).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==0){
            //sgrid.cells(sgrid.getSelectedId(),1).setValue('rev');//split(",",1));
            getDesc(rev,rowId)
        } else if
        (stage==2 && cellInd==4){
            sgrid.cells(rowId,5).setValue(total);
            document.getElementById('total').value=sumColumn(5);
        }

        return true;
    }

    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            sgrid.addRow(newId,"",sgrid.getRowsNum())
        }
        sgrid.selectRow(sgrid.getRowIndex(newId),false,false,true);

    }

    function deleteRow(){
        sgrid.deleteSelectedItem();
    }
    function initKSearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 400, 150, 500, 450);
        w1.setText("Products List");
        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Item_ID,item_type,Description,price");
        grid.setInitWidths("80,100,200,60");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setColSorting("str,str,str,str");
        grid.setColumnColor("white,white,white,white");
        grid.loadXML("debit_conn.php");
         grid.attachEvent("onRowDblClicked", doOnRowDoubleClicked);  
        //         grid.attachEvent("onRowSelect",doOnRowSelected);
        //        grid.attachEvent("onEnter",doOnEnter2);
        grid.enableDragAndDrop(true);

        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


    function doOnRowDoubleClicked(id,ind){
        addRows(1);
        sgrid.cells(sgrid.getSelectedId(),0).setValue(grid.cells(id,0).getValue());
        sgrid.cells(sgrid.getSelectedId(),1).setValue(grid.cells(id,1).getValue());
        sgrid.cells(sgrid.getSelectedId(),2).setValue(grid.cells(id,2).getValue());
        sgrid.cells(sgrid.getSelectedId(),3).setValue(grid.cells(id,3).getValue());
    }

    function initPtsearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 200, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name,en_nr");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
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

    function doOnEnter2(rowId,cellInd){
        //        var qty=grid.cells(grid.getSelectedId(),1).getValue();
        //         protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        alert(grid.cells2(rowId,cellInd).getValue());
        //
    }

    function doOnRowSelected3(id,ind){
        names=grid.cells(id,1).getValue()+" "+grid.cells(id,3).getValue()+" "+grid.cells(id,2).getValue()
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        document.getElementById('pname').value=names;
        document.getElementById('en_nr').value=grid.cells(id,4).getValue();
        sendRequestPost(grid.cells(id,4).getValue());
    }
    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
    }
    
    function sendRequestPost(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="admDetails.php?enr_nr="+str;
        url=url+"&sid="+Math.random();
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged()
    {
        //get payment description
        if (xmlhttp.readyState==4)//show point desc
        {
            var str=xmlhttp.responseText;
            str3=str.split(",");
            document.debit.ward_nr.value=str3[0];
            document.debit.ward_name.value=str3[1];
            document.debit.room_nr.value=str3[2];
            document.debit.bed_nr.value=str3[3];
       
        }
    }

    function getDesc(rev,rowId){
        xmlhttp5=GetXmlHttpObject();
        if (xmlhttp5==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getDesc.php?rev="+rev;
        url=url+"&sid="+Math.random();
        url=url+"&rowID="+rowId;
        url=url+"&callerID=grid";
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
            str2=str.split(",");

            sgrid.cells(sgrid.getSelectedId(),1).setValue(str2[0]);
            sgrid.cells(sgrid.getSelectedId(),2).setValue(str2[1]);
            sgrid.cells(sgrid.getSelectedId(),3).setValue(str2[2]);
            //                sgrid.cells(sgrid.getSelectedId(),4).setValue(str2[1]);
        }
    }

    function getPatient(str)
    {
        xmlhttp9=GetXmlHttpObject();
        if (xmlhttp9==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getDesc.php?pid="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=debit";
        xmlhttp9.onreadystatechange=stateChanged9;
        xmlhttp9.open("POST",url,true);
        xmlhttp9.send(null);

    }

    function stateChanged9()
    {
        //get payment description
        if (xmlhttp9.readyState==4)//show point desc
        {
            var str=xmlhttp9.responseText;
            str2=str.split(",");
            document.debit.pname.value=str2[0]+' '+str2[1]+' '+str2[2];
            document.debit.ward_nr.value=str2[5];
            document.debit.en_nr.value=str2[3];
            document.debit.receiptNo.value=str2[7];
            //applyFilter();
            //countGridRecords(document.csale.patientId.value);
//           getNewReceipt();
        }
    }
    
   function GetXmlHttpObject()
    {
        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        }
        if (window.ActiveXObject)
        {
            // code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
        return null;
    }



</script>

