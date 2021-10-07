/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.require([
    'Ext.Editor',
    'Ext.form.Panel',
    'Ext.form.field.ComboBox',
    'Ext.form.field.Date',
    'Ext.form.FieldSet',
    'Ext.form.RadioGroup',
    'Ext.form.field.Radio',
]);

Ext.onReady(function(){
    var isLargeTheme = Ext.themeName !== 'classic',
        titleOffset = isLargeTheme ? -6 : -4;
        var win;

    var admForm=new Ext.form.Panel({
    // renderTo: 'container',
    height: 288,
    width: 611,
    layout: 'absolute',
    bodyPadding: 10,
    url: '../../data/getPatientFunctions.php?caller=saveHts',
    defaultListenerScope: true,

    items: [
        {
            xtype: 'radiogroup',
            x: 15,
            y: 105,
            width: 495,
            fieldLabel: 'Patient has presumptive TB in the past 3-6 months',
            labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
            labelWidth: 320,
            items: [
                {
                    xtype: 'radiofield',
                    name: 'Qst8',
                    boxLabel: 'Yes'
                },
                {
                    xtype: 'radiofield',
                    name: 'Qst8',
                    boxLabel: 'No'
                }
            ]
        },
        {
            xtype: 'radiogroup',
            x: 15,
            y: 165,
            width: 495,
            fieldLabel: 'Child has malnutrition',
            labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
            labelWidth: 320,
            items: [
                {
                    xtype: 'radiofield',
                    name: 'Qst10',
                    boxLabel: 'Yes'
                },
                {
                    xtype: 'radiofield',
                    name: 'Qst10',
                    boxLabel: 'No'
                }
            ]
        },
        {
            xtype: 'radiogroup',
            x: 15,
            y: 135,
            width: 495,
            fieldLabel: 'Child is sick with signs and symptoms of acute HIV',
            labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
            labelWidth: 320,
            items: [
                {
                    xtype: 'radiofield',
                    name: 'Qst9',
                    boxLabel: 'Yes'
                },
                {
                    xtype: 'radiofield',
                    name: 'Qst9',
                    boxLabel: 'No'
                }
            ]
        },
        {
            xtype: 'displayfield',
            x: 205,
            y: 5,
            value: 'Targeting Peadiatric',
            fieldStyle: 'color:maroon; font-weight:bold;font-size:18px;'
        },
        {
            xtype: 'button',
            x: 140,
            y: 230,
            itemId: 'save',
            width: 110,
            text: 'Save',
            listeners: {
                click: 'onSaveClick'
            }
        },
        {
            xtype: 'button',
            x: 355,
            y: 230,
            itemId: 'close',
            width: 110,
            iconCls: 'x-fa fa-close',
            text: 'Close',
            listeners: {
                click: 'onCloseClick'
            }
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 35,
            height: 45,
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'displayfield',
                    flex: 1,
                    itemId: 'pid',
                    fieldLabel: 'Pid',
                    labelStyle: 'color:maroon;font-size:12px;font-weight:bold;',
                    labelWidth: 60,
                    fieldStyle: 'color:green;font-size:12px;font-weight:bold'
                },
                {
                    xtype: 'displayfield',
                    flex: 1,
                    itemId: 'encounterNr',
                    fieldLabel: 'Encounter No',
                    labelStyle: 'color:maroon;font-size:12px;font-weight:bold;',
                    labelWidth: 60,
                    fieldStyle: 'color:green;font-size:12px;font-weight:bold;'
                },
                {
                    xtype: 'displayfield',
                    flex: 1,
                    itemId: 'names',
                    fieldLabel: 'Patient',
                    labelStyle: 'color:maroon;font-size:12px;font-weight:bold;',
                    labelWidth: 60,
                    fieldStyle: 'color:green;font-size:12px;font-weight:bold;'
                },
                {
                    xtype: 'displayfield',
                    flex: 1,
                    itemId: 'age',
                    fieldLabel: 'Age',
                    labelStyle: 'color:maroon;font-size:12px;font-weight:bold;',
                    labelWidth: 60,
                    fieldStyle: 'color:green;font-size:12px;font-weight:bold;'
                }
            ]
        }
    ],

    onSaveClick: function(button, e, eOpts) {
        var form = button.up('panel').getForm();
        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                params:{
                    pid:button.up('form').down('#pid').getValue(),
                    encounterNr:button.up('form').down('#encounterNr').getValue(),
                },
                success: function(form, action) {
                    Ext.Msg.alert("HTS","Questionare saved successfully");
                    form.reset();
                    button.up('window').hide();
                    // supplierStore.load({});

                },
                failure: function(form, action) {
                    Ext.Msg.alert('Failed', 'Could not save Record.');
                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
        }
    },

    onCloseClick: function(button, e, eOpts) {
        button.up('form').getForm().reset();
        button.up('window').hide();
    }
       
       
        });
    
        
      if(!win){
            win = new Ext.Window({
                applyTo:'container',
                layout:'fit',
                width:750,
                height:450,
                closeAction:'hide',
                title: 'Eligibility screening tool questionnaire',
                plain: true,
                items: [admForm]

            });
        }
        
         var weight = Ext.get('weight');
         var pid=Ext.get('pid').getValue();
         var encounterNr=Ext.get('encounter_nr').getValue();

         weight.on('blur', function(){
            win.show(this);
            win.down('#pid').setValue(pid);
            win.down('#encounterNr').setValue(pid);
        });

});