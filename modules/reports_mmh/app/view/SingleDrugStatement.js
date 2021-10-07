/*
 * File: app/view/SingleDrugStatement.js
 * Date: Mon Oct 22 2018 10:41:52 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.SingleDrugStatement', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.singledrugstatement',

    requires: [
        'ReportsMain.view.SingleDrugStatementViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging',
        'Ext.button.Button',
        'Ext.form.field.Date',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Display'
    ],

    viewModel: {
        type: 'singledrugstatement'
    },
    frame: true,
    height: 550,
    title: 'Single Drug Statement',
    columnLines: true,
    store: 'DrugStatementStore',

    columns: [
        {
            xtype: 'gridcolumn',
            width: 126,
            dataIndex: 'OrderDate',
            text: 'Order Date'
        },
        {
            xtype: 'gridcolumn',
            width: 60,
            dataIndex: 'Pid',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 59,
            dataIndex: 'EncounterNo',
            text: 'Encounter No'
        },
        {
            xtype: 'gridcolumn',
            width: 181,
            dataIndex: 'PatientName',
            text: 'Patient Name'
        },
        {
            xtype: 'gridcolumn',
            width: 56,
            align: 'right',
            dataIndex: 'Price',
            text: 'Price'
        },
        {
            xtype: 'gridcolumn',
            width: 54,
            dataIndex: 'Dosage',
            text: 'Dosage'
        },
        {
            xtype: 'gridcolumn',
            width: 54,
            dataIndex: 'TimesPerDay',
            text: 'Times Per Day'
        },
        {
            xtype: 'gridcolumn',
            width: 40,
            dataIndex: 'Days',
            text: 'Days'
        },
        {
            xtype: 'gridcolumn',
            width: 62,
            dataIndex: 'TotalQty',
            text: 'Total Qty'
        },
        {
            xtype: 'gridcolumn',
            width: 59,
            dataIndex: 'Issued',
            text: 'Issued'
        },
        {
            xtype: 'gridcolumn',
            width: 45,
            dataIndex: 'Balance',
            text: 'Balance'
        },
        {
            xtype: 'gridcolumn',
            width: 72,
            align: 'right',
            dataIndex: 'TotalCost',
            text: 'TotalCost'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Prescriber',
            text: 'Prescriber'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Issuedby',
            text: 'Issuedby'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'DrugStatementStore'
        },
        {
            xtype: 'container',
            dock: 'top',
            height: 42,
            padding: '0 0 0 0',
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'button',
                    x: 855,
                    y: 5,
                    itemId: 'cmdSearchDrug',
                    width: 125,
                    text: '<b>Search</b>'
                },
                {
                    xtype: 'datefield',
                    x: 340,
                    y: 5,
                    itemId: 'StartDate',
                    fieldLabel: 'Start Date',
                    labelWidth: 70,
                    format: 'd-m-Y'
                },
                {
                    xtype: 'datefield',
                    x: 595,
                    y: 5,
                    itemId: 'EndDate',
                    fieldLabel: 'End Date',
                    labelWidth: 70,
                    format: 'd-m-Y'
                },
                {
                    xtype: 'combobox',
                    x: 5,
                    y: 5,
                    itemId: 'cmbDrugStatement',
                    width: 335,
                    displayField: 'Description',
                    minChars: 3,
                    queryMode: 'local',
                    store: 'ItemsListStore',
                    typeAhead: true,
                    valueField: 'PartCode'
                },
                {
                    xtype: 'button',
                    x: 995,
                    y: 5,
                    itemId: 'cmdPrintStatement',
                    width: 85,
                    text: 'Print '
                },
                {
                    xtype: 'button',
                    x: 1095,
                    y: 5,
                    itemId: 'cmdExportDrugStatement',
                    width: 125,
                    text: 'Export to excel'
                }
            ]
        },
        {
            xtype: 'container',
            dock: 'bottom',
            height: 28,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'displayfield',
                    x: 530,
                    y: 5,
                    itemId: 'totalQty',
                    fieldLabel: 'Total Issued',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    value: 'Display Field',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                },
                {
                    xtype: 'displayfield',
                    x: 10,
                    y: 0,
                    itemId: 'totalPatients',
                    width: 270,
                    fieldLabel: 'Total Number of Patients',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    labelWidth: 180,
                    value: 'Display Field',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                },
                {
                    xtype: 'displayfield',
                    x: 785,
                    y: 5,
                    itemId: 'totalCost',
                    fieldLabel: 'Total Cost',
                    labelStyle: 'font-size: small;font-weight: bold;color: green;',
                    value: 'Display Field',
                    fieldStyle: 'font-size: small;font-weight: bold;color: blue;'
                }
            ]
        }
    ]

});