/*
 * File: app/view/MainView.js
 * Date: Fri Mar 06 2020 12:16:44 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Pharmacy.view.MainView', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.mainview',

    requires: [
        'Pharmacy.view.MainViewViewModel',
        'Ext.menu.Menu',
        'Ext.menu.Item'
    ],

    viewModel: {
        type: 'mainview'
    },
    height: 250,
    width: 400,
    layout: 'border',

    items: [
        {
            xtype: 'container',
            region: 'center',
            itemId: 'centerContainer',
            padding: '2 0 0 10',
            layout: 'fit'
        },
        {
            xtype: 'container',
            region: 'west',
            style: 'background-color: #d9f2e6;',
            width: 201,
            layout: 'accordion',
            items: [
                {
                    xtype: 'menu',
                    floating: false,
                    width: 120,
                    title: 'Pharmcy',
                    items: [
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuPrescriptions',
                            iconCls: '',
                            text: 'Prescriptions'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuIssue',
                            text: 'Issue to Patients'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuOrders',
                            text: 'Internal Orders'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuServiceOrders',
                            text: 'Service Orders'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuPatientReturns',
                            text: 'Returns from Patients'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuOrdersReturns',
                            text: 'Returns Internal Orders'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuStockLevels',
                            text: 'Stock Levels'
                        }
                    ]
                },
                {
                    xtype: 'menu',
                    floating: false,
                    width: 120,
                    title: 'Reports',
                    items: [
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuIssuedDrugs',
                            iconCls: 'x-fa fa-anchor',
                            text: 'Issues to Patients'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuInternalOrders',
                            iconCls: 'x-fa fa-atlas',
                            text: 'Internal Orders'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuStockMovements',
                            text: 'Stocks Movements'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuStockAdjustments',
                            text: 'Stocks Adjustments'
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuStockValuation',
                            text: 'Valuation Report'
                        },
                        {
                            xtype: 'menuitem',
                            text: 'Returns from Patients'
                        }
                    ]
                },
                {
                    xtype: 'menu',
                    floating: false,
                    width: 120,
                    title: 'Settings',
                    items: [
                        {
                            xtype: 'menuitem',
                            text: 'Locations'
                        },
                        {
                            xtype: 'menuitem',
                            text: 'Items Category'
                        },
                        {
                            xtype: 'menuitem',
                            text: 'Menu Item'
                        }
                    ]
                }
            ]
        }
    ]

});