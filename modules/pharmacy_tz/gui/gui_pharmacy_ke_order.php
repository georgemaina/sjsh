<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>
<HEAD>
	<TITLE> <?php echo $LDBillingInsurance; ?></TITLE>
	<meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
	<meta name="Author" content="Timo Hasselwander, Robert Meggle">
	<meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
<link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
<script language="javascript" src="../../js/hilitebu.js"></script>

<STYLE TYPE="text/css">
A:link  {color: #000066;}
A:hover {color: #cc0033;}
A:active {color: #cc0000;}
A:visited {color: #000066;}
A:visited:active {color: #cc0000;}
A:visited:hover {color: #cc0033;}
</style>
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

<link rel="StyleSheet" href="dtree.css" type="text/css" />
<script type="text/javascript" src="dtree.js"></script>

<script type="text/javascript">
<?php
		require($root_path.'include/inc_checkdate_lang.php');
                
?>
</script>
<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>


</HEAD>
<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066>
    <table width=100% border=0 cellspacing=0 height=100%>
        <tr><td  valign="top" align="middle">
    <table cellspacing="0"  class="titlebar" border=0>
          <tr valign=top  class="titlebar" >
            <td bgcolor="#99ccff" >
                &nbsp;&nbsp;<font color="#330066"><?php echo $LDOrderProctuctsForm; ?></font>
             </td>
            <td bgcolor="#99ccff" align=right><a
               href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style='filter:alpha(opacity=70)' onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
               href="javascript:gethelp('insurance_companies_overview.php','Administrative Companies :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
               href="pharmacy_tz_pass.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
         </tr>
 </table>		
        </td></tr>
        <tr><td valign="top" align="center">
                

                <table border="1" width="70%">
                <tbody>
                    <form action="" method="POST">
                    <tr>
                        <td>Order Type</td>
                        <td colspan="3">Transfere<input type="radio"  id="orderType" name="orderType" value="" />
                        Consumption<input type="radio" id="orderType" name="orderType" value="" /></td>
                        
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><input type="text" name="ordDate" value="" /></td>
                        <td>Requision No:</td>
                        <td><input type="text" name="ordIrnNo" value="" /></td>
                    </tr>
                    <tr>
                        <td>Ordering Store</td>
                        <td colspan="3"><input type="text" id="storeID" name="storeID" value="" />
                        <input type="text" id="storeDesc" name="storeDesc" value="" /></td>
                        
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>Regular<input type="radio" id="status" name="status" value="" />
                        Consumption<input type="radio" id="status" name="status" value="" />
                        Emergency<input type="radio" id="status" name="status" value="" /></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Supplying Store</td>
                        <td colspan="3"><input type="text" id="supStoreid" name="supStoreid" value="" />
                            <input type="text" id="supStoredesc" name="supStoredesc" value="" /></td>
                       
                    </tr>
                    <tr>
                        <td colspan="4">
                           
                            <div id="gridbox" height="200px" style="background-color:white;"></div>
                             <input type="hidden" id="txtIDs" name="txtIDs">
                            
                        </td>
                      
                    </tr>
                    <tr>
                        <td>Total Items Ordered</td>
                        <td><input type="text" name="totalOrders" value="" /></td>
                        <td colspan="2" align="right">
                            <input type="submit"  id="submit" name="submit" value="Send" />
                            <input type="submit" value="Cancel" />

                         </td>
                    </tr>
         </form>
                    <tr><td colspan="4" align="left">
                            <button onclick=addRows(1)>Delete</button>
                            <button onclick=deleteRow()>Delete</button>
                            <button onclick=initPSearch()>Get Products List</button></td>
                        
                       
                    </tr>
                </tbody>
            </table>
            
        </td>
    </tr>
</table>


</BODY>
</HTML>
<script>
    //display datagrid
     var dhxWins, w1, grid,sgrid;
    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("Item ID,Description,Qty in store,Qty to order");
    sgrid.setInitWidths("80,200,120,120");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ro,ed");
    sgrid.setColSorting("str,str,str,int");
    sgrid.setColumnColor("white,grey,grey");
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
        sgrid.selectRow(mygrid.getRowIndex(newId),false,false,true);
         
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
