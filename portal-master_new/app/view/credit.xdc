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
            "designer|userAlias": "credit",
            "designer|userClassName": "Credit",
            "draggable": true,
            "height": null,
            "itemId": "credit",
            "layout": "absolute",
            "title": "Credit Patients"
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "itemId": "string",
            "title": "string",
            "width": "auto",
            "designer|snapToGrid": "number",
            "draggable": "boolean",
            "layout": "string"
        },
        "name": "Debit1",
        "cn": [
            {
                "type": "Ext.panel.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "bodyStyle": "background:#84b3cc",
                    "draggable": true,
                    "height": 502,
                    "layout|x": 130,
                    "layout|y": 10,
                    "title": "Credits",
                    "width": 825
                },
                "configAlternates": {
                    "bodyStyle": "string",
                    "draggable": "boolean",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "title": "string",
                    "width": "auto"
                },
                "name": "MyPanel5",
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
                            "height": 89,
                            "layout": "absolute",
                            "title": null,
                            "width": null
                        },
                        "configAlternates": {
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "title": "string",
                            "width": "auto"
                        },
                        "name": "MyFieldSet",
                        "cn": [
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Patient No (PID)",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 5,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
                                },
                                "name": "MyTextField10"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Debit No",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 30,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
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
                                    "fieldLabel": "Bill Number",
                                    "labelAlign": "right",
                                    "layout|x": 225,
                                    "layout|y": 30,
                                    "width": 210
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
                                },
                                "name": "MyTextField24"
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
                                    "labelAlign": "right",
                                    "layout|x": 225,
                                    "layout|y": 55,
                                    "width": 210
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
                                },
                                "name": "MyTextField26"
                            },
                            {
                                "type": "Ext.form.field.Date",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Date",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 55,
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
                                },
                                "name": "MyDateField1"
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
                                    "labelAlign": "right",
                                    "layout|x": 235,
                                    "layout|y": 5,
                                    "width": 295
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "labelAlign": "string"
                                },
                                "name": "MyTextField11"
                            }
                        ]
                    },
                    {
                        "type": "Ext.grid.Panel",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|uiInterfaceName": "default-framed",
                            "frame": true,
                            "height": 292,
                            "store": "DebitStore",
                            "title": null
                        },
                        "configAlternates": {
                            "designer|uiInterfaceName": "string",
                            "frame": "boolean",
                            "height": "auto",
                            "title": "string",
                            "store": "store"
                        },
                        "name": "MyGridPanel",
                        "cn": [
                            {
                                "type": "Ext.view.Table",
                                "reference": {
                                    "name": "viewConfig",
                                    "type": "object"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 142
                                },
                                "configAlternates": {
                                    "height": "auto"
                                },
                                "name": "MyTable1"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "PartCode",
                                    "text": "Part Code",
                                    "width": 78
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyColumn20"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "Description",
                                    "text": "Description",
                                    "width": 246
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyColumn21"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "Category",
                                    "text": "Category",
                                    "width": 184
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyColumn22"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "Price",
                                    "text": "Price"
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string"
                                },
                                "name": "MyColumn23"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "Qty",
                                    "text": "Qty",
                                    "width": 55
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyColumn24"
                            },
                            {
                                "type": "Ext.grid.column.Column",
                                "reference": {
                                    "name": "columns",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "dataIndex": "Total",
                                    "text": "Total"
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string"
                                },
                                "name": "MyColumn25"
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
                            "designer|snapToGrid": 10,
                            "height": 79,
                            "layout": "absolute",
                            "title": null
                        },
                        "configAlternates": {
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "title": "string"
                        },
                        "name": "MyFieldSet1",
                        "cn": [
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 30,
                                    "layout|x": 520,
                                    "layout|y": 30,
                                    "text": "Save",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "height": "auto"
                                },
                                "name": "MyButton"
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 30,
                                    "layout|x": 390,
                                    "layout|y": 30,
                                    "text": "Delete Row",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "height": "auto"
                                },
                                "name": "MyButton20"
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 30,
                                    "itemId": "cmdItemsList",
                                    "layout|x": 60,
                                    "layout|y": 30,
                                    "text": "Get Items List",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "height": "auto",
                                    "itemId": "string"
                                },
                                "name": "MyButton18"
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 30,
                                    "layout|x": 660,
                                    "layout|y": 30,
                                    "text": "Cancel",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "height": "auto"
                                },
                                "name": "MyButton1"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Total",
                                    "labelWidth": 50,
                                    "layout|x": 570,
                                    "layout|y": 0
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "labelWidth": "number",
                                    "layout|x": "number",
                                    "layout|y": "number"
                                },
                                "name": "MyTextField14"
                            }
                        ]
                    }
                ]
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {
        "8bf1aa47-11b0-4656-b7e1-6efe00d67a72": {
            "type": "jsonstore",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "autoLoad": false,
                "designer|userAlias": "debitstore",
                "designer|userClassName": "DebitStore",
                "model": "DebitDetails",
                "pageSize": 500,
                "storeId": "DebitStore"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string",
                "model": "model",
                "pageSize": "number",
                "storeId": "string",
                "autoLoad": "boolean",
                "data": "array"
            },
            "name": "BillStore1",
            "cn": [
                {
                    "type": "Ext.data.proxy.Ajax",
                    "reference": {
                        "name": "proxy",
                        "type": "object"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "url": "data/getDataFunctions.php?caller=saveDebit"
                    },
                    "configAlternates": {
                        "url": "string"
                    },
                    "name": "MyAjaxProxy",
                    "cn": [
                        {
                            "type": "Ext.data.writer.Json",
                            "reference": {
                                "name": "writer",
                                "type": "object"
                            },
                            "codeClass": null,
                            "name": "MyJsonWriter"
                        }
                    ]
                }
            ]
        }
    },
    "boundModels": {
        "d7a97cad-a761-465d-91ee-9a6caf327c46": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "debitdetails",
                "designer|userClassName": "DebitDetails"
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
                        "name": "PartCode"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField11"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Description"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField12"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Category"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField13"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Price"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField14"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Qty"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField15"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Total"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField16"
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
                "designer|userAlias": "credit",
                "designer|userClassName": "DebitViewController1"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "DebitViewController1"
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
                "designer|userAlias": "credit",
                "designer|userClassName": "DebitViewModel1"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "DebitViewModel1"
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}