/*
 * File: app/view/AllocateReceipts.js
 * Date: Mon Oct 01 2018 12:14:19 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.view.AllocateReceipts', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.allocatereceipts',

    requires: [
        'Debtors.view.AllocateReceiptsViewModel',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging',
        'Ext.button.Button',
        'Ext.selection.CheckboxModel',
        'Ext.grid.plugin.CellEditing',
        'Ext.form.field.Date',
        'Ext.form.field.Display'
    ],

    viewModel: {
        type: 'allocatereceipts'
    },
    frame: true,
    height: 512,
    width: 1214,
    layout: 'absolute',
    animCollapse: true,

    initConfig: function(instanceConfig) {
        var me = this,
            config = {
                items: [
                    {
                        xtype: 'gridpanel',
                        x: 0,
                        y: 5,
                        height: 440,
                        itemId: 'allocateReceiptsGrid',
                        width: 530,
                        title: 'Receipts',
                        columnLines: true,
                        store: 'ReceiptsAllocateSt',
                        columns: [
                            {
                                xtype: 'gridcolumn',
                                width: 52,
                                dataIndex: 'transno',
                                text: 'Transno'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 64,
                                dataIndex: 'accno',
                                text: 'Accno'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 161,
                                dataIndex: 'debtorname',
                                text: 'Debtorname'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 79,
                                dataIndex: 'transdate',
                                text: 'Transdate'
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'bill_number',
                                text: 'Bill_Number'
                            },
                            {
                                xtype: 'gridcolumn',
                                dataIndex: 'InvoiceAmount',
                                text: 'Receipt Amount'
                            }
                        ],
                        dockedItems: [
                            {
                                xtype: 'pagingtoolbar',
                                dock: 'bottom',
                                width: 360,
                                displayInfo: true,
                                store: 'ReceiptsAllocateSt'
                            },
                            {
                                xtype: 'toolbar',
                                dock: 'top',
                                height: 41,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        itemId: 'txtReceipts',
                                        width: 205,
                                        emptyText: 'Search by Accno, DebtorName'
                                    },
                                    {
                                        xtype: 'button',
                                        width: 91,
                                        text: '<b>Search</b>'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'gridpanel',
                        x: 535,
                        y: 5,
                        height: 440,
                        itemId: 'allocateInvoicesGrid',
                        width: 670,
                        title: 'Invoices',
                        columnLines: true,
                        store: 'InvoiceAllocateSt',
                        selModel: Ext.create('Ext.selection.CheckboxModel', {
                            selType: 'checkboxmodel',
                            mode: 'SIMPLE'
                        }),
                        plugins: [
                            Ext.create('Ext.grid.plugin.CellEditing', {
                                clicksToEdit: 1
                            })
                        ],
                        dockedItems: [
                            {
                                xtype: 'pagingtoolbar',
                                dock: 'bottom',
                                width: 360,
                                displayInfo: true,
                                store: 'InvoiceAllocateSt'
                            },
                            {
                                xtype: 'container',
                                dock: 'top',
                                height: 45,
                                width: 100,
                                layout: 'absolute',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        x: 5,
                                        y: 5,
                                        itemId: 'txtInvoiceSearch',
                                        width: 180,
                                        blankText: 'Search by PId, BillNumber, Name',
                                        emptyText: 'Search by PId, BillNumber, Name'
                                    },
                                    {
                                        xtype: 'button',
                                        x: 510,
                                        y: 5,
                                        itemId: 'cmdSearchInvoices',
                                        width: 135,
                                        text: 'Search'
                                    },
                                    {
                                        xtype: 'datefield',
                                        x: 360,
                                        y: 5,
                                        itemId: 'endDate',
                                        width: 155,
                                        labelWidth: 60,
                                        emptyText: 'EndDate'
                                    },
                                    {
                                        xtype: 'datefield',
                                        x: 200,
                                        y: 5,
                                        itemId: 'startDate',
                                        width: 155,
                                        labelWidth: 60,
                                        emptyText: 'StartDate'
                                    }
                                ]
                            }
                        ],
                        columns: [
                            {
                                xtype: 'gridcolumn',
                                width: 48,
                                dataIndex: 'transno',
                                text: 'No'
                            },
                            {
                                xtype: 'gridcolumn',
                                hidden: true,
                                dataIndex: 'accno',
                                text: 'Accno'
                            },
                            {
                                xtype: 'gridcolumn',
                                hidden: true,
                                dataIndex: 'debtorname',
                                text: 'Debtorname'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 52,
                                dataIndex: 'pid',
                                text: 'Pid'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 165,
                                dataIndex: 'pname',
                                text: 'Patient Name'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 71,
                                dataIndex: 'bill_number',
                                text: 'Bill No'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 106,
                                dataIndex: 'memberNo',
                                text: 'Member No'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 91,
                                dataIndex: 'transdate',
                                text: 'Transdate'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 102,
                                align: 'end',
                                dataIndex: 'InvoiceAmount',
                                text: 'Invoice Amount'
                            },
                            {
                                xtype: 'gridcolumn',
                                width: 123,
                                align: 'end',
                                dataIndex: 'AllocatedAmount',
                                text: 'Allocated Amount',
                                editor: {
                                    xtype: 'textfield'
                                }
                            }
                        ]
                    },
                    {
                        xtype: 'displayfield',
                        x: 515,
                        y: 455,
                        itemId: 'totals',
                        width: 205,
                        fieldLabel: '<b>TOTAL AMOUNT TO ALLOCATE</b>',
                        labelStyle: 'font-size: small;font-weight: bold;color: green;',
                        labelWidth: 200,
                        name: 'totals',
                        fieldStyle: 'font-size: large;font-weight: bold;color: red;'
                    },
                    {
                        xtype: 'textfield',
                        x: 395,
                        y: 455,
                        itemId: 'accno',
                        width: 85,
                        name: 'accno'
                    },
                    {
                        xtype: 'displayfield',
                        x: 25,
                        y: 455,
                        itemId: 'totalReceipt',
                        width: 205,
                        fieldLabel: '<b>TOTAL RECEIPT AMOUNT TO ALLOCATE</b>',
                        labelStyle: 'font-size: small;font-weight: bold;color: green;',
                        labelWidth: 250,
                        name: 'totals',
                        fieldStyle: 'font-size: large;font-weight: bold;color: red;'
                    },
                    {
                        xtype: 'button',
                        x: 895,
                        y: 450,
                        height: 40,
                        itemId: 'cmdAllocateReceipt',
                        width: 135,
                        text: '<b>Allocate Receipt</b>'
                    },
                    {
                        xtype: 'button',
                        x: 1035,
                        y: 450,
                        height: 40,
                        itemId: 'cmdClose',
                        width: 120,
                        text: '<b>Close</b>'
                    }
                ]
            };
        if (instanceConfig) {
            me.self.getConfigurator().merge(me, config, instanceConfig);
        }
        return me.callParent([config]);
    }

});