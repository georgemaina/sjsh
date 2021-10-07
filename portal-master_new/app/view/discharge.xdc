{
    "xdsVersion": "4.2.4",
    "frameworkVersion": "ext62",
    "internals": {
        "type": "Ext.form.Panel",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "bodyPadding": 10,
            "designer|snapToGrid": 5,
            "designer|uiInterfaceName": "default-framed",
            "designer|userAlias": "discharge",
            "designer|userClassName": "Discharge",
            "frame": true,
            "height": 425,
            "layout": "absolute",
            "url": "../data/getDataFunctions.php?task=dischargePatients",
            "width": 672
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "designer|snapToGrid": "number",
            "designer|uiInterfaceName": "string",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "frame": "boolean",
            "height": "auto",
            "layout": "string",
            "width": "auto",
            "url": "string"
        },
        "name": "MyForm1",
        "cn": [
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "height": 175,
                    "layout|x": 5,
                    "layout|y": 80,
                    "padding": "0 0 0 0",
                    "title": "Discharge Types",
                    "width": 444
                },
                "configAlternates": {
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "padding": "auto",
                    "title": "string",
                    "width": "auto"
                },
                "name": "MyFieldSet8",
                "cn": [
                    {
                        "type": "Ext.form.RadioGroup",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "container|align": "stretch",
                            "layout": "vbox",
                            "width": 319
                        },
                        "configAlternates": {
                            "container|align": "string",
                            "layout": "string",
                            "width": "auto"
                        },
                        "name": "MyRadioGroup1",
                        "cn": [
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Regular Discharge",
                                    "fieldLabel": null,
                                    "inputValue": "1",
                                    "layout|flex": 1,
                                    "name": "dischargeType"
                                },
                                "configAlternates": {
                                    "boxLabel": "string",
                                    "fieldLabel": "string",
                                    "inputValue": "string",
                                    "layout|flex": "number",
                                    "name": "string"
                                },
                                "name": "MyRadio4"
                            },
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Medical Discharge",
                                    "fieldLabel": null,
                                    "inputValue": "9",
                                    "name": "dischargeType"
                                },
                                "configAlternates": {
                                    "boxLabel": "string",
                                    "fieldLabel": "string",
                                    "inputValue": "string",
                                    "name": "string"
                                },
                                "name": "MyRadio3"
                            },
                            {
                                "type": "Ext.form.field.Radio",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "boxLabel": "Death of Patient",
                                    "fieldLabel": null,
                                    "inputValue": "7",
                                    "name": "dischargeType"
                                },
                                "configAlternates": {
                                    "boxLabel": "string",
                                    "fieldLabel": "string",
                                    "inputValue": "string",
                                    "name": "string"
                                },
                                "name": "MyRadio5"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.form.field.TextArea",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "emptyText": "Discharge Summary Notes",
                    "frame": true,
                    "height": 110,
                    "layout|x": 5,
                    "layout|y": 260,
                    "name": "dischargeSummary",
                    "shadow": "frame",
                    "shadowOffset": 1,
                    "width": 445
                },
                "configAlternates": {
                    "frame": "boolean",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "emptyText": "string",
                    "name": "string",
                    "shadow": "auto",
                    "shadowOffset": "number"
                },
                "name": "MyTextArea2"
            },
            {
                "type": "Ext.form.field.Time",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Discharge Time",
                    "format": "H:i:s",
                    "itemId": "dischargeTime",
                    "layout|x": 10,
                    "layout|y": 45,
                    "name": "dischargeTime",
                    "width": 225
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "name": "string",
                    "format": "string"
                },
                "name": "MyTimeField"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "height": 35,
                    "itemId": "saveDischarge",
                    "layout|x": 5,
                    "layout|y": 380,
                    "text": "Discharge",
                    "value": "cmdDischarge",
                    "width": 95
                },
                "configAlternates": {
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string",
                    "value": "auto",
                    "width": "auto",
                    "itemId": "string"
                },
                "name": "MyButton22"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "height": 35,
                    "layout|x": 110,
                    "layout|y": 380,
                    "text": "Print Summary",
                    "value": "cmdDischarge",
                    "width": 135
                },
                "configAlternates": {
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string",
                    "value": "auto",
                    "width": "auto"
                },
                "name": "MyButton24"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "height": 35,
                    "itemId": "cmdClose",
                    "layout|x": 295,
                    "layout|y": 380,
                    "text": "Cancel",
                    "value": "cmdClose",
                    "width": 150
                },
                "configAlternates": {
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string",
                    "width": "auto",
                    "itemId": "string",
                    "value": "auto"
                },
                "name": "MyButton23"
            },
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "dockedItems",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dock": "right",
                    "height": 421,
                    "layout": "form",
                    "margin": "0 0 0 0",
                    "style": "background:#386d87",
                    "width": 235
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "container|align": "string",
                    "dock": "string",
                    "layout": "string",
                    "style": "string",
                    "margin": "auto"
                },
                "name": "MyFieldSet7",
                "cn": [
                    {
                        "type": "Ext.form.field.Display",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "PID",
                            "fieldStyle": "color:#a7e88b; font-size:11px;font-weight-bold;",
                            "itemId": "pid",
                            "labelAlign": "top",
                            "labelStyle": "font-weight:bold; font-size:11px; color:#f4f6fc;",
                            "labelWidth": 30,
                            "margin": "0 0 0 0",
                            "padding": "0 0 0 0",
                            "width": 180
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "itemId": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "fieldStyle": "string",
                            "labelStyle": "string",
                            "labelAlign": "string",
                            "margin": "auto",
                            "padding": "auto"
                        },
                        "name": "MyDisplayField"
                    },
                    {
                        "type": "Ext.form.field.Display",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Names",
                            "fieldStyle": "color:#a7e88b; font-size:11px;font-weight-bold;",
                            "itemId": "names",
                            "labelAlign": "top",
                            "labelPad": 0,
                            "labelStyle": "font-weight:bold; font-size:11px; color:#f4f6fc;",
                            "labelWidth": 60,
                            "padding": "0 0 0 0",
                            "width": 180
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "value": "string",
                            "width": "auto",
                            "itemId": "string",
                            "fieldStyle": "string",
                            "labelPad": "number",
                            "labelStyle": "string",
                            "labelAlign": "string",
                            "padding": "auto"
                        },
                        "name": "MyDisplayField1"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Encounter No",
                            "fieldStyle": "color:#a7e88b; font-size:11px;font-weight-bold;",
                            "itemId": "encounterNo",
                            "labelAlign": "top",
                            "labelStyle": "font-weight:bold; font-size:11px; color:#f4f6fc;",
                            "name": "encounter_nr",
                            "readOnly": true,
                            "width": 150
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "value": "string",
                            "width": "auto",
                            "itemId": "string",
                            "fieldStyle": "string",
                            "labelStyle": "string",
                            "hidden": "boolean",
                            "name": "string",
                            "readOnly": "boolean",
                            "labelAlign": "string"
                        },
                        "name": "MyTextField43"
                    },
                    {
                        "type": "Ext.form.field.Display",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Date of Birth",
                            "fieldStyle": "color:#a7e88b; font-size:11px;font-weight-bold;",
                            "frame": false,
                            "height": 150,
                            "itemId": "Dob",
                            "labelAlign": "top",
                            "labelStyle": "font-weight:bold; font-size:11px; color:#f4f6fc;",
                            "width": 180
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "value": "string",
                            "width": "auto",
                            "itemId": "string",
                            "fieldStyle": "string",
                            "labelStyle": "string",
                            "frame": "boolean",
                            "height": "auto",
                            "labelAlign": "string"
                        },
                        "name": "MyDisplayField2"
                    },
                    {
                        "type": "Ext.toolbar.Spacer",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "name": "MySpacer"
                    }
                ]
            },
            {
                "type": "Ext.form.field.Date",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Discharge Date",
                    "itemId": "dischargeDate",
                    "layout|x": 10,
                    "layout|y": 10,
                    "name": "dischargeDate"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string"
                },
                "name": "MyDateField"
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {},
    "viewController": {
        "xdsVersion": "4.2.4",
        "frameworkVersion": "ext66",
        "internals": {
            "type": "Ext.app.ViewController",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "discharge",
                "designer|userClassName": "DischargeViewController"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            }
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    },
    "viewModel": {
        "xdsVersion": "4.2.4",
        "frameworkVersion": "ext66",
        "internals": {
            "type": "Ext.app.ViewModel",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "discharge",
                "designer|userClassName": "DischargeViewModel"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            }
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}