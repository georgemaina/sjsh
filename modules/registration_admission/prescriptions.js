Ext.require([
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.data.field.Field',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.form.field.Display',
        'Ext.form.field.Number'
]);
Ext.require('Dosage');

Ext.onReady(function () {
    var isLargeTheme = Ext.themeName !== 'classic',
        titleOffset = isLargeTheme ? -6 : -4;
    var win,win2;

    // var itemsStore =Ext.data.StoreManager.lookup('itemsliststore');
    // itemsStore.load({});
   
    
Ext.define('PrescriptionModel', {
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'nr'
        },
        {
            name: 'encounterNo'
        },
        {
            name: 'Partcode'
        },
        {
            name: 'Description'
        },
        {
            name: 'dosage'
        },
        {
            name: 'timesperday'
        },
        {
            name: 'days'
        },
        {
            name: 'notes'
        },
        {
            name: 'status'
        }
    ]
});

var PrescriptionStore = Ext.create('Ext.data.Store',{
    model: 'PrescriptionModel', 
    autoLoad:true,
    proxy: {
        type: 'ajax',
        url: '../../data/getDataFunctions.php?task=getCurrentPrescriptions',
        reader: {
            type: 'json'
        }
    }
});

Ext.define('ItemsListModel', {
    extend: 'Ext.data.Model',
  //  alias: 'itemslistmodel',
    fields: [
        {
            name: 'partcode'
        },
        {
            name: 'item_description'
        },
        {
            name: 'category'
        },
        {
            name: 'unit_price'
        },
        {
            name: 'qty'
        },
        {
            name: 'Total'
        }
    ]
});

var ItemslistStore=Ext.create('Ext.data.Store',{
   // extend: 'Ext.data.Store',
   // alias: 'itemsliststore',
    pageSize: 3000,
    autoLoad: true,
    model: 'ItemsListModel',
    proxy: {
        type: 'ajax',
        url: '../../data/getDataFunctions.php?task=getItemsList&sParams=\'Drug_list\',\'medical-supplies\'',
        reader: {
            type: 'json'
        }
    }
});

//ItemslistStore.load({});

