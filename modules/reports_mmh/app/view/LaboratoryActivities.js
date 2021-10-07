/*
 * File: app/view/LaboratoryActivities.js
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

Ext.define('ReportsMain.view.LaboratoryActivities', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.laboratoryactivities',

    requires: [
        'ReportsMain.view.LaboratoryActivitiesViewModel',
        'Ext.grid.feature.GroupingSummary',
        'Ext.XTemplate',
        'Ext.view.Table',
        'Ext.form.FieldSet',
        'Ext.form.field.Date',
        'Ext.form.field.Number',
        'Ext.form.field.ComboBox',
        'Ext.button.Button',
        'Ext.toolbar.Paging',
        'Ext.form.field.Display',
        'Ext.grid.column.Column'
    ],

    viewModel: {
        type: 'laboratoryactivities'
    },
    height: 615,
    closable: true,
    collapsible: true,
    title: 'Laboratory Activities',
    columnLines: true,
    store: 'LabActivitiesStore',

    features: [
        {
            ftype: 'groupingsummary',
            groupHeaderTpl: [
                '{columnName}: {name} ({rows.length} Lab Test{[values.rows.length > 1 ? "s" : ""]})'
            ]
        }
    ],
    viewConfig: {
        height: 302
    },
    dockedItems: [
        {
            xtype: 'fieldset',
            dock: 'top',
            height: 157,
            padding: '0 0 0 0',
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'datefield',
                    x: 15,
                    y: 10,
                    itemId: 'StartDate',
                    fieldLabel: 'From Date',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'datefield',
                    x: 15,
                    y: 45,
                    itemId: 'EndDate',
                    fieldLabel: 'To Date',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'textfield',
                    x: 15,
                    y: 80,
                    itemId: 'pid',
                    fieldLabel: 'PID',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'numberfield',
                    x: 295,
                    y: 10,
                    itemId: 'age1',
                    fieldLabel: 'Between Age',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'numberfield',
                    x: 295,
                    y: 45,
                    itemId: 'age2',
                    fieldLabel: 'And Age',
                    labelAlign: 'right',
                    labelWidth: 80
                },
                {
                    xtype: 'combobox',
                    x: 575,
                    y: 80,
                    itemId: 'labTest',
                    width: 380,
                    fieldLabel: 'Lab Test',
                    labelAlign: 'right',
                    labelWidth: 80,
                    displayField: 'Description',
                    minChars: 2,
                    queryMode: 'local',
                    store: 'LabParams',
                    typeAhead: true,
                    valueField: 'ItemID'
                },
                {
                    xtype: 'combobox',
                    x: 820,
                    y: 225,
                    itemId: 'visits',
                    fieldLabel: 'Admission',
                    labelWidth: 80,
                    store: [
                        'New',
                        'Revisit'
                    ]
                },
                {
                    xtype: 'combobox',
                    x: 575,
                    y: 10,
                    itemId: 'gender',
                    fieldLabel: 'Gender',
                    labelAlign: 'right',
                    labelWidth: 80,
                    store: [
                        'Male',
                        'Female'
                    ]
                },
                {
                    xtype: 'combobox',
                    x: 285,
                    y: 80,
                    itemId: 'RequestedBy',
                    fieldLabel: 'Requested By',
                    labelAlign: 'right',
                    labelWidth: 90,
                    displayField: 'StaffName',
                    minChars: 3,
                    store: 'PersonnelStore',
                    typeAhead: true,
                    valueField: 'StaffName'
                },
                {
                    xtype: 'combobox',
                    x: 575,
                    y: 45,
                    itemId: 'status',
                    fieldLabel: 'Status',
                    labelAlign: 'right',
                    labelWidth: 80,
                    store: [
                        'Done',
                        'Pending'
                    ]
                },
                {
                    xtype: 'button',
                    x: 110,
                    y: 115,
                    itemId: 'cmdPreviewLabActivites',
                    width: 135,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    x: 660,
                    y: 115,
                    itemId: 'cmdExportLabActivities',
                    width: 130,
                    text: '<b>Export</b>'
                },
                {
                    xtype: 'button',
                    x: 385,
                    y: 115,
                    itemId: 'cmdPrintDiagnosis',
                    width: 130,
                    text: '<b>Print</b>'
                }
            ]
        },
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'LabActivitiesStore'
        },
        {
            xtype: 'container',
            dock: 'bottom',
            height: 38,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'displayfield',
                    x: 650,
                    y: 0,
                    itemId: 'txtTotals',
                    width: 255,
                    fieldLabel: 'Total Value',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            width: 65,
            dataIndex: 'PID',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 199,
            dataIndex: 'Names',
            text: 'Names'
        },
        {
            xtype: 'gridcolumn',
            width: 68,
            dataIndex: 'Admission',
            text: 'Admission'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Bill_Date',
            text: 'Bill Date'
        },
        {
            xtype: 'gridcolumn',
            width: 88,
            dataIndex: 'Bill_Time',
            text: 'Bill Time'
        },
        {
            xtype: 'gridcolumn',
            width: 88,
            dataIndex: 'LabCode',
            text: 'Lab Code'
        },
        {
            xtype: 'gridcolumn',
            width: 209,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            summaryRenderer: function(val, params, data, metaData) {
                return ((val === 0 || val > 1) ? '(' + val + ' Tests)' : '(1 Test)');
            },
            summaryType: 'count',
            width: 81,
            dataIndex: 'Qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            summaryRenderer: function(val, params, data, metaData) {
                return val;
            },
            field: {
                xtype: 'numberfield'
            },
            summaryType: 'sum',
            width: 88,
            align: 'end',
            dataIndex: 'Total',
            text: 'Total'
        },
        {
            xtype: 'gridcolumn',
            width: 139,
            dataIndex: 'Status',
            text: 'Test Status'
        },
        {
            xtype: 'gridcolumn',
            width: 139,
            dataIndex: 'RequestedBy',
            text: 'Requested By'
        }
    ]

});