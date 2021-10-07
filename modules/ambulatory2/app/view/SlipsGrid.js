/*
 * File: app/view/SlipsGrid.js
 *
 * This file was generated by Sencha Architect version 3.2.0.
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

Ext.define('OutPatient.view.SlipsGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.slipsgrid',

    requires: [
        'Ext.grid.View',
        'Ext.form.FieldContainer',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.column.Column',
        'Ext.selection.RowModel',
        'Ext.toolbar.Paging'
    ],

    frame: true,
    height: 562,
    resizable: true,
    closable: true,
    title: 'Credit Slips',
    columnLines: true,
    store: 'SlipsStore',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'fieldcontainer',
                    dock: 'top',
                    height: 31,
                    width: 100,
                    layout: 'absolute',
                    items: [
                        {
                            xtype: 'textfield',
                            x: 5,
                            y: 5,
                            itemId: 'txtSearchSlip',
                            width: 265,
                            emptyText: ' Search via names,PID, Acc no, Acc name'
	                     },
	                     {
                            xtype: 'datefield',
                            itemId: 'startDate',
                            fieldLabel: 'StartDate',
                            labelWidth: 60,
                            x: 290,
                            y: 5,
                            name: 'startDate',
                            format: 'Y-m-d',
                            submitFormat: 'Y-m-d'
                        },{
                            xtype: 'datefield',
                            itemId: 'endDate',
                            fieldLabel: 'EndDate',
                            labelWidth: 60,
                            x: 510,
                            y: 5,
                            name: 'endDate',
                            format: 'Y-m-d',
                            submitFormat: 'Y-m-d'
                        },
                        {
                            xtype: 'button',
                            x: 750,
                            y: 5,
                            itemId: 'cmdSearchSlip',
                            width: 100,
                            text: '<b>Search >>></b>'
                        },
                        {
                            xtype: 'button',
                            x: 890,
                            y: 5,
                            itemId: 'cmdReprintSlip',
                            width: 100,
                            text: '<b>Reprint</b>'
                        }
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    width: 360,
                    displayInfo: true,
                    store: 'SlipsStore'
                }
            ],
            columns: [
                {
                    xtype: 'gridcolumn',
                    width: 62,
                    dataIndex: 'id',
                    text: 'ID'
                },
                {
                    xtype: 'gridcolumn',
                    width: 71,
                    dataIndex: 'pid',
                    text: 'Pid'
                },
                {
                    xtype: 'gridcolumn',
                    width: 69,
                    dataIndex: 'slipNo',
                    text: 'Slip No'
                },
                {
                    xtype: 'gridcolumn',
                    width: 122,
                    dataIndex: 'slipDate',
                    text: 'Slip Date'
                },
                {
                    xtype: 'gridcolumn',
                    width: 122,
                    dataIndex: 'slipTime',
                    text: 'Time'
                },
                {
                    xtype: 'gridcolumn',
                    width: 210,
                    dataIndex: 'Names',
                    text: 'Names'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'accNo',
                    text: 'Acc No'
                },
                {
                    xtype: 'gridcolumn',
                    width: 258,
                    dataIndex: 'accName',
                    text: 'Acc Name'
                }
            ],
            selModel: Ext.create('Ext.selection.RowModel', {
                pruneRemoved: false
            })
        });

        me.callParent(arguments);
    }

});