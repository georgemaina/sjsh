
Ext.require([
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column'
]);


Ext.onReady(function () {
    var isLargeTheme = Ext.themeName !== 'classic',
        titleOffset = isLargeTheme ? -6 : -4;
    var win;

    var vitalsForm = new Ext.form.Panel({
        url: '../../data/getDataFunctions.php?task=saveVitals',
        height: 390,
        width: 744,
        bodyPadding: 10,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
		//defaultListenerScope: true,
        items: [
            {
                xtype: 'fieldset',
                height: 315,
                padding: '5 0 0 0',
                style: 'background:#1b5f87',
                width: 744,
                layout: 'absolute',
                items: [
                    {
                        xtype: 'textfield',
                        x: 60,
                        y: 0,
                        fieldLabel: 'Weight',
                        itemId: 'weight',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 80,
                        name: 'weight',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 20,
                        y: 35,
                        fieldLabel: 'Height',
                        id: 'height',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 120,
                        name: 'height',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: -9,
                        y: 70,
                        fieldLabel: 'Head Circumference',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 150,
                        name: 'head_c',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 30,
                        y: 105,
                        width: 200,
                        fieldLabel: 'Blood Pressure',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 110,
                        name: 'bp',
                        emptyText: 'Systoric',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 235,
                        y: 105,
                        width: 80,
                        labelAlign: 'right',
                        name: 'bp2',
                        emptyText: 'Diastoric',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 60,
                        y: 140,
                        fieldLabel: 'Pulse rate',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 80,
                        name: 'pulse',
                        value: 0
                    },
                    {
                        xtype: 'textareafield',
                        x: 60,
                        y: 175,
                        height: 82,
                        width: 550,
                        fieldLabel: 'Notes',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 80,
                        name: 'notes'
                    },
                    {
                        xtype: 'textfield',
                        x: 325,
                        y: -2,
                        fieldLabel: 'Respiratory rate',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 110,
                        name: 'resprate',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 335,
                        y: 35,
                        fieldLabel: 'Temperature',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        name: 'temperature',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 355,
                        y: 105,
                        fieldLabel: 'SPO2',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 80,
                        name: 'spo2',
                        value: 0
                    },
                    {
                        xtype: 'textfield',
                        x: 355,
                        y: 70,
                        fieldLabel: 'BMI',
                        itemId: 'bmi',
                        labelAlign: 'right',
                        labelStyle: 'color:white; font-weight:bold;',
                        labelWidth: 80,
                        name: 'bmi'
                    },
                    {
                        xtype: 'button',
                        x: 135,
                        y: 260,
                        height: 40,
                        itemId: 'SaveVitals',
                        width: 140,
                        text: 'Save',
                        iconCls: 'x-fa fa-save',
                        handler: function(){
                            var pid=Ext.getCmp('pid').getValue();
                            var form = this.up('panel').getForm(); // get the basic form
                            if (form.isValid()) { // make sure the form contains valid data before submitting
                                form.submit({
                                    params:{
                                        pid:pid
                                    },
                                    success: function (form, action) {
                                        Ext.Msg.alert('Thank you!', 'The Vitals has been saved Successfully.');
                                        this.up('form').getForm().reset();
                                        this.up('window').hide();
                
                                    },
                                    failure: function (form, action) {
                                        var jsonResp = Ext.decode(action.response.responseText);
                
                                        Ext.Msg.alert('Failed', 'Could not save Vitals. \n Error=' + jsonResp.error);
                                    }
                                });
                            } else { // display error alert if the data is invalid
                                Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
                            }
                        }
                    },
                    {
                        xtype: 'button',
                        x: 425,
                        y: 260,
                        height: 40,
                        itemId: 'cmdClose',
                        width: 140,
                        text: 'Close',
                        iconCls: 'x-fa fa-close',
                        handler :function() {
                            var form = this.up('form').getForm();
                            if (form.isValid()) {
                                this.up('window').hide();
                            }
                        }
                    }
                ]
            }
        ],
        dockedItems: [
            {
                xtype: 'fieldset',
                dock: 'top',
                height: 45,
                style: 'background:#386d87',
                width: 744,
                layout: {
                    type: 'hbox',
                    align: 'stretch'
                },
                items: [
                    {
                        xtype: 'displayfield',
                        itemId: 'pid',
                        id:'pid',
                        width: 120,
                        fieldLabel: 'PID',
                        labelStyle: 'font-weight:bold; color:#f4f6fc;',
                        labelWidth: 30,
                        fieldStyle: 'color:#a7e88b;font-weight-bold;'
                    },
                    {
                        xtype: 'displayfield',
                        itemId: 'names',
                        id: 'names',
                        width: 271,
                        fieldLabel: 'Names',
                        labelPad: 0,
                        labelStyle: 'font-weight:bold; color:#f4f6fc;',
                        labelWidth: 60,
                        fieldStyle: 'color:#a7e88b;font-weight-bold;'
                    },
                    {
                        xtype: 'textfield',
                        hidden: true,
                        itemId: 'encounterNo',
                        id: 'encounterNo',
                        width: 211,
                        fieldLabel: 'Encounter No',
                        labelStyle: 'font-weight:bold; color:#f4f6fc;',
                        name: 'encounterNo',
                        fieldStyle: '',
                        readOnly: true
                    },
                    {
                        xtype: 'displayfield',
                        itemId: 'Dob',
                        id: 'Dob',
                        width: 239,
                        fieldLabel: 'Date of Birth',
                        labelStyle: 'font-weight:bold; color:#f4f6fc;',
                        fieldStyle: 'color:#a7e88b;font-weight-bold;'
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
            title: 'Patient Vitals',
            plain: true,
            items: [vitalsForm]

        });
    }

    var vitals = Ext.get('vitals');
	var pid = Ext.get('pid2');
	var encounterNr=Ext.get('encNo');
    var names = Ext.get('names2');
    var dob = Ext.get('dob');
    var pHeight=Ext.getCmp('height');

    pHeight.on('change', function(field, newValue, oldValue, eOpts){
        //Ext.alert('Weight',field.up('form').down('#weight').getValue());
        var weight=field.up('form').down('#weight').getValue();
        var height=field.up('form').down('#height').getValue();
        var heightInMtrs=height/100;
        var bmi = weight/(heightInMtrs * heightInMtrs);
        var strBmi =Math.round(bmi);
        field.up('form').down('#bmi').setValue(strBmi);

    });

    vitals.on('click', function(){
        //Ext.Msg.alert("Test",Ext.get('names').getValue());
            win.show(this);
			Ext.getCmp('pid').setValue(pid.getValue());
			Ext.getCmp('names').setValue(names.getValue());
			Ext.getCmp('encounterNo').setValue(encounterNr.getValue());
            Ext.getCmp('Dob').setValue(dob.getValue());

    });


});