/*
 * File: app/view/PharmacyRevenue.js
 * Date: Mon Mar 09 2020 09:46:08 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.PharmacyRevenue', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.pharmacyrevenue',

    requires: [
        'ReportsMain.view.PharmacyRevenueViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Number',
        'Ext.toolbar.Paging',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.button.Button',
        'Ext.form.field.Display'
    ],

    viewModel: {
        type: 'pharmacyrevenue'
    },
    height: 600,
    itemId: 'revenueByItem',
    closable: true,
    collapsible: true,
    title: 'Pharmacy Revenue By Item',
    columnLines: true,
    store: 'PharmacyRevenueStore',

    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'ItemNo',
            text: 'Item No'
        },
        {
            xtype: 'gridcolumn',
            width: 311,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            width: 155,
            dataIndex: 'Category',
            text: 'Category'
        },
        {
            xtype: 'numbercolumn',
            align: 'right',
            dataIndex: 'UnitPrice',
            text: 'Unit Price'
        },
        {
            xtype: 'numbercolumn',
            align: 'right',
            dataIndex: 'Quantities',
            text: 'Quantities',
            format: '000'
        },
        {
            xtype: 'numbercolumn',
            width: 125,
            align: 'right',
            dataIndex: 'TotalAmount',
            text: 'Total Amount'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'PharmacyRevenueStore'
        },
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'datefield',
                    itemId: 'StartDate',
                    width: 232,
                    fieldLabel: 'Start Date',
                    labelWidth: 70,
                    format: 'd-m-Y'
                },
                {
                    xtype: 'datefield',
                    itemId: 'EndDate',
                    width: 232,
                    fieldLabel: 'End Date',
                    labelWidth: 60,
                    format: 'd-m-Y'
                },
                {
                    xtype: 'combobox',
                    itemId: 'EndDate1',
                    width: 232,
                    labelWidth: 60,
                    emptyText: 'Select Revenue Type',
                    store: [
                        'Cash',
                        'Insurance',
                        'Inpatient'
                    ]
                },
                {
                    xtype: 'button',
                    itemId: 'cmdPreviewPharmacyrevenue',
                    width: 102,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    itemId: 'cmdPrintPharmacyrevenue',
                    width: 117,
                    text: '<b>Print</b>'
                },
                {
                    xtype: 'button',
                    itemId: 'cmdExportPharmacyrevenue',
                    width: 117,
                    text: '<b>Export to Excel</b>'
                }
            ]
        },
        {
            xtype: 'container',
            dock: 'bottom',
            height: 35,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'displayfield',
                    x: 700,
                    y: 0,
                    itemId: 'totals',
                    width: 210,
                    fieldLabel: 'Total Value',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    value: '',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                }
            ]
        }
    ]

});