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

    new Ext.form.Panel({
    renderTo: 'container',
    height: 459,
    width: 944,
    layout: 'absolute',
    bodyPadding: 3,
    title: 'Encounter Form',

     items: [
        {
            xtype: 'fieldset',
            x: 5,
            y: 55,
            height: 175,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: -1,
                    width: 515,
                    fieldLabel: '2a. Do you smoke cigarettes',
                    labelWidth: 270,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 5,
                            name: 'Smoking',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 6,
                            name: 'Smoking',
                            boxLabel: 'No'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 7,
                            name: 'Smoking',
                            boxLabel: 'Stopped'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 25,
                    width: 515,
                    fieldLabel: '2b. Do you sometimes drink alcohol',
                    labelWidth: 270,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 8,
                            name: 'Drinking',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 9,
                            name: 'Drinking',
                            boxLabel: 'No'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 10,
                            name: 'Drinking',
                            boxLabel: 'Stopped'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 50,
                    width: 440,
                    fieldLabel: '2c. Family history of cardiovascular disease?',
                    labelWidth: 270,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 11,
                            name: 'Cardiovascular',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 12,
                            name: 'Cardiovascular',
                            boxLabel: 'No'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 75,
                    width: 440,
                    fieldLabel: '2d. Do you have diabetes?',
                    labelWidth: 270,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 13,
                            name: 'Diabetes',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 14,
                            name: 'Diabetes',
                            boxLabel: 'No'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 10,
                    y: 100,
                    tabIndex: 15,
                    width: 745,
                    fieldLabel: 'Observations',
                    labelWidth: 120,
                    name: 'Observations'
                },
                {
                    xtype: 'datefield',
                    x: 10,
                    y: 135,
                    tabIndex: 16,
                    fieldLabel: 'LMP if Applicable',
                    labelWidth: 120,
                    name: 'LMPfemale'
                },
                {
                    xtype: 'checkboxfield',
                    x: 310,
                    y: 135,
                    tabIndex: 17,
                    name: 'NotApplicable',
                    boxLabel: 'Not Applicable'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 230,
            height: 50,
            padding: '0 0 0 0',
            layout: 'absolute',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 30,
                    y: 15,
                    height: 20,
                    width: 340,
                    fieldLabel: 'Any known allergies to drugs?',
                    labelWidth: 200,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 18,
                            name: 'DrugAllergies',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 19,
                            boxLabel: 'No'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 435,
                    y: 10,
                    tabIndex: 20,
                    width: 335,
                    fieldLabel: 'Specify',
                    name: 'AllergiesSpecify'
                },
                {
                    xtype: 'label',
                    x: 5,
                    y: 0,
                    text: 'Contra indications'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 5,
            height: 45,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'textfield',
                    x: 10,
                    y: 5,
                    itemId: 'BPFirstReading1',
                    tabIndex: 1,
                    width: 150,
                    fieldLabel: 'BP First Reading',
                    name: 'BPFirstReading1',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 225,
                    y: 5,
                    itemId: 'BPSecondReading1',
                    tabIndex: 3,
                    width: 160,
                    fieldLabel: 'BP Second Reading',
                    labelWidth: 110,
                    name: 'BPSecondReading1',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 160,
                    y: 5,
                    itemId: 'BPFirstReading2',
                    tabIndex: 2,
                    width: 45,
                    name: 'BPFirstReading2',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 385,
                    y: 5,
                    itemId: 'BPSecondReading2',
                    tabIndex: 4,
                    width: 50,
                    name: 'BPSecondReading2',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 445,
                    y: 5,
                    itemId: 'pid',
                    tabIndex: 4,
                    width: 120,
                    name: 'pid',
                    allowBlank: false,
                    emptyText: 'PID'
                },
                {
                    xtype: 'textfield',
                    x: 570,
                    y: 5,
                    itemId: 'encounterNr',
                    tabIndex: 4,
                    width: 120,
                    name: 'encounterNr',
                    allowBlank: false,
                    emptyText: 'EncounterNr'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 280,
            height: 50,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 20,
                    y: 15,
                    height: 20,
                    width: 405,
                    fieldLabel: 'Adhering to current medications',
                    labelWidth: 210,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 21,
                            name: 'AdheringMedication',
                            boxLabel: 'N/A'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 22,
                            name: 'AdheringMedication',
                            boxLabel: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 23,
                            name: 'AdheringMedication',
                            boxLabel: 'No'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 435,
                    y: 15,
                    tabIndex: 24,
                    width: 335,
                    fieldLabel: 'Specify Reason',
                    name: 'AdheringSpecify'
                },
                {
                    xtype: 'label',
                    x: 0,
                    y: -1,
                    text: 'Current Treatment'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 330,
            height: 45,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 15,
                    y: 15,
                    height: 30,
                    width: 445,
                    fieldLabel: '',
                    labelWidth: 200,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 25,
                            name: 'FollowupPlan',
                            boxLabel: 'Continue care at Facilty'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 26,
                            name: 'FollowupPlan',
                            boxLabel: 'Refer to Another Facility'
                        }
                    ]
                },
                {
                    xtype: 'datefield',
                    x: 440,
                    y: 10,
                    tabIndex: 27,
                    width: 250,
                    fieldLabel: 'Return Date',
                    name: 'ReturnDate',
                    allowBlank: false
                },
                {
                    xtype: 'label',
                    x: 5,
                    y: 0,
                    text: 'Follow Up Plan'
                }
            ]
        },
        {
            xtype: 'button',
            x: 595,
            y: 380,
            height: 35,
            itemId: 'cmdSave',
            tabIndex: 28,
            width: 110,
            text: 'Save',
            listeners: {
                click: 'onCmdSaveClick'
            }
        },
        {
            xtype: 'button',
            x: 725,
            y: 380,
            height: 35,
            itemId: 'cmdClose',
            width: 110,
            text: 'Close'
        }
    ]
       
       
    });
    
      if(!win){
            win = new Ext.Window({
                applyTo:'container',
                layout:'fit',
                width:350,
                height:200,
                closeAction:'hide',
                title: 'Hypertension',
                plain: true,
                items: [admForm]

            });
        }
        
         var bp2 = Ext.get('bp2');

        bp2.on('blur', function(){
            win.show(this);
        });
        
});