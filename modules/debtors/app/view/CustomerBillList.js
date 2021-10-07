/*
 * File: app/view/CustomerBillList.js
 * Date: Fri Sep 28 2018 10:01:07 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.view.CustomerBillList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.customerbilllist',

    requires: [
        'Debtors.view.CustomerBillListViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.toolbar.Spacer',
        'Ext.toolbar.Fill'
    ],

    viewModel: {
        type: 'customerbilllist'
    },
    height: 365,
    minHeight: 320,
    scrollable: true,
    width: 800,
    title: 'Bill Items List',
    store: 'CustomerBill',
    defaultListenerScope: true,

    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'pid',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'service_type',
            text: 'Service_type'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'partcode',
            text: 'Partcode'
        },
        {
            xtype: 'gridcolumn',
            width: 196,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'price',
            text: 'Price'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'total',
            text: 'Total'
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    text: 'Save'
                },
                {
                    xtype: 'button',
                    text: 'Update'
                },
                {
                    xtype: 'button',
                    text: 'Delete'
                },
                {
                    xtype: 'tbspacer',
                    height: 10,
                    width: 146
                },
                {
                    xtype: 'button',
                    text: 'Print Invoice',
                    listeners: {
                        click: 'onButtonClick1'
                    }
                },
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Close',
                    listeners: {
                        click: 'onButtonClick'
                    }
                }
            ]
        }
    ],

    onButtonClick1: function(button, e, eOpts) {

    },

    onButtonClick: function(button, e, eOpts) {

        var win2 = button.up('customerbill');
        var comp1=win2.down('CustomerInformation');
        var comp2=win2.down('CustomerBillList');

        win2.remove(comp1,comp2);
        win2.close();
    }

});