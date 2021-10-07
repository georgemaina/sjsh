<script>
    //display datagrid
     
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("stockid,item_description,quantity in Store,reorderlevel,unit_price");
    sgrid.setInitWidths("100,300,120,120,120");
    sgrid.setSkin("light");
    sgrid.setColSorting("str,str,int,int,int");
    sgrid.setColTypes("ed,ro,ro,ro,ro");
    sgrid.attachHeader("#connector_text_filter,#connector_text_filter");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.loadXML("levconn_pop.php");
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

var dhxWins, w1, grid;

   

    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            sgrid.addRow(newId,"",sgrid.getRowsNum())
        }
        sgrid.selectRow(mygrid.getRowIndex(newId),false,false,true);

    }

    function deleteRow(){
        sgrid.deleteSelectedItem();
    }




</script>
