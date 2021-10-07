/*
 * File: app/view/Workload.js
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

Ext.define('ReportsMain.view.Workload', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.workload',

    requires: [
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.form.field.Date',
        'Ext.button.Button',
        'Ext.grid.column.Column'
    ],

    height: 615,
    resizable: true,
    closable: true,
    icon: '../../icons/fam/grid.png',
    title: 'Workload Report',
    columnLines: true,
    store: 'WorkloadStore',

    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'WorkloadStore'
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
                    altFormats: 'Y-m-d',
                    format: 'Y-m-d',
                    submitFormat: 'Y-d-m'
                },
                {
                    xtype: 'button',
                    height: 30,
                    itemId: 'cmdSearchWorkload',
                    width: 110,
                    text: '<b>Preview</b>'
                },
                {
                    xtype: 'button',
                    height: 30,
                    itemId: 'cmdPrint',
                    width: 100,
                    text: '<b>Print</b>'
                },
                {
                    xtype: 'button',
                    height: 30,
                    itemId: 'cmdExportDoctorsWorkload',
                    width: 124,
                    text: '<b>Export To Excel</b>'
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            width: 225,
            dataIndex: 'Clinician',
            text: 'Clinician'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Notes',
            text: 'Notes'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Diagnosis',
            text: 'Diagnosis'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Labs',
            text: 'Labs'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Prescription',
            text: 'Prescription'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Xray',
            text: 'Xray'
        }
    ]

});