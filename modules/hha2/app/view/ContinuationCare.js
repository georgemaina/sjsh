/*
 * File: app/view/ContinuationCare.js
 *
 * This file was generated by Sencha Architect version 4.2.3.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.2.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.2.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('hha.view.ContinuationCare', {
    extend: 'Ext.form.Panel',
    alias: 'widget.continuationcare',

    requires: [
        'hha.view.ContinuationCareViewModel',
        'hha.view.SharedPanel',
        'Ext.form.FieldSet',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.form.RadioGroup',
        'Ext.form.field.Radio',
        'Ext.form.field.TextArea',
        'Ext.form.Panel',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.Label'
    ],

    viewModel: {
        type: 'continuationcare'
    },
    draggable: true,
    frame: true,
    height: 580,
    resizable: true,
    scrollable: true,
    layout: 'absolute',
    animCollapse: true,
    closable: true,
    collapsible: true,
    title: 'HTN CONTINUATION OF CARE FORM',
    url: 'data/getDatafunctions.php?task=saveContinuationCare',

    items: [
        {
            xtype: 'fieldset',
            x: 5,
            y: 0,
            height: 140,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 25,
                    width: 335,
                    fieldLabel: 'Name of Facility',
                    labelWidth: 90,
                    name: 'FacilityName',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 350,
                    y: 30,
                    width: 245,
                    fieldLabel: 'Facility ID',
                    labelWidth: 110,
                    name: 'FacilityID',
                    allowBlank: false
                },
                {
                    xtype: 'datefield',
                    x: 640,
                    y: 30,
                    width: 245,
                    fieldLabel: 'Date',
                    labelWidth: 90,
                    name: 'ScreeningDate',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 640,
                    y: 55,
                    width: 245,
                    fieldLabel: 'NationalID',
                    labelWidth: 90,
                    name: 'NationalID'
                },
                {
                    xtype: 'textfield',
                    x: 910,
                    y: 55,
                    hidden: true,
                    itemId: 'FormID',
                    width: 130,
                    labelWidth: 90,
                    name: 'FormID'
                },
                {
                    xtype: 'datefield',
                    x: 350,
                    y: 80,
                    width: 245,
                    fieldLabel: 'Date of Birth',
                    labelWidth: 110,
                    name: 'DOB',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 350,
                    y: 105,
                    width: 245,
                    fieldLabel: 'Mobile Number',
                    labelWidth: 110,
                    name: 'MobileNumber'
                },
                {
                    xtype: 'textfield',
                    x: 350,
                    y: 55,
                    width: 245,
                    fieldLabel: 'Unique Patient ID',
                    labelWidth: 110,
                    name: 'UniqueID',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 75,
                    width: 335,
                    fieldLabel: 'County',
                    labelWidth: 90,
                    name: 'County',
                    value: 'KIRINYAGA',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 50,
                    width: 335,
                    fieldLabel: 'Patient Name',
                    labelWidth: 90,
                    name: 'PatientName',
                    allowBlank: false
                },
                {
                    xtype: 'combobox',
                    x: 5,
                    y: 100,
                    fieldLabel: 'Mobile Consent',
                    labelWidth: 90,
                    name: 'MobileConsent',
                    value: 'Yes',
                    store: [
                        'Yes',
                        'No'
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 640,
                    y: 85,
                    width: 280,
                    fieldLabel: 'Sex',
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            name: 'Sex',
                            boxLabel: 'Male',
                            inputValue: 'Male'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Sex',
                            boxLabel: 'Female',
                            inputValue: 'Female'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 717,
                    y: 114,
                    hidden: true,
                    fieldLabel: '',
                    name: 'EncounterNo'
                },
                {
                    xtype: 'textfield',
                    x: 720,
                    y: 115,
                    hidden: true,
                    itemId: 'PID',
                    name: 'PID'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 240,
            height: 165,
            layout: 'absolute',
            title: '2. Clinical Observations',
            items: [
                {
                    xtype: 'textareafield',
                    x: 0,
                    y: 15,
                    height: 121,
                    itemId: 'Observations',
                    width: 795,
                    fieldLabel: '2a. Observations: Eg weight gain, change in diabetic status, LMP if female etc',
                    labelAlign: 'top',
                    name: 'Observations'
                },
                {
                    xtype: 'textfield',
                    x: 840,
                    y: 70,
                    hidden: true,
                    itemId: 'Questions',
                    width: 60,
                    name: 'Questions'
                }
            ]
        },
        {
            xtype: 'sharedpanel',
            animCollapse: true,
            collapsible: true,
            y: 410
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 145,
            height: 80,
            layout: 'absolute',
            title: '1. Vital Signs',
            items: [
                {
                    xtype: 'textfield',
                    x: 695,
                    y: 12,
                    tabIndex: 19,
                    itemId: 'txtBMI',
                    width: 85,
                    fieldLabel: 'BMI',
                    labelAlign: 'top',
                    name: 'BMI',
                    enforceMaxLength: true,
                    maxLength: 6
                },
                {
                    xtype: 'textfield',
                    x: 575,
                    y: 12,
                    tabIndex: 18,
                    itemId: 'txtWeight',
                    width: 85,
                    fieldLabel: 'Weight',
                    labelAlign: 'top',
                    name: 'Weight',
                    allowBlank: false,
                    enforceMaxLength: true,
                    maxLength: 6
                },
                {
                    xtype: 'textfield',
                    x: 445,
                    y: 12,
                    tabIndex: 17,
                    itemId: 'txtHeight',
                    width: 85,
                    fieldLabel: 'Height',
                    labelAlign: 'top',
                    name: 'Height',
                    allowBlank: false,
                    enforceMaxLength: true,
                    maxLength: 6
                },
                {
                    xtype: 'textfield',
                    x: 175,
                    y: 30,
                    tabIndex: 16,
                    itemId: 'BPSecondReading1',
                    width: 45,
                    fieldLabel: '',
                    labelAlign: 'top',
                    name: 'BPSecondReading1',
                    allowBlank: false,
                    allowOnlyWhitespace: false,
                    enforceMaxLength: true,
                    maxLength: 3
                },
                {
                    xtype: 'textfield',
                    x: 220,
                    y: 30,
                    tabIndex: 16,
                    itemId: 'BPSecondReading2',
                    width: 45,
                    fieldLabel: '',
                    labelAlign: 'top',
                    name: 'BPSecondReading2',
                    allowBlank: false,
                    allowOnlyWhitespace: false,
                    enforceMaxLength: true,
                    maxLength: 3
                },
                {
                    xtype: 'textfield',
                    x: 10,
                    y: 30,
                    tabIndex: 15,
                    itemId: 'BPFirstReading1',
                    width: 45,
                    fieldLabel: '',
                    labelAlign: 'top',
                    name: 'BPFirstReading1',
                    allowBlank: false,
                    allowOnlyWhitespace: false,
                    enforceMaxLength: true,
                    maxLength: 3
                },
                {
                    xtype: 'textfield',
                    x: 50,
                    y: 30,
                    tabIndex: 15,
                    width: 45,
                    fieldLabel: '',
                    labelAlign: 'top',
                    name: 'BPFirstReading2',
                    allowBlank: false,
                    allowOnlyWhitespace: false
                },
                {
                    xtype: 'label',
                    x: 270,
                    y: 30,
                    height: 10,
                    text: 'mmHg'
                },
                {
                    xtype: 'label',
                    x: 660,
                    y: 35,
                    text: 'kg'
                },
                {
                    xtype: 'label',
                    x: 530,
                    y: 35,
                    text: 'cm'
                },
                {
                    xtype: 'label',
                    x: 100,
                    y: 35,
                    height: 10,
                    text: 'mmHg'
                },
                {
                    xtype: 'textfield',
                    x: 835,
                    y: 35,
                    hidden: true,
                    itemId: 'Vitals',
                    width: 60,
                    name: 'Vitals'
                },
                {
                    xtype: 'label',
                    x: 10,
                    y: 10,
                    height: 25,
                    width: 100,
                    text: 'BP(1st Reading)'
                },
                {
                    xtype: 'label',
                    x: 170,
                    y: 10,
                    height: 25,
                    width: 100,
                    text: 'BP(2nd Reading)'
                }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            x: 433,
            y: 12,
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    itemId: 'cmdContiniousCare',
                    width: 140,
                    text: '<b>Save</b>'
                }
            ]
        }
    ]

});