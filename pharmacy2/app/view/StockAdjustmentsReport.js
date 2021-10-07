/*
 * File: app/view/StockAdjustmentsReport.js
 * Date: Tue Feb 18 2020 14:50:08 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Pharmacy.view.StockAdjustmentsReport', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.stockadjustmentsreport',

    requires: [
        'Pharmacy.view.StockAdjustmentsReportViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.form.FieldContainer',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.button.Button',
        'Ext.toolbar.Paging'
    ],

    viewModel: {
        type: 'stockadjustmentsreport'
    },
    title: 'Stocks Adjustments',
    store: 'StockAdjustmentsStore',

    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'ID',
            text: 'ID'
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
            dataIndex: 'PrevQty',
            text: 'Prev Qty'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'NewQty',
            text: 'New Qty'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'InputUser',
            text: 'Input User'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'AdjDate',
            text: 'Adj Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'AdjTime',
            text: 'Adj Time'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Reason',
            text: 'Reason'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Store',
            text: 'Store'
        }
    ],
    dockedItems: [
        {
            xtype: 'fieldcontainer',
            dock: 'top',
            height: 43,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'datefield',
                    x: 10,
                    y: 5,
                    itemId: 'startdate',
                    width: 205,
                    fieldLabel: 'Start Date',
                    labelWidth: 70
                },
                {
                    xtype: 'textfield',
                    x: 445,
                    y: 5,
                    itemId: 'partcode',
                    width: 190,
                    fieldLabel: 'PartCode',
                    labelAlign: 'right',
                    labelWidth: 70
                },
                {
                    xtype: 'combobox',
                    x: 645,
                    y: 5,
                    itemId: 'ordLoc',
                    fieldLabel: 'Location',
                    labelAlign: 'right',
                    labelWidth: 70,
                    displayField: 'Description',
                    minChars: 2,
                    queryMode: 'local',
                    store: 'DepartmentStore',
                    typeAhead: true,
                    valueField: 'ID'
                },
                {
                    xtype: 'datefield',
                    x: 225,
                    y: 5,
                    itemId: 'enddate',
                    width: 205,
                    fieldLabel: 'End Date',
                    labelWidth: 70
                },
                {
                    xtype: 'button',
                    x: 900,
                    y: 5,
                    itemId: 'cmdPreviewAdjustments',
                    width: 150,
                    iconCls: 'x-fa fa-search',
                    text: 'Preview Report'
                }
            ]
        },
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'StockAdjustmentsStore'
        }
    ]

});