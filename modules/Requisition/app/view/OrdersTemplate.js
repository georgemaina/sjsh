/*
 * File: app/view/OrdersTemplate.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
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

Ext.define('Requisition.view.OrdersTemplate', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.orderstemplate',

    requires: [
        'Requisition.view.OrdersTemplateViewModel',
        'Ext.toolbar.Paging',
        'Ext.form.FieldContainer',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.column.Column',
        'Ext.grid.plugin.RowEditing',
        'Ext.selection.RowModel'
    ],

    viewModel: {
        type: 'orderstemplate'
    },
    reference: 'orderstemplate',
    itemId: 'ordersTemp',
    closable: true,
    collapsible: true,
    title: 'Orders Template',
    columnLines: true,
    store: 'OrdersTemplateStore',

    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'OrdersTemplateStore'
        },
        {
            xtype: 'fieldcontainer',
            dock: 'top',
            height: 50,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 5,
                    itemId: 'txtSearchParams',
                    width: 240,
                    emptyText: 'Search By Partcode,description'
                },
                {
                    xtype: 'button',
                    x: 250,
                    y: 5,
                    height: 30,
                    itemId: 'cmdSearchItems',
                    width: 85,
                    text: 'Search'
                },
                {
                    xtype: 'button',
                    x: 250,
                    y: 5,
                    height: 30,
                    itemId: 'cmdSearchItems1',
                    width: 85,
                    text: 'Search'
                },
                {
                    xtype: 'button',
                    x: 390,
                    y: 5,
                    height: 30,
                    itemId: 'cmdUpdateOrder',
                    width: 205,
                    text: 'Update Quantity to Order'
                },
                {
                    xtype: 'button',
                    x: 605,
                    y: 5,
                    height: 30,
                    itemId: 'cmdExport',
                    width: 125,
                    text: 'Export to Excel'
                },
                {
                    xtype: 'button',
                    x: 875,
                    y: 5,
                    height: 30,
                    itemId: 'cmdCompleteOrders',
                    width: 155,
                    text: 'Complete the Order'
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            width: 56,
            dataIndex: 'ID',
            lockable: true,
            locked: true,
            text: 'ID'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'StockID',
            lockable: true,
            locked: true,
            text: 'Part Code'
        },
        {
            xtype: 'gridcolumn',
            width: 244,
            dataIndex: 'Description',
            lockable: true,
            locked: true,
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            width: 128,
            dataIndex: 'PurchasingUnit',
            text: 'Purchasing Unit'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Level',
            text: 'Level',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'MonthlyUsage',
            text: 'Monthly Usage',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'PreviousMonthUsage',
            text: 'Previoue Month Usage'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'LastQuarterUsage',
            text: 'Last Quarter Usage'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'PreviousYearUsage',
            text: 'Previous Year Usage'
        },
        {
            xtype: 'gridcolumn',
            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                return record.get('MonthlyUsage')*2.5;
            },
            dataIndex: 'ReorderLevel',
            text: 'Reorder Level'
        },
        {
            xtype: 'gridcolumn',
            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                return record.get('MonthlyUsage')*4;
            },
            dataIndex: 'MaximumLevel',
            text: 'Maximum Level'
        },
        {
            xtype: 'gridcolumn',
            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                if(record.get('MaximumLevel')>record.get('Level')){
                    return Math.round((record.get('MaximumLevel')-record.get('Level')),2);
                }else{
                    return false;
                }
            },
            dataIndex: 'IFToOrder',
            text: 'IF To Order'
        },
        {
            xtype: 'gridcolumn',
            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                return Math.round(record.get('IFToOrder'),record.get('SuggestedOrder'));
            },
            dataIndex: 'OrderInMultiplesOf',
            text: 'Suggested Order'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'OrderInMultiplesOf',
            text: 'Order In Multiples Of'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'QtyToOrder',
            text: 'Qty To Order',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'AlternateSupplier',
            text: 'Alternate Supplier'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'SupplierCode',
            text: 'Supplier'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Comments',
            text: 'Comment'
        }
    ],
    plugins: [
        {
            ptype: 'rowediting'
        }
    ],
    selModel: {
        selType: 'rowmodel'
    }

});