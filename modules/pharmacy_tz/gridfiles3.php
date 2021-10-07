<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("Item_ID,Description,Qty in store,Count Qty");
    sgrid.setInitWidths("80,200,120,120");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ed,ed");
    sgrid.setColSorting("str,str,int,int");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

var dhxWins, w1, grid;

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
function initDrugsSearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.enableAutoViewport(false);
        //dhxWins.attachViewportTo("winVP");
        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 400, 150, 450, 450);
        w1.setText("Products List");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Store,Item_ID,Description,Quantity at Hand");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,80,200,60,");
        grid.loadXML("stockCount_pop.php");
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
        sgrid.cells(sgrid.getSelectedId(),0).setValue(grid.cells(id,1).getValue());
        sgrid.cells(sgrid.getSelectedId(),1).setValue(grid.cells(id,2).getValue());
        sgrid.cells(sgrid.getSelectedId(),2).setValue(grid.cells(id,3).getValue());
       // sgrid.cells(sgrid.getSelectedId(),3).setValue(grid.cells(id,3).getValue());
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
    
    function previewStCount(){
        
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
