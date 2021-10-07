<script>


    sgrid = new dhtmlXGridObject('gridbox2');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("date,time,req_no,store,store_desc,sup_storeid,sup_storedesc,status,input_user");
    sgrid.setInitWidths("80,80,120,120,200,120,120,120,120");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ed,ed,ed,ed,ed,ed,ed");
    sgrid.setColSorting("str,str,str,str,str,str,str,str,str");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
//    sgrid.loadXML("ordersconn.php");
    sgrid.attachEvent("onRowSelect",doOnRowSelected);
    sgrid.init();
    sgrid.enableSmartRendering(true);
//    myDp=new dataProcessor('');
//    sgrid.submitOnlyChanged(false);


    kgrid = new dhtmlXGridObject('gridbox');
    kgrid.setImagePath('codebase/imgs/');
    kgrid.setHeader("item_id,Item_desc,qty,price,qty_issued,Balance,Qty_In_Store");
    kgrid.setInitWidths("80,250,80,120,120,120,120");
    kgrid.setSkin("light");
    kgrid.setColTypes("ed,ed,ed,ed,ed,ed,ro");
    kgrid.setColSorting("str,str,str,str,str,str,str");
    kgrid.setColumnColor("white,white,white");
    kgrid.attachEvent("onEditCell",doOnCellEdit);
//    kgrid.enableDragAndDrop(true);
    kgrid.enableKeyboardSupport(true);
//    grid.loadXML("servConn.php");
    kgrid.init();
    kgrid.enableSmartRendering(true);

    myDp=new dataProcessor('');
    kgrid.submitOnlyChanged(false);

   var dhxWins, w1, grid;
    function doOnRowSelected(id,ind){
        
        var req_no=sgrid.cells(id,2).getValue();
        var sup_store=sgrid.cells(id,5).getValue();
        document.getElementById("ordIrnNo").value=sgrid.cells(id,2).getValue();
        document.getElementById("ordDate").value=sgrid.cells(id,0).getValue();
        document.getElementById("storeID").value=sgrid.cells(id,3).getValue();
        document.getElementById("storeDesc").value=sgrid.cells(id,4).getValue();
        document.getElementById("supStoreid").value=sgrid.cells(id,5).getValue();
        document.getElementById("supStoredesc").value=sgrid.cells(id,6).getValue();
        document.getElementById("status").value=sgrid.cells(id,7).getValue();
        applyFilter(req_no,sup_store);
    }

function getsOrder(suppStore){
       sgrid.clearAll(); //remove all data
        gridQString = "ordersconn2.php?supstore="+suppStore;//document.getElementById("ordIrnNo").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        sgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

         var myDp=new dataProcessor(gridQString);
         myDp.init(sgrid);
    }


    function applyFilter(req_no,sup_store){
       kgrid.clearAll(); //remove all data
        gridQString = "IRConn.php?reqno="+req_no+"&sup_store="+sup_store;//document.getElementById("ordIrnNo").value;//save query string in global variable (see step 5 for details)

        //getGridRecords(document.getElementById("patientId").value)
        kgrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

         var myDp=new dataProcessor(gridQString);
         myDp.init(kgrid);
    }

   function doOnCellEdit(stage,rowId,cellInd){

        var qty=kgrid.cells(kgrid.getSelectedId(),2).getValue();
        var qtyServ=kgrid.cells(kgrid.getSelectedId(),4).getValue();
        var total=qty-qtyServ;
        if(stage==2 && cellInd==4){
            kgrid.cells(rowId,5).setValue(total);
        }


        return true;
    }

    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            grid.addRow(newId,"",grid.getRowsNum())
        }
        grid.selectRow(grid.getRowIndex(newId),false,false,true);

    }

    function deleteRow(){
        sgrid.deleteSelectedItem();
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
        grid.setHeader("Item_ID,Description,qty,Reorder Level");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ed,ro");
        grid.setInitWidths("80,180,60,60");
        grid.loadXML("revconn_pop.php");
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
//        alert(grid.cells2(rowId,cellInd).getValue());
//
    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
    }



    




</script>

