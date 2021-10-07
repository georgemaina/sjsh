/*
 * File: app/view/LabResults.js
 * Date: Mon Mar 09 2020 09:46:08 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.LabResults', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.labresults',

    requires: [
        'ReportsMain.view.AdmDisViewModel1',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.toolbar.Fill',
        'Ext.form.field.Display',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.button.Button',
        'Ext.grid.column.Column',
        'Ext.grid.plugin.RowWidget',
        'Ext.XTemplate'
    ],

    viewModel: {
        type: 'labresults'
    },
    height: 600,
    width: 1787,
    animCollapse: true,
    closable: true,
    collapsible: true,
    title: 'Lab Results',
    columnLines: true,
    store: 'LabResultStore',

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
                    x: 575,
                    y: 5,
                    itemId: 'disType',
                    width: 225,
                    fieldLabel: 'Results',
                    labelAlign: 'right',
                    displayField: 'DisType',
                    store: 'DischargesStore',
                    valueField: 'No'
                },
                {
                    xtype: 'combobox',
                    x: 575,
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
                    width: 295,
                    fieldLabel: 'Lab Test',
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
                    xtype: 'textfield',
                    x: 220,
                    y: 40,
                    itemId: 'groupWards',
                    width: 235,
                    fieldLabel: 'PID',
                    labelAlign: 'right',
                    labelWidth: 90
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
            dataIndex: 'Pid',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 220,
            dataIndex: 'Names',
            text: 'Names'
        },
        {
            xtype: 'gridcolumn',
            width: 148,
            dataIndex: 'TestDate',
            text: 'Test Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'LabCode',
            text: 'Lab Code'
        },
        {
            xtype: 'gridcolumn',
            width: 237,
            dataIndex: 'LabTest',
            text: 'Lab Test'
        },
        {
            xtype: 'gridcolumn',
            width: 130,
            dataIndex: 'RequestedBy',
            text: 'Requested By'
        }
    ],
    plugins: [
        {
            ptype: 'rowwidget',
            rowBodyTpl: [
                'rowBodyTpl'
            ],
            widget: '{"xtype":"grid",\n  autoload:true,\n  title:"Lab Results for Test:{record.LabTest}"\n  bind:{\n     store:testResults\n  }\n\n}'
        }
    ]

});