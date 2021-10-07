/*
 * File: app/view/XrayRevenue.js
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

Ext.define('ReportsMain.view.XrayRevenue', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.xrayrevenue',

    requires: [
        'ReportsMain.view.XrayRevenueViewModel',
        'Ext.view.Table',
        'Ext.form.FieldSet',
        'Ext.form.field.Date',
        'Ext.button.Button',
        'Ext.toolbar.Paging',
        'Ext.grid.column.Number'
    ],

    viewModel: {
        type: 'xrayrevenue'
    },
    height: 615,
    closable: true,
    collapsible: true,
    title: 'X-Ray Revenue ',
    columnLines: true,
    store: 'XrayRevenueStore',

    dockedItems: [
        {
            xtype: 'fieldset',
            dock: 'top',
            height: 48,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'datefield',
                    x: 0,
                    y: 0,
                    itemId: 'strDate1',
                    fieldLabel: 'From Date',
                    labelWidth: 80
                },
                {
                    xtype: 'datefield',
                    x: 265,
                    y: 0,
                    itemId: 'strDate2',
                    fieldLabel: 'To Date',
                    labelWidth: 60
                },
                {
                    xtype: 'button',
                    x: 525,
                    y: -3,
                    itemId: 'cmdPreviewXrayRevenue',
                    width: 135,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    x: 810,
                    y: 0,
                    itemId: 'cmdExportXrayRevenue',
                    width: 130,
                    text: '<b>Export</b>'
                },
                {
                    xtype: 'button',
                    x: 670,
                    y: -1,
                    itemId: 'cmdPrintXrayrevenue',
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
            store: 'XrayRevenueStore'
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'xraycode',
            text: 'Xraycode'
        },
        {
            xtype: 'gridcolumn',
            width: 281,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'numbercolumn',
            align: 'right',
            dataIndex: 'TotalTests',
            text: 'Total Tests',
            format: '000'
        },
        {
            xtype: 'numbercolumn',
            align: 'right',
            dataIndex: 'Price',
            text: 'Price'
        },
        {
            xtype: 'numbercolumn',
            align: 'right',
            dataIndex: 'Total',
            text: 'Total'
        }
    ]

});