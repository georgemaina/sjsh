{
    "xdsVersion": "3.2.0",
    "frameworkVersion": "ext51",
    "internals": {
        "type": "Ext.panel.Panel",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "designer|uiInterfaceName": "default-framed",
            "designer|userAlias": "sharedpanel",
            "designer|userClassName": "SharedPanel",
            "frame": true,
            "height": 800,
            "scrollable": false,
            "width": null
        },
        "name": "MyPanel",
        "configAlternates": {
            "scrollable": "boolean"
        },
        "cn": [
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|snapToGrid": 5,
                    "height": 59,
                    "layout": "absolute",
                    "title": "<b>3. Contra Indications</b>"
                },
                "name": "MyFieldSet1",
                "cn": [
                    {
                        "type": "Ext.form.RadioGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "3a. Any known allergies to drugs",
                            "height": 30,
                            "labelWidth": 200,
                            "layout|x": 5,
                            "layout|y": 5,
                            "width": 355
                        },
                        "name": "MyRadioGroup6",
                        "cn": [
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Yes",
                                    "fieldLabel": null,
                                    "inputValue": "Yes",
                                    "name": "DrugAllergies"
                                },
                                "name": "MyRadio7"
                            },
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "No",
                                    "fieldLabel": null,
                                    "inputValue": "No",
                                    "name": "DrugAllergies"
                                },
                                "name": "MyRadio8"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "If yes Specify",
                            "labelWidth": 80,
                            "layout|x": 390,
                            "layout|y": 5,
                            "name": "AllergyDetails",
                            "width": 395
                        },
                        "name": "MyTextField21"
                    }
                ]
            },
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|snapToGrid": 5,
                    "height": 66,
                    "layout": "absolute",
                    "title": "<b>4. Current Treatment</b>"
                },
                "name": "MyFieldSet2",
                "cn": [
                    {
                        "type": "Ext.form.RadioGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "4a. Adhering to current medications?",
                            "height": 30,
                            "labelWidth": 210,
                            "layout|x": 5,
                            "layout|y": 5,
                            "width": 400
                        },
                        "name": "MyRadioGroup6",
                        "cn": [
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "N/A",
                                    "fieldLabel": null,
                                    "inputValue": "N/A",
                                    "name": "AdheringMedications"
                                },
                                "name": "MyRadio7"
                            },
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Yes",
                                    "fieldLabel": null,
                                    "inputValue": "Yes",
                                    "name": "AdheringMedications"
                                },
                                "name": "MyRadio9"
                            },
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "No",
                                    "fieldLabel": null,
                                    "inputValue": "No",
                                    "name": "AdheringMedications"
                                },
                                "name": "MyRadio8"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Specify reason",
                            "labelWidth": 90,
                            "layout|x": 390,
                            "layout|y": 5,
                            "name": "AdheringMedicationsDetails",
                            "width": 385
                        },
                        "name": "MyTextField21"
                    }
                ]
            },
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|snapToGrid": 5,
                    "height": 128,
                    "layout": "absolute",
                    "title": "<b>5a.Prescribed Treatment - Protocol</b>"
                },
                "name": "MyFieldSet3",
                "cn": [
                    {
                        "type": "Ext.form.FieldSet",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|snapToGrid": 5,
                            "height": 100,
                            "layout": "absolute",
                            "layout|x": 0,
                            "layout|y": 5,
                            "title": "",
                            "width": 470
                        },
                        "name": "MyFieldSet4",
                        "cn": [
                            {
                                "type": "Ext.form.Label",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "layout|x": 155,
                                    "layout|y": 5,
                                    "text": "Mild Hypertension"
                                },
                                "name": "MyLabel7"
                            },
                            {
                                "type": "Ext.form.Label",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "layout|x": 80,
                                    "layout|y": 30,
                                    "text": "SBP 140-159 and/or DBP 90-99 mmHg"
                                },
                                "name": "MyLabel8"
                            },
                            {
                                "type": "Ext.form.CheckboxGroup",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": null,
                                    "layout|x": 10,
                                    "layout|y": 60,
                                    "width": 415
                                },
                                "name": "MyCheckboxGroup",
                                "cn": [
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Lifestyle",
                                            "fieldLabel": null,
                                            "inputValue": "Lifestyle",
                                            "name": "LifestyleMild"
                                        },
                                        "name": "MyCheckbox"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "CCBs",
                                            "fieldLabel": null,
                                            "inputValue": "ccbs",
                                            "name": "CCBsMild"
                                        },
                                        "name": "MyCheckbox1"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Diuretic",
                                            "fieldLabel": null,
                                            "inputValue": "diuretic",
                                            "name": "DiureticMild"
                                        },
                                        "name": "MyCheckbox2"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Others",
                                            "fieldLabel": null,
                                            "inputValue": "others",
                                            "name": "OthersMild"
                                        },
                                        "name": "MyCheckbox3"
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.FieldSet",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|snapToGrid": 5,
                            "height": 100,
                            "layout": "absolute",
                            "layout|x": 480,
                            "layout|y": 5,
                            "title": "",
                            "width": 470
                        },
                        "name": "MyFieldSet5",
                        "cn": [
                            {
                                "type": "Ext.form.Label",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "layout|x": 155,
                                    "layout|y": 5,
                                    "text": "Moderate to Severe Hypertension:"
                                },
                                "name": "MyLabel7"
                            },
                            {
                                "type": "Ext.form.Label",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "layout|x": 80,
                                    "layout|y": 30,
                                    "text": "SBP >=160 and/or DBP>=100 mmHg"
                                },
                                "name": "MyLabel8"
                            },
                            {
                                "type": "Ext.form.CheckboxGroup",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": null,
                                    "layout|x": 10,
                                    "layout|y": 60,
                                    "width": 415
                                },
                                "name": "MyCheckboxGroup",
                                "cn": [
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Lifestyle",
                                            "fieldLabel": null,
                                            "name": "LifestyleModerate"
                                        },
                                        "name": "MyCheckbox"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "CCBs",
                                            "fieldLabel": null,
                                            "name": "CCBsModerate"
                                        },
                                        "name": "MyCheckbox1"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Diuretic",
                                            "fieldLabel": null,
                                            "name": "DiureticModerate"
                                        },
                                        "name": "MyCheckbox2"
                                    },
                                    {
                                        "type": "Ext.form.field.Checkbox",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "boxLabel": "Others",
                                            "fieldLabel": null,
                                            "name": "OthersModerate"
                                        },
                                        "name": "MyCheckbox3"
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|snapToGrid": 5,
                    "height": 520,
                    "layout": "absolute",
                    "scrollable": false,
                    "style": null,
                    "title": "<b>5b. Prescribed Treatment - Formulary(drug names to be inserted per institution formulary)</b>",
                    "width": null
                },
                "name": "MyFieldSet6",
                "configAlternates": {
                    "style": "string",
                    "scrollable": "boolean"
                },
                "cn": [
                    {
                        "type": "Ext.form.Label",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 10,
                            "style": "font-weight:bold;",
                            "text": "CCBs: (insert drug names and dosage)"
                        },
                        "name": "MyLabel10",
                        "configAlternates": {
                            "style": "string"
                        }
                    },
                    {
                        "type": "Ext.form.Label",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 95,
                            "style": "font-weight:bold;",
                            "text": "Diuretics"
                        },
                        "name": "MyLabel11",
                        "configAlternates": {
                            "style": "string"
                        }
                    },
                    {
                        "type": "Ext.form.Label",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 145,
                            "style": "font-weight:bold;",
                            "text": "ACE/Diuretic Combination: Zestoretic (hydrochlorothiazide and lisinopril)"
                        },
                        "name": "MyLabel12",
                        "configAlternates": {
                            "style": "string"
                        }
                    },
                    {
                        "type": "Ext.form.Label",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 200,
                            "style": "font-weight:bold;",
                            "text": "Other Medicines"
                        },
                        "name": "MyLabel13",
                        "configAlternates": {
                            "style": "string"
                        }
                    },
                    {
                        "type": "Ext.form.CheckboxGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Plendil (felodipine)",
                            "labelWidth": 180,
                            "layout|x": 5,
                            "layout|y": 35,
                            "width": 330
                        },
                        "name": "MyCheckboxGroup1",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "5mg",
                                    "fieldLabel": null,
                                    "inputValue": "Plendil5mg",
                                    "name": "Plendil5mg"
                                },
                                "name": "MyCheckbox4"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "10mg",
                                    "fieldLabel": null,
                                    "inputValue": "Plendil10mg",
                                    "name": "Plendil10mg"
                                },
                                "name": "MyCheckbox5"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.CheckboxGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Hydrochlorothiazide",
                            "labelWidth": 180,
                            "layout|x": 5,
                            "layout|y": 110,
                            "width": 335
                        },
                        "name": "MyCheckboxGroup3",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "5mg",
                                    "fieldLabel": null,
                                    "name": "Hydrochlorothiazide5mg"
                                },
                                "name": "MyCheckbox4"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "10mg",
                                    "fieldLabel": null,
                                    "inputValue": "Hydrochlorothiazide10mg",
                                    "name": "Hydrochlorothiazide10mg"
                                },
                                "name": "MyCheckbox5"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.CheckboxGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Zestoretic (hydrochlorothiazide and lisinopril",
                            "labelWidth": 180,
                            "layout|x": 5,
                            "layout|y": 155,
                            "width": 330
                        },
                        "name": "MyCheckboxGroup4",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "12.5mg/20mg",
                                    "fieldLabel": null,
                                    "inputValue": "Zestoretic",
                                    "name": "Zestoretic"
                                },
                                "name": "MyCheckbox4"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.CheckboxGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": null,
                            "layout|x": 360,
                            "layout|y": 35,
                            "width": 290
                        },
                        "name": "MyCheckboxGroup2",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Start",
                                    "fieldLabel": null,
                                    "inputValue": "PlendilStart",
                                    "name": "PlendilStart"
                                },
                                "name": "MyCheckbox6"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "CT",
                                    "fieldLabel": null,
                                    "inputValue": "PlendilCT",
                                    "name": "PlendilCT"
                                },
                                "name": "MyCheckbox7"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Titration",
                                    "fieldLabel": null,
                                    "inputValue": "PlendilTitration",
                                    "name": "PlendilTitration"
                                },
                                "name": "MyCheckbox8"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Stop",
                                    "fieldLabel": null,
                                    "inputValue": "PlendilStop",
                                    "name": "PlendilStop"
                                },
                                "name": "MyCheckbox9"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.CheckboxGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": null,
                            "layout|x": 355,
                            "layout|y": 160,
                            "width": 225
                        },
                        "name": "MyCheckboxGroup5",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Start",
                                    "fieldLabel": null,
                                    "inputValue": "ZestoreticStart",
                                    "name": "ZestoreticStart"
                                },
                                "name": "MyCheckbox6"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "CT",
                                    "fieldLabel": null,
                                    "inputValue": "ZestoreticCT",
                                    "name": "ZestoreticCt"
                                },
                                "name": "MyCheckbox7"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Stop",
                                    "fieldLabel": null,
                                    "inputValue": "ZestoreticStop",
                                    "name": "ZestoreticStop"
                                },
                                "name": "MyCheckbox9"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.field.ComboBox",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Insert frequency (OD/BD)",
                            "labelWidth": 140,
                            "layout|x": 670,
                            "layout|y": 35,
                            "name": "PlendilFrequency",
                            "width": 255
                        },
                        "name": "MyComboBox"
                    },
                    {
                        "type": "Ext.form.field.ComboBox",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Insert frequency (OD/BD)",
                            "labelWidth": 140,
                            "layout|x": 670,
                            "layout|y": 65,
                            "name": "OtherCcbsFrequency",
                            "width": 255
                        },
                        "name": "MyComboBox2"
                    },
                    {
                        "type": "Ext.form.field.ComboBox",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Insert frequency (OD/BD)",
                            "labelWidth": 140,
                            "layout|x": 670,
                            "layout|y": 110,
                            "name": "OtherCcbsFrequency",
                            "width": 255
                        },
                        "name": "MyComboBox5"
                    },
                    {
                        "type": "Ext.form.field.ComboBox",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Insert frequency (OD/BD)",
                            "labelWidth": 140,
                            "layout|x": 670,
                            "layout|y": 160,
                            "name": "ZestoreticFrequency",
                            "width": 255
                        },
                        "name": "MyComboBox1"
                    },
                    {
                        "type": "Ext.form.field.ComboBox",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Insert frequency (OD/BD)",
                            "labelWidth": 140,
                            "layout|x": 670,
                            "layout|y": 220,
                            "width": 255
                        },
                        "name": "MyComboBox3"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Other?",
                            "labelWidth": 50,
                            "layout|x": 5,
                            "layout|y": 60,
                            "name": "OtherCcbs5m"
                        },
                        "name": "MyTextField22"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "",
                            "labelWidth": 50,
                            "layout|x": 5,
                            "layout|y": 220
                        },
                        "name": "MyTextField23"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "",
                            "labelWidth": 50,
                            "layout|x": 200,
                            "layout|y": 220
                        },
                        "name": "MyTextField24"
                    },
                    {
                        "type": "Ext.grid.Panel",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 250,
                            "title": "Prescribed Medicines"
                        },
                        "name": "MyGridPanel",
                        "cn": [
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "string",
                                    "text": "String"
                                },
                                "name": "MyColumn"
                            },
                            {
                                "type": "Ext.grid.column.Number",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "number",
                                    "text": "Number"
                                },
                                "name": "MyNumberColumn"
                            },
                            {
                                "type": "Ext.grid.column.Date",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "date",
                                    "text": "Date"
                                },
                                "name": "MyDateColumn"
                            },
                            {
                                "type": "Ext.grid.column.Boolean",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "bool",
                                    "text": "Boolean"
                                },
                                "name": "MyBooleanColumn"
                            },
                            {
                                "type": "Ext.view.Table",
                                "reference": {
                                    "name": "viewConfig",
                                    "type": "object"
                                },
                                "codeClass": null,
                                "name": "MyTable"
                            }
                        ]
                    },
                    {
                        "type": "Ext.form.FieldSet",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|snapToGrid": 5,
                            "height": 85,
                            "layout": "absolute",
                            "layout|y": 400,
                            "title": "<b>6 Followup plan</b>"
                        },
                        "name": "MyFieldSet7",
                        "cn": [
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Continue care at facility",
                                    "fieldLabel": null,
                                    "inputValue": "ContinueAtFacility",
                                    "layout|x": 55,
                                    "layout|y": 5,
                                    "name": "ContinueFacility"
                                },
                                "name": "MyCheckbox10"
                            },
                            {
                                "type": "Ext.form.field.Checkbox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Refer to another facility",
                                    "fieldLabel": null,
                                    "inputValue": "ReferAnotherFacility",
                                    "layout|x": 55,
                                    "layout|y": 35,
                                    "name": "ReferFacility"
                                },
                                "name": "MyCheckbox11"
                            },
                            {
                                "type": "Ext.form.field.Date",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Return Date",
                                    "layout|x": 300,
                                    "layout|y": 5,
                                    "name": "ReturnDate"
                                },
                                "name": "MyDateField"
                            }
                        ]
                    }
                ]
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {},
    "viewController": {
        "xdsVersion": "3.2.0",
        "frameworkVersion": "ext51",
        "internals": {
            "type": "Ext.app.ViewController",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "sharedpanel",
                "designer|userClassName": "SharedPanelViewController"
            }
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    },
    "viewModel": {
        "xdsVersion": "3.2.0",
        "frameworkVersion": "ext51",
        "internals": {
            "type": "Ext.app.ViewModel",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "sharedpanel",
                "designer|userClassName": "SharedPanelViewModel"
            }
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}