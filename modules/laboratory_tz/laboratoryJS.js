/**
 * Created by george maina
 * Email: georgemainake@gmail.com
 * Copyright: All rights reserved on 5/15/14.
 */
 
 Ext.require([
    'Ext.form.Panel',
    'Ext.form.field.ComboBox',
    'Ext.XTemplate',
    'Ext.button.Button',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json'
]);

Ext.define('labTests', {
    extend: 'Ext.data.Model',
    fields: [
        {type: 'string', name: 'item_id'},
        {type: 'string', name: 'testName'}
    ]
});

var LabTestsStore=Ext.create('Ext.data.Store', {
    // alias: 'store.labtests',
    autoLoad: true,
    model: 'labTests',
    proxy: {
        type: 'ajax',
        url: 'getlaboratoryData.php?task=getLabTests',
        reader: {
            type: 'json',
            root: 'labtests'
        }
    }
});

LabTestsStore.load({});

function checkButton(item_id){

		var labForm= new Ext.FormPanel({
			height: 300,
			width:650,
			name:'labParams',
			layout: 'absolute',
			bodyPadding: 10,
			url: 'getlaboratoryData.php?task=insertResultsParams',
			items: [
				{
					xtype: 'combobox',
					x: 5,
					y: 30,
					width: 350,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Item Description',
					labelAlign: 'right',
					labelWidth: 120,
					name: 'item_id',
					allowBlank: false,
					displayField: 'testName',
					queryMode: 'local',
					store: LabTestsStore,
					valueField: 'item_id'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 60,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Results',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'results',
					allowBlank: false
				},
				{
					xtype: 'combobox',
					x: 25,
					y: 90,
					width: 350,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Input Type',
					labelAlign: 'right',
					name: 'input_type',
					allowBlank: false,
					queryMode: 'local',
					minChars: 2,
					store: ['INPUT_BOX','DROP_DOWN','TEXT_AREA','TITLE']
				},
				{
					xtype: 'textfield',
					x: 5,
					y: 120,
					fieldLabel: 'Drop down Values',
					width:500,
					labelAlign: 'right',
					labelWidth: 120,
					name: 'dropDown'
				},
				{
					xtype: 'textfield',
					x: 75,
					y: 150,
					fieldLabel: 'Normal',
					labelAlign: 'right',
					labelWidth: 50,
					name: 'normal'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 180,
					fieldLabel: 'Ranges',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'ranges'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 220,
					fieldLabel: 'Results',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'result_values'
				},
				{
					xtype: 'button',
					x: 120,
					y: 250,
					height: 40,
					itemId: 'cmdSave',
					width: 145,
					text: 'Save',
					listeners: {
						click: {
							 fn: onCmdSaveClick
						}
					}
				},
				{
					xtype: 'button',
					x: 430,
					y: 250,
					height: 40,
					itemId: 'cmdClose',
					width: 145,
					text: 'Close',
					listeners: {
						click: {
						   fn: onCmdCloseClick
						}
					}
				}
			]
		});
          
		  labForm.getForm().findField('item_id').setValue(item_id);
		  
		  Ext.Ajax.request({
            url: 'getlaboratoryData.php?task=getResultsParams',
            params:{
                resultID:item_id
            },
            success: function(response, opts) {
                var obj = Ext.decode(response.responseText);
               Ext.getCmp('results').setValue(obj.results);
               labForm.getForm().findField('results').setValue(obj.results);
             },
            failure: function(response, opts) {
                console.log('server-side failure with status code ' + response.status);
                Ext.Msg.alert("Error:","Uable to generate LPO");
            },
            scope : this

        });
		  //labForm.getForm().findField('results').setValue(results);
		 // labForm.getForm().findField('input_type').setValue(input_type);
		 // labForm.getForm().findField('normal').setValue(normal);
		 // labForm.getForm().findField('ranges').setValue(ranges);
		  //labForm.getForm().findField('result').setValue(result);
	   
		 var win;
		
		 win =  Ext.create('widget.window', {
              //  applyTo:'lab-win',
                layout:'fit',
                title: 'Laboratory Results Parameters ',
                closeAction:'hide',
                plain: true,
				items:[labForm],
				
		 });
		 
		 
		    win.show();


        function onCmdSaveClick(button,e,eOpts){
            //xt.Msg.alert('test','test');
            var form = button.up('form').getForm(); // get the basic form
            if (form.isValid()) { // make sure the form contains valid data before submitting
                form.submit({
                    success: function(form, action) {
                        Ext.Msg.alert('Success', 'Saved new Member successfully.');

                        var win = button.up('newmemberswindow');
                        win.removeAll();
                        win.destroy();
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert('Failed', 'Could not save Member. Error='+action.result.errors.clientNo);
                    }
                });
            } else { // display error alert if the data is invalid
                Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
            }
        }

        function onCmdCloseClick(button,e,eOpts){
            var win=button.up('window');
            win.close()
        }
		
		

    

        //        });
    
	
	

}




