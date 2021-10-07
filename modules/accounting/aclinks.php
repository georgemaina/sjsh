<?php require_once 'roots.php'; 

?>

<table>

    <tbody class="submenu">

        <tr  class="submenu_title">
            <td colspan="2">Activities</td>
        </tr>
        <tr class="tr1">
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=Debit">Debit Patient</a></td>
        </tr>
<!--        <tr class="tr1">-->
<!--            <td colspan="2" class="submenu_item"><a href="--><?php //echo $root_path ?><!--modules/accounting/debit/app.html\">New Debit Patient</a></td>-->
<!--        </tr>-->
        <tr class="tr1">
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=Credit">Credit Patient</a></td>
        </tr>
        <tr class="tr1">
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=BedCharge">Daily Bed Charge</a></td>
        </tr>
        <tr class="tr1">
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=FinaliseInvoice">Finalise Invoice</a></td>
        </tr>
<!--        <tr class="tr1">-->
<!--            <td colspan="2" class="submenu_item"><a href="--><?php //echo $root_path ?><!--modules/accounting/finaliseinvoice1.php">Invoice Listing</a></td>-->
<!--        </tr>-->
        <tr class="tr1">
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=NhifCredit">NHIF Credit</a></td>
        </tr>
<!--        <tr class="tr1">-->
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/insuranceCredit.php">Insurance Credit</a></td>
<!--        </tr>-->
        <tr>
            <td colspan="2"  class="submenu_title">Billing Reports:</td>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=DetailInvoice">Interim Invoice Detail</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=SummaryInvoice&final=0">interim Invoice Summary</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=FinalDetail">Reprint Final Invoice Detail</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=FinalSummary">Reprint Final Invoice Summary</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=PendingInvoices">Pending Invoice</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=NhifCreditReport">NHIF Credits</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=WardProcReport">Inpatient Ward Procedure Report</a></td>
        </tr>
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item"><a href="<?php echo $root_path ?>modules/accounting/inpatientAccounting_pass.php?target=WardOccupancy">Bed Occupancy</a></td>
        </tr>
<!--        <tr>-->
<!--            <td colspan="2"  class="submenu_title">Debtor Reports:</td>-->
<!--        <tr class="tr1">-->
<!--        <tr class="tr1">-->
<!--            <td align=center class="submenu_item"><img src="--><?php //echo $root_path ?><!--gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>-->
<!--            <td class="submenu_item"><a href="--><?php //echo $root_path ?><!--modules/accounting/reports/wardOccupancy.php?caller=pending">Invoice Statements</a></td>-->
<!--        </tr>-->
<!--        <tr class="tr1">-->
<!--            <td align=center class="submenu_item"><img src="--><?php //echo $root_path ?><!--gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>-->
<!--            <td class="submenu_item"><a href="--><?php //echo $root_path ?><!--modules/accounting/reports/wardOccupancy.php?caller=pending">Detailed Invoices</a></td>-->
<!--        </tr>-->
        <tr class="tr1">
            <td align=center class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" border=0 width="17" height="17"></td>
            <td class="submenu_item">
                <input type="button" id="show-btn" value="Update Dates" />
            </td>
        </tr>
    </tbody>
</table>
<div id="hello-win" align="center"></div>     





<script>
    var dhxWins, w1, grid;
    function initPsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 300, 100, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../cashbook/codebase/imgs/");
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
           
            getBillNumber(document.getElementById('pid').value)

        }
    }
    
    
function getBillNumber(str){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../getDesc.php?pid="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getBillNumbers";
        xmlhttp10.onreadystatechange=stateChangedPid;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
    }
    
    function stateChangedPid()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
    
            document.getElementById('billNumbers').innerHTML=str;

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
    
