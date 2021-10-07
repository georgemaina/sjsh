/*
 * File: app/view/OpMobidity.js
 * Date: Mon Mar 09 2020 09:51:39 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.OpMobidity', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.opmobidity',

    requires: [
        'ReportsMain.view.OpMobidityViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Date',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'opmobidity'
    },
    closable: true,
    collapsible: true,
    title: 'Outpatient Daily Mobidity Report',
    columnLines: true,
    store: 'OpMobidityStore',

    columns: [
        {
            xtype: 'gridcolumn',
            width: 68,
            dataIndex: 'icdCode',
            text: 'Icd Code'
        },
        {
            xtype: 'gridcolumn',
            width: 224,
            dataIndex: 'disease',
            text: 'Disease'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '1',
            text: '1'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '2',
            text: '2'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '3',
            text: '3'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '4',
            text: '4'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '5',
            text: '5'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '6',
            text: '6'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '7',
            text: '7'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '8',
            text: '8'
        },
        {
            xtype: 'gridcolumn',
            width: 30,
            dataIndex: '9',
            text: '9'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '10',
            text: '10'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '11',
            text: '11'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '12',
            text: '12'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '13',
            text: '13'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '14',
            text: '14'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '15',
            text: '15'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '16',
            text: '16'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '17',
            text: '17'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '18',
            text: '18'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '19',
            text: '19'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '20',
            text: '20'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '21',
            text: '21'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '22',
            text: '22'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '23',
            text: '23'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '24',
            text: '24'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '25',
            text: '25'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '26',
            text: '26'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '27',
            text: '27'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '28',
            text: '28'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '29',
            text: '29'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '30',
            text: '30'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: '31',
            text: '31'
        },
        {
            xtype: 'gridcolumn',
            style: 'font-size: smaller;font-weight: bold;color: blue;',
            width: 78,
            align: 'center',
            dataIndex: 'TOTALS',
            text: 'TOTALS'
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            height: 47,
            items: [
                {
                    xtype: 'combobox',
                    itemId: 'reportType',
                    width: 321,
                    fieldLabel: 'Select Report Type',
                    labelWidth: 120,
                    displayField: 'month',
                    store: [
                        [
                            'OP',
                            'OP Mobidity Above 5'
                        ],
                        [
                            'OPC',
                            'OP Mobidity Below 5'
                        ]
                    ],
                    valueField: 'ID'
                },
                {
                    xtype: 'datefield',
                    itemId: 'startDate',
                    width: 232,
                    fieldLabel: 'Start Date',
                    labelWidth: 70,
                    format: 'Y-m-d'
                },
                {
                    xtype: 'datefield',
                    itemId: 'endDate',
                    width: 232,
                    fieldLabel: 'End Date',
                    labelWidth: 70,
                    format: 'Y-m-d'
                },
                {
                    xtype: 'button',
                    itemId: 'cmdPreview',
                    width: 99,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    itemId: 'cmdPrint',
                    width: 83,
                    text: '<b>Print'
                },
                {
                    xtype: 'button',
                    itemId: 'cmdExport',
                    text: '<b>Export to Excel'
                }
            ]
        }
    ]

});