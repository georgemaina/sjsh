/*
 * File: app/view/ServicesList.js
 * Date: Fri Jun 19 2020 11:20:13 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Inpatient.view.ServicesList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.serviceslist',

    requires: [
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging'
    ],

    height: 414,
    itemId: 'serviceList',
    width: 619,
    bodyStyle: 'background-color: #d9f2e6;',
    columnLines: true,
    store: 'ServiceListStore',

    dockedItems: [
        {
            xtype: 'fieldset',
            dock: 'top',
            height: 46,
            padding: 0,
            style: 'background-color: #d9f2e6;',
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 5,
                    itemId: 'txtSearchItems',
                    padding: 0,
                    width: 380,
                    emptyText: 'Search by Description, PartCode'
                },
                {
                    xtype: 'textfield',
                    x: 390,
                    y: 5,
                    itemId: 'sourceID'
                }
            ]
        },
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'ServiceListStore'
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'partcode',
            text: 'Partcode'
        },
        {
            xtype: 'gridcolumn',
            width: 272,
            dataIndex: 'item_description',
            text: 'Item Description'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'unit_price',
            text: 'Unit Price'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            width: 135,
            dataIndex: 'purchasing_class',
            text: 'Purchasing Class'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            width: 150,
            dataIndex: 'category',
            text: 'Category'
        }
    ]

});