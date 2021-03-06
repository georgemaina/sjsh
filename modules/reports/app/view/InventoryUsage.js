/*
 * File: app/view/InventoryUsage.js
 * Date: Thu May 21 2020 09:09:10 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.InventoryUsage', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.inventoryusage',

    requires: [
        'ReportsMain.view.InventoryUsageViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.form.FieldContainer',
        'Ext.form.field.ComboBox'
    ],

    viewModel: {
        type: 'inventoryusage'
    },
    height: 615,
    closable: true,
    title: 'Inventory Usage',
    columnLines: true,

    bind: {
        store: 'InventoryUsageStore'
    },
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'Month',
            text: 'Month'
        },
        {
            xtype: 'gridcolumn',
            width: 137,
            dataIndex: 'OpeningStock',
            text: 'Opening Stock'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Received',
            text: 'Received'
        },
        {
            xtype: 'gridcolumn',
            width: 131,
            dataIndex: 'ClosingStock',
            text: 'Closing Stock'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Usage',
            text: 'Usage'
        }
    ],
    dockedItems: [
        {
            xtype: 'fieldcontainer',
            dock: 'top',
            height: 42,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'combobox',
                    x: 5,
                    y: 5,
                    width: 265,
                    emptyText: 'Select Item to view its Usage',
                    displayField: 'Description',
                    minChars: 3,
                    queryMode: 'local',
                    store: 'ItemsListStore',
                    typeAhead: true,
                    valueField: 'PartCode'
                },
                {
                    xtype: 'combobox',
                    x: 270,
                    y: 5,
                    width: 245,
                    emptyText: 'Select the Store Location of the Item',
                    displayField: 'Description',
                    minChars: 2,
                    queryMode: 'local',
                    store: 'LocationsStore',
                    typeAhead: true,
                    valueField: 'LocCode'
                }
            ]
        }
    ]

});