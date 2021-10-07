/*
 * File: app/view/NhifCredits.js
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

Ext.define('ReportsMain.view.NhifCredits', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.nhifcredits',

    requires: [
        'ReportsMain.view.NhifCreditsViewModel',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.form.field.Date',
        'Ext.button.Button',
        'Ext.grid.column.Number',
        'Ext.grid.column.Date'
    ],

    viewModel: {
        type: 'nhifcredits'
    },
    height: 620,
    resizable: true,
    closable: true,
    icon: '../../icons/fam/grid.png',
    title: 'NHIF CLAIMS',
    columnLines: true,
    store: 'NhifClaimStore',

    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'LabTestsStore'
        },
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'datefield',
                    itemId: 'startDate',
                    fieldLabel: 'Start Date',
                    labelWidth: 65,
                    altFormats: 'Y-m-d',
                    format: 'Y-m-d',
                    submitFormat: 'Y-m-d'
                },
                {
                    xtype: 'datefield',
                    itemId: 'endDate',
                    fieldLabel: 'End Date',
                    labelWidth: 60,
                    format: 'Y-m-d',
                    submitFormat: 'Y-d-m'
                },
                {
                    xtype: 'button',
                    height: 25,
                    itemId: 'cmdSearchNhif',
                    width: 110,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    height: 25,
                    itemId: 'cmdPrint',
                    width: 100,
                    text: '<b>Print</b>'
                },
                {
                    xtype: 'button',
                    height: 25,
                    itemId: 'cmdExportNhif',
                    width: 146,
                    text: '<b>Export To Excel</b>'
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'ClaimNo',
            text: 'Claim No'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'NHIFNo',
            text: 'Nhifno'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'PID',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'BillNumber',
            text: 'Bill Number'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'AdmissionDate',
            text: 'Admission Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'DischargeDate',
            text: 'Discharge Date'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'BedDays',
            text: 'Bed Days'
        },
        {
            xtype: 'numbercolumn',
            dataIndex: 'InvoiceAmount',
            text: 'Invoice Amount'
        },
        {
            xtype: 'numbercolumn',
            dataIndex: 'TotalCredit',
            text: 'Total Credit'
        },
        {
            xtype: 'numbercolumn',
            dataIndex: 'Balance',
            text: 'Balance'
        },
        {
            xtype: 'datecolumn',
            dataIndex: 'InputDate',
            text: 'Input Date',
            format: 'm/j/Y'
        }
    ]

});