//    function getPatient(str)
//    {
//        xmlhttp3=GetXmlHttpObject();
//        if (xmlhttp3==null)
//        {
//            alert ("Browser does not support HTTP Request");
//            return;
//        }
//        var url="cashbookFns.php?desc3="+str;
//        url=url+"&sid="+Math.random();
//        xmlhttp3.onreadystatechange=stateChanged3;
//        xmlhttp3.open("POST",url,true);
//        xmlhttp3.send(null);
//
//    }
//
//    function stateChanged3()
//    {
//        //get payment description
//        if (xmlhttp3.readyState==4)//show point desc
//        {
//            var str=xmlhttp3.responseText;
//            var str2=str.search(/,/)+1
//            document.getElementById('pname').value=str //.split(",",1);
//            //        applyFilter();
//            getBillNumbers(document.getElementById('pid').value)
//
//        }
//    }
    
    Ext.onReady(function(){
        var win;
        var button = Ext.get('show-btn');

        button.on('click', function(){
        
            var admForm = new Ext.FormPanel({
                url:'updateAdmissionDate.php',
                height: 326,
                width: 542,
                layout: 'absolute',
                bodyPadding: 10,
                frame:true,
                items: [
                    {
                        xtype: 'textfield',
                        id: 'PID',
                        fieldLabel: 'PID',
                        labelAlign: 'right',
                        name: 'PID'
                    },
                    {
                        xtype: 'textfield',
                        y: 35,
                        id: 'encNo',
                        fieldLabel: 'Encounter No',
                        labelAlign: 'right',
                        name: 'encNo'
                    },
                    {
                        xtype: 'datefield',
                        y: 60,
                        fieldLabel: 'Admission Date',
                        labelAlign: 'right',
                        name: 'admDate'
                    },
                    {
                        xtype: 'datefield',
                        y: 85,
                        id: 'disDate',
                        fieldLabel: 'Discharge Date',
                        labelAlign: 'right',
                        name: 'disDate'
                    }
                ],
                buttons: [{
                        text: 'Search', handler: function() {
                            var pid = admForm.getForm().findField("PID").getValue();
                             getEncounterNo(pid);
                        }
                    },{
                        text: 'Save', handler: function() {
                            var pid = admForm.getForm().findField("PID").getValue();
                            var encNo=admForm.getForm().findField('encNo').getValue();
                            var admDate=admForm.getForm().findField('admDate').getValue();
                            var disDate=admForm.getForm().findField('disDate').getValue();
                            Ext.Ajax.request({
                                url: 'updateAdmDates.php',
                                method: 'POST',
                                params: {
                                    pid:pid,
                                    encNo:encNo,
                                    admDate:admDate,
                                    disDate:disDate,
                                    task:"updateAdm"
                                },
                                waitMsg:'Saving Data...',
                                success: function (form, action) {
                                    win.hide();
                                },
                                failure:function(form, action) {
                                    Ext.MessageBox.alert('Message', 'Save failed, Check that all values are OK ');
                                }
                            });

                        }
                    },{
                        text: 'Cancel', handler: function() {
                            win.hide();
                        }
                    }]
            });
            // create the window on the first click and reuse on subsequent clicks
            if(!win){
                win = new Ext.Window({
                    applyTo:'hello-win',
                    layout:'fit',
                    width:350,
                    height:200,
                    closeAction:'hide',
                    title: 'Update Admission and Discharge Dates',
                    plain: true,
                    items: [admForm]

                });
            }
            win.show(this);
            //            admForm.getForm().findField("pid").setValue("<?php echo $pid ?>");
            //            admForm.getForm().findField("encNo").setValue("<?php echo $encounter_nr ?>");
        });
    });
    
    function getEncounterNo(str){
//        alert('test');
         xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?caller=admDate";
        url=url+"&pid="+str;
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChangedAdm;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
    }
    
    function stateChangedAdm()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
             str2=str.split(",");

              Ext.getCmp("admForm").getForm().findField("encNo").setValue(str2[0]);
//            document.getElementById('encNo').value=str2[0]; //.split(",",1);


        }
    }
</script>




