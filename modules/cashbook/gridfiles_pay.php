
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
            }else if(ledger=="GL" || ledger=="gl" || ledger=='PC' || ledger=='pc'){
                getGL();
            }
           // document.getElementById('ledger').value=ledger
        }else if(stage==2 && cellInd==1){
            if(ledger=="SUP"){
                getSupplierDesc(cellValue);
            }else if(ledger=="GL" || ledger=="gl"){
                getGLDesc(cellValue)
            }else if(ledger=="PC" || ledger=="pc"){
                getGLDesc(cellValue)
            }else if(ledger=="IP" || ledger=="ip"){
                getPatientDesc(cellValue)
            }else if(ledger=="DB" || ledger=="db"){
                getDebtorDesc(cellValue)
            }
            document.getElementById('ledger').value=ledger
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
    
    function stateChanged()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");
        
            sgrid.cells(sgrid.getSelectedId(),2).setValue(str2[0]);
          
        }
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

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        document.getElementById('total').value=out;
        return out;
    }


    function initRevPop(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 340, 300);
        w1.setText("revenue codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
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
        //        sgrid.selectRow(grid.getRowIndex(newId),false,false,true);

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

    function getPatients(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 700, 220, 340, 300);
        w1.setText("Patients");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("PID,firstName,lastName,Surname");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,100,100,100");
        grid.loadXML("patient_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected5);
        //        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
        //         grid.enableDragAndDrop(true);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowSelected5(id,ind){
        str= grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();
        sgrid.cells(sgrid.getSelectedId(),1).setValue(id);
        sgrid.cells(sgrid.getSelectedId(),2).setValue(str);
    }

    function doOnRowSelected4(id,ind){
        str= grid.cells(id,1).getValue();

        if( document.getElementById('gl_acc').value==''){
            document.getElementById('gl_acc').value=id;
            document.getElementById('gl_Desc').value=str;
        }
        sgrid.cells(sgrid.getSelectedId(),1).setValue(id);
        sgrid.cells(sgrid.getSelectedId(),2).setValue(str);
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
    
   
    

    Ext.onReady(function(){
        var win;
        var button = Ext.get('show-btn');
        button.on('click', function(){
             
            var chequeStore=new Ext.data.JsonStore({
                url: 'getCheques.php',
                root: 'unpaidCheques',
                id: 'ID',//
                baseParams:{
                    task: "getCheques"
                },//This
                fields:['ID','Ledger','cash_point','Voucher_No','Pay_mode','pdate','cheque_no','payee','Total']
            });

            chequeStore.load();
            var sm2 = new Ext.grid.CheckboxSelectionModel({
                width:18
//                listeners: {
//                    // On selection change, set enabled state of the removeButton
//                    // which was placed into the GridPanel using the ref config
//                    selectionchange: function(sm) {
//                        if (sm.getCount()) {
//                            allocPanel.removeButton.enable();
//                        } else {
//                            allocPanel.removeButton.disable();
//                        }
//                    }
//                }
            });
            
            var chequesColModel=function(finish,start){
                var  columns= [
                    sm2,
                    {header: "ID",width:40,dataIndex: 'ID',sortable: true, hidden:false},
                    {header: "pdate",width: 120,dataIndex: 'pdate',sortable: true},
                    {header: "Ledger",width:40,dataIndex: 'Ledger',sortable: true, hidden:false},
                    {header: "cash_point",width: 60,dataIndex: 'cash_point',sortable: true},
                    {header: "Voucher_No",width: 80,dataIndex: 'Voucher_No',sortable: true},
                    {header: "Pay_mode",width: 80,dataIndex: 'Pay_mode',sortable: true},
                    {header: "cheque_no",width: 80,dataIndex: 'cheque_no',sortable: true},
                    {header: "Total",width: 120,dataIndex: 'Total',sortable: true},
                    {header: "payee",width: 120,dataIndex: 'payee',sortable: true}
                ];
            return new Ext.grid.ColumnModel({
                columns: columns.slice(start || 0, finish),
                defaults: {
                    sortable: true
                }
            });
        }
            
            var chequeDetails = new Ext.grid.EditorGridPanel({
                id:'chequeDetails',
                store: chequeStore,
                colModel: chequesColModel(10),
                width:700,
                height:250,
                clicksToEdit:2,
                sm:sm2,
                stripeRows: true,
                dockedItems: [
                    {
                        xtype: 'pagingtoolbar',
                        dock: 'top',
                        width: 360,
                        displayInfo: true,
                        store: 'chequeStore'
                    }
                ],
                getSelections : function(){
                    var myselect= [].concat(this.selections.items);
                    return myselect;
//                    Ext.MessageBox.alert('selected',[].concat(this.selections.items))
                },buttons: [{
                        text: 'Print Cheque', handler: function() {
                           var cheqID = '';
                           var selectedKeys=chequeDetails.selModel.selections.keys;
                            Ext.each(selectedKeys, function(node){
                            if(cheqID.length > 0){
                                cheqID +=cheqID + ', ';
                            }
                            cheqID = selectedKeys;
                            });

                            printCheque(cheqID);

                        }
                    }
                    ,{
                        text: 'Print Voucher', handler: function() {
                            var cheqID = '';
                            var selectedKeys=chequeDetails.selModel.getSelections();
                            var result = '';
                            var ID = '';
                            Ext.each(selectedKeys, function (record) {
                               result =record.get('Ledger');
                                ID = selectedKeys;
                               if(ID.length>1){
                                    cheqID += record.get('ID') + ',';
                                }else{
                                    cheqID = record.get('ID');
                                }
                               
                            });
                           // Ext.Msg.alert('Test', cheqID);

                            if(result==='PC' || result==="pc"){
                                displayPettyVoucher(cheqID);
                            }else{
                                displayVoucher(cheqID);
                            }

                        }
                    } ,{
                        text: 'Print Liquisition', handler: function() {
                            var cheqID = '';
                            var selectedKeys=chequeDetails.selModel.selections.keys;
//                            seletionModel = chequeDetails.getSelectionModel();
//                            selectedRecords = selectionModel.getSelection();
//                            myValue = selectedRecords[0].get('Ledger')

                            Ext.each(selectedKeys, function(node){
                                if(cheqID.length > 0){
                                    cheqID += cheqID +',';
                                }
                                cheqID = selectedKeys;
                            });
                            displayLiquisition(cheqID);

                        }
                    } ,{
                        text: 'Print Cheque Voucher', handler: function() {
                            var cheqID = '';
                            var selectedKeys=chequeDetails.selModel.selections.keys;
//                            seletionModel = chequeDetails.getSelectionModel();
//                            selectedRecords = selectionModel.getSelection();
//                            myValue = selectedRecords[0].get('Ledger')

                            Ext.each(selectedKeys, function(node){
                                if(cheqID.length > 0){
                                    cheqID += cheqID +', ';
                                }
                                cheqID = selectedKeys;
                            });

                            displayCheques(cheqID);

                        }
                    },{
                        text: 'Done', handler: function() {
                            var cheqID = '';
                            var selectedKeys=chequeDetails.selModel.selections.keys;
                            Ext.each(selectedKeys, function(node){
                                if(cheqID.length > 0){
                                    cheqID += cheqID +',';
                                }
                                cheqID = selectedKeys;
                            });

                            Ext.Msg.show({
                                title:'Close Payments?',
                                msg: 'Are you sure you want to Close Payments ',
                                buttons: Ext.Msg.YESNOCANCEL,
                                icon: Ext.Msg.QUESTION,
                                fn: function(rec) {
                                    if (rec === "yes") {
                                        Ext.Ajax.request({
                                            url: 'cashbookFns.php?callerID=closePayments&ids='+cheqID,
                                            params: {
                                                ID:cheqID
                                            },
                                            waitMsg: 'Closing payments ...',
                                            success: function(response){
                                                var text = response.responseText;
                                                Ext.Msg.alert('Delete','Payment Successfully Closed');
                                                var chequeStore=Ext.data.StoreManager.lookup('chequeStore');
                                                chequeStore.load({});

                                            },
                                            failure:function(response){
                                                var resp = JSON.parseJSON(response);
                                                Ext.Msg.alert('Error','There was a problem closing the Payments, Contact System Administrator');
                                            }
                                        });

                                    }
                                }
                            });

                        }
                    },{
                        text: 'Close', handler: function() {
                            win.hide();
                          
                          
                        }
                    }]
            });
            
            function refreshGrid(){
                chequeStore.reload();
            }
             
//            var admForm = new Ext.FormPanel({
//                labelWidth: 75, // label settings here cascade unless overridden
//                url:'getCheques.php',
//                frame:true,
//                title: 'Print Cheque/Voucher',
//                bodyStyle:'padding:5px 5px 0',
//                width: 600,
//                record: null,
//                layout: 'column',
//                items:[chequeDetails]
//
//            });
            // create the window on the first click and reuse on subsequent clicks
            if(!win){
                win = new Ext.Window({
                    applyTo:'hello-win',
                    layout:'fit',
                    title: 'Payment Voucher and Cheques to Print',
                    closeAction:'hide',
                    plain: true,
                    items: [chequeDetails]
                });
            }
            win.show(this);

            //        });
        })   
    })


    function displayPettyVoucher(cheqID) {
        //      alert(CashPoint+''+payMode+' '+voucherNo);
        window.open('reports/petty_cash.php?cheqID='+cheqID
            ,"receipt","menubar=yes,toolbar=no,width=600,height=550,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

    function displayVoucher(cheqID) {
        //      alert(CashPoint+''+payMode+' '+voucherNo);
        window.open('reports/payment_voucher.php?cheqID='+cheqID
        ,"receipt","menubar=yes,toolbar=no,width=600,height=550,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

    function displayLiquisition(cheqID) {
        //      alert(CashPoint+''+payMode+' '+voucherNo);
         window.open('reports/RequisitionForm.php?cheqID='+cheqID
            ,"receipt","menubar=yes,toolbar=no,width=600,height=550,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

    function displayCheques(cheqID) {
        window.open('reports/ChequeVoucher.php?cheqID='+cheqID
            ,"receipt","menubar=yes,toolbar=no,width=600,height=550,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }


    function printCheque(cheqID ) {   
        window.open('reports/paymentCheques.php?cheqID='+cheqID
        ,"receipt","menubar=no,toolbar=no,width=600,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

    function printChequePetty(cheqID ) {
        window.open('reports/payment_cheque.php?cheqID='+cheqID
            ,"receipt","menubar=no,toolbar=no,width=600,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
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
        <input type="button"  id="show-btn" value="Print Vouchers and Cheques" />
    </td>
</tr></table>

