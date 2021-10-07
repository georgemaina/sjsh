/**
 * Created by george maina
 * Email: georgemainake@gmail.com
 * Copyright: All rights reserved on 5/22/14.
 */

Ext.require([
    'Ext.form.*',
    'Ext.grid.Panel',
    'Ext.XTemplate',
    'Ext.grid.column.Number',
    'Ext.grid.column.Date',
    'Ext.grid.column.Boolean',
    'Ext.grid.View',
    'Ext.button.Button',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json'
]);

Ext.define('IouModel', {
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'ID'
        },
        {
            name: 'IouDate'
        },
        {
            name: 'Payee'
        },
        {
            name: 'AmountGiven'
        },
        {
            name: 'AmountSpent'
        },
        {
            name: 'Balance'
        },
        {
            name: 'Towards'
        },
        {
            name: 'Status'
        },
        {
            name: 'InputUser'
        }
    ]
});

var IouStore=Ext.create('Ext.data.Store', {
    // alias: 'store.labtests',
    autoLoad: true,
    model: 'IouModel',
    proxy: {
        type: 'ajax',
        url: 'getCashBookFunctions.php?task=getIous',
        reader: {
            type: 'json',
            root: 'ious'
        }
    }
});

IouStore.load({});

Ext.onReady(function() {

    var formPanel = Ext.create('Ext.form.Panel', {
        height: 605,
        width: 1014,
        layout: 'absolute',
        bodyPadding: 10,
        closable: true,
        collapsible: true,
        title: 'IOU Form',
        url: 'getCashbookFunctions.php?task=insertIOU',

        items: [
            {
                xtype: 'textfield',
                x: -7,
                y: 5,
                width: 370,
                fieldLabel: 'Payee',
                labelAlign: 'right',
                name: 'Payee',
                allowBlank: false,
                tabIndex: 1
            },
            {
                xtype: 'textfield',
                x: -7,
                y: 65,
                width: 260,
                fieldLabel: 'Amount Spent',
                labelAlign: 'right',
                name: 'AmountSpent',
                tabIndex: 4,
                listeners: {
                    change: {
                        fn: onTextfieldChange
                    }
                }
            },
            {
                xtype: 'textfield',
                x: -7,
                y: 95,
                width: 260,
                fieldLabel: 'Balance',
                labelAlign: 'right',
                name: 'Balance',
                itemId:'Balance',
                tabIndex: 5
            },
            {
                xtype: 'textfield',
                x: -7,
                y: 35,
                width: 260,
                fieldLabel: 'Amount Given ',
                labelAlign: 'right',
                name: 'AmountGiven',
                itemId:'AmountGiven',
                allowBlank: false,
                tabIndex: 3
            },
            {
                xtype: 'datefield',
                x: 360,
                y: 5,
                width: 245,
                fieldLabel: 'Date',
                labelAlign: 'right',
                labelWidth: 50,
                name: 'IouDate',
                allowBlank: false,
                tabIndex: 2
            },
            {
                xtype: 'textareafield',
                x: -7,
                y: 125,
                height: 48,
                width: 430,
                fieldLabel: 'Towards',
                labelAlign: 'right',
                name: 'Towards',
                allowBlank: false,
                tabIndex: 6
            },
            {
                xtype: 'gridpanel',
                x: 5,
                y: 220,
                frame: true,
                height: 290,
                width: 1010,
                title: 'Active IOUs',
                store: IouStore,
                columns: [
                    {
                        xtype: 'gridcolumn',
                        width: 62,
                        dataIndex: 'ID',
                        text: 'ID'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'IouDate',
                        text: 'IouDate'
                    },
                    {
                        xtype: 'gridcolumn',
                        width: 200,
                        dataIndex: 'Payee',
                        text: 'Payee'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'AmountGiven',
                        text: 'Amount Given'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'AmountSpent',
                        text: 'Amount Spent'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'Balance',
                        text: 'Balance'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'Narrative',
                        text: 'Narrative'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'Status',
                        text: 'Status'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'InputUser',
                        text: 'Input User'
                    }
                ],
                dockedItems: [
                    {
                        xtype: 'pagingtoolbar',
                        dock: 'bottom',
                        width: 360,
                        displayInfo: true,
                        store: IouStore
                    }
                ],
                listeners: {
                    itemclick: {
                        fn: onGridpanelItemClick
                    }
                },
                selModel: Ext.create('Ext.selection.RowModel', {

                })
            },
            {
                xtype: 'button',
                x: 130,
                y: 185,
                height: 30,
                width: 100,
                text: 'Save',
                listeners: {
                    click: {
                        fn: onCmdSaveClick
                    }
                }
            },
            {
                xtype: 'button',
                x: 25,
                y: 185,
                height: 30,
                itemId: 'cmdNew',
                width: 100,
                text: 'New',
                listeners: {
                    click: {
                        fn: onCmdNewClick
                    }
                }
            },
            {
                xtype: 'button',
                x: 235,
                y: 185,
                height: 30,
                width: 95,
                text: 'Cancel IOU'
            },
            {
                xtype: 'button',
                x: 335,
                y: 185,
                height: 30,
                width: 100,
                text: 'Print'
            },
            {
                xtype: 'button',
                x: 775,
                y: 185,
                height: 30,
                width: 110,
                text: 'Close'
            },
            {
                xtype: 'textfield',
                x: 500,
                y: 110,
                itemId: 'formStatus',
                value: 'Insert',
                name: 'formStatus',
                hidden: true
            },
            {
                xtype: 'textfield',
                x: 500,
                y: 130,
                itemId: 'ID',
                name: 'ID',
                hidden: true
            }
        ]
    });

    formPanel.render('iouform');

    function onCmdSaveClick(button,e,eOpts){
        //xt.Msg.alert('test','test');
        var form = button.up('form').getForm(); // get the basic form
        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                success: function(form, action) {
                    Ext.Msg.alert('Success', 'Saved new IOU successfully.');

                    IouStore.load({});
                    form.reset();

                    form.findField('formStatus').setValue('Insert');
                },
                failure: function(form, action) {
                    Ext.Msg.alert('Failed', 'Could not save IOU. Error=');
                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
        }
    }

    function onGridpanelItemClick(dataview, record, item, index, e, eOpts) {
        formPanel.loadRecord(record);
        formPanel.down('#formStatus').setValue('Update');
    }

    function onCmdNewClick (button, e, eOpts) {
        var currform=button.up('form').getForm();
        currform.reset();

        button.up('form').getForm().findField('formStatus').setValue('Insert');
    }

    function onTextfieldChange (field, newValue, oldValue, eOpts) {
        var strAmountgiven=formPanel.down('#AmountGiven').getValue();
        var balance=strAmountgiven-newValue;
        var strAmountgiven=formPanel.down('#Balance').setValue(balance);
       // Ext.Msg.alert('test',strAmountgiven);
    }




});


