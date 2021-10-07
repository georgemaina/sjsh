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
            "designer|userAlias": "cashpoints",
            "designer|userClassName": "CashPoints",
            "height": 687,
            "layout": "absolute",
            "title": "Cash Points",
            "width": 798
        },
        "configAlternates": {
            "bodyPadding": "auto",
            "designer|snapToGrid": "number",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "layout": "string",
            "title": "string",
            "width": "auto"
        },
        "name": "MyForm",
        "cn": [
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "cashpoint",
                    "fieldLabel": "Cash Point",
                    "itemId": "cashpoint",
                    "layout|x": 5,
                    "layout|y": 5,
                    "name": "CashPoint"
                },
                "configAlternates": {
                    "designer|displayName": "string",
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string"
                },
                "name": "MyTextField63"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "prefix",
                    "fieldLabel": "Prefix",
                    "itemId": "prefix",
                    "layout|x": 5,
                    "layout|y": 75,
                    "name": "Prefix"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "designer|displayName": "string",
                    "itemId": "string",
                    "name": "string"
                },
                "name": "MyTextField64"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "description",
                    "fieldLabel": "Description",
                    "itemId": "description",
                    "layout|x": 5,
                    "layout|y": 40,
                    "name": "Description"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "designer|displayName": "string",
                    "itemId": "string",
                    "name": "string"
                },
                "name": "MyTextField65"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "save",
                    "itemId": "save",
                    "layout|x": 280,
                    "layout|y": 110,
                    "text": "Save"
                },
                "configAlternates": {
                    "designer|displayName": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string"
                },
                "name": "MyButton11"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "refresh",
                    "itemId": "refresh",
                    "layout|x": 335,
                    "layout|y": 110,
                    "text": "Refresh"
                },
                "configAlternates": {
                    "designer|displayName": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string"
                },
                "name": "MyButton12"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "deletecashpoint",
                    "itemId": "deletecashpoint",
                    "layout|x": 5,
                    "layout|y": 545,
                    "text": "Delete Cash Point"
                },
                "configAlternates": {
                    "designer|displayName": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string"
                },
                "name": "MyButton13"
            },
            {
                "type": "Ext.grid.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "height": 395,
                    "layout|x": 5,
                    "layout|y": 145,
                    "store": [
                        "{cashPointsModels}"
                    ],
                    "title": "My Grid Panel"
                },
                "configAlternates": {
                    "store": "binding",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "title": "string"
                },
                "name": "MyGridPanel5",
                "cn": [
                    {
                        "type": "Ext.view.Table",
                        "reference": {
                            "name": "viewConfig",
                            "type": "object"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "width": 733
                        },
                        "configAlternates": {
                            "width": "auto"
                        },
                        "name": "MyTable4"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "code",
                            "text": "Code"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn19"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "name",
                            "text": "Name"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
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
                            "dataIndex": "prefix",
                            "text": "Prefix"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
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
                            "dataIndex": "nextreceiptNo",
                            "text": "Nextreceipt No"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn25"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "nextvoucherNo",
                            "text": "Nextvoucher No"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn26"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "nextshiftNo",
                            "text": "Nextshift No"
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string"
                        },
                        "name": "MyColumn27"
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
                "designer|userAlias": "cashpoints",
                "designer|userClassName": "CashPointsViewController"
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
                "designer|userAlias": "cashpoints",
                "designer|userClassName": "CashPointsViewModel"
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