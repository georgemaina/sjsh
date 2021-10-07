/*
 * File: app/view/DebtorRegisterForm.js
 * Date: Fri Sep 28 2018 10:01:07 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.view.DebtorRegisterForm', {
    extend: 'Ext.form.Panel',
    alias: 'widget.debtorregisterform',

    requires: [
        'Debtors.view.DebtorRegisterFormViewModel',
        'Ext.form.FieldSet',
        'Ext.tab.Panel',
        'Ext.tab.Tab',
        'Ext.XTemplate',
        'Ext.form.field.ComboBox',
        'Ext.form.field.TextArea',
        'Ext.form.field.HtmlEditor',
        'Ext.form.field.Date',
        'Ext.form.field.Checkbox'
    ],

    viewModel: {
        type: 'debtorregisterform'
    },
    autoRender: true,
    autoShow: true,
    frame: true,
    height: 479,
    width: 789,
    detachOnRemove: false,
    layout: 'absolute',
    animCollapse: true,
    url: './data/getDataFunctions.php?task=insertDebtor',

    items: [
        {
            xtype: 'fieldset',
            x: 610,
            y: 120,
            height: 260,
            width: 175,
            layout: 'absolute',
            items: [
                {
                    xtype: 'button',
                    height: 35,
                    itemId: 'cmdSaveDebtor',
                    width: 130,
                    text: 'Save'
                },
                {
                    xtype: 'button',
                    x: 0,
                    y: 50,
                    height: 34,
                    itemId: 'cmdNewDbtor',
                    width: 130,
                    text: 'New'
                },
                {
                    xtype: 'button',
                    x: -2,
                    y: 100,
                    height: 34,
                    itemId: 'cmdPrint',
                    width: 130,
                    text: 'Print'
                },
                {
                    xtype: 'button',
                    x: -2,
                    y: 150,
                    height: 34,
                    itemId: 'mnuGuarantorsForm',
                    width: 130,
                    text: 'Guarantors Form'
                },
                {
                    xtype: 'button',
                    x: 0,
                    y: 200,
                    height: 33,
                    itemId: 'cmdClose',
                    width: 130,
                    text: 'Close'
                }
            ]
        },
        {
            xtype: 'tabpanel',
            x: 5,
            y: 120,
            height: 355,
            width: 600,
            activeTab: 0,
            items: [
                {
                    xtype: 'panel',
                    frame: true,
                    height: 233,
                    width: 528,
                    layout: 'absolute',
                    title: 'Address Info',
                    items: [
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 10,
                            width: 365,
                            fieldLabel: 'Address',
                            labelAlign: 'right',
                            name: 'address1'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 45,
                            width: 365,
                            fieldLabel: 'Town',
                            labelAlign: 'right',
                            name: 'address2'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 80,
                            fieldLabel: 'Phone',
                            labelAlign: 'right',
                            name: 'phone',
                            maxLength: 10,
                            minLength: 10
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 115,
                            fieldLabel: 'Email',
                            labelAlign: 'right',
                            name: 'email'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 150,
                            afterLabelTextTpl: [
                                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
                            ],
                            fieldLabel: 'Contact Person',
                            labelAlign: 'right',
                            name: 'contact',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 185,
                            fieldLabel: 'alt Phone',
                            labelAlign: 'right',
                            name: 'altphone'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    frame: true,
                    layout: 'absolute',
                    title: 'Additional Info',
                    items: [
                        {
                            xtype: 'combobox',
                            x: -4,
                            y: 5,
                            width: 335,
                            afterLabelTextTpl: [
                                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
                            ],
                            fieldLabel: 'Location',
                            labelAlign: 'right',
                            name: 'location',
                            allowBlank: false,
                            displayField: 'Location',
                            minChars: 2,
                            queryMode: 'local',
                            store: 'DebtorLocationStore',
                            typeAhead: true,
                            valueField: 'Location'
                        },
                        {
                            xtype: 'combobox',
                            x: -3,
                            y: 40,
                            width: 335,
                            afterLabelTextTpl: [
                                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
                            ],
                            fieldLabel: 'Sub Location',
                            labelAlign: 'right',
                            name: 'subLocation',
                            allowBlank: false,
                            displayField: 'Location',
                            minChars: 2,
                            queryMode: 'local',
                            store: 'DebtorSubLocationStore',
                            typeAhead: true,
                            valueField: 'Location'
                        },
                        {
                            xtype: 'textfield',
                            x: -3,
                            y: 75,
                            width: 335,
                            fieldLabel: 'Village',
                            labelAlign: 'right',
                            name: 'village'
                        },
                        {
                            xtype: 'textfield',
                            x: -3,
                            y: 110,
                            fieldLabel: 'Chief',
                            labelAlign: 'right',
                            name: 'chief'
                        },
                        {
                            xtype: 'textfield',
                            x: -3,
                            y: 145,
                            fieldLabel: 'Ass Chief',
                            labelAlign: 'right',
                            name: 'assChief'
                        },
                        {
                            xtype: 'textfield',
                            x: -3,
                            y: 180,
                            fieldLabel: 'Village Elder',
                            labelAlign: 'right',
                            name: 'villageElder'
                        },
                        {
                            xtype: 'textfield',
                            x: -3,
                            y: 215,
                            width: 385,
                            fieldLabel: 'Nearest School',
                            labelAlign: 'right',
                            name: 'nearSchool'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    frame: true,
                    layout: 'absolute',
                    title: 'Payments info',
                    items: [
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 5,
                            fieldLabel: 'Credit Limit',
                            labelAlign: 'right',
                            name: 'creditLimit'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 40,
                            fieldLabel: 'OP Cover',
                            labelAlign: 'right',
                            name: 'OP_Cover'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 75,
                            fieldLabel: 'IP Cover',
                            labelAlign: 'right',
                            name: 'IP_Cover'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 110,
                            fieldLabel: 'OP Usage',
                            labelAlign: 'right',
                            name: 'OP_Usage'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 145,
                            fieldLabel: 'IP Usage',
                            labelAlign: 'right',
                            name: 'IP_Usage'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 180,
                            fieldLabel: 'OP Exceed',
                            labelAlign: 'right',
                            name: 'OP_Exceed'
                        },
                        {
                            xtype: 'textfield',
                            x: 0,
                            y: 215,
                            fieldLabel: 'IP Exceed',
                            labelAlign: 'right',
                            name: 'IP_Exceed'
                        },
                        {
                            xtype: 'textfield',
                            x: 280,
                            y: 5,
                            itemId: 'Discount',
                            fieldLabel: 'Staff Discount',
                            labelAlign: 'right',
                            name: 'staffdiscount'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    frame: true,
                    height: 326,
                    width: 448,
                    layout: 'absolute',
                    title: 'Guarantors Info',
                    items: [
                        {
                            xtype: 'textfield',
                            x: 5,
                            y: 5,
                            width: 390,
                            fieldLabel: 'Gurantors Names',
                            labelWidth: 110,
                            name: 'guarantorsName'
                        },
                        {
                            xtype: 'textfield',
                            x: 35,
                            y: 110,
                            width: 240,
                            fieldLabel: 'Sub Location',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsSubLoc'
                        },
                        {
                            xtype: 'textfield',
                            x: 320,
                            y: 145,
                            width: 240,
                            fieldLabel: 'Guarantee the Payment og Ksh.',
                            labelAlign: 'top',
                            labelWidth: 80,
                            name: 'guarantorsAmount'
                        },
                        {
                            xtype: 'textfield',
                            x: 35,
                            y: 40,
                            width: 240,
                            fieldLabel: 'ID No',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsID'
                        },
                        {
                            xtype: 'textfield',
                            x: 35,
                            y: 75,
                            itemId: 'guarantorsLocation',
                            width: 240,
                            fieldLabel: 'Location ',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsLocation'
                        },
                        {
                            xtype: 'textfield',
                            x: 285,
                            y: 110,
                            width: 240,
                            fieldLabel: 'Village',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsVillage'
                        },
                        {
                            xtype: 'textfield',
                            x: 35,
                            y: 145,
                            width: 240,
                            fieldLabel: 'Address',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsAddress'
                        },
                        {
                            xtype: 'textfield',
                            x: 35,
                            y: 180,
                            width: 240,
                            fieldLabel: 'Phone',
                            labelAlign: 'right',
                            labelWidth: 80,
                            name: 'guarantorsPhone'
                        },
                        {
                            xtype: 'combobox',
                            x: 315,
                            y: 35,
                            width: 260,
                            fieldLabel: 'Relationship to Patient',
                            labelAlign: 'top',
                            labelWidth: 140,
                            name: 'guarantorsRelation',
                            displayField: 'Kin',
                            minChars: 2,
                            queryMode: 'local',
                            store: 'KinsStore',
                            typeAhead: true,
                            valueField: 'Kin'
                        },
                        {
                            xtype: 'textareafield',
                            x: 45,
                            y: 225,
                            height: 82,
                            itemId: 'otherInfo',
                            width: 475,
                            fieldLabel: 'Other Info',
                            labelAlign: 'right',
                            labelWidth: 70,
                            name: 'otherInfo'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    title: 'Other Info',
                    items: [
                        {
                            xtype: 'htmleditor',
                            height: 308,
                            name: 'statementInfo'
                        }
                    ]
                }
            ]
        },
        {
            xtype: 'datefield',
            x: 420,
            y: 5,
            afterLabelTextTpl: [
                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
            ],
            fieldLabel: 'Date Joined',
            labelWidth: 80,
            name: 'joined',
            format: 'Y/m/d'
        },
        {
            xtype: 'checkboxfield',
            anchor: '100%',
            x: 505,
            y: 80,
            name: 'dbStatus',
            boxLabel: 'Debtor is Inactive',
            inputValue: 'yes',
            uncheckedValue: 'No'
        },
        {
            xtype: 'datefield',
            x: 350,
            y: 40,
            afterLabelTextTpl: [
                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
            ],
            fieldLabel: 'Expected Payment Date',
            labelWidth: 150,
            name: 'nextPaymentDate',
            format: 'Y/m/d'
        },
        {
            xtype: 'textfield',
            x: 620,
            y: 400,
            hidden: true,
            itemId: 'formStatus',
            name: 'formStatus'
        },
        {
            xtype: 'combobox',
            x: 10,
            y: 10,
            width: 300,
            afterLabelTextTpl: [
                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
            ],
            fieldLabel: 'Debtor Type',
            labelAlign: 'right',
            name: 'category',
            displayField: 'custNames',
            minChars: 2,
            queryMode: 'local',
            store: 'CustomerType',
            typeAhead: true,
            valueField: 'ID'
        },
        {
            xtype: 'textfield',
            x: 10,
            y: 45,
            itemId: 'accno',
            width: 225,
            afterLabelTextTpl: [
                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
            ],
            fieldLabel: 'Account No',
            labelAlign: 'right',
            name: 'accno'
        },
        {
            xtype: 'textfield',
            x: 10,
            y: 80,
            width: 345,
            afterLabelTextTpl: [
                '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
            ],
            fieldLabel: 'Account Name',
            labelAlign: 'right',
            name: 'name'
        }
    ]

});