{
    "xdsVersion": "4.2.2",
    "frameworkVersion": "ext65",
    "internals": {
        "type": "Ext.form.FieldSet",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "designer|snapToGrid": 5,
            "designer|userAlias": "dosage",
            "designer|userClassName": "Dosage",
            "frame": true,
            "height": 110,
            "layout": "absolute",
            "padding": 0,
            "width": 789
        },
        "configAlternates": {
            "designer|snapToGrid": "number",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "frame": "boolean",
            "height": "auto",
            "layout": "string",
            "width": "auto",
            "padding": "auto"
        },
        "cn": [
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "PartCode",
                    "fieldStyle": "color:maroon; font-weight:bold;font-size:12px;",
                    "itemId": "partCode",
                    "labelStyle": "color:maroon; font-weight:bold;font-size:12px;",
                    "labelWidth": 60,
                    "layout|x": 10,
                    "layout|y": 0,
                    "margin": 0,
                    "name": "partCode",
                    "padding": 0,
                    "readOnly": true,
                    "width": 125
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "value": "string",
                    "width": "auto",
                    "fieldStyle": "string",
                    "itemId": "string",
                    "labelStyle": "string",
                    "margin": "auto",
                    "padding": "auto",
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "name": "string",
                    "readOnly": "boolean"
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
                    "fieldStyle": "color:maroon; font-weight:bold;font-size:12px;",
                    "itemId": "description",
                    "labelStyle": "",
                    "layout|x": 140,
                    "layout|y": 0,
                    "margin": 0,
                    "name": "description",
                    "padding": 0,
                    "value": [
                        "Description"
                    ],
                    "width": 450
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "value": "string",
                    "width": "auto",
                    "fieldStyle": "string",
                    "itemId": "string",
                    "labelStyle": "string",
                    "margin": "auto",
                    "padding": "auto",
                    "name": "string"
                },
                "name": "MyTextField12"
            },
            {
                "type": "Ext.form.field.Display",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Qty in Store:",
                    "fieldStyle": "color:red; font-weight:bold;font-size:14px;",
                    "itemId": "qty",
                    "labelStyle": "color:#3d74b3; font-weight:bold;font-size:14px;",
                    "labelWidth": 90,
                    "layout|x": 600,
                    "layout|y": -7,
                    "value": "Quantity",
                    "width": 305
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "value": "string",
                    "width": "auto",
                    "fieldStyle": "string",
                    "itemId": "string",
                    "fieldLabel": "string",
                    "labelStyle": "string",
                    "labelWidth": "number"
                },
                "name": "MyDisplayField5"
            },
            {
                "type": "Ext.form.field.Display",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Total Cost",
                    "fieldStyle": "color:red; font-weight:bold;font-size:14px;",
                    "itemId": "totalCost",
                    "labelStyle": "color:#3d74b3; font-weight:bold;font-size:14px;",
                    "labelWidth": 80,
                    "layout|x": 610,
                    "layout|y": 40,
                    "value": "Total Cost:",
                    "width": 305
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "value": "string",
                    "width": "auto",
                    "fieldStyle": "string",
                    "itemId": "string",
                    "fieldLabel": "string",
                    "labelStyle": "string",
                    "labelWidth": "number"
                },
                "name": "MyDisplayField6"
            },
            {
                "type": "Ext.form.field.Display",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Item Cost",
                    "fieldStyle": "color:red; font-weight:bold;font-size:14px;",
                    "itemId": "unitCost",
                    "labelStyle": "color:#3d74b3; font-weight:bold;font-size:14px;",
                    "labelWidth": 78,
                    "layout|x": 615,
                    "layout|y": 15,
                    "value": "Item Cost:",
                    "width": 305
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "value": "string",
                    "width": "auto",
                    "fieldStyle": "string",
                    "itemId": "string",
                    "fieldLabel": "string",
                    "labelStyle": "string",
                    "labelWidth": "number"
                },
                "name": "MyDisplayField8"
            },
            {
                "type": "Ext.form.field.Number",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "allowBlank": false,
                    "fieldLabel": "Dose",
                    "fieldStyle": "color:#3d74b3; font-weight:bold;font-size:12px;",
                    "itemId": "dose",
                    "labelAlign": "right",
                    "labelStyle": "color:green; font-weight:bold;font-size:12px;",
                    "labelWidth": 50,
                    "layout|x": 20,
                    "layout|y": 40,
                    "value": [
                        "1"
                    ],
                    "width": 120
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "labelAlign": "string",
                    "labelStyle": "string",
                    "allowBlank": "boolean",
                    "fieldStyle": "string",
                    "value": "object"
                },
                "name": "MyNumberField1"
            },
            {
                "type": "Ext.form.field.Number",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "allowBlank": false,
                    "fieldLabel": "Times Per Day",
                    "fieldStyle": "color:#3d74b3; font-weight:bold;font-size:12px;",
                    "itemId": "timesperday",
                    "labelStyle": "color:green; font-weight:bold;font-size:12px;",
                    "labelWidth": 90,
                    "layout|x": 145,
                    "layout|y": 40,
                    "maxLength": 6,
                    "maxValue": 6,
                    "minLength": 1,
                    "minValue": 1,
                    "width": 160
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "labelStyle": "string",
                    "allowBlank": "boolean",
                    "fieldStyle": "string",
                    "maxLength": "number",
                    "maxValue": "number",
                    "minLength": "number",
                    "minValue": "number"
                },
                "name": "MyNumberField2"
            },
            {
                "type": "Ext.form.field.Number",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "allowBlank": false,
                    "fieldLabel": "Days",
                    "fieldStyle": "color:#3d74b3; font-weight:bold;font-size:12px;",
                    "itemId": "days",
                    "labelStyle": "color:green; font-weight:bold;font-size:12px;",
                    "labelWidth": 50,
                    "layout|x": 310,
                    "layout|y": 40,
                    "maxLength": 60,
                    "maxValue": 60,
                    "minLength": 1,
                    "minValue": 1,
                    "width": 130
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "labelStyle": "string",
                    "allowBlank": "boolean",
                    "fieldStyle": "string",
                    "maxLength": "number",
                    "maxValue": "number",
                    "minLength": "number",
                    "minValue": "number"
                },
                "name": "MyNumberField3"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "allowBlank": false,
                    "fieldLabel": "Total Dose",
                    "fieldStyle": "color:#3d74b3; font-weight:bold;font-size:12px;",
                    "itemId": "total",
                    "labelStyle": "color:green; font-weight:bold;font-size:12px;",
                    "labelWidth": 70,
                    "layout|x": 450,
                    "layout|y": 40,
                    "readOnly": true,
                    "width": 140
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "labelStyle": "string",
                    "allowBlank": "boolean",
                    "fieldStyle": "string",
                    "readOnly": "boolean"
                },
                "name": "MyTextField9"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "allowBlank": false,
                    "fieldStyle": "color:#3d74b3; font-weight:bold;font-size:12px;",
                    "itemId": "itemNumber",
                    "labelStyle": "color:green; font-weight:bold;font-size:12px;",
                    "labelWidth": 70,
                    "layout|x": 600,
                    "layout|y": 75,
                    "name": "arr_item_number",
                    "readOnly": true,
                    "width": 55
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "labelWidth": "number",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "itemId": "string",
                    "labelStyle": "string",
                    "allowBlank": "boolean",
                    "fieldStyle": "string",
                    "readOnly": "boolean",
                    "name": "string"
                },
                "name": "MyTextField7"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fieldLabel": "Comment",
                    "itemId": "comment",
                    "labelAlign": "right",
                    "labelStyle": "color:blue; font-weight:bold;font-size:12px;",
                    "layout|x": -31,
                    "layout|y": 75,
                    "width": 620
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "labelAlign": "string",
                    "labelStyle": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto"
                },
                "name": "MyTextField4"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "iconCls": "x-fa fa-trash",
                    "itemId": "cmdRemoveDose",
                    "layout|x": 675,
                    "layout|y": 75,
                    "text": "Remove",
                    "width": 95
                },
                "configAlternates": {
                    "iconCls": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyButton8"
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {},
    "viewController": {
        "xdsVersion": "4.2.2",
        "frameworkVersion": "ext65",
        "internals": {
            "type": "Ext.app.ViewController",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "dosage",
                "designer|userClassName": "DosageViewController"
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
        "xdsVersion": "4.2.2",
        "frameworkVersion": "ext65",
        "internals": {
            "type": "Ext.app.ViewModel",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "dosage",
                "designer|userClassName": "DosageViewModel"
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