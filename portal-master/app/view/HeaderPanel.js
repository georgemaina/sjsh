/*
 * File: app/view/HeaderPanel.js
 * Date: Mon May 18 2020 11:00:01 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.4.
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

Ext.define('CarePortal.view.HeaderPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.headerpanel',

    requires: [
        'CarePortal.view.HeaderPanelViewModel',
        'Ext.form.field.Display'
    ],

    viewModel: {
        type: 'headerpanel'
    },
    frame: true,
    height: 31,

    layout: {
        type: 'hbox',
        padding: '1 1 1 1'
    },
    items: [
        {
            xtype: 'displayfield',
            itemId: 'PatientName',
            width: 313,
            fieldLabel: 'Name',
            labelStyle: 'font-size: small;font-weight: bold;color: green;',
            labelWidth: 60,
            value: 'George Maina Chege',
            fieldStyle: 'font-size: small;font-weight: bold;color: green;font-style:normal;color: #2E5494;'
        },
        {
            xtype: 'displayfield',
            itemId: 'Gender',
            width: 158,
            fieldLabel: 'Gender',
            labelStyle: 'font-size: small;font-weight: bold;color: green;',
            labelWidth: 70,
            fieldStyle: 'font-size: small;font-weight: bold;color: green;font-style:normal;color: #2E5494;'
        },
        {
            xtype: 'displayfield',
            itemId: 'Age',
            width: 229,
            fieldLabel: 'Age',
            labelStyle: 'font-size: small;font-weight: bold;color: green;',
            labelWidth: 50,
            fieldStyle: 'font-size: small;font-weight: bold;color: green;font-style:normal;color: #2E5494;'
        },
        {
            xtype: 'displayfield',
            itemId: 'PID',
            width: 171,
            fieldLabel: 'PID',
            labelStyle: 'font-size: small;font-weight: bold;color: green;',
            labelWidth: 50,
            fieldStyle: 'font-size: medium;font-weight: bold;color: green;font-style:normal;color: #2E5494;'
        },
        {
            xtype: 'displayfield',
            itemId: 'EncounterNo',
            width: 222,
            fieldLabel: 'Encounter No',
            labelStyle: 'font-size: small;font-weight: bold;color: green;',
            labelWidth: 130,
            fieldStyle: 'font-size: small;font-weight: bold;color: green;font-style:normal;color: #2E5494;'
        }
    ]

});