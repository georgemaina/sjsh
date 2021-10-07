<link rel="stylesheet" type="text/css" href="../accounting.css">
<table class="style2">
      <tbody>
        <tr>
            <td colspan=4 class=pgtitle>Billing Reports:</td>
        <tr>
          <td align=center><img src="../../../gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
          <td><a href="detail_invoice.php?caller=Detail">Patient Invoice Detail</a></td>

        </tr>
        <tr>
          <td align=center><img src="../../../gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
          <td><a href="summary_invoice.php?caller=Summary">Patient Invoice Summary</a></td>
        </tr>
        <tr>
          <td align=center><img src="../../../gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
          <td><a href="Pending_Invoices.php?caller=pending">Pending Invoice</a></td>
        </tr>
       <tr>
          <td align=center><img src="../../../gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
          <td class="submenu_item"><a href="reporting_weberp.php">Finalize Invoice</a></td>
        </tr>
    </tbody>
</table>


<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../../cashbook/codebase/dhtmlxgrid.css'>
<script src='../../cashbook/codebase/dhtmlxcommon.js'></script>
<script src='../../cashbook/codebase/dhtmlxgrid.js'></script>
<script src='../../cashbook/codebase/dhtmlxgrid_form.js'></script>
<script src='../../cashbook/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../cashbook/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../cashbook/codebase/dhtmlxgridcell.js'></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../../include/dhtmlxConnector/codebase/connector.js'></script>



<script>
var dhxWins, w1, grid;
function initPsearch(){
        dhxWins = new dhtmlXWindows();
        
        dhxWins.setImagePath("../../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 600, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../../cashbook/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80");
        grid.loadXML("pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


 function doOnRowSelected3(id,ind){
        document.getElementById('pid').value=+id;

    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('pid').value=+rowId;
        closeWindow();
    }

    function closeWindow() {
        dhxWins.window("w1").close();
    }

     function getPatient(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?desc3="+str;
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChanged3;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

    function stateChanged3()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
        var str2=str.search(/,/)+1
        document.getElementById('pname').value=str //.split(",",1);
        applyFilter();

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


