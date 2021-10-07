/*
 * File: app/view/ProcedureClassGrid.js
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

Ext.define('Procedures.view.ProcedureClassGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.procedureclassgrid',

    requires: [
        'Ext.grid.View',
        'Ext.toolbar.Paging',
        'Ext.grid.plugin.CellEditing',
        'Ext.button.Button',
        'Ext.toolbar.Fill',
        'Ext.form.field.Text',
        'Ext.grid.column.Number'
    ],

    minHeight: 600,
    minWidth: 1000,
    title: 'Procedure Classes',
    columnLines: true,
    store: 'ProcedureClassListStore',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            viewConfig: {
                enableTextSelection: true
            },
            dockedItems: [
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    width: 360,
                    displayInfo: true,
                    store: 'ProcedureClassListStore'
                },
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            id: 'newClass',
                            text: 'New Class'
                        },
                        {
                            xtype: 'button',
                            id: 'cmdSaveClass',
                            text: 'Save'
                        },
                        {
                            xtype: 'tbfill'
                        },
                        {
                            xtype: 'button',
                            text: 'Delete Class'
                        }
                    ]
                }
            ],
            plugins: [
                Ext.create('Ext.grid.plugin.CellEditing', {
                    triggerEvent: 'celldblclick'
                })
            ],
            columns: [
                {
                    xtype: 'gridcolumn',
                    width: 51,
                    dataIndex: 'ID',
                    text: 'ID',
                    editor: {
                        xtype: 'textfield',
                        name: 'ID'
                    }
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'proc_class',
                    text: 'Proc_class',
                    editor: {
                        xtype: 'textfield',
                        name: 'proc_class'
                    }
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'class_value',
                    text: 'Class_value',
                    editor: {
                        xtype: 'textfield',
                        name: 'class_value'
                    }
                },
                {
                    xtype: 'numbercolumn',
                    dataIndex: 'cost',
                    text: 'Cost',
                    editor: {
                        xtype: 'textfield',
                        name: 'cost'
                    }
                }
            ]
        });

        me.callParent(arguments);
    }

});