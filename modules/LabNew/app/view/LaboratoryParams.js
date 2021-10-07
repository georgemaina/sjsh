/*
 * File: app/view/LaboratoryParams.js
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

Ext.define('Lab.view.LaboratoryParams', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.laboratoryparams',

    requires: [
        'Lab.view.LaboratoryParamsViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging',
        'Ext.button.Button',
        'Ext.form.field.ComboBox'
    ],

    viewModel: {
        type: 'laboratoryparams'
    },
    height: 630,
    title: 'Laboratory Parameters',
    columnLines: true,
    store: 'LabParamsStore',

    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'PartCode',
            text: 'Part Code'
        },
        {
            xtype: 'gridcolumn',
            width: 269,
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            width: 173,
            dataIndex: 'GroupID',
            text: 'Group Id'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Price',
            text: 'Price'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'FieldType',
            text: 'Field Type'
        }
    ],
    dockedItems: [
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            store: 'LabParamsStore'
        },
        {
            xtype: 'container',
            dock: 'top',
            height: 43,
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'button',
                    x: 365,
                    y: 5,
                    itemId: 'cmdNewParams',
                    iconCls: 'x-fa fa-plus',
                    text: 'New Parameter'
                },
                {
                    xtype: 'combobox',
                    x: 5,
                    y: 5,
                    itemId: 'labParams',
                    width: 285,
                    emptyText: 'Select Parameter Group',
                    displayField: 'Description',
                    minChars: 1,
                    queryMode: 'local',
                    store: 'LabParamGroups',
                    typeAhead: true,
                    valueField: 'Description'
                }
            ]
        }
    ]

});