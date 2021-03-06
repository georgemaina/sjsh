/*
 * File: app/view/Receipts.js
 * Date: Thu Jun 11 2020 17:14:24 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.4.
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

Ext.define('Inpatient.view.Receipts', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.receipts',

    requires: [
        'Inpatient.view.BillsViewModel1',
        'Ext.view.Table',
        'Ext.grid.plugin.RowEditing',
        'Ext.toolbar.Paging',
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.selection.CheckboxModel',
        'Ext.grid.column.Date'
    ],

    viewModel: {
        type: 'receipts'
    },
    flex: 1,
    itemId: 'receipts',
    title: 'Receipts Management',
    columnLines: true,
    store: 'ReceiptStore',

    plugins: [
        {
            ptype: 'rowediting'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'ReceiptStore'
        },
        {
            xtype: 'fieldset',
            dock: 'top',
            height: 43,
            padding: '0 1 0 0',
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 10,
                    y: 5,
                    itemId: 'txtSearchReceipt',
                    width: 285,
                    emptyText: 'Search PID, Receipt No.....'
                },
                {
                    xtype: 'button',
                    x: 300,
                    y: 5,
                    itemId: 'cmdSearchReceipt',
                    width: 85,
                    text: 'Search'
                },
                {
                    xtype: 'button',
                    x: 390,
                    y: 5,
                    itemId: 'cmdUpdateReceipts',
                    width: 220,
                    text: 'Update Changes to the Receipt'
                },
                {
                    xtype: 'button',
                    x: 615,
                    y: 5,
                    itemId: 'cmdDeleteReceipts',
                    width: 160,
                    text: 'Remove Selected Rows'
                },
                {
                    xtype: 'button',
                    x: 780,
                    y: 5,
                    itemId: 'cmdReprintReceipt',
                    text: 'Reprint Receipt'
                }
            ]
        }
    ],
    selModel: {
        selType: 'checkboxmodel'
    },
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'Sale_ID',
            text: 'Sale Id'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Patient',
            text: 'Pid',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Payer',
            text: 'Payer'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Customer',
            text: 'Customer'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'ReceiptNo',
            text: 'Receipt No'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Pay_Mode',
            text: 'Pay Mode',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'datecolumn',
            dataIndex: 'ReceiptDate',
            text: 'Receipt Date',
            format: 'm/j/Y'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'InputTime',
            text: 'Input Time'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'CashPoint',
            text: 'Cash Point',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'ShiftNo',
            text: 'Shift No',
            editor: {
                xtype: 'textfield'
            }
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
            dataIndex: 'ServiceType',
            text: 'Service Type'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Amount',
            text: 'Price',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Total',
            text: 'Total',
            editor: {
                xtype: 'textfield'
            }
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Cashier',
            text: 'Cashier'
        }
    ]

});