/*
 * File: app/view/Vitalslist.js
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

Ext.define('CarePortal.view.Vitalslist', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.vitalslist',

    requires: [
        'CarePortal.view.VitalslistViewModel',
        'Ext.view.Table',
        'Ext.grid.column.Column'
    ],

    viewModel: {
        type: 'vitalslist'
    },
    height: 360,
    scrollable: 'both',
    title: 'Vitals',
    store: 'VitalsStore',

    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'Description',
            text: 'Description'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Value',
            text: 'Value'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Lower',
            text: 'Lower'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'Upper',
            text: 'Upper'
        },
        {
            xtype: 'gridcolumn',
            width: 128,
            dataIndex: 'EncounterNo',
            text: 'Encounter No'
        },
        {
            xtype: 'gridcolumn',
            width: 161,
            dataIndex: 'VitalsTime',
            text: 'Vitals Time'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'VitalID',
            text: 'Vital Id'
        }
    ]

});