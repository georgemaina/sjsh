/*
 * File: app/view/OrdersTemplate.js
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

Ext.define('Pharmacy.view.OrdersTemplate', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.orderstemplate',

    requires: [
        'Pharmacy.view.OrdersTemplateViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging'
    ],

    viewModel: {
        type: 'orderstemplate'
    },
    height: 457,
    itemId: 'orders',
    closable: true,
    collapsible: true,
    title: 'Requisition Template',

    bind: {
        store: 'OrdersTempStore'
    },
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'PartCode',
            text: 'Part Code'
        },
        {
            xtype: 'gridcolumn',
            width: 263,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            width: 151,
            dataIndex: 'PurchasingUnit',
            text: 'Purchasing Unit'
        },
        {
            xtype: 'gridcolumn',
            width: 54,
            dataIndex: 'Level',
            text: 'Level'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'MonthlyUsage',
            text: 'Monthly Usage'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'ReorderLevel',
            text: 'Reorder Level'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'PartCode',
            text: 'Part Code'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'PurchasingUnit',
            text: 'Purchasing Unit'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Level',
            text: 'Level'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'MonthlyUsage',
            text: 'Monthly Usage'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'ReorderLevel',
            text: 'Reorder Level'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'OrdersTempStore'
        }
    ]

});