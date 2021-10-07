/*
 * File: app/view/PaymentReceipts.js
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

Ext.define('CarePortal.view.PaymentReceipts', {
    extend: 'Ext.form.Panel',
    alias: 'widget.paymentreceipts',

    requires: [
        'CarePortal.view.PaymentReceiptsViewModel',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column'
    ],

    viewModel: {
        type: 'receipts'
    },
    height: 503,
    width: 789,
    layout: 'absolute',
    bodyPadding: 10,

    items: [
        {
            xtype: 'textfield',
            x: 410,
            y: 40,
            itemId: 'cheque',
            width: 360,
            fieldLabel: 'Cheque',
            labelAlign: 'right',
            name: 'Cheque'
        },
        {
            xtype: 'datefield',
            x: 410,
            y: 5,
            itemId: 'date',
            width: 360,
            fieldLabel: 'Date',
            labelAlign: 'right',
            name: 'Date'
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 5,
            itemId: 'receiptnumber',
            fieldLabel: 'Receipt No',
            name: 'ReceiptNumber'
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 145,
            itemId: 'toward',
            width: 445,
            fieldLabel: 'Toward',
            name: 'Toward'
        },
        {
            xtype: 'combobox',
            x: 495,
            y: 110,
            itemId: 'drawerbank',
            fieldLabel: 'Drawer Bank',
            labelAlign: 'right',
            name: 'DrawersBank'
        },
        {
            xtype: 'textfield',
            x: 425,
            y: 425,
            itemId: 'total',
            width: 345,
            fieldLabel: 'Total',
            name: 'Total'
        },
        {
            xtype: 'textfield',
            x: 215,
            y: 40,
            width: 235
        },
        {
            xtype: 'textfield',
            x: 600,
            y: 75
        },
        {
            xtype: 'textfield',
            x: 215,
            y: 75,
            width: 235
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 110,
            itemId: 'payee',
            width: 445,
            fieldLabel: 'Payee',
            name: 'Payee'
        },
        {
            xtype: 'textfield',
            x: 410,
            y: 75,
            itemId: 'glaccount',
            width: 190,
            fieldLabel: 'GL Acc:',
            labelAlign: 'right',
            name: 'GLAccount'
        },
        {
            xtype: 'textfield',
            x: 5,
            y: 75,
            itemId: 'patientnumber',
            width: 210,
            fieldLabel: 'Patient No:(PID)',
            name: 'PatientNumber'
        },
        {
            xtype: 'combobox',
            x: 5,
            y: 40,
            itemId: 'paymentmode',
            width: 210,
            fieldLabel: 'Payment Mode',
            name: 'PaymentMode'
        },
        {
            xtype: 'button',
            x: 530,
            y: 460,
            itemId: 'save',
            width: 145,
            text: 'Save'
        },
        {
            xtype: 'gridpanel',
            x: 10,
            y: 185,
            height: 235,
            title: 'Receipt Details',
            viewConfig: {
                width: 811
            },
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'ledger',
                    text: 'Ledger'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'code',
                    text: 'Code'
                },
                {
                    xtype: 'gridcolumn',
                    width: 321,
                    dataIndex: 'name',
                    text: 'Name'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'amount',
                    text: 'Amount'
                }
            ]
        }
    ]

});