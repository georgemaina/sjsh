<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("item_number,Description,price,qty,total");
    sgrid.setInitWidths("80,270,100,60,100");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ed,ed,ed");
    sgrid.setColSorting("str,str,str,str,str");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.init();
    sgrid.enableSmartRendering(true);
//    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

var dhxWins, w1, grid;

   function doOnCellEdit(stage,rowId,cellInd){
        var qty=sgrid.cells(sgrid.getSelectedId(),3).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),2).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==3){
            sgrid.cells(rowId,4).setValue(total);
            document.getElementById('total').value=sumColumn(4);
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

        w1 = dhxWins.createWindow("w1", 400, 150, 300, 450);
        w1.setText("Products List");
        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("partcode,purchasing_class,item_description,unit_price");
        grid.setInitWidths("80,150,200,60");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setColSorting("str,str,str");
        grid.setColumnColor("white,white,white");
        grid.loadXML("debit_conn.php");
//         grid.attachEvent("onRowSelect",doOnRowSelected);
//        grid.attachEvent("onEnter",doOnEnter2);
//        grid.enableDragAndDrop(true);

        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
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

