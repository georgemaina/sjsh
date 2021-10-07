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
            "container|align": "stretch",
            "designer|userAlias": "vitals",
            "designer|userClassName": "Vitals",
            "height": 390,
            "layout": "vbox",
            "url": "../data/getDataFunctions.php?task=saveVitals",
            "width": 744
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "designer|initialView": "boolean",
            "designer|snapToGrid": "number",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "layout": "string",
            "width": "auto",
            "container|align": "string",
            "url": "string"
        },
        "name": "MyForm",
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
                    "height": 315,
                    "layout": "absolute",
                    "width": 744
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "designer|snapToGrid": "number",
                    "layout": "string"
                },
                "name": "MyFieldSet5",
                "cn": [
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Weight",
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 45,
                            "layout|y": 0,
                            "name": "weight"
                        },
                        "configAlternates": {
                            "emptyText": "string",
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "name": "string"
                        },
                        "name": "MyTextField6"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Height",
                            "labelAlign": "right",
                            "labelWidth": 120,
                            "layout|x": 5,
                            "layout|y": 35,
                            "name": "height"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField11"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Head Circumference",
                            "labelAlign": "right",
                            "labelWidth": 150,
                            "layout|x": -26,
                            "layout|y": 70,
                            "name": "head_c"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField12"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "emptyText": "Systoric",
                            "fieldLabel": "Blood Pressure",
                            "labelAlign": "right",
                            "layout|x": 25,
                            "layout|y": 105,
                            "name": "bp",
                            "width": 190
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string",
                            "width": "auto"
                        },
                        "name": "MyTextField13"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "emptyText": "Diastoric",
                            "labelAlign": "right",
                            "layout|x": 220,
                            "layout|y": 105,
                            "name": "bp2",
                            "width": 80
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField20"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Pulse rate",
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 45,
                            "layout|y": 140,
                            "name": "pulse"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField14"
                    },
                    {
                        "type": "Ext.form.field.TextArea",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Notes",
                            "height": 82,
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 45,
                            "layout|y": 175,
                            "name": "notes",
                            "width": 540
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string",
                            "height": "auto",
                            "width": "auto"
                        },
                        "name": "MyTextArea1"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Respiratory rate",
                            "labelAlign": "right",
                            "layout|x": 310,
                            "layout|y": 0,
                            "name": "resprate"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField15"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "Temperature",
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 330,
                            "layout|y": 35,
                            "name": "temperature"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField16"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "SPO2",
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 330,
                            "layout|y": 105,
                            "name": "spo2"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField18"
                    },
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldLabel": "BMI",
                            "labelAlign": "right",
                            "labelWidth": 80,
                            "layout|x": 330,
                            "layout|y": 70,
                            "name": "bmi"
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "labelAlign": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "emptyText": "string",
                            "name": "string"
                        },
                        "name": "MyTextField19"
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "height": 40,
                            "itemId": "SaveVitals",
                            "layout|x": 135,
                            "layout|y": 260,
                            "text": "Save",
                            "width": 140
                        },
                        "configAlternates": {
                            "height": "auto",
                            "itemId": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyButton2"
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "height": 40,
                            "itemId": "cmdClose",
                            "layout|x": 425,
                            "layout|y": 260,
                            "text": "Close",
                            "width": 140
                        },
                        "configAlternates": {
                            "height": "auto",
                            "layout|x": "number",
                            "layout|y": "number",
                            "text": "string",
                            "width": "auto",
                            "itemId": "string"
                        },
                        "name": "MyButton3"
                    }
                ]
            },
            {
                "type": "Ext.form.FieldSet",
                "reference": {
                    "name": "dockedItems",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "container|align": "stretch",
                    "dock": "top",
                    "height": 45,
                    "layout": "hbox",
                    "style": "background:#386d87",
                    "width": 744
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
                    "style": "string"
                },
                "name": "MyFieldSet9",
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
                            "fieldStyle": "color:#a7e88b;font-weight-bold;",
                            "itemId": "pid",
                            "labelStyle": "font-weight:bold; color:#f4f6fc;",
                            "labelWidth": 30,
                            "width": 125
                        },
                        "configAlternates": {
                            "fieldLabel": "string",
                            "itemId": "string",
                            "labelWidth": "number",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "fieldStyle": "string",
                            "labelStyle": "string"
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
                            "fieldStyle": "color:#a7e88b;font-weight-bold;",
                            "itemId": "names",
                            "labelPad": 0,
                            "labelStyle": "font-weight:bold; color:#f4f6fc;",
                            "labelWidth": 60,
                            "width": 271
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
                            "labelStyle": "string"
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
                            "fieldStyle": "",
                            "hidden": true,
                            "itemId": "encounterNo",
                            "labelStyle": "font-weight:bold; color:#f4f6fc;",
                            "name": "encounter_nr",
                            "readOnly": true,
                            "width": 211
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
                            "readOnly": "boolean"
                        },
                        "name": "MyTextField29"
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
                            "fieldStyle": "color:#a7e88b;font-weight-bold;",
                            "itemId": "Dob",
                            "labelStyle": "font-weight:bold; color:#f4f6fc;",
                            "width": 239
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
                            "labelStyle": "string"
                        },
                        "name": "MyDisplayField2"
                    }
                ]
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
                "designer|userAlias": "vitals",
                "designer|userClassName": "VitalsViewController"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "MyFormViewController"
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
                "designer|userAlias": "vitals",
                "designer|userClassName": "VitalsViewModel"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "MyFormViewModel"
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}