/*
 * File: app/view/ResultsGrid.js
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

Ext.define('ReportsMain.view.ResultsGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.resultsgrid',

    requires: [
        'ReportsMain.view.ResultsGridViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.form.field.Display',
        'Ext.toolbar.Paging'
    ],

    viewModel: {
        type: 'resultsgrid'
    },
    border: false,
    height: 615,
    scrollable: {
        x: true,
        y: true
    },
    title: 'Results Grid',
    columnLines: true,
    store: 'RevenueStore',

    viewConfig: {
        scrollable: {
            x: true,
            y: true
        }
    },
    columns: [
        {
            xtype: 'gridcolumn',
            width: 235,
            dataIndex: 'Category',
            text: 'Category'
        },
        {
            xtype: 'gridcolumn',
            align: 'right',
            dataIndex: 'Amount',
            text: 'Amount'
        }
    ],
    dockedItems: [
        {
            xtype: 'container',
            dock: 'bottom',
            height: 24,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'displayfield',
                    x: 200,
                    y: -3,
                    itemId: 'txtTotal',
                    width: 115,
                    fieldLabel: 'Total',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    value: '',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                }
            ]
        },
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'RevenueStore'
        }
    ]

});