<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="../accounting.css">
<!--<link rel="stylesheet" type="text/css" href="../../include/Extjs/resources/css/ext-all.css" />
<script type="text/javascript" src="../../include/Extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../include/Extjs/ext-all.js"></script>-->

<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');




    require('roots.php');
    global $db;

echo "<table width=100% border=1>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>
        Ward Occupancy</font></td></tr>
    <tr><td align=left valign=top width=15%>";
require($root_path."modules/accounting/aclinks.php");
echo '</td>
           <td align=left valign=top width=75%> 
      
</td></tr>';
   

echo '<table width=100%><tbody>
                    <tr>
                        <td colspan=9 align=center class="pgtitle">Ward Occupancy</td>
                     </tr></table>';




?>
<script>
//
//    Ext.onReady(function(){
//        var win;
//        var button = Ext.get('show-btn');
//
//        button.on('click', function(){
//        
//            var admForm = new Ext.FormPanel({
//                labelWidth: 75, // label settings here cascade unless overridden
//                url:'updateAdmissionDate.php',
//                frame:true,
//                title: 'Admission Date Form',
//                bodyStyle:'padding:5px 5px 0',
//                width: 300,
//                defaults: {width: 230},
//                defaultType: 'textfield',
//
//                items: [{
//                        fieldLabel: 'PID',
//                        name: 'pid',
//                        id: 'pid',
//                        allowBlank:false
//                    },{
//                        fieldLabel: 'Encounter No',
//                        name: 'encNo',
//                        id: 'encNo'
//                    },   new Ext.form.DateField({
//                        fieldLabel: 'Admission Date',
//                        name: 'admDate',
//                        id: 'admDate',
//                        width:190,
//                        allowBlank:false
//                    }),   new Ext.form.DateField({
//                        fieldLabel: 'Discharge Date',
//                        name: 'disDate',
//                        id: 'disDate',
//                        width:190,
//                        allowBlank:false
//                    })
//                ],
//
//                buttons: [{
//                        text: 'Save', handler: function() {
//                            var pid = admForm.getForm().findField("pid").getValue();
//                            var encNo=admForm.getForm().findField('encNo').getValue();
//                            var admDate=admForm.getForm().findField('admDate').getValue();
//                             var disDate=admForm.getForm().findField('disDate').getValue();
//                            Ext.Ajax.request({
//                                url: 'updateAdmDates.php',
//                                method: 'POST',
//                                params: {
//                                    pid:pid,
//                                    encNo:encNo,
//                                    admDate:admDate,
//                                    disDate:disDate,
//                                    task:"updateAdm"
//                                },
//                                waitMsg:'Saving Data...',
//                                success: function (form, action) {
//                                    win.hide();
//                                },
//                                failure:function(form, action) {
//                                    Ext.MessageBox.alert('Message', 'Save failed, Check that all values are OK ');
//                                }
//                            });
//
//                        }
//                    },{
//                        text: 'Cancel', handler: function() {
//                            win.hide();
//                        }
//                    }]
//            });
//            // create the window on the first click and reuse on subsequent clicks
//            if(!win){
//                win = new Ext.Window({
//                    applyTo:'hello-win',
//                    layout:'fit',
//                    width:500,
//                    height:300,
//                    closeAction:'hide',
//                    plain: true,
//
//                    items: [admForm]
//
//                });
//            }
//            win.show(this);
//            admForm.getForm().findField("pid").setValue("<?php //echo $pid ?>");
//            admForm.getForm().findField("encNo").setValue("<?php //echo $encounter_nr ?>");
//        });
//    });
</script>
