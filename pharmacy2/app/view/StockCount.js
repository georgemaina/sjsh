/*
 * File: app/view/StockCount.js
 * Date: Wed Nov 27 2019 14:33:20 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Pharmacy.view.StockCount', {
    extend: 'Ext.form.Panel',
    alias: 'widget.stockcount',

    requires: [
        'Pharmacy.view.StockCountViewModel',
        'Ext.button.Button',
        'Ext.form.field.File',
        'Ext.form.Label',
        'Ext.form.field.ComboBox',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column'
    ],

    viewModel: {
        type: 'stockcount'
    },
    height: 507,
    width: 812,
    layout: 'absolute',
    bodyPadding: 10,
    title: 'StockCounts',

    items: [
        {
            xtype: 'button',
            x: 535,
            y: 5,
            itemId: 'upload',
            text: 'Upload'
        },
        {
            xtype: 'filefield',
            x: 175,
            y: 5,
            width: 210
        },
        {
            xtype: 'label',
            x: 5,
            y: 5,
            itemId: 'importstockcountsfromexcel',
            text: 'ImportStockCountfromExcel'
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 45,
            itemId: 'date',
            fieldLabel: 'Date',
            name: 'Date'
        },
        {
            xtype: 'textfield',
            x: 285,
            y: 80
        },
        {
            xtype: 'combobox',
            x: 5,
            y: 80,
            itemId: 'store',
            fieldLabel: 'Store',
            name: 'Store'
        },
        {
            xtype: 'gridpanel',
            x: 5,
            y: 115,
            height: 260,
            title: 'My Grid Panel',
            bind: {
                store: '{stockCountsModels}'
            },
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Item_ID',
                    text: 'Item Id'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Description',
                    text: 'Description'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Qty_in_Store',
                    text: 'Qty In Store'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Count_Qty',
                    text: 'Count Qty'
                }
            ]
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 380,
            itemId: 'totalitemscounted',
            fieldLabel: 'TotalItemsCounted',
            name: 'TotalItemsCounted'
        },
        {
            xtype: 'button',
            x: 110,
            y: 415,
            itemId: 'exportcounts',
            text: 'ExportCounts'
        },
        {
            xtype: 'button',
            x: 735,
            y: 380,
            itemId: 'send',
            text: 'Send'
        },
        {
            xtype: 'button',
            x: 470,
            y: 415,
            itemId: 'clearimportedandrestartimport',
            text: 'ClearImportedandRestartImport'
        },
        {
            xtype: 'button',
            x: 355,
            y: 415,
            itemId: 'getproductslist',
            text: 'GetProductsList'
        },
        {
            xtype: 'button',
            x: 290,
            y: 415,
            itemId: 'delete',
            text: 'Delete'
        },
        {
            xtype: 'button',
            x: 215,
            y: 415,
            itemId: 'addrow',
            text: 'AddRow'
        }
    ]

});