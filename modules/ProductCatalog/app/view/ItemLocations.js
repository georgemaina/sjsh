/*
 * File: app/view/ItemLocations.js
 * Date: Mon Sep 24 2018 16:07:55 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ProductCatalog.view.ItemLocations', {
    extend: 'Ext.form.Panel',
    alias: 'widget.itemlocations',

    requires: [
        'ProductCatalog.view.PriceTypesViewModel1',
        'Ext.form.field.ComboBox',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'itemlocations'
    },
    height: 194,
    width: 347,
    layout: 'absolute',
    bodyPadding: 10,
    url: 'data/getDataFunctions.php?task=insertNewItemLocation',

    items: [
        {
            xtype: 'textfield',
            x: 10,
            y: 10,
            tabIndex: 1,
            fieldLabel: 'PartCode',
            name: 'PartCode'
        },
        {
            xtype: 'textfield',
            x: 10,
            y: 115,
            tabIndex: 3,
            fieldLabel: 'Quantity',
            name: 'Quantity'
        },
        {
            xtype: 'textfield',
            x: 135,
            y: 120,
            hidden: true,
            itemId: 'formStatus',
            width: 80,
            name: 'formStatus'
        },
        {
            xtype: 'combobox',
            x: 10,
            y: 45,
            tabIndex: 2,
            fieldLabel: 'Location',
            name: 'LocCode',
            displayField: 'Description',
            minChars: 2,
            queryMode: 'local',
            store: 'LocationsStore',
            typeAhead: true,
            valueField: 'ID'
        },
        {
            xtype: 'combobox',
            x: 10,
            y: 80,
            tabIndex: 2,
            fieldLabel: 'Item Status',
            name: 'Item_Status',
            displayField: 'Description',
            minChars: 2,
            queryMode: 'local',
            store: 'ItemStatusStore',
            typeAhead: true,
            valueField: 'ID'
        },
        {
            xtype: 'button',
            x: 40,
            y: 155,
            tabIndex: 4,
            itemId: 'cmdSave',
            width: 75,
            text: 'Save'
        },
        {
            xtype: 'button',
            x: 255,
            y: 155,
            tabIndex: 5,
            itemId: 'cmdClose',
            width: 75,
            text: 'Close'
        },
        {
            xtype: 'button',
            x: 145,
            y: 155,
            itemId: 'cmdDelete',
            width: 75,
            text: 'Delete'
        }
    ]

});