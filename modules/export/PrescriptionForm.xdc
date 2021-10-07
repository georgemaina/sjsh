{
    "xdsVersion": "4.2.2",
    "frameworkVersion": "ext65",
    "internals": {
        "type": "Ext.panel.Panel",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "container|align": "stretch",
            "designer|uiInterfaceName": "default-framed",
            "designer|userAlias": "prescripionform",
            "designer|userClassName": "PrescripionForm",
            "frame": true,
            "height": 558,
            "layout": "hbox"
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "container|align": "string",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "layout": "string",
            "title": "string",
            "width": "auto",
            "designer|uiInterfaceName": "string",
            "frame": "boolean"
        },
        "name": "MyPanel1",
        "cn": [
            {
                "type": "linkedinstance",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "layout|flex": 0,
                    "width": 410
                },
                "configAlternates": {
                    "layout|flex": "number",
                    "width": "auto"
                },
                "name": "itemslist1",
                "masterInstanceId": "30566b7d-c815-405a-8920-4760ae1f5b05"
            },
            {
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
                    "frame": true,
                    "layout": "absolute",
                    "layout|flex": 0,
                    "width": 61
                },
                "configAlternates": {
                    "bodyPadding": "auto",
                    "designer|snapToGrid": "number",
                    "designer|uiInterfaceName": "string",
                    "frame": "boolean",
                    "layout": "string",
                    "layout|flex": "number",
                    "width": "auto"
                },
                "name": "MyForm",
                "cn": [
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 255,
                            "text": "<<<"
                        },
                        "configAlternates": {
                            "layout|x": "number",
                            "layout|y": "number",
                            "text": "string"
                        },
                        "name": "MyButton3"
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "layout|x": 5,
                            "layout|y": 190,
                            "text": ">>>"
                        },
                        "configAlternates": {
                            "text": "string",
                            "layout|x": "number",
                            "layout|y": "number"
                        },
                        "name": "MyButton7"
                    }
                ]
            },
            {
                "type": "Ext.form.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "bodyPadding": 10,
                    "itemId": "dosageList",
                    "layout|flex": 1,
                    "minWidth": 600,
                    "scrollable": "vertical",
                    "url": "../../data/getDataFunctions.php?task=savePrescription"
                },
                "configAlternates": {
                    "bodyPadding": "auto",
                    "itemId": "string",
                    "layout|flex": "number",
                    "minWidth": "number",
                    "scrollable": "auto",
                    "url": "string"
                },
                "name": "MyForm1",
                "cn": [
                    {
                        "type": "Ext.form.FieldContainer",
                        "reference": {
                            "name": "dockedItems",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "container|align": "stretch",
                            "container|pack": "center",
                            "dock": "bottom",
                            "height": 51,
                            "layout": "hbox"
                        },
                        "configAlternates": {
                            "container|align": "string",
                            "container|pack": "string",
                            "dock": "string",
                            "height": "auto",
                            "layout": "string"
                        },
                        "name": "MyFieldContainer",
                        "cn": [
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "iconCls": "x-fa fa-plus",
                                    "itemId": "cmdPrescribe",
                                    "margin": "0 10 0 0",
                                    "text": "Prescribe",
                                    "width": 116
                                },
                                "configAlternates": {
                                    "iconCls": "string",
                                    "itemId": "string",
                                    "margin": "auto",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyButton14"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "itemId": "counter",
                                    "name": "counter",
                                    "value": [
                                        "0"
                                    ],
                                    "width": 42
                                },
                                "configAlternates": {
                                    "itemId": "string",
                                    "name": "string",
                                    "value": "object",
                                    "width": "auto"
                                },
                                "name": "MyTextField10"
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
                            "height": 44,
                            "itemId": "patientForm1",
                            "layout": "hbox"
                        },
                        "configAlternates": {
                            "container|align": "string",
                            "height": "auto",
                            "layout": "string",
                            "itemId": "string",
                            "dock": "string"
                        },
                        "name": "MyFieldSet6",
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
                                    "fieldStyle": "color:green; font-weight:bold;",
                                    "itemId": "Pid",
                                    "labelStyle": "color:red; font-weight:bold;",
                                    "labelWidth": 20,
                                    "margin": "0 10  0 0",
                                    "name": "Pid"
                                },
                                "configAlternates": {
                                    "name": "string",
                                    "value": "string",
                                    "itemId": "string",
                                    "fieldLabel": "string",
                                    "fieldStyle": "string",
                                    "labelStyle": "string",
                                    "labelWidth": "number",
                                    "margin": "auto"
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
                                    "fieldLabel": "Patient",
                                    "fieldStyle": "color:green; font-weight:bold;",
                                    "itemId": "Names",
                                    "labelStyle": "color:red; font-weight:bold;",
                                    "labelWidth": 50,
                                    "margin": "0 10 0 0",
                                    "name": "Names"
                                },
                                "configAlternates": {
                                    "value": "string",
                                    "name": "string",
                                    "itemId": "string",
                                    "fieldLabel": "string",
                                    "fieldStyle": "string",
                                    "labelStyle": "string",
                                    "labelWidth": "number",
                                    "margin": "auto"
                                },
                                "name": "MyDisplayField1"
                            },
                            {
                                "type": "Ext.form.field.Display",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Encounter No",
                                    "fieldStyle": "color:green; font-weight:bold;",
                                    "itemId": "EncounterNo",
                                    "labelStyle": "color:red; font-weight:bold;",
                                    "margin": "0 10 0 0",
                                    "name": "EncounterNo"
                                },
                                "configAlternates": {
                                    "value": "string",
                                    "name": "string",
                                    "itemId": "string",
                                    "fieldLabel": "string",
                                    "fieldStyle": "string",
                                    "labelStyle": "string",
                                    "margin": "auto"
                                },
                                "name": "MyDisplayField2"
                            },
                            {
                                "type": "Ext.form.field.Display",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "fieldLabel": "Prescription Date",
                                    "fieldStyle": "color:green; font-weight:bold;",
                                    "itemId": "PrescribeDate",
                                    "labelStyle": "color:red; font-weight:bold;",
                                    "labelWidth": 120,
                                    "margin": "0 10 0 0",
                                    "name": "PrescribeDate"
                                },
                                "configAlternates": {
                                    "value": "string",
                                    "name": "string",
                                    "itemId": "string",
                                    "fieldLabel": "string",
                                    "fieldStyle": "string",
                                    "labelStyle": "string",
                                    "labelWidth": "number",
                                    "margin": "auto"
                                },
                                "name": "MyDisplayField3"
                            }
                        ]
                    }
                ]
            }
        ]
    },
    "linkedNodes": {
        "30566b7d-c815-405a-8920-4760ae1f5b05": {
            "id": "30566b7d-c815-405a-8920-4760ae1f5b05",
            "internals": {
                "type": "Ext.grid.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "bodyStyle": "background-color: #d9f2e6;",
                    "columnLines": true,
                    "designer|userAlias": "itemslist",
                    "designer|userClassName": "ItemsList",
                    "height": 414,
                    "itemId": "itemsList",
                    "store": "ItemslistStore",
                    "width": 632
                },
                "configAlternates": {
                    "columnLines": "boolean",
                    "designer|userAlias": "string",
                    "designer|userClassName": "string",
                    "height": "auto",
                    "store": "store",
                    "width": "auto",
                    "bodyStyle": "string",
                    "itemId": "string"
                },
                "name": "MyGridPanel5",
                "viewControllerInstanceId": "a34ab4a5-9471-43b2-9e71-f535e2ac126a",
                "viewModelInstanceId": "b05e0379-8845-45bb-a220-bd4f806c0555",
                "cn": [
                    {
                        "type": "Ext.form.FieldSet",
                        "reference": {
                            "name": "dockedItems",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|snapToGrid": 5,
                            "dock": "top",
                            "height": 43,
                            "layout": "absolute",
                            "padding": 0,
                            "style": "background-color: #d9f2e6;",
                            "width": 100
                        },
                        "configAlternates": {
                            "designer|snapToGrid": "number",
                            "dock": "string",
                            "height": "auto",
                            "layout": "string",
                            "padding": "auto",
                            "width": "auto",
                            "style": "string"
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
                                    "emptyText": "Search by Description, PartCode",
                                    "itemId": "txtSearchItems",
                                    "layout|x": 5,
                                    "layout|y": 5,
                                    "padding": 0,
                                    "width": 380
                                },
                                "configAlternates": {
                                    "emptyText": "string",
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "padding": "auto",
                                    "width": "auto"
                                },
                                "name": "MyTextField2"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "hidden": true,
                                    "itemId": "sourceID",
                                    "layout|x": 285,
                                    "layout|y": 5
                                },
                                "configAlternates": {
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "hidden": "boolean"
                                },
                                "name": "MyTextField8"
                            }
                        ]
                    },
                    {
                        "type": "Ext.view.Table",
                        "reference": {
                            "name": "viewConfig",
                            "type": "object"
                        },
                        "codeClass": null,
                        "name": "MyTable5"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "partcode",
                            "text": "Partcode"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn2"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "item_description",
                            "text": "Item Description",
                            "width": 272
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyColumn3"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "qty",
                            "text": "Qty"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn1"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "unit_price",
                            "text": "Unit Price"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn40"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "purchasing_class",
                            "hidden": true,
                            "text": "Purchasing Class",
                            "width": 135
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "hidden": "boolean",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyColumn4"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "category",
                            "hidden": true,
                            "text": "Category",
                            "width": 150
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "hidden": "boolean",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyColumn5"
                    },
                    {
                        "type": "Ext.toolbar.Paging",
                        "reference": {
                            "name": "dockedItems",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "displayInfo": true,
                            "dock": "bottom",
                            "store": "ItemslistStore",
                            "width": 360
                        },
                        "configAlternates": {
                            "displayInfo": "boolean",
                            "dock": "string",
                            "store": "store",
                            "width": "auto"
                        },
                        "name": "MyPagingToolbar1"
                    }
                ],
                "designerId": "30566b7d-c815-405a-8920-4760ae1f5b05"
            }
        }
    },
    "boundStores": {
        "c156f9ea-8b7c-4863-8af6-b0491898a59a": {
            "type": "jsonstore",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "autoLoad": true,
                "designer|userAlias": "itemsliststore",
                "designer|userClassName": "ItemslistStore",
                "model": "ItemsList",
                "pageSize": 3000,
                "storeId": "ItemslistStore"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string",
                "model": "model",
                "storeId": "string",
                "autoLoad": "boolean",
                "pageSize": "number"
            },
            "name": "MyJsonStore",
            "cn": [
                {
                    "type": "Ext.data.proxy.Ajax",
                    "reference": {
                        "name": "proxy",
                        "type": "object"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "url": "../../data/getDataFunctions.php?task=getItemsList&sParams='Drug_list','medical-supplies'"
                    },
                    "configAlternates": {
                        "url": "string"
                    },
                    "name": "MyAjaxProxy6",
                    "cn": [
                        {
                            "type": "Ext.data.reader.Json",
                            "reference": {
                                "name": "reader",
                                "type": "object"
                            },
                            "codeClass": null,
                            "configAlternates": {
                                "rootProperty": "string"
                            },
                            "name": "MyJsonReader6"
                        }
                    ]
                }
            ]
        }
    },
    "boundModels": {
        "4eb03221-ede5-40ef-af24-0d489e484edc": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "itemslist",
                "designer|userClassName": "ItemsList"
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
                        "name": "partcode"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField50"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "item_description"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField51"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "qty"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField54"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "purchasing_class"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField52"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "category"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField53"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "unit_price"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField55"
                }
            ]
        }
    },
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
                "designer|userAlias": "prescripionform",
                "designer|userClassName": "PrescripionFormViewController"
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
                "designer|userAlias": "prescripionform",
                "designer|userClassName": "PrescripionFormViewModel"
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