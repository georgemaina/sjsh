/*
 * File: app/view/UsersForm.js
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

Ext.define('Lab.view.UsersForm', {
    extend: 'Ext.form.Panel',
    alias: 'widget.usersform',

    requires: [
        'Lab.view.UsersFormViewModel',
        'Ext.form.field.ComboBox',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'usersform'
    },
    height: 127,
    width: 400,
    layout: 'absolute',
    bodyPadding: 10,
    bodyStyle: 'background:#629670;',
    url: '../../data/getDataFunctions.php?task=updateReceiver',

    items: [
        {
            xtype: 'combobox',
            anchor: '100%',
            x: 5,
            y: 15,
            fieldLabel: 'Staff List',
            labelAlign: 'right',
            labelStyle: 'color:white;font-weight:bold;',
            displayField: 'staff_name',
            minChars: 1,
            queryMode: 'local',
            store: 'StaffList',
            typeAhead: true,
            valueField: 'ID'
        },
        {
            xtype: 'button',
            x: 35,
            y: 70,
            height: 40,
            itemId: 'cmdSaveReceiver',
            width: 115,
            iconCls: 'x-fa fa-save',
            text: 'Save'
        },
        {
            xtype: 'button',
            x: 240,
            y: 70,
            height: 40,
            itemId: 'cmdClose',
            width: 95,
            iconCls: 'x-fa fa-close',
            text: 'Close'
        }
    ]

});