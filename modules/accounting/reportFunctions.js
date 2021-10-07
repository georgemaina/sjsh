/*!
 * Ext JS Library 3.3.0
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
pharmReports = function() {

    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.BLANK_IMAGE_URL="../../../include/Extjs/resources/images/default/s.gif";
    return {
        init: function() {
            Ext.QuickTips.init();
            
                      
            /* Datepicker */
            var datefield = new Ext.form.DateField({
                renderTo: 'datefield',
                labelWidth: 100, // label settings here cascade unless overridden
                frame: false,
                width: 180,
                name: 'strDate1',
                id:'strDate1',
                format: 'Y-m-d',
                submitFormat: 'Y-m-d'
            });
            
            var datefield2 = new Ext.form.DateField({
                renderTo: 'datefield2',
                frame: false,
                width: 180,
                name: 'strDate2',
                id:'strDate2',
                format: 'Y-m-d',
                submitFormat: 'Y-m-d'
            });
          
        }
    };
    
}();
Ext.onReady(pharmReports.init, pharmReports);

function getBillNumbers4(pid){
     // simple array store
            var store = new Ext.data.JsonStore({
                url:'../getDesc.php',
                root:'bills',
                fields: ['billNumber'],
                id: 'ID',//
                baseParams:{
                    callerID:'getBillNumbers',
                    pid:pid
                }
               

            });
            
            store.load();
            
            var typeCombo = new Ext.form.ComboBox({
                typeAhead: true,
                id:'billNumber',
                name:"billNumber",
                forceSelection: true,
                triggerAction: 'all',
                emptyText:'Select Bill Number',
                selectOnFocus:true,
                lazyRender:true,
                mode: 'local',
                fieldLabel:'Invoice Number',
                store: store,
                valueField: 'billNumber',
                displayField: 'billNumber',
                applyTo: 'bills'
            });
}