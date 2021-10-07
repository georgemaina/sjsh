/*
 * File: app/view/ProceduresList.js
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

Ext.define('Procedures.view.ProceduresList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.procedureslist',

    requires: [
        'Ext.grid.View',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging'
    ],

    minHeight: 600,
    minWidth: 1000,
    title: 'Procedures List',
    store: 'ProceduresStore',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'partcode',
                    text: 'Partcode'
                },
                {
                    xtype: 'gridcolumn',
                    width: 165,
                    dataIndex: 'Item_Description',
                    text: 'Item_Description'
                },
                {
                    xtype: 'gridcolumn',
                    width: 193,
                    dataIndex: 'item_full_description',
                    text: 'Item_full_description'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'purchasing_class',
                    text: 'Purchasing_class'
                },
                {
                    xtype: 'gridcolumn',
                    width: 158,
                    dataIndex: 'category',
                    text: 'Category'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'unit_price',
                    text: 'Unit_price'
                }
            ],
            dockedItems: [
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    width: 360,
                    displayInfo: true,
                    store: 'ProceduresStore'
                },
                {
                    xtype: 'toolbar',
                    dock: 'top'
                }
            ]
        });

        me.callParent(arguments);
    }

});