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
        height: 451,
        width: 734,
        layout: 'absolute',
        bodyPadding: 10,
        //title: 'Eligibility Screening Tool Questionare',
        url: '../../data/getPatientFunctions.php?caller=saveHts',
        defaultListenerScope: true,
        items: [
            {
                xtype: 'radiogroup',
                x: 10,
                y: 85,
                width: 734,
                fieldLabel: 'Sexual partner is HIV positive',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst1',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst1',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 160,
                width: 734,
                fieldLabel: 'Patient is a key population (MSM/ Sex worker/IDUs/)',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst3',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst3',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 190,
                width: 734,
                fieldLabel: 'Patient has had unprotected sex with multiple sexual partners of unknown HIV status in the past 3-6 months',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst4',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst4',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 305,
                width: 734,
                fieldLabel: 'Patient has presumptive TB in the past 3-6 months',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst7',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst7',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 275,
                width: 734,
                fieldLabel: 'Patient has signs and symptoms of Acute HIV infection in the past 3-6 months',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst6',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst6',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 235,
                width: 734,
                fieldLabel: 'Patient has had a recent sexually transmitted illness in the past 3-6 months',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst5',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst5',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'radiogroup',
                x: 10,
                y: 125,
                width: 734,
                fieldLabel: 'Patient has had a recent high risk exposure (less than a month)',
                labelStyle: 'color:blue;font-size:12px;font-weight:bold;',
                labelWidth: 500,
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'Qst2',
                        boxLabel: 'Yes'
                    },
                    {
                        xtype: 'radiofield',
                        name: 'Qst2',
                        boxLabel: 'No'
                    }
                ]
            },
            {
                xtype: 'displayfield',
                x: 195,
                y: 5,
                value: 'Adolescents and Adults',
                fieldStyle: 'color:maroon; font-weight:bold;font-size:18px;'
            },
            {
                xtype: 'button',
                x: 190,
                y: 350,
                height: 45,
                style: 'color:blue;font-size:12px;font-weight:bold;',
                width: 135,
                iconCls: 'x-fa fa-save',
                text: 'Save',
                listeners: {
                    click: 'onButtonClick1'
                }
            },
            {
                xtype: 'button',
                x: 500,
                y: 350,
                height: 45,
                width: 135,
                iconCls: 'x-fa fa-close',
                text: 'Close',
                listeners: {
                    click: 'onButtonClick'
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
    
        onButtonClick1: function(button, e, eOpts) {
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
    
        onButtonClick: function(button, e, eOpts) {
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