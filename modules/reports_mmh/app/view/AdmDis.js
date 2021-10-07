/*
 * File: app/view/AdmDis.js
 * Date: Mon Oct 22 2018 10:41:52 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.4.
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

Ext.define('ReportsMain.view.AdmDis', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.admdis',

    requires: [
        'ReportsMain.view.AdmDisViewModel',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.toolbar.Fill',
        'Ext.form.field.Display',
        'Ext.grid.column.Column',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Number',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'admdis'
    },
    height: 600,
    width: 1787,
    animCollapse: true,
    closable: true,
    collapsible: true,
    title: 'Admissions and Discharges',
    columnLines: true,
    store: 'AdmDisStore',

    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'AdmDisStore',
            items: [
                {
                    xtype: 'tbfill',
                    height: 10,
                    width: 387
                },
                {
                    xtype: 'displayfield',
                    itemId: 'totalDischarges',
                    fieldLabel: 'Total Patients',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    labelWidth: 120,
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                }
            ]
        },
        {
            xtype: 'container',
            dock: 'top',
            height: 77,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'datefield',
                    x: 5,
                    y: 5,
                    itemId: 'startDate',
                    width: 220,
                    fieldLabel: 'Start Date',
                    labelAlign: 'right',
                    labelWidth: 70
                },
                {
                    xtype: 'datefield',
                    x: 5,
                    y: 40,
                    itemId: 'endDate',
                    width: 220,
                    fieldLabel: 'End Date',
                    labelAlign: 'right',
                    labelWidth: 70
                },
                {
                    xtype: 'combobox',
                    x: 480,
                    y: 5,
                    itemId: 'disType',
                    width: 225,
                    fieldLabel: 'Discharge Type',
                    labelAlign: 'right',
                    displayField: 'DisType',
                    store: 'DischargesStore',
                    valueField: 'No'
                },
                {
                    xtype: 'combobox',
                    x: 480,
                    y: 40,
                    itemId: 'sex',
                    width: 225,
                    fieldLabel: 'Gender',
                    labelAlign: 'right',
                    store: [
                        [
                            'm',
                            'Male'
                        ],
                        [
                            'f',
                            'Female'
                        ]
                    ]
                },
                {
                    xtype: 'combobox',
                    x: 250,
                    y: 5,
                    itemId: 'ward',
                    width: 235,
                    fieldLabel: 'Wards',
                    labelAlign: 'right',
                    labelWidth: 60,
                    displayField: 'WardName',
                    minChars: 2,
                    queryMode: 'local',
                    store: 'WardStore',
                    typeAhead: true,
                    valueField: 'No'
                },
                {
                    xtype: 'combobox',
                    x: 220,
                    y: 40,
                    itemId: 'groupWards',
                    width: 265,
                    fieldLabel: 'Group Wards',
                    labelAlign: 'right',
                    labelWidth: 90,
                    queryMode: 'local',
                    store: [
                        [
                            'adults',
                            'General Adults'
                        ],
                        [
                            'paeds',
                            'General Paediatrics'
                        ],
                        [
                            'mat',
                            'Maternity Mothers'
                        ]
                    ]
                },
                {
                    xtype: 'numberfield',
                    x: 715,
                    y: 5,
                    itemId: 'age1',
                    width: 155,
                    fieldLabel: 'Age Between',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'numberfield',
                    x: 715,
                    y: 40,
                    itemId: 'age2',
                    width: 155,
                    fieldLabel: 'And',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'button',
                    x: 880,
                    y: 5,
                    itemId: 'cmdPreviewAdmDis',
                    text: 'Preview'
                },
                {
                    xtype: 'button',
                    x: 880,
                    y: 40,
                    itemId: 'cmdExportAdmDis',
                    width: 155,
                    text: 'Export to Excel'
                },
                {
                    xtype: 'button',
                    x: 960,
                    y: 5,
                    itemId: 'cmdPrintAdmDis',
                    width: 75,
                    text: 'Print'
                },
                {
                    xtype: 'textfield',
                    x: 1050,
                    y: 5,
                    hidden: true,
                    itemId: 'txtAdmDis',
                    width: 110
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'PID',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 221,
            dataIndex: 'Names',
            text: 'Names'
        },
        {
            xtype: 'gridcolumn',
            width: 58,
            dataIndex: 'Sex',
            text: 'Sex'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Dob',
            text: 'Dob'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Paid',
            text: 'Age'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'AdmissionDate',
            text: 'Admission Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'DischargeDate',
            text: 'Discharge Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'BedDays',
            text: 'Bed Days'
        },
        {
            xtype: 'gridcolumn',
            width: 142,
            dataIndex: 'Ward',
            text: 'Ward'
        },
        {
            xtype: 'gridcolumn',
            width: 142,
            dataIndex: 'Payment',
            text: 'Payment'
        },
        {
            xtype: 'gridcolumn',
            width: 142,
            dataIndex: 'NHIFCredit',
            text: 'NHIF Credit'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            dataIndex: 'DisType',
            text: 'DisType'
        }
    ]

});