/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var mygrid;
var gridQString = "";

function addRow(){
    var newId = (new Date()).valueOf()
    mygrid.addRow(newId,"",mygrid.getRowsNum())
    mygrid.selectRow(mygrid.getRowIndex(newId),false,false,true);
}
function removeRow(){
    var selId = mygrid.getSelectedId()
    mygrid.deleteRow(selId);
}

function doOnRowSelected(rowID,celInd){
    alert("Selected row ID is "+rowID+"\nUser clicked cell with index "+celInd);

}

function applyFilter(){
    mygrid.clearAll(); //remove all data
    gridQString = "getGridRecords.php?name_mask="+document.getElementById("nm_mask").value;//save query string in global variable (see step 5 for details)
    mygrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed
}

function sortGridOnServer(ind,gridObj,direct){
    mygrid.clearAll();
    mygrid.loadXML(gridQString+(gridQString.indexOf("?")>=0?"&":"?")+"orderby="+ind+"&direct="+direct);
    mygrid.setSortImgState(true,ind,direct);
    return false;
}

