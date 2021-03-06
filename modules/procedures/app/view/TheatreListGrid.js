/*
 * File: app/view/TheatreListGrid.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Procedures.view.TheatreListGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.theatrelistgrid',

    requires: [
        'Ext.grid.View',
        'Ext.toolbar.Paging',
        'Ext.grid.column.Action'
    ],

    minHeight: 600,
    minWidth: 1000,
    animCollapse: true,
    closable: true,
    collapsible: true,
    title: 'Theatre List',
    store: 'TheatreListStore',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            viewConfig: {
                id: 'spongeCounts'
            },
            dockedItems: [
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    width: 360,
                    displayInfo: true
                },
                {
                    xtype: 'toolbar',
                    dock: 'top'
                }
            ],
            columns: [
                {
                    xtype: 'gridcolumn',
                    width: 53,
                    dataIndex: 'pid',
                    text: 'Pid'
                },
                {
                    xtype: 'gridcolumn',
                    width: 67,
                    dataIndex: 'encounter_nr',
                    text: 'Encounter_nr'
                },
                {
                    xtype: 'gridcolumn',
                    width: 65,
                    dataIndex: 'BookingNo',
                    text: 'BookingNo'
                },
                {
                    xtype: 'gridcolumn',
                    width: 68,
                    dataIndex: 'selian_pid',
                    text: 'File Number'
                },
                {
                    xtype: 'gridcolumn',
                    width: 172,
                    dataIndex: 'pnames',
                    text: 'Pnames'
                },
                {
                    xtype: 'gridcolumn',
                    width: 54,
                    dataIndex: 'sex',
                    text: 'Sex'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'date_birth',
                    text: 'Date_birth'
                },
                {
                    xtype: 'gridcolumn',
                    width: 131,
                    dataIndex: 'diagnosis',
                    text: 'Diagnosis'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'surgeon',
                    text: 'Surgeon'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'asst_surgeon',
                    text: 'Asst_surgeon'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'procedure_date',
                    text: 'Procedure_date'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'procedure_type',
                    text: 'Procedure_type'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'procedure_class',
                    text: 'Procedure_class'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'class_code',
                    text: 'Class_code'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'op_starttime',
                    text: 'Op_starttime'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'op_endtime',
                    text: 'Op_endtime'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'scrub_nurse',
                    text: 'Scrub_nurse'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'op_room',
                    text: 'Op_room'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'status',
                    text: 'Status'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'notes',
                    text: 'Notes'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'allergies',
                    text: 'Allergies'
                },
                {
                    xtype: 'actioncolumn',
                    id: 'checklist',
                    width: 56,
                    align: 'center',
                    text: 'Check List',
                    tooltip: 'Open Checklist',
                    altText: 'Checklist',
                    icon: '../../icons/list.png',
                    items: [
                        {
                            handler: function(view, rowIndex, colIndex, item, e, record, row) {

                            },
                            icon: '../../icons/list.png'
                        }
                    ]
                },
                {
                    xtype: 'actioncolumn',
                    id: 'anaesthesia',
                    width: 86,
                    align: 'center',
                    text: 'Anesthesia Form',
                    tooltip: 'Open Anesthesia Form',
                    altText: 'Anesthesia',
                    items: [
                        {
                            handler: function(view, rowIndex, colIndex, item, e, record, row) {

                            },
                            icon: '../../icons/acceptcalibration.png'
                        }
                    ]
                },
                {
                    xtype: 'actioncolumn',
                    id: 'spongeCountsRec',
                    width: 89,
                    align: 'center',
                    text: 'Sponge Count',
                    tooltip: 'Theatre Sponge Count Record',
                    icon: '\'../../icons/acceptcalibration.png\'',
                    items: [
                        {
                            icon: '../../icons/report_check.png'
                        }
                    ]
                },
                {
                    xtype: 'actioncolumn',
                    align: 'center',
                    text: 'Operation Notes',
                    tooltip: 'Operation Summary Notes',
                    items: [
                        {
                            icon: '../../icons/note_accept.png'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});