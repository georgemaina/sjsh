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
            "designer|uiInterfaceName": "default",
            "designer|userAlias": "nhifcredit",
            "designer|userClassName": "NhifCredit",
            "height": null,
            "itemId": "nhifcredit",
            "layout": "absolute",
            "title": "NHIF Credit",
            "width": null
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "itemId": "string",
            "layout": "string",
            "title": "string",
            "width": "auto",
            "url": "string",
            "designer|snapToGrid": "number",
            "designer|uiInterfaceName": "string"
        },
        "name": "MyForm",
        "cn": [
            {
                "type": "Ext.form.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "bodyBorder": true,
                    "bodyStyle": "background:#84b3cc",
                    "designer|snapToGrid": 5,
                    "designer|uiInterfaceName": "default",
                    "draggable": true,
                    "frameHeader": false,
                    "height": 397,
                    "layout": "absolute",
                    "layout|x": 145,
                    "layout|y": 10,
                    "title": "NHIF Credits",
                    "titleAlign": "center",
                    "titleCollapse": false,
                    "url": "data/getDatafunctions.php?caller=insertNhifCredit",
                    "width": 919
                },
                "configAlternates": {
                    "height": "auto",
                    "title": "string",
                    "designer|snapToGrid": "number",
                    "designer|uiInterfaceName": "string",
                    "draggable": "boolean",
                    "layout": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "width": "auto",
                    "bodyBorder": "boolean",
                    "bodyStyle": "string",
                    "frameHeader": "boolean",
                    "titleAlign": "string",
                    "titleCollapse": "boolean",
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
                            "frame": true,
                            "height": 155,
                            "layout": "absolute",
                            "layout|x": 5,
                            "layout|y": -1,
                            "padding": "0 0 0 0",
                            "title": null,
                            "width": 900
                        },
                        "configAlternates": {
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "title": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "frame": "boolean",
                            "padding": "auto"
                        },
                        "name": "MyFieldSet2",
                        "cn": [
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "Credit No",
                                    "itemId": "creditNo",
                                    "labelAlign": "right",
                                    "labelStyle": "",
                                    "layout|x": -4,
                                    "layout|y": 0,
                                    "name": "creditNo",
                                    "readOnly": true,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "labelStyle": "string",
                                    "name": "string",
                                    "readOnly": "boolean"
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
                                    "emptyText": "Ward No",
                                    "fieldLabel": "Ward No",
                                    "itemId": "ward",
                                    "labelAlign": "right",
                                    "layout|x": 530,
                                    "layout|y": -1,
                                    "name": "ward",
                                    "readOnly": true,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "emptyText": "string",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "readOnly": "boolean"
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
                                    "emptyText": "Encounter No",
                                    "fieldLabel": "Encounter No",
                                    "itemId": "encounterNr",
                                    "labelAlign": "right",
                                    "layout|x": 530,
                                    "layout|y": 35,
                                    "name": "encounterNr",
                                    "readOnly": true,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "emptyText": "string",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "readOnly": "boolean"
                                },
                                "name": "MyTextField23"
                            },
                            {
                                "type": "Ext.form.field.ComboBox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "displayField": "BillNumbers",
                                    "emptyText": "Bill Number",
                                    "fieldLabel": "Bill Number",
                                    "itemId": "billNumber",
                                    "labelAlign": "right",
                                    "layout|x": 250,
                                    "layout|y": 0,
                                    "name": "billNumber",
                                    "queryMode": "local",
                                    "readOnly": true,
                                    "store": "BillNumbersStore",
                                    "valueField": "BillNumbers",
                                    "width": 235
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "emptyText": "string",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "readOnly": "boolean",
                                    "allowBlank": "boolean",
                                    "displayField": "datafield",
                                    "queryMode": "string",
                                    "store": "store",
                                    "valueField": "datafield"
                                },
                                "name": "MyComboBox12"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": null,
                                    "itemId": "pname",
                                    "layout|x": 225,
                                    "layout|y": 35,
                                    "name": "pname",
                                    "width": 260
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "itemId": "string",
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
                                    "allowBlank": false,
                                    "fieldLabel": "Days",
                                    "itemId": "days",
                                    "labelAlign": "right",
                                    "layout|x": -3,
                                    "layout|y": 105,
                                    "name": "days",
                                    "readOnly": true,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string",
                                    "readOnly": "boolean"
                                },
                                "name": "MyTextField17"
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
                                    "fieldLabel": "Admission Date",
                                    "itemId": "admissionDate",
                                    "labelAlign": "right",
                                    "layout|x": -4,
                                    "layout|y": 70,
                                    "name": "admissionDate",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
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
                                    "allowBlank": false,
                                    "fieldLabel": "Release Date",
                                    "itemId": "releaseDate",
                                    "labelAlign": "right",
                                    "layout|x": 530,
                                    "layout|y": 105,
                                    "name": "releaseDate",
                                    "width": 295
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
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
                                    "allowBlank": false,
                                    "fieldLabel": "Discharge Date",
                                    "itemId": "dischargeDate",
                                    "labelAlign": "right",
                                    "layout|x": 530,
                                    "layout|y": 70,
                                    "name": "dischargeDate",
                                    "width": 295
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string"
                                },
                                "name": "MyTextField21"
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
                                    "fieldLabel": "Patient No",
                                    "itemId": "txtPid3",
                                    "labelAlign": "right",
                                    "layout|x": -3,
                                    "layout|y": 35,
                                    "name": "txtPid3",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string"
                                },
                                "name": "MyTextField19"
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
                            "frame": true,
                            "height": 110,
                            "layout": "absolute",
                            "layout|x": 5,
                            "layout|y": 140,
                            "padding": "0 0 0 0",
                            "title": null,
                            "width": 900
                        },
                        "configAlternates": {
                            "title": "string",
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "padding": "auto",
                            "width": "auto",
                            "frame": "boolean"
                        },
                        "name": "MyFieldSet3",
                        "cn": [
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "NHIF No",
                                    "itemId": "nhifNo",
                                    "labelAlign": "right",
                                    "layout|x": 0,
                                    "layout|y": 0,
                                    "name": "nhifNo",
                                    "width": 220
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto"
                                },
                                "name": "MyTextField25"
                            },
                            {
                                "type": "Ext.form.field.ComboBox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "displayField": "RateType",
                                    "fieldLabel": "NHIF Client Type",
                                    "itemId": "nhifClientType",
                                    "labelAlign": "right",
                                    "labelWidth": 120,
                                    "layout|x": 510,
                                    "layout|y": 5,
                                    "minChars": 2,
                                    "name": "nhifClientType",
                                    "queryMode": "local",
                                    "store": "NhifRateStore",
                                    "typeAhead": true,
                                    "valueField": "ID",
                                    "width": 270
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "labelWidth": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "displayField": "datafield",
                                    "itemId": "string",
                                    "minChars": "number",
                                    "name": "string",
                                    "queryMode": "string",
                                    "store": "store",
                                    "typeAhead": "boolean",
                                    "valueField": "datafield"
                                },
                                "name": "MyComboBox9"
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
                                    "fieldLabel": "NHIF Credit Per Day",
                                    "itemId": "creditPerDay",
                                    "labelAlign": "right",
                                    "labelWidth": 120,
                                    "layout|x": 510,
                                    "layout|y": 35,
                                    "name": "creditPerDay",
                                    "width": 270
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelWidth": "number",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "allowBlank": "boolean"
                                },
                                "name": "MyTextField41"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "itemId": "rateCalc",
                                    "labelAlign": "right",
                                    "labelWidth": 120,
                                    "layout|x": 780,
                                    "layout|y": 35,
                                    "name": "rateCalc",
                                    "width": 50
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelWidth": "number",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string"
                                },
                                "name": "MyTextField46"
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
                                    "fieldLabel": "Total credit",
                                    "itemId": "creditAmount",
                                    "labelAlign": "right",
                                    "labelWidth": 120,
                                    "layout|x": 510,
                                    "layout|y": 65,
                                    "name": "totalCredit",
                                    "width": 270
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelWidth": "number",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string"
                                },
                                "name": "MyTextField32"
                            },
                            {
                                "type": "Ext.form.field.ComboBox",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "NHIF Debtor Acc",
                                    "itemId": "accno",
                                    "labelAlign": "right",
                                    "layout|x": 0,
                                    "layout|y": 35,
                                    "name": "accno",
                                    "value": [
                                        "NHIF"
                                    ],
                                    "width": 205
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "value": "object"
                                },
                                "name": "MyComboBox11"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": null,
                                    "itemId": "name",
                                    "layout|x": 210,
                                    "layout|y": 35,
                                    "name": "nhifAccount",
                                    "value": [
                                        "NHIF DEBTORS ACCOUNT"
                                    ],
                                    "width": 285
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "itemId": "string",
                                    "name": "string",
                                    "value": "object"
                                },
                                "name": "MyTextField28"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "NHIF Category",
                                    "itemId": "nhifCat",
                                    "labelAlign": "right",
                                    "layout|x": -1,
                                    "layout|y": 70,
                                    "name": "nhifCat",
                                    "value": [
                                        "A"
                                    ],
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "labelAlign": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "value": "object",
                                    "width": "auto"
                                },
                                "name": "MyTextField27"
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
                            "frame": true,
                            "height": 106,
                            "layout": "absolute",
                            "layout|x": 5,
                            "layout|y": 250,
                            "padding": "0 0 0 0",
                            "title": null,
                            "width": 900
                        },
                        "configAlternates": {
                            "title": "string",
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "frame": "boolean",
                            "padding": "auto"
                        },
                        "name": "MyFieldSet4",
                        "cn": [
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "Incoice Amount",
                                    "itemId": "invoiceAmount",
                                    "labelAlign": "right",
                                    "layout|x": -2,
                                    "layout|y": 5,
                                    "name": "invoiceAmount",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto"
                                },
                                "name": "MyTextField33"
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
                                    "fieldLabel": "Balance",
                                    "itemId": "balance",
                                    "labelAlign": "right",
                                    "layout|x": 530,
                                    "layout|y": 5,
                                    "name": "balance",
                                    "width": 250
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto"
                                },
                                "name": "MyTextField34"
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
                                    "itemId": "cmdSaveNhif",
                                    "layout|x": 245,
                                    "layout|y": 45,
                                    "text": "Save",
                                    "width": 105
                                },
                                "configAlternates": {
                                    "height": "auto",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "itemId": "string"
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
                                    "layout|x": 430,
                                    "layout|y": 45,
                                    "text": "Cancel",
                                    "width": 105
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
                    }
                ]
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {
        "0c0f5177-03e0-4932-bbfb-9703a36a42cb": {
            "type": "jsonstore",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "autoLoad": false,
                "designer|userAlias": "billnumbersstore",
                "designer|userClassName": "BillNumbersStore",
                "model": "BillNumbers",
                "pageSize": 1000,
                "storeId": "BillNumbersStore"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string",
                "model": "model",
                "pageSize": "number",
                "storeId": "string",
                "autoLoad": "boolean"
            },
            "name": "ItemsListStore1",
            "cn": [
                {
                    "type": "Ext.data.proxy.Ajax",
                    "reference": {
                        "name": "proxy",
                        "type": "object"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "url": "data/getDataFunctions.php?caller=getBillNumbers"
                    },
                    "configAlternates": {
                        "url": "string"
                    },
                    "name": "MyAjaxProxy",
                    "cn": [
                        {
                            "type": "Ext.data.reader.Json",
                            "reference": {
                                "name": "reader",
                                "type": "object"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "rootProperty": "billnumbers"
                            },
                            "configAlternates": {
                                "rootProperty": "string"
                            },
                            "name": "MyJsonReader"
                        }
                    ]
                }
            ]
        },
        "69a7fd9e-d319-4a8c-b392-56ddbe9c415a": {
            "type": "jsonstore",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "autoLoad": false,
                "designer|userAlias": "nhifratestore",
                "designer|userClassName": "NhifRateStore",
                "model": "NhifRates",
                "pageSize": 500,
                "storeId": "NhifRateStore"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string",
                "model": "model",
                "pageSize": "number",
                "storeId": "string",
                "autoLoad": "boolean"
            },
            "name": "ReceiptStore1",
            "cn": [
                {
                    "type": "Ext.data.proxy.Ajax",
                    "reference": {
                        "name": "proxy",
                        "type": "object"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "url": "data/getDataFunctions.php?caller=getNhifRates"
                    },
                    "configAlternates": {
                        "url": "string"
                    },
                    "name": "MyAjaxProxy",
                    "cn": [
                        {
                            "type": "Ext.data.reader.Json",
                            "reference": {
                                "name": "reader",
                                "type": "object"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "rootProperty": "nhifRates"
                            },
                            "configAlternates": {
                                "rootProperty": "string"
                            },
                            "name": "MyJsonReader"
                        }
                    ]
                }
            ]
        }
    },
    "boundModels": {
        "9f59395e-35cc-4b98-9b88-b36caa971724": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "billnumbers",
                "designer|userClassName": "BillNumbers"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "MyModel",
            "cn": [
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "BillNumbers"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField21"
                }
            ]
        },
        "6c282005-1547-4487-8114-162dff37e2c6": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "nhifrates",
                "designer|userClassName": "NhifRates"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "MyModel",
            "cn": [
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "ID"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField42"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "RateType"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField43"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "RateValue"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField44"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "RateCalc"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField45"
                }
            ]
        }
    },
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
                "designer|userAlias": "nhifcredit",
                "designer|userClassName": "NhifCreditViewController"
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
                "designer|userAlias": "nhifcredit",
                "designer|userClassName": "NhifCreditViewModel"
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