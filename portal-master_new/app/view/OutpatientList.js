/*
 * File: app/view/OutpatientList.js
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

Ext.define('CarePortal.view.OutpatientList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.outpatientlist',

    requires: [
        'CarePortal.view.OutpatientListViewModel',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.form.FieldContainer',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.column.Widget',
        'Ext.selection.RowModel'
    ],

    viewModel: {
        type: 'outpatientlist'
    },
    draggable: {
        moveOnDrag: false
    },
    frame: true,
    height: 640,
    itemId: 'OutpatientAdmissions',
    autoDestroy: false,
    columnLines: true,
    store: 'OPAdmissionsStore',

    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'OPAdmissionsStore'
        },
        {
            xtype: 'fieldcontainer',
            dock: 'top',
            height: 40,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 0,
                    y: 5,
                    itemId: 'txtOpSearch',
                    width: 470,
                    emptyText: 'Search by PId, Names'
                },
                {
                    xtype: 'button',
                    x: 470,
                    y: 5,
                    width: 95,
                    text: 'Search'
                }
            ]
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            width: 93,
            dataIndex: 'Status',
            text: 'Status'
        },
        {
            xtype: 'gridcolumn',
            width: 56,
            dataIndex: 'Pid',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 165,
            dataIndex: 'Names',
            text: 'Names'
        },
        {
            xtype: 'gridcolumn',
            width: 126,
            dataIndex: 'PaymentMode',
            text: 'Payment Mode'
        },
        {
            xtype: 'gridcolumn',
            width: 96,
            dataIndex: 'EncounterDate',
            text: 'Date'
        },
        {
            xtype: 'gridcolumn',
            width: 72,
            dataIndex: 'EncounterDate',
            text: 'Time'
        },
        {
            xtype: 'gridcolumn',
            width: 119,
            dataIndex: 'Department',
            text: 'Encounter No'
        },
        {
            xtype: 'gridcolumn',
            width: 179,
            dataIndex: 'Clinic',
            text: 'Clinic'
        },
        {
            xtype: 'gridcolumn',
            width: 72,
            dataIndex: 'Gender',
            text: 'Gender'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'DateOfBirth',
            text: 'Dob'
        },
        {
            xtype: 'gridcolumn',
            width: 53,
            dataIndex: 'Age',
            text: 'Age'
        },
        {
            xtype: 'widgetcolumn',
            width: 166,
            align: 'center',
            text: 'Admission Data',
            widget: {
                xtype: 'button',
                bind: 'outpatients',
                itemId: 'viewPatients',
                iconCls: 'x-fa fa-user',
                text: 'View Admission Data'
            }
        }
    ],
    selModel: {
        selType: 'rowmodel'
    }

});