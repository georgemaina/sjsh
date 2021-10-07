/*
 * File: app/view/AssignBeds.js
 * Date: Fri Feb 28 2020 05:39:31 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Inpatient.view.AssignBeds', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.assignbeds',

    requires: [
        'Inpatient.view.AssignBedsViewModel',
        'Inpatient.view.PatientDetails',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.panel.Panel',
        'Ext.button.Button',
        'Ext.grid.feature.Grouping',
        'Ext.XTemplate',
        'Ext.grid.column.Action'
    ],

    viewModel: {
        type: 'assignbeds'
    },
    viewModel: 'wardoccupancy',
    height: 580,
    minHeight: 500,
    width: 894,
    columnLines: true,
    store: 'OccupancyStore',

    viewConfig: {
        width: 574
    },
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'OccupancyStore'
        },
        {
            xtype: 'panel',
            dock: 'top',
            height: 85,
            width: 100,
            layout: 'absolute',
            bodyStyle: 'background:#386d87',
            items: [
                {
                    xtype: 'patientdetails',
                    height: 100,
                    width: 775,
                    x: 0,
                    y: -5
                },
                {
                    xtype: 'button',
                    x: 790,
                    y: 10,
                    iconCls: 'x-fa fa-arrow-right',
                    text: 'MyButton'
                }
            ]
        }
    ],
    features: [
        {
            ftype: 'grouping',
            showSummaryRow: false,
            groupHeaderTpl: [
                '{columnName}: {name}'
            ],
            hideGroupedHeader: true
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            width: 70,
            dataIndex: 'BedNo',
            text: 'Bed No'
        },
        {
            xtype: 'actioncolumn',
            id: 'assignToBed',
            width: 92,
            align: 'center',
            text: 'Assign Bed',
            items: [
                {
                    getClass: function(v, metadata, r, rowIndex, colIndex, store) {
                        if(r.get('Pid')!==''){
                            return 'x-hidden-visibility';
                        }
                    },
                    altText: 'Assign Here',
                    icon: '../../gui/img/common/default/angle_down_r.gif',
                    tooltip: 'Assign Here'
                }
            ]
        },
        {
            xtype: 'gridcolumn',
            width: 70,
            dataIndex: 'Pid',
            text: 'Pid'
        },
        {
            xtype: 'gridcolumn',
            width: 209,
            dataIndex: 'Names',
            text: 'Names'
        },
        {
            xtype: 'gridcolumn',
            width: 111,
            dataIndex: 'EncounterNo',
            text: 'Encounter No'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            dataIndex: 'BillNumber',
            text: 'Bill Number'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Sex',
            text: 'Sex'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'BirthDate',
            text: 'Birth Date'
        },
        {
            xtype: 'gridcolumn',
            width: 121,
            dataIndex: 'AdmissionDate',
            text: 'Admission Date'
        },
        {
            xtype: 'gridcolumn',
            width: 146,
            dataIndex: 'PaymentMode',
            text: 'Payment Mode'
        }
    ]

});