Ext.onReady(function(){
    var win;
    var button = Ext.get('lab-btn');
	var button2 = Ext.get('edit-btn');
	
    button.on('click', function(){

        //Ext.Msg.alert('test','test');

        // create the window on the first click and reuse on subsequent clicks

        if(!win){
            win =  Ext.create('widget.window', {
              //  applyTo:'lab-win',
                layout:'fit',
                title: 'Laboratory Results Parameters ',
                closeAction:'hide',
                plain: true,
                items: [ {
                                xtype: 'form',
                                height: 300,
                                width:650,
                                layout: 'absolute',
                                bodyPadding: 10,
                                url: 'getlaboratoryData.php?task=insertResultsParams',
                                items: [
				{
					xtype: 'combobox',
					x: 5,
					y: 30,
					width: 350,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Item Description',
					labelAlign: 'right',
					labelWidth: 120,
					name: 'item_id',
					allowBlank: false,
					displayField: 'testName',
					queryMode: 'local',
					store: LabTestsStore,
					valueField: 'item_id'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 60,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Results',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'results',
					allowBlank: false
				},
				{
					xtype: 'combobox',
					x: 25,
					y: 90,
					width: 350,
					afterLabelTextTpl: [
						'<span style="color:red;font-weight:bold" data-qtip="Required">*</span>'
					],
					fieldLabel: 'Input Type',
					labelAlign: 'right',
					name: 'input_type',
					allowBlank: false,
					queryMode: 'local',
					minChars: 2,
					store: ['INPUT_BOX','DROP_DOWN','TEXT_AREA','TITLE']
				},
				{
					xtype: 'textfield',
					x: 5,
					y: 120,
					fieldLabel: 'Drop down Values',
					width:500,
					labelAlign: 'right',
					labelWidth: 120,
					name: 'dropDown'
				},
				{
					xtype: 'textfield',
					x: 75,
					y: 150,
					fieldLabel: 'Normal',
					labelAlign: 'right',
					labelWidth: 50,
					name: 'normal'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 180,
					fieldLabel: 'Ranges',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'ranges'
				},
				{
					xtype: 'textfield',
					x: 65,
					y: 220,
					fieldLabel: 'Results',
					labelAlign: 'right',
					labelWidth: 60,
					name: 'result_values'
				},
				{
					xtype: 'button',
					x: 120,
					y: 250,
					height: 40,
					itemId: 'cmdSave',
					width: 145,
					text: 'Save',
					listeners: {
						click: {
							 fn: onCmdSaveClick
						}
					}
				},
				{
					xtype: 'button',
					x: 430,
					y: 250,
					height: 40,
					itemId: 'cmdClose',
					width: 145,
					text: 'Close',
					listeners: {
						click: {
						   fn: onCmdCloseClick
						}
					}
				}
			]


                    }
                ]
            });
        }

        function onCmdSaveClick(button,e,eOpts){
            //xt.Msg.alert('test','test');
            var form = button.up('form').getForm(); // get the basic form
            if (form.isValid()) { // make sure the form contains valid data before submitting
                form.submit({
                    success: function(form, action) {
                        Ext.Msg.alert('Success', 'Saved new Member successfully.');

                        var win = button.up('newmemberswindow');
                        win.removeAll();
                        win.destroy();
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert('Failed', 'Could not save Member. Error='+action.result.errors.clientNo);
                    }
                });
            } else { // display error alert if the data is invalid
                Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
            }
        }

        function onCmdCloseClick(button,e,eOpts){
            var win=button.up('window');
            win.close()
        }

        win.show(this);

        //        });
    })
	
	
})