<script> //display datagrid
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("ledger,code,Name,Amount");
    sgrid.setInitWidths("80,80,250,80");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ro,ed");
    sgrid.setColValidators("NotEmpty,NotEmpty,NotEmpty,NotEmpty");
    sgrid.setColSorting("str,str,str,str");
    sgrid.setColumnColor("white,white,white");
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.enableKeyboardSupport(true);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    var dhxWins, w1, grid,xmlhttp5,xmlhttp6;

    function initRevPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 650, 200, 650, 300);
        w1.setText("revenue codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("rev_code,rev_desc,partcode,item_description,amount,GL,Account");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
        grid.setInitWidths("80,180,60,180,60,60,100");
        grid.loadXML("revconn_pop.php");
        grid.attachEvent("onEnter",doOnEnter2);
//        grid.enableDragAndDrop(true);
        grid.attachEvent("onRowDblClicked", doOnRowDoubleClicked);

        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowDoubleClicked(id,ind){
        addRows(1);

        // alert(grid.cells(id,5).getValue());

        document.getElementById('toward').value=grid.cells(id,3).getValue();
        sgrid.cells(sgrid.getSelectedId(),0).setValue("GL");
//        sgrid.cells(sgrid.getSelectedId(),1).setValue(grid.cells(id,5).getValue());
//        sgrid.cells(sgrid.getSelectedId(),2).setValue(grid.cells(id,6).getValue());
//        sgrid.cells(sgrid.getSelectedId(),3).setValue(grid.cells(id,4).getValue());

    }

  function myRowIds(){
        var rId='';
        for(var i=0;i<=sgrid.getRowsNum();i++){
            rId += parseFloat(sgrid.getRowId(i))+','; // get row id by its index
        }
    }

     function deleteRow(){
        sgrid.deleteSelectedItem();
    }
    
    function closeWindow() {
        parent.dhxWins.window("w1").close();
    }
    //function doOnCellEdit(){
    //  parent.dhxWins.window("w1").close();
    //}

    function doOnEnter(rowId,cellInd){
        var rev= sgrid.cells(sgrid.getSelectedId(),sgrid.getSelectedCellIndex()).getValue();
         protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        if(cellInd==0){
           initRevPop();}

    }

    function doOnRowSelected(id,ind){

        // var qty=grid.cells(grid.getSelectedId(),1).getValue();
        //        alert("User pressed Enter on row with id "+id+" and index "+ sgrid.cells(id,ind).getValue());
//        getGridRecords(document.getElementById("patientId").value)

    }

    function doOnCellEdit(stage,rowId,cellInd){
        var ledger=sgrid.cells(sgrid.getSelectedId(),0).getValue();
        var cellValue=sgrid.cells(sgrid.getSelectedId(),1).getValue();
        if(stage==2 && cellInd==0){
            if(ledger=="SUP" ||ledger=="sup"){
                getSuppliers();
            }else if(ledger=="DB" || ledger=="db"){
                getDebtors();
            }else if(ledger=="IP" || ledger=="ip"){
                getPatients();
            }else if(ledger=="GL" || ledger=="gl"){
                getGL();
            }
        }else if(stage==2 && cellInd==1){
            if(ledger=="SUP"){
                getSupplierDesc(cellValue);
            }else if(ledger=="GL"){
                getGLDesc(cellValue)
            }else if(ledger=="IP"){
                getPatientDesc(cellValue)
            }else if(ledger=="DB"){
                getDebtorDesc(cellValue)
            }
        }else if(stage==2 && cellInd==2){
              sgrid.cells(sgrid.getSelectedId(),3).setValue("0");
        }else if(stage==2 && cellInd==3){
               sumColumn(3);
        }

        return true;
    }
    
    function getSupplierDesc(sup){
    xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?suplierid="+sup;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=Suppliers";
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }
    
    function getGLDesc(gl){
    xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?GL="+gl;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=GL";
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }
    
    function getPatientDesc(pid){
    xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?PID="+pid;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=Patients";
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }
    
    function getDebtorDesc(str){
    xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?accNo="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=debtors";
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }
    
    function stateChanged()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");
        
            sgrid.cells(sgrid.getSelectedId(),2).setValue(str2[0]);
            document.getElementById('pid').value=sgrid.cells(sgrid.getSelectedId(),1).getValue();
            document.getElementById('pname').value=sgrid.cells(sgrid.getSelectedId(),2).getValue();

             //document.getElementById('payee').value=sgrid.cells(sgrid.getSelectedId(),2).getValue();
          
        }
    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        document.getElementById('total').value=out;
        return out;
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
        sgrid.selectRow(grid.getRowIndex(newId),false,false,true);

    }


   
    function initLabPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 340, 300);
        w1.setText("Laboratory codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
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

function getDebtors(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 500, 320);
        w1.setText("Debtors Codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("accno,name,category,os_bal,last_trans");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,180,60,80,80");
        grid.loadXML("debtor_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected5);
        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


function getLedgers(){
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
        grid.setHeader("Code,Description");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro");
        grid.setInitWidths("80,180");
        grid.loadXML("ledger_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected4);
        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


function getSuppliers(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 700, 220, 340, 300);
        w1.setText("Suppliers");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("SupplierID,Supplier");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro");
        grid.setInitWidths("80,180");
        grid.loadXML("supplier_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected4);
//        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
//         grid.enableDragAndDrop(true);
        grid.init();
        grid.enableSmartRendering(true);
    }
    
function getPatients(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 700, 220, 560, 320);
        w1.setText("Patients");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("PID,firstName,lastName,Surname,encounter_date,encounter_nr");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro");
        grid.setInitWidths("80,100,100,100,100,100");
        grid.loadXML("patient_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected5);
//        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
//         grid.enableDragAndDrop(true);
        grid.init();
        grid.enableSmartRendering(true);
    }

function getGL(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 700, 220, 340, 300);
        w1.setText("General Ledger");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("AccountCode,AccountName");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro");
        grid.setInitWidths("80,180");
        grid.loadXML("gl_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected4);
//        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
//         grid.enableDragAndDrop(true);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowSelected5(id,ind){
         str= grid.cells(id,1).getValue();//+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
         sgrid.cells(sgrid.getSelectedId(),1).setValue(id);
         sgrid.cells(sgrid.getSelectedId(),2).setValue(str);
         document.csale.pid.value=id;
         document.csale.pname.value=str;
    }

    function doOnRowSelected4(id,ind){
        str= grid.cells(id,1).getValue();
         sgrid.cells(sgrid.getSelectedId(),1).setValue(id);
         sgrid.cells(sgrid.getSelectedId(),2).setValue(str);
         
           document.csale.pid.value=id;
         document.csale.pname.value=str;
    }

    function doOnEnter4(rowId,cellInd){

        closeWindow();
    }

    

    

    function doOnRowSelected3(id,ind){
        document.getElementById('patientId').value=+id;
        document.getElementById('patient_name').value=
            grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
        getPatient(id);
    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('patientId').value=+rowId;
        closeWindow();
    }

    function closeWindow() {
        dhxWins.window("w1").close();
    }

    




</script>

<tr><td align="left" colspan="6">
        <input type="hidden" id="txtIDs" name="txtIDs">
        <button onclick=addRows(1) id="addrow">Add Row</button>
        <button onclick=initRevPop()>Get Revenue codes</button>
        <button onclick=initLabPop()>Get Lab codes</button>
        <button onclick=getSuppliers()>Get Suppliers</button>
        <button onclick=getLedgers()>Get Ledger Accounts</button>
        <button onclick=deleteRow()>Delete Row</button>
    </td>
</tr></table>

