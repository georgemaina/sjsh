/*
 * File: app/view/StoolOCParasite.js
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

Ext.define('Lab.view.StoolOCParasite', {
    extend: 'Ext.form.Panel',
    alias: 'widget.stoolocparasite',

    requires: [
        'Lab.view.StoolOCParasiteViewModel',
        'Ext.form.FieldSet',
        'Ext.form.field.ComboBox'
    ],

    viewModel: {
        type: 'stoolocparasite'
    },
    margin: '0 0 3 0',
    width: 644,
    bodyPadding: 10,
    title: 'Stool oc Parasite',

    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'fieldset',
            layout: 'vbox',
            title: 'Stool O C Parasites',
            items: [
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'OCCULT BLOOD',
                    name: 'group-ltest102-54',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'ASCARIS',
                    name: 'group-ltest102-55',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'HOOKWARM',
                    name: 'group-ltest102-56',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'TAENIA SPP',
                    name: 'group-ltest102-57',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'TRICURIS',
                    name: 'group-ltest102-58',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'STRONGYLOIDES',
                    name: 'group-ltest102-58',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'GIARDIA',
                    name: 'group-ltest102-59',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'ECOLI',
                    name: 'group-ltest102-60',
                    store: [
                        'POS',
                        'NEG'
                    ]
                },
                {
                    xtype: 'combobox',
                    margin: '0 0 3 0',
                    width: 194,
                    fieldLabel: 'EHISTOLYNCA',
                    name: 'group-ltest102-61',
                    store: [
                        'POS',
                        'NEG'
                    ]
                }
            ]
        },
        {
            xtype: 'fieldset',
            flex: 1,
            layout: 'auto',
            items: [
                {
                    xtype: 'fieldset',
                    padding: 0,
                    layout: 'vbox',
                    title: 'Macroscopic',
                    items: [
                        {
                            xtype: 'textfield',
                            flex: 1,
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'COLOUR',
                            labelAlign: 'right',
                            name: 'group-ltest102-49'
                        },
                        {
                            xtype: 'textfield',
                            flex: 1,
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'CONSISTENCY',
                            labelAlign: 'right',
                            name: 'group-ltest102-50'
                        },
                        {
                            xtype: 'textfield',
                            flex: 1,
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'BLOODSPOTS',
                            labelAlign: 'right',
                            name: 'group-ltest102-51'
                        },
                        {
                            xtype: 'textfield',
                            flex: 1,
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'WARMA',
                            labelAlign: 'right',
                            name: 'group-ltest102-52'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    padding: 0,
                    title: 'Microscopic',
                    items: [
                        {
                            xtype: 'textfield',
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'OVA',
                            labelAlign: 'right',
                            name: 'group-ltest102-118'
                        },
                        {
                            xtype: 'textfield',
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'CYST',
                            labelAlign: 'right',
                            name: 'group-ltest102-119'
                        },
                        {
                            xtype: 'textfield',
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'TROPHOLOITE',
                            labelAlign: 'right',
                            name: 'group-ltest102-120'
                        },
                        {
                            xtype: 'textfield',
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'BACTERIA',
                            labelAlign: 'right',
                            name: 'group-ltest102-121'
                        },
                        {
                            xtype: 'textfield',
                            margin: '0 0 3 0',
                            width: 373,
                            fieldLabel: 'PARASITE',
                            labelAlign: 'right',
                            name: 'group-ltest102-122'
                        }
                    ]
                }
            ]
        }
    ]

});