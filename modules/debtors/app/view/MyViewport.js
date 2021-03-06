/*
 * File: app/view/MyViewport.js
 * Date: Mon Aug 16 2021 14:09:43 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.view.MyViewport', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.myviewport',

    requires: [
        'Debtors.view.MyViewportViewModel',
        'Ext.tab.Panel',
        'Ext.tab.Tab',
        'Ext.menu.Menu',
        'Ext.menu.Item',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.form.field.ComboBox'
    ],

    viewModel: {
        type: 'myviewport'
    },
    layout: 'border',
    defaultListenerScope: true,

    items: [
        {
            xtype: 'container',
            region: 'center'
        },
        {
            xtype: 'container',
            region: 'west',
            resizable: false,
            width: 318,
            items: [
                {
                    xtype: 'tabpanel',
                    height: 933,
                    activeTab: 0,
                    items: [
                        {
                            xtype: 'panel',
                            height: 742,
                            id: 'debtorJobs',
                            layout: 'anchor',
                            title: 'Debtors & Jobs',
                            items: [
                                {
                                    xtype: 'panel',
                                    frame: true,
                                    height: 208,
                                    layout: 'absolute',
                                    animCollapse: true,
                                    collapsed: true,
                                    collapsible: true,
                                    title: 'Debtors Details',
                                    titleCollapse: true,
                                    items: [
                                        {
                                            xtype: 'menu',
                                            floating: false,
                                            items: [
                                                {
                                                    xtype: 'menuitem',
                                                    itemId: 'mnuDebtorsRegister',
                                                    icon: '../../icons/register.png',
                                                    text: 'Debtors Register',
                                                    focusable: true
                                                },
                                                {
                                                    xtype: 'menuitem',
                                                    itemId: 'mnuDebtorsList',
                                                    icon: '../../icons/list.png',
                                                    text: 'Debtors List',
                                                    focusable: true
                                                },
                                                {
                                                    xtype: 'menuitem',
                                                    itemId: 'mnuDebtorMembers',
                                                    icon: '../../icons/blogs-icon.png',
                                                    text: 'Debtor Members',
                                                    focusable: true
                                                },
                                                {
                                                    xtype: 'menuitem',
                                                    hidden: true,
                                                    itemId: 'mnuTransactions',
                                                    icon: '../../icons/document-next-icon.png',
                                                    text: 'Transactions',
                                                    focusable: true
                                                },
                                                {
                                                    xtype: 'menuitem',
                                                    itemId: 'mnuInvoices',
                                                    icon: '../../icons/report_check.png',
                                                    text: 'Invoices',
                                                    focusable: true
                                                },
                                                {
                                                    xtype: 'menuitem',
                                                    itemId: 'mnuReceipts',
                                                    icon: '../../icons/blogs-icon.png',
                                                    text: 'Receipts',
                                                    focusable: true
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype: 'gridpanel',
                                    height: 400,
                                    itemId: 'debtorBalances',
                                    maxHeight: 600,
                                    minHeight: 500,
                                    scrollable: true,
                                    animCollapse: true,
                                    collapsible: true,
                                    title: 'Balances',
                                    columnLines: true,
                                    store: 'CustomerTrans',
                                    viewConfig: {
                                        height: 288
                                    },
                                    columns: [
                                        {
                                            xtype: 'gridcolumn',
                                            width: 60,
                                            dataIndex: 'accno',
                                            text: 'Accno'
                                        },
                                        {
                                            xtype: 'gridcolumn',
                                            width: 145,
                                            dataIndex: 'names',
                                            text: 'Names'
                                        },
                                        {
                                            xtype: 'gridcolumn',
                                            align: 'end',
                                            dataIndex: 'balance',
                                            text: 'Balance'
                                        }
                                    ],
                                    dockedItems: [
                                        {
                                            xtype: 'container',
                                            dock: 'top',
                                            height: 30,
                                            width: 100,
                                            layout: 'absolute',
                                            items: [
                                                {
                                                    xtype: 'textfield',
                                                    x: 150,
                                                    y: 0,
                                                    itemId: 'txtSearchParam',
                                                    width: 165,
                                                    labelAlign: 'right',
                                                    labelWidth: 80,
                                                    name: 'txtFind',
                                                    emptyText: 'Find Debtor',
                                                    listeners: {
                                                        blur: 'onTextfieldBlur'
                                                    }
                                                },
                                                {
                                                    xtype: 'combobox',
                                                    x: 0,
                                                    y: 0,
                                                    stateful: true,
                                                    itemId: 'cbCategory',
                                                    width: 150,
                                                    labelAlign: 'right',
                                                    labelWidth: 80,
                                                    emptyText: 'Debtor Category',
                                                    displayField: 'custNames',
                                                    queryMode: 'local',
                                                    store: 'CustomerType',
                                                    typeAhead: true,
                                                    valueField: 'ID'
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'panel',
                            height: 1200,
                            id: 'debtorTrans',
                            width: 934,
                            title: 'Customer Transactions',
                            items: [
                                {
                                    xtype: 'menu',
                                    floating: false,
                                    width: 283,
                                    title: 'Transactions',
                                    items: [
                                        {
                                            xtype: 'menuitem',
                                            itemId: 'mnuAllocateReceipts',
                                            text: 'Allocate Receipts',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            itemId: 'mnuCredits',
                                            text: 'Miscellaneous Credit',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            itemId: 'mnuDebits',
                                            text: 'Miscellaneous Debit',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            itemId: 'mnuModifybills',
                                            text: 'Modify Bills',
                                            focusable: true
                                        }
                                    ]
                                },
                                {
                                    xtype: 'menu',
                                    floating: false,
                                    height: 261,
                                    width: 283,
                                    items: [
                                        {
                                            xtype: 'menuitem',
                                            id: 'debtorslist',
                                            text: 'Debtors List ',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            id: 'debtorslist1',
                                            itemId: 'mnuDebtorBalances',
                                            text: 'Debtor Balances',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            id: 'customers',
                                            text: 'Customers',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            height: 32,
                                            id: 'invoices',
                                            text: 'Invoices',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            id: 'receipts',
                                            text: 'Receipts',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            id: 'allocations',
                                            itemId: 'mnuAllocations',
                                            text: 'Allocations',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            text: 'Members expenditure',
                                            focusable: true
                                        },
                                        {
                                            xtype: 'menuitem',
                                            text: 'Member dependants',
                                            focusable: true
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ],

    onTextfieldBlur: function(component, event, eOpts) {
        var debtorjobs=Ext.data.StoreManager.lookup('CustomerTrans');
        debtorjobs.load({
            params: {
                accno: component.value
            },
            callback: function(records, operation, success) {


            },
            scope: this
        });
    }

});