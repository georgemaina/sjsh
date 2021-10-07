/*
 * File: app/view/DontorsNotesEntry.js
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

Ext.define('CarePortal.view.DontorsNotesEntry', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.dontorsnotesentry',

    requires: [
        'CarePortal.view.DontorsNotesEntryViewModel',
        'Ext.form.field.ComboBox',
        'Ext.form.field.TextArea',
        'Ext.form.FieldContainer',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'dontorsnotesentry'
    },
    height: 487,
    width: 685,
    title: 'Doctors Notes',

    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'combobox',
            width: 510,
            fieldLabel: 'Chief Complaints',
            labelAlign: 'top'
        },
        {
            xtype: 'textareafield',
            height: 112,
            width: 505,
            fieldLabel: 'Comments',
            labelAlign: 'top'
        },
        {
            xtype: 'fieldcontainer',
            flex: 1,
            height: 220,
            width: 597,
            layout: 'absolute',
            items: [
                {
                    xtype: 'button',
                    x: 450,
                    y: 5,
                    height: 35,
                    width: 125,
                    text: 'Save'
                },
                {
                    xtype: 'container',
                    x: -8,
                    y: 45,
                    height: 190,
                    scrollable: 'vertical'
                }
            ]
        }
    ]

});