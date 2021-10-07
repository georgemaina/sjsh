// JavaScript Document
                                           
    function setHtcNotes(str) {
        // alert(str)
        xmlhttp = GetXmlHttpObject();
        if (str == "OPTOUT") {

            if (xmlhttp == null)
            {
                alert("Browser does not support HTTP Request");
                return;
            }
            var url = "myFunctions.php?task=getHtcReasons";
            url = url + "&sid=" + Math.random();
            xmlhttp.onreadystatechange = stateChanged7;
            xmlhttp.open("POST", url, true);
            xmlhttp.send(null);
        }

    }

    function stateChanged7()
    {
        //get payment description
        if (xmlhttp.readyState == 4)
        {
            var str3 = xmlhttp.responseText;

            document.getElementById('optOutReason').innerHTML = str3;


        }
    }

    function checkIfInnitialExists(pid) {
        // alert(str)
        xmlhttp = GetXmlHttpObject();
        if (xmlhttp === null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }
        var url = "data/getDataFunctions.php?task=validatePatient";
        url = url + "&pid=" + pid;
        url = url + "&validateSource=1";
        url = url + "&sid=" + Math.random();
        xmlhttp.onreadystatechange = stateChanged9;
        xmlhttp.open("POST", url, true);
        xmlhttp.send(null);

    }

    function stateChanged9()
    {
        //get payment description
        if (xmlhttp.readyState === 4)
        {
            var str3 = xmlhttp.responseText;
            // if(str3>0){
            document.getElementById('innitialMode').value = str3;
            //}


        }
    }

    function checkBP() {
        alert('Test')
    }


    Ext.require([
        'Ext.Editor',
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Date',
        'Ext.form.FieldSet',
        'Ext.form.RadioGroup',
        'Ext.form.field.Radio',
        'Ext.button.Button'
    ]);



    Ext.onReady(function () {
        var isLargeTheme = Ext.themeName !== 'classic',
                titleOffset = isLargeTheme ? -6 : -4;
        var win, win2;

        var continuationForm = new Ext.form.Panel({
            height: 460,
            width: 944,
            layout: 'absolute',
            bodyPadding: 3,
            url: 'data/getDatafunctions.php?task=saveContinuationCare',
            defaultListenerScope: true, 
            items: [
        {
            xtype: 'fieldset',
            x: 5,
            y: 60,
            height: 260,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'textareafield',
                    x: 5,
                    y: 170,
                    height: 81,
                    tabIndex: 15,
                    width: 765,
                    fieldLabel: 'Observations: Eg weight gain, change in diabetic status, LMP if female etc',
                    labelAlign: 'top',
                    labelWidth: 70,
                    name: 'Observations'
                },
                {
                    xtype: 'textfield',
                    x: -57,
                    y: 10,
                    id: 'Heights',
                    itemId: 'Height',
                    width: 180,
                    fieldLabel: 'Height',
                    labelAlign: 'right',
                    name: 'Height',
                    emptyText: 'Height'
                },
                {
                    xtype: 'textfield',
                    x: 80,
                    y: 10,
                    id: 'Weights',
                    itemId: 'Weight',
                    width: 180,
                    fieldLabel: 'Weight',
                    labelAlign: 'right',
                    name: 'Weight',
                    allowBlank: false,
                    emptyText: 'Weight'
                },
                {
                    xtype: 'textfield',
                    x: 200,
                    y: 10,
                    id: 'BMIs',
                    itemId: 'BMI',
                    width: 180,
                    fieldLabel: 'BMI',
                    labelAlign: 'right',
                    name: 'BMI',
                    emptyText: 'BMI'
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 40,
                    width: 405,
                    fieldLabel: 'Do you smoke cigarettes',
                    labelWidth: 200,
                    items: [
                        {
                            xtype: 'radiofield',
                            name: 'Smoking',
                            boxLabel: 'Yes',
                            inputValue: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Smoking',
                            boxLabel: 'No',
                            inputValue: 'No'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Smoking',
                            boxLabel: 'Stopped',
                            inputValue: 'Stopped'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 70,
                    width: 405,
                    fieldLabel: 'Do you sometimes drink alcohol',
                    labelWidth: 200,
                    items: [
                        {
                            xtype: 'radiofield',
                            name: 'Drinking',
                            boxLabel: 'Yes',
                            inputValue: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Drinking',
                            boxLabel: 'No',
                            inputValue: 'No'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Drinking',
                            boxLabel: 'Stopped',
                            inputValue: 'Stopped'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 100,
                    width: 405,
                    fieldLabel: 'Do you eat Healthy diet (low salt/fats, high vegetable/fruit at least 5 times/day)',
                    labelWidth: 265,
                    items: [
                        {
                            xtype: 'radiofield',
                            name: 'Diet',
                            boxLabel: 'Yes',
                            inputValue: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Diet',
                            boxLabel: 'No',
                            inputValue: 'No'
                        }
                    ]
                },
                {
                    xtype: 'radiogroup',
                    x: 10,
                    y: 135,
                    width: 405,
                    fieldLabel: 'Adequate physical activity ( 3 times a week at least 30 min)',
                    labelWidth: 265,
                    items: [
                        {
                            xtype: 'radiofield',
                            name: 'Physical',
                            boxLabel: 'Yes',
                            inputValue: 'Yes'
                        },
                        {
                            xtype: 'radiofield',
                            name: 'Physical',
                            boxLabel: 'No',
                            inputValue: 'No'
                        }
                    ]
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 325,
            height: 50,
            padding: '0 0 0 0',
            layout: 'absolute',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 30,
                    y: 15,
                    height: 20,
                    width: 340,
                    fieldLabel: 'Any known allergies to drugs?',
                    labelWidth: 200,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 18,
                            name: 'DrugAllergies',
                            boxLabel: 'Yes',
                            inputValue: 'Y'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 19,
                            name: 'DrugAllergies',
                            boxLabel: 'No',
                            inputValue: 'N'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 440,
                    y: 10,
                    tabIndex: 20,
                    width: 335,
                    fieldLabel: 'Specify',
                    name: 'AllergiesSpecify'
                },
                {
                    xtype: 'label',
                    x: 5,
                    y: 0,
                    text: 'Contra indications'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 5,
            height: 50,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'textfield',
                    x: 10,
                    y: 5,
                    id: 'BPFirstReadings1',
                    itemId: 'BPFirstReading1',
                    tabIndex: 1,
                    width: 150,
                    fieldLabel: 'BP First Reading',
                    name: 'BPFirstReading1',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 230,
                    y: 5,
                    id: 'BPSecondReadings1',
                    itemId: 'BPSecondReading1',
                    tabIndex: 3,
                    width: 160,
                    fieldLabel: 'BP Second Reading',
                    labelWidth: 110,
                    name: 'BPSecondReading1',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 160,
                    y: 5,
                    id: 'BPFirstReadings2',
                    itemId: 'BPFirstReading2',
                    tabIndex: 2,
                    width: 45,
                    name: 'BPFirstReading2',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 385,
                    y: 5,
                    id: 'BPSecondReadings2',
                    itemId: 'BPSecondReading2',
                    tabIndex: 4,
                    width: 50,
                    name: 'BPSecondReading2',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    x: 450,
                    y: 5,
                    id: 'pids',
                    itemId: 'pid',
                    tabIndex: 4,
                    width: 225,
                    fieldLabel: 'Pid',
                    labelAlign: 'right',
                    name: 'pid',
                    allowBlank: false,
                    emptyText: 'PID'
                },
                {
                    xtype: 'textfield',
                    x: 690,
                    y: 5,
                    id: 'encounterNrs',
                    itemId: 'encounterNr',
                    tabIndex: 4,
                    width: 225,
                    fieldLabel: 'EncounterNo',
                    labelAlign: 'right',
                    name: 'encounterNr',
                    allowBlank: false,
                    emptyText: 'EncounterNr'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 380,
            height: 50,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 20,
                    y: 15,
                    height: 20,
                    width: 405,
                    fieldLabel: 'Adhering to current medications',
                    labelWidth: 210,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 21,
                            name: 'AdheringMedication',
                            boxLabel: 'N/A',
                            inputValue: 'NA'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 22,
                            name: 'AdheringMedication',
                            boxLabel: 'Yes',
                            inputValue: 'Y'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 23,
                            name: 'AdheringMedication',
                            boxLabel: 'No',
                            inputValue: 'N'
                        }
                    ]
                },
                {
                    xtype: 'textfield',
                    x: 440,
                    y: 15,
                    tabIndex: 24,
                    width: 335,
                    fieldLabel: 'Specify Reason',
                    name: 'AdheringSpecify'
                },
                {
                    xtype: 'label',
                    x: 0,
                    y: -1,
                    text: 'Current Treatment'
                }
            ]
        },
        {
            xtype: 'fieldset',
            x: 5,
            y: 435,
            height: 45,
            padding: '0 0 0 0',
            layout: 'absolute',
            title: '',
            items: [
                {
                    xtype: 'radiogroup',
                    x: 15,
                    y: 15,
                    height: 30,
                    width: 445,
                    fieldLabel: '',
                    labelWidth: 200,
                    allowBlank: false,
                    items: [
                        {
                            xtype: 'radiofield',
                            tabIndex: 25,
                            name: 'FollowupPlan',
                            boxLabel: 'Continue care at Facilty',
                            inputValue: 'Continue'
                        },
                        {
                            xtype: 'radiofield',
                            tabIndex: 26,
                            name: 'FollowupPlan',
                            boxLabel: 'Refer to Another Facility',
                            inputValue: 'Refer'
                        }
                    ]
                },
                {
                    xtype: 'datefield',
                    x: 440,
                    y: 10,
                    hidden: true,
                    tabIndex: 27,
                    width: 250,
                    fieldLabel: 'Return Date',
                    name: 'ReturnDate'
                },
                {
                    xtype: 'label',
                    x: 5,
                    y: 0,
                    text: 'Follow Up Plan'
                }
            ]
        },
        {
            xtype: 'button',
            x: 635,
            y: 490,
            height: 35,
            itemId: 'cmdSave',
            tabIndex: 28,
            width: 110,
            text: 'Save',
            listeners: {
                click: 'onCmdSaveClick'
            }
        },
        {
            xtype: 'button',
            x: 765,
            y: 490,
            height: 35,
            itemId: 'cmdClose',
            width: 110,
            text: 'Close',
            listeners: {
                click: 'onCmdCloseClick'
            }
        }
    ],

            onCmdSaveClick: function (button, e, eOpts) {
                var form = button.up('panel').getForm(); // get the basic form
                if (form.isValid()) { // make sure the form contains valid data before submitting
                    form.submit({
                        success: function (form, action) {
                            Ext.Msg.alert('Thank you!', 'The Encounter has been saved Successfully.');
                            button.up('form').getForm().reset();
                            button.up('window').hide();

                        },
                        failure: function (form, action) {
                            var jsonResp = Ext.decode(action.response.responseText);

                            Ext.Msg.alert('Failed', 'Could not save Encounter. \n Error=' + jsonResp.error);
                        }
                    });
                } else { // display error alert if the data is invalid
                    Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
                }
            },

            onCmdCloseClick: function (button, e, eOpts) {
                var form = button.up('form').getForm();
                if (form.isValid()) {
                    this.up('window').hide();
                }
            }
        });

        var encounterForm = new Ext.form.Panel({
            //renderTo: 'container',
            height: 555,
            width: 944,
            layout: 'absolute',
            bodyPadding: 3,
            url: 'data/getDatafunctions.php?task=saveinitial',
            defaultListenerScope: true,
            items: [
                {
                    xtype: 'fieldset',
                    x: 5,
                    y: 55,
                    height: 265,
                    padding: '0 0 0 0',
                    layout: 'absolute',
                    title: '',
                    items: [
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: -1,
                            width: 515,
                            fieldLabel: '2a. Do you smoke cigarettes',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 5,
                                    name: 'Smoking',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 6,
                                    name: 'Smoking',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 7,
                                    name: 'Smoking',
                                    boxLabel: 'Stopped',
                                    inputValue: 'Stopped'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 25,
                            width: 515,
                            fieldLabel: '2b. Do you sometimes drink alcohol',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 8,
                                    name: 'Drinking',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 9,
                                    name: 'Drinking',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 10,
                                    name: 'Drinking',
                                    boxLabel: 'Stopped',
                                    inputValue: 'Stopped'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 50,
                            width: 440,
                            fieldLabel: '2c. Family history of cardiovascular disease?',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 11,
                                    name: 'Cardiovascular',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 12,
                                    name: 'Cardiovascular',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 75,
                            width: 440,
                            fieldLabel: '2d. Do you have diabetes?',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 13,
                                    name: 'Diabetes',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 14,
                                    name: 'Diabetes',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 100,
                            height: 35,
                            width: 440,
                            fieldLabel: '2e. Do you eat Healthy diet (low salt/fats, high vegetable/fruit at least 5 times/day)',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 13,
                                    name: 'Diet',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 14,
                                    name: 'Diet',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 135,
                            height: 35,
                            width: 440,
                            fieldLabel: '2f. Adequate physical activity ( 3 times a week at least 30 min)',
                            labelWidth: 270,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 13,
                                    name: 'Physical',
                                    boxLabel: 'Yes',
                                    inputValue: 'Yes'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 14,
                                    name: 'Physical',
                                    boxLabel: 'No',
                                    inputValue: 'No'
                                }
                            ]
                        },
                        {
                            xtype: 'radiogroup',
                            x: 10,
                            y: 175,
                            height: 35,
                            width: 545,
                            fieldLabel: 'Hypertension',
                            labelWidth: 270,
                            allowBlank: true,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 13,
                                    labelWidth: 130,
                                    name: 'HtnStatus',
                                    boxLabel: 'New HTN patient',
                                    inputValue: 'New'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 14,
                                    name: 'HtnStatus',
                                    boxLabel: 'Known HTN patient',
                                    inputValue: 'Known'
                                }
                            ]
                        },
                        {
                            xtype: 'textfield',
                            x: 10,
                            y: 205,
                            tabIndex: 15,
                            width: 745,
                            fieldLabel: 'Observations',
                            labelWidth: 120,
                            name: 'Observations'
                        },
                        {
                            xtype: 'datefield',
                            x: 10,
                            y: 235,
                            tabIndex: 16,
                            fieldLabel: 'LMP if Applicable',
                            labelWidth: 120,
                            name: 'LMPfemale'
                        },
                        {
                            xtype: 'checkboxfield',
                            x: 305,
                            y: 235,
                            tabIndex: 17,
                            name: 'NotApplicable',
                            boxLabel: 'Not Applicable'
                        },
                        {
                            xtype: 'textfield',
                            x: 570,
                            y: 5,
                            id: 'Height',
                            itemId: 'Height',
                            width: 180,
                            fieldLabel: 'Height',
                            labelAlign: 'right',
                            name: 'Height',
                            emptyText: 'Height'
                        },
                        {
                            xtype: 'textfield',
                            x: 570,
                            y: 30,
                            id: 'Weight',
                            itemId: 'Weight',
                            width: 180,
                            fieldLabel: 'Weight',
                            labelAlign: 'right',
                            name: 'Weight',
                            emptyText: 'Weight'
                        },
                        {
                            xtype: 'textfield',
                            x: 570,
                            y: 55,
                            id: 'BMI',
                            itemId: 'BMI',
                            width: 180,
                            fieldLabel: 'BMI',
                            labelAlign: 'right',
                            name: 'BMI',
                            emptyText: 'BMI'
                        },
                        {
                            xtype: 'datefield',
                            x: 560,
                            y: 175,
                            fieldLabel: 'Date Diagnosed',
                            name: 'DateDiagnosed',
                            allowBlank: true
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    x: 5,
                    y: 325,
                    height: 50,
                    padding: '0 0 0 0',
                    layout: 'absolute',
                    items: [
                        {
                            xtype: 'radiogroup',
                            x: 30,
                            y: 15,
                            height: 20,
                            width: 340,
                            fieldLabel: 'Any known allergies to drugs?',
                            labelWidth: 200,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 18,
                                    name: 'DrugAllergies',
                                    boxLabel: 'Yes',
                                    inputValue: 'Y'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 19,
                                    name: 'DrugAllergies',
                                    boxLabel: 'No',
                                    inputValue: 'N'
                                }
                            ]
                        },
                        {
                            xtype: 'textfield',
                            x: 440,
                            y: 10,
                            tabIndex: 20,
                            width: 335,
                            fieldLabel: 'Specify',
                            name: 'AllergiesSpecify'
                        },
                        {
                            xtype: 'label',
                            x: 5,
                            y: 0,
                            text: 'Contra indications'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    x: 5,
                    y: 5,
                    height: 50,
                    padding: '0 0 0 0',
                    layout: 'absolute',
                    title: '',
                    items: [
                        {
                            xtype: 'textfield',
                            x: 10,
                            y: 5,
                            id: 'BPFirstReading1',
                            itemId: 'BPFirstReading1',
                            tabIndex: 1,
                            width: 150,
                            fieldLabel: 'BP First Reading',
                            name: 'BPFirstReading1',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            x: 230,
                            y: 5,
                            id: 'BPSecondReading1',
                            itemId: 'BPSecondReading1',
                            tabIndex: 3,
                            width: 160,
                            fieldLabel: 'BP Second Reading',
                            labelWidth: 110,
                            name: 'BPSecondReading1',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            x: 160,
                            y: 5,
                            id: 'BPFirstReading2',
                            itemId: 'BPFirstReading2',
                            tabIndex: 2,
                            width: 45,
                            name: 'BPFirstReading2',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            x: 390,
                            y: 5,
                            id: 'BPSecondReading2',
                            itemId: 'BPSecondReading2',
                            tabIndex: 4,
                            width: 50,
                            name: 'BPSecondReading2',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            x: 450,
                            y: 5,
                            id: 'pid',
                            itemId: 'pid',
                            tabIndex: 4,
                            width: 225,
                            fieldLabel: 'Pid',
                            labelAlign: 'right',
                            name: 'pid',
                            allowBlank: false,
                            emptyText: 'PID'
                        },
                        {
                            xtype: 'textfield',
                            x: 710,
                            y: 5,
                            id: 'encounterNr',
                            itemId: 'encounterNr',
                            tabIndex: 4,
                            width: 185,
                            fieldLabel: 'Encounter No',
                            labelWidth: 80,
                            name: 'encounterNr',
                            allowBlank: false,
                            emptyText: 'EncounterNr'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    x: 5,
                    y: 380,
                    height: 50,
                    padding: '0 0 0 0',
                    layout: 'absolute',
                    title: '',
                    items: [
                        {
                            xtype: 'radiogroup',
                            x: 20,
                            y: 15,
                            height: 20,
                            width: 405,
                            fieldLabel: 'Adhering to current medications',
                            labelWidth: 210,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 21,
                                    name: 'AdheringMedication',
                                    boxLabel: 'N/A',
                                    inputValue: 'NA'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 22,
                                    name: 'AdheringMedication',
                                    boxLabel: 'Yes',
                                    inputValue: 'Y'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 23,
                                    name: 'AdheringMedication',
                                    boxLabel: 'No',
                                    inputValue: 'N'
                                }
                            ]
                        },
                        {
                            xtype: 'textfield',
                            x: 440,
                            y: 15,
                            tabIndex: 24,
                            width: 335,
                            fieldLabel: 'Specify Reason',
                            name: 'AdheringSpecify'
                        },
                        {
                            xtype: 'label',
                            x: 0,
                            y: -1,
                            text: 'Current Treatment'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    x: 5,
                    y: 435,
                    height: 45,
                    padding: '0 0 0 0',
                    layout: 'absolute',
                    title: '',
                    items: [
                        {
                            xtype: 'radiogroup',
                            x: 15,
                            y: 15,
                            height: 30,
                            width: 445,
                            fieldLabel: '',
                            labelWidth: 200,
                            allowBlank: false,
                            items: [
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 25,
                                    name: 'FollowupPlan',
                                    boxLabel: 'Continue care at Facilty',
                                    inputValue: 'Continue'
                                },
                                {
                                    xtype: 'radiofield',
                                    tabIndex: 26,
                                    name: 'FollowupPlan',
                                    boxLabel: 'Refer to Another Facility',
                                    inputValue: 'Refer'
                                }
                            ]
                        },
                        {
                            xtype: 'datefield',
                            x: 440,
                            y: 10,
                            hidden: true,
                            tabIndex: 27,
                            width: 250,
                            fieldLabel: 'Return Date',
                            name: 'ReturnDate',
                            allowBlank: true
                        },
                        {
                            xtype: 'label',
                            x: 5,
                            y: 0,
                            text: 'Follow Up Plan'
                        }
                    ]
                },
                {
                    xtype: 'button',
                    x: 600,
                    y: 485,
                    height: 35,
                    itemId: 'cmdSave',
                    tabIndex: 28,
                    width: 110,
                    text: 'Save',
                    listeners: {
                        click: 'onCmdSaveClick'
                    }
                },
                {
                    xtype: 'button',
                    x: 730,
                    y: 485,
                    height: 35,
                    itemId: 'cmdClose',
                    width: 110,
                    text: 'Close',
                    listeners: {
                        click: 'onCmdCloseClick'
                    }
                }
            ],

            onCmdSaveClick: function (button, e, eOpts) {
                var form = button.up('panel').getForm(); // get the basic form
                if (form.isValid()) { // make sure the form contains valid data before submitting
                    form.submit({
                        success: function (form, action) {
                            Ext.Msg.alert('Thank you!', 'The Encounter has been saved Successfully.');
                            button.up('form').getForm().reset();
                            button.up('window').hide();
                        },
                        failure: function (form, action) {
                            var jsonResp = Ext.decode(action.response.responseText);

                            Ext.Msg.alert('Failed', 'Could not save Encounter. \n Error=' + jsonResp.error);
                        }
                    });
                } else { // display error alert if the data is invalid
                    Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
                }
            },
            onCmdCloseClick: function (button, e, eOpts) {
                var form = button.up('form').getForm();
                if (form.isValid()) {
                    this.up('window').hide();
                }
            }
        });




        if (!win) {
            win = new Ext.Window({
                applyTo: 'container',
                layout: 'fit',
                width: 960,
                height: 560,
                closable: false,
                closeAction: 'hide',
                scrollable: 'vertical',
                title: 'Hypertension: Initial Encounter',
                plain: true,
                items: [encounterForm]

            });
        }

        if (!win2) {
            win2 = new Ext.Window({
                applyTo: 'container',
                layout: 'fit',
                width: 960,
                height: 560,
                closable: false,
                closeAction: 'hide',
                scrollable: 'vertical',
                title: 'Hypertension: Continuation of Care',
                plain: true,
                items: [continuationForm]

            });
        }

        var bp2 = Ext.get('bp2');
        var bp1 = Ext.get('bp');
        var height = Ext.get('height');
        var weight = Ext.get('weight');
        var bmi = Ext.get('bmi');
        var pid = Ext.get('pid');
        var encounterNr = Ext.get('encounter_nr');
        var innitialMode = Ext.get('innitialMode');
		    var strHtn=Ext.get('htnKnown');

        checkIfInnitialExists(pid.getValue());
		
		    strHtn.on('change', function(){
			//Ext.Msg.alert("Test",strHtn.getValue());
			   if (innitialMode.getValue() > 0) {
                    win2.show(this);
                    // Ext.Msg.alert("test",bp1.getValue());
                    Ext.getCmp('BPFirstReadings1').setValue(bp1.getValue());
                    Ext.getCmp('BPFirstReadings2').setValue(bp2.getValue());
                    Ext.getCmp('Heights').setValue(height.getValue());
                    Ext.getCmp('Weights').setValue(weight.getValue());
                    Ext.getCmp('BMIs').setValue(bmi.getValue());
                    Ext.getCmp('pids').setValue(pid.getValue());
                    Ext.getCmp('encounterNrs').setValue(encounterNr.getValue());
                } else {
                    win.show(this);
                    Ext.getCmp('BPFirstReading1').setValue(bp1.getValue());
                    Ext.getCmp('BPFirstReading2').setValue(bp2.getValue());
                    Ext.getCmp('Height').setValue(height.getValue());
                    Ext.getCmp('Weight').setValue(weight.getValue());
                    Ext.getCmp('BMI').setValue(bmi.getValue());
                    Ext.getCmp('pid').setValue(pid.getValue());
                    Ext.getCmp('encounterNr').setValue(encounterNr.getValue());
                }
		});
		
        bp2.on('blur', function () {
//            Ext.Msg.alert('Test',innitialMode.getValue());

					
			if (bp1.getValue() >99 && bp1.getValue() <140  || bp2.getValue()> 49 && bp2.getValue()<90) {
				var strRadio=" Known HTN:  Yes:<input type='checkbox' name='htnKnown' value='Yes' id='htnKnown'>";
				//Ext.Msg.alert("test",bp1.getValue());
				document.getElementById("htnKnown").innerHTML=strRadio;
			}

            if (bp1.getValue() > 139 || bp2.getValue() > 89) {
                if (innitialMode.getValue() > 0) {
                    win2.show(this);
                    // Ext.Msg.alert("test",bp1.getValue());
                    Ext.getCmp('BPFirstReadings1').setValue(bp1.getValue());
                    Ext.getCmp('BPFirstReadings2').setValue(bp2.getValue());
                    Ext.getCmp('Heights').setValue(height.getValue());
                    Ext.getCmp('Weights').setValue(weight.getValue());
                    Ext.getCmp('BMIs').setValue(bmi.getValue());
                    Ext.getCmp('pids').setValue(pid.getValue());
                    Ext.getCmp('encounterNrs').setValue(encounterNr.getValue());
                } else {
                    win.show(this);
                    Ext.getCmp('BPFirstReading1').setValue(bp1.getValue());
                    Ext.getCmp('BPFirstReading2').setValue(bp2.getValue());
                    Ext.getCmp('Height').setValue(height.getValue());
                    Ext.getCmp('Weight').setValue(weight.getValue());
                    Ext.getCmp('BMI').setValue(bmi.getValue());
                    Ext.getCmp('pid').setValue(pid.getValue());
                    Ext.getCmp('encounterNr').setValue(encounterNr.getValue());
                }

            }
        });
		
		

    });