//************************************************************ */
//define the dosage panel  new Ext.form.Panel
//var dosage =Ext.create(Ext.form.FieldSet,{
Ext.define('Dosage',{
    extend:'Ext.form.Fieldset',
    alias: 'widget.dosage',
    frame: true,
    height: 110,
    padding: 0,
    width: 789,
    layout: 'absolute',
    items: [
        {
            xtype: 'textfield',
            x: 10,
            y: 0,
            itemId: 'partCode',
            margin: 0,
            padding: 0,
            width: 125,
            fieldLabel: 'PartCode',
            labelStyle: 'color:maroon; font-weight:bold;font-size:12px;',
            labelWidth: 60,
            name: 'partCode',
            fieldStyle: 'color:maroon; font-weight:bold;font-size:12px;',
            readOnly: true
        },
        {
            xtype: 'textfield',
            x: 140,
            y: 0,
            itemId: 'description',
            margin: 0,
            padding: 0,
            width: 450,
            labelStyle: '',
            name: 'description',
            value: 'Description',
            fieldStyle: 'color:maroon; font-weight:bold;font-size:12px;'
        },
        {
            xtype: 'displayfield',
            x: 600,
            y: -7,
            itemId: 'qty',
            width: 305,
            fieldLabel: 'Qty in Store:',
            labelStyle: 'color:#3d74b3; font-weight:bold;font-size:14px;',
            labelWidth: 90,
            value: 'Quantity',
            fieldStyle: 'color:red; font-weight:bold;font-size:14px;'
        },
        {
            xtype: 'displayfield',
            x: 610,
            y: 40,
            itemId: 'totalCost',
            width: 305,
            fieldLabel: 'Total Cost',
            labelStyle: 'color:#3d74b3; font-weight:bold;font-size:14px;',
            labelWidth: 80,
            value: 'Total Cost:',
            fieldStyle: 'color:red; font-weight:bold;font-size:14px;'
        },
        {
            xtype: 'displayfield',
            x: 615,
            y: 15,
            itemId: 'unitCost',
            width: 305,
            fieldLabel: 'Item Cost',
            labelStyle: 'color:#3d74b3; font-weight:bold;font-size:14px;',
            labelWidth: 78,
            value: 'Item Cost:',
            fieldStyle: 'color:red; font-weight:bold;font-size:14px;'
        },
        {
            xtype: 'numberfield',
            x: 20,
            y: 40,
            itemId: 'dose',
            width: 120,
            fieldLabel: 'Dose',
            labelAlign: 'right',
            labelStyle: 'color:green; font-weight:bold;font-size:12px;',
            labelWidth: 50,
            value: 1,
            fieldStyle: 'color:#3d74b3; font-weight:bold;font-size:12px;',
            allowBlank: false
        },
        {
            xtype: 'numberfield',
            x: 145,
            y: 40,
            itemId: 'timesperday',
            width: 160,
            fieldLabel: 'Times Per Day',
            labelStyle: 'color:green; font-weight:bold;font-size:12px;',
            labelWidth: 90,
            fieldStyle: 'color:#3d74b3; font-weight:bold;font-size:12px;',
            allowBlank: false,
            maxLength: 6,
            minLength: 1,
            maxValue: 6,
            minValue: 1
        },
        {
            xtype: 'numberfield',
            x: 310,
            y: 40,
            itemId: 'days',
            width: 130,
            fieldLabel: 'Days',
            labelStyle: 'color:green; font-weight:bold;font-size:12px;',
            labelWidth: 50,
            fieldStyle: 'color:#3d74b3; font-weight:bold;font-size:12px;',
            allowBlank: false,
            maxLength: 60,
            minLength: 1,
            maxValue: 60,
            minValue: 1
        },
        {
            xtype: 'textfield',
            x: 450,
            y: 40,
            itemId: 'total',
            width: 140,
            fieldLabel: 'Total Dose',
            labelStyle: 'color:green; font-weight:bold;font-size:12px;',
            labelWidth: 70,
            fieldStyle: 'color:#3d74b3; font-weight:bold;font-size:12px;',
            readOnly: true,
            allowBlank: false
        },
        {
            xtype: 'textfield',
            x: 600,
            y: 75,
            itemId: 'itemNumber',
            width: 55,
            labelStyle: 'color:green; font-weight:bold;font-size:12px;',
            labelWidth: 70,
            name: 'arr_item_number',
            fieldStyle: 'color:#3d74b3; font-weight:bold;font-size:12px;',
            readOnly: true,
            allowBlank: false
        },
        {
            xtype: 'textfield',
            x: -31,
            y: 75,
            itemId: 'comment',
            width: 620,
            fieldLabel: 'Comment',
            labelAlign: 'right',
            labelStyle: 'color:blue; font-weight:bold;font-size:12px;'
        },
        {
            xtype: 'button',
            x: 675,
            y: 75,
            itemId: 'cmdRemoveDose',
            width: 95,
            iconCls: 'x-fa fa-trash',
            text: 'Remove'
        }
    ]

});

// End of Dosage panel
//************************************************************ */

