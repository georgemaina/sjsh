
<script> //display datagrid
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("catID,item_Cat,proc_code,prec_desc,amount,qty,total,Bill_date,Bill_Time");
    sgrid.setInitWidths("80,180,80,180,100,50,100,100,100");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ro,ro,ro,ed,ed,ro,ro,ro");
    sgrid.setColValidators("NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty");
    sgrid.setColSorting("str,str,str,str,str,int,int,int,int");
    sgrid.setColumnColor("white,white,white");
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.enableKeyboardSupport(true);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    var dhxWins, w1, grid,xmlhttp5;

 

    function myRowIds(){
        var rId='';
        for(var i=0;i<=sgrid.getRowsNum();i++){
            rId += parseFloat(sgrid.getRowId(i))+','; // get row id by its index
        }
        document.getElementById('txtIDs').value=rId;
    }

    function applyFilter(){

        sgrid.clearAll(); //remove all data
        gridQString = "cashsale_conn.php?patientId="+document.getElementById("patientId").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

        var revDP=new dataProcessor(gridQString);
        revDP.init(sgrid);

          getCashPointS(document.csale.cash_point.value);
    }

     function deleteRow(){
//        sgrid.deleteSelectedItem();
//        var ind1 = window.prompt('Row[index] to delete', sgrid.getSelectedId());
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
                var url="gridVals.php?delete="+ind1;
                url=url+"&sid="+Math.random();
                url=url+"&del="+ind1;
                xmlhttp.onreadystatechange=stateChanged7;
                xmlhttp.open("POST",url,true);
                xmlhttp.send(null);
         }else{
            return; 
         }
    }
    
    function stateChanged7()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str3=xmlhttp.responseText;
                if(str3==1){
                    applyFilter();
                }


        }
    }
    
    function closeWindow() {
        parent.dhxWins.window("w1").close();
    }
    //function doOnCellEdit(){
    //  parent.dhxWins.window("w1").close();
    //}

    function doOnEnter(rowId,cellInd){
        //        var rev= sgrid.cells(sgrid.getSelectedId(),sgrid.getSelectedCellIndex()).getValue();
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
        var rev2=sgrid.cells(sgrid.getSelectedId(),2).getValue();
        var qty=sgrid.cells(sgrid.getSelectedId(),5).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),4).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==0){
            //sgrid.cells(sgrid.getSelectedId(),1).setValue('rev');//split(",",1));
            getDesc(rev,rowId)
        }else if(stage==2 && cellInd==2){
            getDesc2(rev2,rowId)
        }else if(stage==2 && cellInd==5){
            sgrid.cells(rowId,6).setValue(total);
            document.getElementById('total').value=sumColumn(6);
        }


        return true;
    }

    function roundNumber(num, dec) {
            var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
            return result;
    }

    function sumColumn(ind){
        var pmode=document.getElementById('paymode').value;
        if(pmode!='MCASH'){
            var out = 0;
            for(var i=0;i<sgrid.getRowsNum();i++){
                out+= parseFloat(sgrid.cells2(i,ind).getValue())
            }
            document.getElementById('total').value=roundNumber(out,2);
            return out;
        }else{
            var out = 0;
            for(var i=0;i<sgrid.getRowsNum();i++){
                out+= parseFloat(sgrid.cells2(i,ind).getValue())
            }
            document.getElementById('total').value=roundNumber(out,2);

             mpesa=parseFloat(document.getElementById('mpesa').value!=''? document.getElementById('mpesa').value:'0');
             visa=parseFloat(document.getElementById('visa').value!=''? document.getElementById('visa').value:'0');
             credit=parseFloat(document.getElementById('credit').value!=''? document.getElementById('credit').value:'0');

             out=mpesa+visa+credit;
             document.getElementById('paid').value=roundNumber(out,2);
             return out;
        }
    }


    function initRevPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 600, 300);
        w1.setText("revenue codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("catID,item_Cat,rev_code,rev_desc,amount");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("60,120,80,180,60");
        grid.loadXML("revconn_pop.php");
        // grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        grid.attachEvent("onRowDblClicked", doOnRowDoubleClicked); 
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
        sgrid.cells(sgrid.getSelectedId(),4).setValue(grid.cells(id,4).getValue());
        sgrid.cells(sgrid.getSelectedId(),5).setValue(1);
        sgrid.cells(sgrid.getSelectedId(),6).setValue(grid.cells(id,4).getValue());
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
        sgrid.selectRow(sgrid.getRowIndex(newId),false,false,true);

    }


    function initPharmPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 340, 300);

        w1.setText("Pharmacy codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("code,Test Desc,Price");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("80,180,60");
        grid.loadXML("pharmconn_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


    function initDrugPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 550, 300);
        w1.setText("Laboratory codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("category,class,code,desc,Price");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,100,80,180,60");
        grid.loadXML("labconn_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
         grid.attachEvent("onRowDblClicked", doOnRowDoubleClicked2); 
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function initPatientSearch(){
        var dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600,220, 600, 300);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Pid, first_Name,Surname,Last_name,EncounterNr,BillNo,EncounterClass");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,80,80,80");
        grid.loadXML("pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
//        grid.attachEvent("onRowDblClicked", doOnRowDoubleClicked);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


function doOnRowDoubleClicked2(id,ind){
        addRows(1);
        sgrid.cells(sgrid.getSelectedId(),0).setValue(grid.cells(id,0).getValue());
        sgrid.cells(sgrid.getSelectedId(),1).setValue(grid.cells(id,1).getValue());
        sgrid.cells(sgrid.getSelectedId(),2).setValue(grid.cells(id,2).getValue());
        sgrid.cells(sgrid.getSelectedId(),3).setValue(grid.cells(id,3).getValue());
        sgrid.cells(sgrid.getSelectedId(),4).setValue(grid.cells(id,4).getValue());
        
    }
    function initProcPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 340, 300);
        w1.setText("Procedure codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
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




    function doOnRowSelected3(id,ind){
        document.getElementById('patientId').value=+id;
        document.getElementById('patient_name').value=
            grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
            document.getElementById('encounter_nr').value=grid.cells(id,4).getValue()
            document.getElementById('payer').value=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
            document.getElementById('bill_number').value=grid.cells(id,5).getValue();
            document.getElementById('encounter_class_nr').value=grid.cells(id,6).getValue()
        getPatient(id);
    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('patientId').value=+rowId;
        closeWindow();
    }

    function closeWindow() {
        dhxWins.window("w1").close();
    }

    function getDesc2(rev2,rowId){
        xmlhttp6=GetXmlHttpObject();
        if (xmlhttp6==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="gridVals.php?rev="+rev2;
        url=url+"&sid="+Math.random();
        url=url+"&rowID="+rowId;
        xmlhttp6.onreadystatechange=stateChanged6;
        xmlhttp6.open("POST",url,true);
        xmlhttp6.send(null);
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
            str2=str.split(",");
        
            sgrid.cells(sgrid.getSelectedId(),1).setValue(str2[4]);
            sgrid.cells(sgrid.getSelectedId(),2).setValue(str2[3]);
            sgrid.cells(sgrid.getSelectedId(),3).setValue(str2[0]);
            sgrid.cells(sgrid.getSelectedId(),4).setValue(str2[1]);
        }
    }

    function stateChanged6()
    {
        //get payment description
        if (xmlhttp6.readyState==4)
        {
            var str3=xmlhttp6.responseText;
            str4=str3.split(",");
            sgrid.cells(sgrid.getSelectedId(),3).setValue(str4[0]);
            sgrid.cells(sgrid.getSelectedId(),4).setValue(str4[1]);

        }
    }
    //function splitString(stringToSplit,separator) {
    //  var arrayOfStrings = stringToSplit.split(separator);

    //for (var i=0; i < arrayOfStrings.length; i++) {
    //    sgrid.cells(arrayOfStrings[2],1).setValue(arrayOfStrings[0]);
    //    sgrid.cells(arrayOfStrings[2],4).setValue(arrayOfStrings[1]);
    //document.write(arrayOfStrings[i] + " / ");
    //  }
    //}

    //var tempestString = "Oh brave new world that has such people in it.";
    //var monthString = "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec";




</script>

<tr><td align="left" colspan="6">
        <input type="hidden" id="txtIDs" name="txtIDs">
        <button onclick=addRows(1) id="addrow">Add Row</button>
        <button onclick=initRevPop()>Get Revenue codes</button>
        <button onclick=initDrugPop()>Get Drug codes</button>
        <button onclick=deleteRow()>Delete Row</button>
    </td>
</tr></table>

