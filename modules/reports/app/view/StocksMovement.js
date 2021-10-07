/*
 * File: app/view/StocksMovement.js
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

Ext.define('ReportsMain.view.StocksMovement', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.stocksmovement',

    requires: [
        'ReportsMain.view.StocksMovementViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging',
        'Ext.form.FieldContainer',
        'Ext.form.field.Date',
        'Ext.button.Button',
        'Ext.form.field.ComboBox',
        'Ext.grid.feature.GroupingSummary'
    ],

    viewModel: {
        type: 'stocksmovement'
    },
    height: 615,
    title: 'Stocks Movement',
    columnLines: true,
    store: 'StockMovementStore',

    columns: [
        {
            xtype: 'gridcolumn',
            summaryRenderer: function(val, params, data, metaData) {
                return ((value === 0 || value > 1) ? '(' + value + ' Drugs)' : '(1 Drug)');
            },
            summaryType: 'count',
            dataIndex: 'PartCode',
            text: 'PartCode'
        },
        {
            xtype: 'gridcolumn',
            width: 119,
            dataIndex: 'UnitsMeasure',
            text: 'Units Measure'
        },
        {
            xtype: 'gridcolumn',
            width: 152,
            dataIndex: 'Date',
            text: 'Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'TransType',
            text: 'Trans Type'
        },
        {
            xtype: 'gridcolumn',
            width: 81,
            dataIndex: 'TransNo',
            text: 'Trans No'
        },
        {
            xtype: 'gridcolumn',
            width: 273,
            dataIndex: 'Narration',
            text: 'Narration'
        },
        {
            xtype: 'gridcolumn',
            width: 90,
            dataIndex: 'Location',
            text: 'Location'
        },
        {
            xtype: 'gridcolumn',
            width: 99,
            align: 'right',
            dataIndex: 'Cost',
            text: 'Cost'
        },
        {
            xtype: 'gridcolumn',
            summaryRenderer: function(val, params, data, metaData) {
                return value + ' Records';
            },
            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                return value;
            },
            summaryType: 'sum',
            width: 89,
            dataIndex: 'Qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            width: 79,
            dataIndex: 'Level',
            text: 'Level'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Operator',
            text: 'Operator'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'StockMovementStore'
        },
        {
            xtype: 'fieldcontainer',
            dock: 'top',
            height: 78,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'datefield',
                    x: 15,
                    y: 5,
                    itemId: 'startDate',
                    width: 205,
                    fieldLabel: 'Start Date',
                    labelWidth: 70,
                    ariaFormat: 'd-m-Y',
                    format: 'd-m-Y',
                    submitFormat: 'Y-m-d'
                },
                {
                    xtype: 'datefield',
                    x: 225,
                    y: 5,
                    itemId: 'endDate',
                    width: 200,
                    fieldLabel: 'End Date',
                    labelWidth: 70,
                    format: 'd-m-Y',
                    submitFormat: 'Y-m-d'
                },
                {
                    xtype: 'button',
                    x: 1005,
                    y: 5,
                    itemId: 'cmdPreviewMovement',
                    width: 85,
                    text: 'Preview'
                },
                {
                    xtype: 'button',
                    x: 1095,
                    y: 5,
                    itemId: 'cmdPrintMovement',
                    width: 85,
                    text: 'Print'
                },
                {
                    xtype: 'button',
                    x: 1005,
                    y: 40,
                    itemId: 'cmdExportMovement',
                    width: 85,
                    text: 'Export'
                },
                {
                    xtype: 'combobox',
                    x: 705,
                    y: 5,
                    itemId: 'Category',
                    width: 290,
                    fieldLabel: 'Category',
                    labelWidth: 60,
                    displayField: 'Category',
                    minChars: 2,
                    store: 'DrugCategoryStore',
                    typeAhead: true,
                    valueField: 'CatID'
                },
                {
                    xtype: 'combobox',
                    x: 705,
                    y: 40,
                    itemId: 'TransTypes',
                    width: 290,
                    fieldLabel: 'Types',
                    labelWidth: 60,
                    emptyText: 'Select Type oF Movement',
                    displayField: 'typeName',
                    minChars: 2,
                    queryMode: 'local',
                    store: 'TransTypeStore',
                    typeAhead: true,
                    valueField: 'typeID'
                },
                {
                    xtype: 'combobox',
                    x: 435,
                    y: 5,
                    itemId: 'Location',
                    fieldLabel: 'From Location',
                    labelWidth: 90,
                    emptyText: 'Movement from location',
                    displayField: 'Description',
                    minChars: 3,
                    queryMode: 'local',
                    store: 'LocationsStore',
                    typeAhead: true,
                    valueField: 'LocCode'
                },
                {
                    xtype: 'combobox',
                    x: 435,
                    y: 40,
                    itemId: 'Location2',
                    fieldLabel: 'To Location',
                    labelWidth: 90,
                    emptyText: 'Movement to Location',
                    displayField: 'Description',
                    minChars: 3,
                    queryMode: 'local',
                    store: 'LocationsStore',
                    typeAhead: true,
                    valueField: 'LocCode'
                },
                {
                    xtype: 'combobox',
                    x: 10,
                    y: 40,
                    itemId: 'PartCode',
                    width: 415,
                    fieldLabel: 'Items',
                    labelWidth: 75,
                    displayField: 'Description',
                    minChars: 3,
                    pageSize: 2000,
                    queryMode: 'local',
                    store: 'ItemsListStore',
                    typeAhead: true,
                    valueField: 'PartCode'
                }
            ]
        }
    ],
    features: [
        {
            ftype: 'groupingsummary',
            enableGroupingMenu: false,
            groupHeaderTpl: '{columnName}: {name} ({rows.length} Total{[values.rows.length > 1 ? "s" : ""]})',
            hideGroupedHeader: true,
            showGroupsText: 'Description'
        }
    ]

});