Ext.define('ItemsList', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.itemslist',

    requires: [
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.toolbar.Paging'
    ],

    height: 414,
    id: 'itemsList',
    width: 632,
    bodyStyle: 'background-color: #d9f2e6;',
    columnLines: true,
    store: ItemslistStore,

    dockedItems: [
        {
            xtype: 'fieldset',
            dock: 'top',
            height: 46,
            padding: 0,
            style: 'background-color: #d9f2e6;',
            width: 100,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 5,
                    y: 5,
                    itemId: 'txtSearchItems',
                    padding: 0,
                    width: 380,
                    emptyText: 'Search by Description, PartCode'
                },
                {
                    xtype: 'textfield',
                    x: 390,
                    y: 5,
                    itemId: 'sourceID'
                }
            ]
        },
        {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            width: 360,
            displayInfo: true,
            //store: 'ItemslistStore'
        }
    ],
    columns: [
        {
            xtype: 'gridcolumn',
            dataIndex: 'partcode',
            text: 'Partcode'
        },
        {
            xtype: 'gridcolumn',
            width: 272,
            dataIndex: 'item_description',
            text: 'Item Description'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'qty',
            text: 'Qty'
        },
        {
            xtype: 'gridcolumn',
            dataIndex: 'unit_price',
            text: 'Unit Price'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            width: 135,
            dataIndex: 'purchasing_class',
            text: 'Purchasing Class'
        },
        {
            xtype: 'gridcolumn',
            hidden: true,
            width: 150,
            dataIndex: 'category',
            text: 'Category'
        }
    ]

});

    var prescriptionsPanel = new Ext.form.Panel({
        height: 390,
        width: 744,
        bodyPadding: 10,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
		defaultListenerScope: true,
        items: [
            {
                xtype: 'fieldset',
                height: 45,
                padding: 0,
                layout: 'absolute',
                items: [
                    {
                        xtype: 'button',
                        x: 35,
                        y: 5,
                        id: 'cmdNewPrescription',
                        width: 160,
                        iconCls: 'x-fa fa-plus',
                        text: 'New Prescription'
                    }
                ]
            },
            {
                xtype: 'gridpanel',
                height: 400,
                store: PrescriptionStore,
                columns: [
                    {
                        xtype: 'gridcolumn',
                        width: 67,
                        dataIndex: 'nr',
                        text: 'Nr'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'encounterNo',
                        text: 'Encounter No'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'Partcode',
                        text: 'Partcode'
                    },
                    {
                        xtype: 'gridcolumn',
                        width: 203,
                        dataIndex: 'Description',
                        text: 'Description'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'dosage',
                        text: 'Dosage'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'timesperday',
                        text: 'Timesperday'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'days',
                        text: 'Days'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'notes',
                        text: 'Notes'
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'status',
                        text: 'Status'
                    }
                ]
            }
        ]
    });

    var newPrescriptionsPanel = new Ext.form.Panel({
        height: 558,
        //width: 1000,
        bodyPadding: 10,
        frame:true,
        layout: {
            type: 'hbox',
            align: 'stretch'
        },
		defaultListenerScope: true,
        items: [
            {
                xtype: 'itemslist',
                width: 410,
                flex: 0
            },
            {
                xtype: 'form',
                flex: 0,
                frame: true,
                width: 61,
                layout: 'absolute',
                bodyPadding: 10,
                items: [
                    {
                        xtype: 'button',
                        x: 5,
                        y: 255,
                        text: '<<<'
                    },
                    {
                        xtype: 'button',
                        x: 5,
                        y: 190,
                        text: '>>>'
                    }
                ]
            },
            {
                xtype: 'form',
                flex: 1,
                itemId: 'dosageList',
                id:'dosageList',
                minWidth: 600,
                scrollable: 'vertical',
                bodyPadding: 10,
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                url: '../../data/getDataFunctions.php?task=savePrescription',
                dockedItems: [
                    {
                        xtype: 'fieldcontainer',
                        dock: 'bottom',
                        height: 51,
                        layout: {
                            type: 'hbox',
                            align: 'stretch',
                            pack: 'center'
                        },
                        items: [
                            {
                                xtype: 'button',
                                itemId: 'cmdPrescribe',
                                margin: '0 10 0 0',
                                width: 116,
                                iconCls: 'x-fa fa-plus',
                                text: 'Prescribe'
                            },
                            {
                                xtype: 'textfield',
                                itemId: 'counter',
                                id:'counter',
                                width: 42,
                                name: 'counter',
                                value: 0
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        dock: 'top',
                        height: 44,
                        itemId: 'patientForm1',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [
                            {
                                xtype: 'displayfield',
                                itemId: 'Pid',
                                margin: '0 10  0 0',
                                fieldLabel: 'PID',
                                labelStyle: 'color:red; font-weight:bold;',
                                labelWidth: 20,
                                name: 'Pid',
                                fieldStyle: 'color:green; font-weight:bold;'
                            },
                            {
                                xtype: 'displayfield',
                                itemId: 'Names',
                                margin: '0 10 0 0',
                                fieldLabel: 'Patient',
                                labelStyle: 'color:red; font-weight:bold;',
                                labelWidth: 50,
                                name: 'Names',
                                fieldStyle: 'color:green; font-weight:bold;'
                            },
                            {
                                xtype: 'displayfield',
                                itemId: 'EncounterNo',
                                margin: '0 10 0 0',
                                fieldLabel: 'Encounter No',
                                labelStyle: 'color:red; font-weight:bold;',
                                name: 'EncounterNo',
                                fieldStyle: 'color:green; font-weight:bold;'
                            },
                            {
                                xtype: 'displayfield',
                                itemId: 'PrescribeDate',
                                margin: '0 10 0 0',
                                fieldLabel: 'Prescription Date',
                                labelStyle: 'color:red; font-weight:bold;',
                                labelWidth: 120,
                                name: 'PrescribeDate',
                                fieldStyle: 'color:green; font-weight:bold;'
                            }
                        ]
                    }
                ]
            }
        ]
    });

     
    if (!win) {
        win = new Ext.Window({
           applyTo: 'container',
            layout: 'fit',
            height: 437,
			width: 745,
            closable: true,
            closeAction: 'hide',
            scrollable: 'vertical',
            title: 'Patients Prescriptions',
            plain: true ,
            items: [prescriptionsPanel]

        });
    }

    if (!win2) {
        win2 = new Ext.Window({
           applyTo: 'container',
            layout: 'fit',
            height: 437,
			//width: 745,
            closable: true,
            closeAction: 'hide',
            scrollable: 'vertical',
            title: 'New Prescriptions',
            plain: true ,
            items: [newPrescriptionsPanel]

        });
    }

    var prescPanel = Ext.get('prescriptions');
    
	var pid = Ext.get('pid2');
	var encounterNr=Ext.get('encNo');
    var names = Ext.get('names2');
    var dob = Ext.get('dob');
    var newPresc=Ext.getCmp('cmdNewPrescription');
    var itemsList=Ext.getCmp('itemsList');
    
    itemsList.on('itemdblclick',function(gridpanel, record, item, index, e, options){
        var counter=Ext.getCmp("counter").getValue();
        var i=counter;
        var dosages=Ext.create('Ext.form.Panel',{ 
            items:{
                xtype:'dosage'
            }
         });
        //dosages.add(dosage);
        
        // dosage.itemId=record.get('partcode');
        // dosages.down('#partCode').setValue(record.get('partcode'));
        // dosages.down('#description').setValue(record.get('item_description'));
        // dosages.down('#qty').setValue(record.get('qty'));
        // dosages.down('#unitCost').setValue(record.get('unit_price'));
        // dosages.down('#dose').setValue(1);
        // dosages.down('#itemNumber').setValue(counter);

         
        // dosages.down('#partCode').name='partCode'+ i;
        // dosages.down('#description').name='description'+ i;
        // dosages.down('#dose').name='dose'+ i ;
        // dosages.down('#timesperday').name='timesperday'+ i;
        // dosages.down('#days').name='days'+ i ;
        // dosages.down('#total').name='total'+ i ;
        // dosages.down('#comment').name='comment'+ i;

        counter=parseInt(counter)+1;
        // newVar=parseInt(counter;

       // Ext.Msg.alert('Prescription',newVar);
       Ext.getCmp("counter").setValue(counter);
       Ext.getCmp('dosageList').add(dosages);
        //  Ext.getCmp('dosageList').doLayout();
    });

    newPresc.on('click', function(){
        win2.show(this);
    });

    prescPanel.on('click', function(){
        //Ext.Msg.alert("Test",Ext.get('names').getValue());
            win.show(this);
           
			// Ext.getCmp('pid').setValue(pid.getValue());
			// Ext.getCmp('names').setValue(names.getValue());
			// Ext.getCmp('encounterNo').setValue(encounterNr.getValue());
            // Ext.getCmp('Dob').setValue(dob.getValue());

    });


});