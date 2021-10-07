/*
 * File: app/view/Discharge.js
 * Date: Fri Feb 28 2020 05:39:31 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.5.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.5.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Inpatient.view.Discharge', {
    extend: 'Ext.form.Panel',
    alias: 'widget.discharge',

    requires: [
        'Inpatient.view.DischargeViewModel',
        'Ext.form.FieldSet',
        'Ext.form.field.TextArea',
        'Ext.form.field.Time',
        'Ext.button.Button',
        'Ext.form.field.Date',
        'Ext.form.field.Display',
        'Ext.toolbar.Spacer',
        'Ext.form.RadioGroup',
        'Ext.form.field.Radio'
    ],

    viewModel: {
        type: 'discharge'
    },
    frame: true,
    height: 425,
    width: 672,
    layout: 'absolute',
    bodyPadding: 10,
    url: '../../data/getDataFunctions.php?task=dischargePatients',

    items: [
        {
            xtype: 'fieldset',
            x: 5,
            y: 80,
            formBind: true,
            height: 175,
            padding: '0 0 0 0',
            width: 444,
            title: 'Discharge Types'
        },
        {
            xtype: 'textareafield',
            x: 5,
            y: 260,
            shadow: 'frame',
            shadowOffset: 1,
            frame: true,
            height: 110,
            width: 445,
            name: 'dischargeSummary',
            allowBlank: false,
            emptyText: 'Discharge Summary Notes'
        },
        {
            xtype: 'timefield',
            x: 20,
            y: 55,
            itemId: 'dischargeTime',
            width: 275,
            fieldLabel: 'Discharge Time',
            name: 'dischargeTime',
            allowBlank: false,
            format: 'H:i:s'
        },
        {
            xtype: 'button',
            x: 5,
            y: 380,
            formBind: true,
            height: 35,
            itemId: 'saveDischarge',
            width: 110,
            iconCls: 'x-fa fa-star',
            text: 'Discharge',
            value: 'cmdDischarge'
        },
        {
            xtype: 'button',
            x: 135,
            y: 380,
            height: 35,
            width: 135,
            iconCls: 'x-fa fa-print',
            text: 'Print Summary',
            value: 'cmdDischarge'
        },
        {
            xtype: 'button',
            x: 290,
            y: 380,
            height: 35,
            itemId: 'cmdClose',
            width: 110,
            iconCls: 'x-fa fa-close',
            text: 'Cancel',
            value: 'cmdClose'
        },
        {
            xtype: 'datefield',
            x: 20,
            y: 20,
            itemId: 'dischargeDate',
            fieldLabel: 'Discharge Date',
            name: 'dischargeDate',
            allowBlank: false
        },
        {
            xtype: 'radiogroup',
            x: 85,
            y: 120,
            width: 672,
            allowBlank: false,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'radiofield',
                    name: 'dischargeType',
                    boxLabel: 'Regular Discharge',
                    inputValue: '1'
                },
                {
                    xtype: 'radiofield',
                    name: 'dischargeType',
                    boxLabel: 'Medical Discharge',
                    inputValue: '9'
                },
                {
                    xtype: 'radiofield',
                    name: 'dischargeType',
                    boxLabel: 'Death of the Patient',
                    inputValue: '7'
                }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'fieldset',
            x: 0,
            y: 0,
            dock: 'left',
            height: 421,
            margin: '0 0 0 0',
            style: 'background:#386d87',
            width: 235,
            layout: 'form',
            items: [
                {
                    xtype: 'displayfield',
                    itemId: 'pid',
                    margin: '0 0 0 0',
                    padding: '0 0 0 0',
                    width: 180,
                    fieldLabel: 'PID',
                    labelAlign: 'right',
                    labelStyle: 'font-weight:bold; font-size:11px; color:#f4f6fc;',
                    labelWidth: 30,
                    fieldStyle: 'color:#a7e88b; font-size:12px;font-weight:bold;background:#386d87'
                },
                {
                    xtype: 'displayfield',
                    itemId: 'names',
                    padding: '0 0 0 0',
                    width: 180,
                    fieldLabel: 'Names',
                    labelAlign: 'right',
                    labelPad: 0,
                    labelStyle: 'font-weight:bold; font-size:11px; color:#f4f6fc;',
                    labelWidth: 60,
                    fieldStyle: 'color:#a7e88b; font-size:12px;font-weight:bold;background:#386d87'
                },
                {
                    xtype: 'displayfield',
                    itemId: 'encounterNo',
                    width: 150,
                    fieldLabel: 'Encounter No',
                    labelAlign: 'right',
                    labelStyle: 'font-weight:bold; font-size:11px; color:#f4f6fc;',
                    name: 'encounter_nr',
                    fieldStyle: 'color:#a7e88b; font-size:12px;font-weight:bold;background:#386d87',
                    readOnly: true
                },
                {
                    xtype: 'displayfield',
                    frame: false,
                    height: 150,
                    itemId: 'Dob',
                    width: 180,
                    fieldLabel: 'Date of Birth',
                    labelAlign: 'right',
                    labelStyle: 'font-weight:bold; font-size:11px; color:#f4f6fc;',
                    fieldStyle: 'color:#a7e88b; font-size:12px;font-weight:bold;background:#386d87'
                },
                {
                    xtype: 'displayfield',
                    frame: false,
                    height: 150,
                    itemId: 'wardNo',
                    width: 180,
                    fieldLabel: 'Ward No',
                    labelAlign: 'right',
                    labelStyle: 'font-weight:bold; font-size:11px; color:#f4f6fc;',
                    fieldStyle: 'color:#a7e88b; font-size:12px;font-weight:bold;background:#386d87'
                },
                {
                    xtype: 'tbspacer'
                }
            ]
        }
    ]

});