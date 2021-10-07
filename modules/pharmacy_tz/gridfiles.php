<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("Item_ID,Description,purchasing_unit,Qty_in_store,Qty_to_order");
    sgrid.setInitWidths("80,200,120,120,120");
    sgrid.setSkin("light");
    sgrid.setColTypes("ro,ro,ro,ro,ed,ed");
   // sgrid.setColValidators("NotEmpty,NotEmpty,NotEmpty,NotEmpty,NotEmpty");
    sgrid.setColSorting("str,str,str,str,int");
    sgrid.setColumnColor("white,white,white");
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.enableDragAndDrop(true);
    sgrid.enableKeyboardSupport(true);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

var dhxWins, w1, grid;

   function doOnCellEdit(stage,rowId,cellInd){

        var qty=sgrid.cells(sgrid.getSelectedId(),4).getValue();

        if(stage==2 && cellInd==4) {
            //sgrid.cells(rowId,6).setValue(total);
            document.getElementById('totalOrders').value = sumColumn(4);
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
function initPSearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        store=document.getElementById('supStoreid').value;

        w1 = dhxWins.createWindow("w1", 400, 150, 600, 450);
        w1.setText("Products List");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Item_ID,Description,purchasing_unit,qty,Reorder Level");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ed,ro");
        grid.setInitWidths("80,180,100,60,60");
        grid.loadXML("revconn_pop.php?store="+store);
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter2);
        grid.enableDragAndDrop(true);
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
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
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
