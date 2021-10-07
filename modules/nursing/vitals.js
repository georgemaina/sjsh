
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
        url: '../data/getDatafunctions.php?task=saveVitals',
		bodyPadding: 3,
        height: 470,
		width: 745,
        layout: 'absolute',
		defaultListenerScope: true,
      items: [
        {
            xtype: 'fieldset',
            x: -1,
            y: 35,
            height: 265,
            width: 744,
            layout: 'absolute',
            items: [
                {
                    xtype: 'textfield',
                    x: 35,
                    y: 10,
                    fieldLabel: 'Weight',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'weight',
                    emptyText: 'Weight'
                },
                {
                    xtype: 'textfield',
                    x: -6,
                    y: 40,
                    fieldLabel: 'Height',
                    labelAlign: 'right',
                    labelWidth: 120,
                    name: 'height',
                    emptyText: 'Height'
                },
                {
                    xtype: 'textfield',
                    x: -6,
                    y: 70,
                    fieldLabel: 'Head Circumference',
                    labelAlign: 'right',
                    labelWidth: 120,
                    name: 'head_c',
                    emptyText: 'Head Circumference'
                },
                {
                    xtype: 'textfield',
                    x: 15,
                    y: 100,
                    width: 180,
                    fieldLabel: 'Blood Pressure',
                    labelAlign: 'right',
                    name: 'bp1',
                    emptyText: 'Systoric'
                },
                {
                    xtype: 'textfield',
                    x: 195,
                    y: 100,
                    width: 75,
					name:'bp2',
                    labelAlign: 'right',
                    emptyText: 'Diastoric'
                },
                {
                    xtype: 'textfield',
                    x: 35,
                    y: 130,
                    fieldLabel: 'Pulse rate',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'pulse',
                    emptyText: 'Pulse Rate'
                },
                {
                    xtype: 'textfield',
                    x: 310,
                    y: 10,
                    fieldLabel: 'Respiratory rate',
                    labelAlign: 'right',
                    name: 'resprate',
                    emptyText: 'Respiratory Rate'
                },
                {
                    xtype: 'textfield',
                    x: 330,
                    y: 40,
                    fieldLabel: 'Temperature',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'temperature',
                    emptyText: 'Temperature'
                },
                {
                    xtype: 'combobox',
                    x: 330,
                    y: 70,
                    fieldLabel: 'HTC',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'htc',
                    emptyText: 'HTC'
                },
                {
                    xtype: 'textfield',
                    x: 330,
                    y: 130,
                    fieldLabel: 'SPO2',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'spo2',
                    emptyText: 'SPO2'
                },
                {
                    xtype: 'textareafield',
                    x: 35,
                    y: 160,
                    height: 58,
                    width: 530,
                    fieldLabel: 'Comment',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'notes',
                    emptyText: 'Comments'
                },
                {
                    xtype: 'textfield',
                    x: 330,
                    y: 100,
                    fieldLabel: 'BMI',
                    labelAlign: 'right',
                    labelWidth: 80,
                    name: 'bmi',
                    emptyText: 'BMI'
                },
                {
                    xtype: 'button',
                    x: 165,
                    y: 225,
                    height: 40,
                    itemId: 'Save',
                    width: 140,
                    text: 'Save',
                    listeners: {
                        click: 'onCmdSaveClick'
                    }
                },
                {
                    xtype: 'button',
                    x: 440,
                    y: 225,
                    height: 40,
                    itemId: 'Close',
                    width: 140,
                    text: 'Close',
                    listeners: {
                        click: 'onCmdCloseClick'
                    }
                }
            ]
        },
        {
            xtype: 'gridpanel',
            x: 0,
            y: 300,
            height: 190,
            width: 740,
            title: 'Vitals List',
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'ID',
                    text: 'ID'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Weight',
                    text: 'Weight'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Height',
                    text: 'Height'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'HeadCircumference',
                    text: 'Head Circumference'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'BP',
                    text: 'Bp'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'PulseRate',
                    text: 'Pulse Rate'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Respiratory',
                    text: 'Respiratory'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'Temperature',
                    text: 'Temperature'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'HTC',
                    text: 'Htc'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'BMI',
                    text: 'Bmi'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'SPO2',
                    text: 'Spo2'
                }
            ]
        },
        {
            xtype: 'displayfield',
            x: -1,
            y: 5,
            itemId: 'pid',
			id: 'pid',
            width: 170,
            fieldLabel: 'PID',
            labelWidth: 40
        },
        {
            xtype: 'displayfield',
            x: 190,
            y: 5,
            itemId: 'names',
			id: 'names',
            width: 170,
            fieldLabel: 'Names',
            labelWidth: 60
        },
        {
            xtype: 'textfield',
            x: 465,
            y: 5,
            itemId: 'encounterNr',
			name: 'encounter_nr',
			id: 'encounterNr',
            width: 170,
            fieldLabel: 'encounter No',
            labelWidth: 80
        }
    ],

        onCmdSaveClick: function (button, e, eOpts) {
            var form = button.up('panel').getForm(); // get the basic form
            if (form.isValid()) { // make sure the form contains valid data before submitting
                form.submit({
                    success: function (form, action) {
                        Ext.Msg.alert('Thank you!', 'The Vitals has been saved Successfully.');
                        button.up('form').getForm().reset();
                        button.up('window').hide();

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
    });


    if (!win) {
        win = new Ext.Window({
           applyTo: 'container',
            layout: 'fit',
            height: 500,
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
	var pid = Ext.get('pid');
	var encounterNr=Ext.get('encounterNr');
	var names = Ext.get('names');

    vitals.on('click', function(){
       // Ext.Msg.alert("Test",Ext.get('names').getValue());
            win.show(this);
			Ext.getCmp('pid').setValue(pid.getValue());
			Ext.getCmp('names').setValue(names.getValue());
			Ext.getCmp('encounterNr').setValue(encounterNr.getValue());


    });


});