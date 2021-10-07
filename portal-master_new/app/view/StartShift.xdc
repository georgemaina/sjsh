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
            "designer|userAlias": "startshift",
            "designer|userClassName": "StartShift",
            "height": 301,
            "layout": "absolute",
            "title": "Start Shift",
            "width": 575
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
        "name": "MyForm1",
        "cn": [
            {
                "type": "Ext.form.Label",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "layout|x": 1,
                    "layout|y": 1,
                    "text": "Your are about to start a shift"
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string"
                },
                "name": "MyLabel3"
            },
            {
                "type": "Ext.form.field.ComboBox",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "cashpoint",
                    "fieldLabel": "Cash Point",
                    "itemId": "cashpoint",
                    "layout|x": 0,
                    "layout|y": 30,
                    "name": "CashPoint"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string",
                    "designer|displayName": "string"
                },
                "name": "MyComboBox"
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
                    "layout|x": 0,
                    "layout|y": 65,
                    "name": "Description"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string",
                    "designer|displayName": "string"
                },
                "name": "MyTextField"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "cashier",
                    "fieldLabel": "Cashier",
                    "itemId": "cashier",
                    "layout|x": 0,
                    "layout|y": 100,
                    "name": "Cashier"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string",
                    "designer|displayName": "string"
                },
                "name": "MyTextField1"
            },
            {
                "type": "Ext.form.field.Text",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "startdate",
                    "fieldLabel": "Start Date",
                    "itemId": "startdate",
                    "layout|x": 0,
                    "layout|y": 135,
                    "name": "StartDate"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string",
                    "designer|displayName": "string"
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
                    "designer|displayName": "starttime",
                    "fieldLabel": "Start Time",
                    "itemId": "starttime",
                    "layout|x": 0,
                    "layout|y": 170,
                    "name": "StartTime"
                },
                "configAlternates": {
                    "fieldLabel": "string",
                    "itemId": "string",
                    "layout|x": "number",
                    "layout|y": "number",
                    "name": "string",
                    "designer|displayName": "string"
                },
                "name": "MyTextField3"
            },
            {
                "type": "Ext.button.Button",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|displayName": "startshift",
                    "layout|x": 140,
                    "layout|y": 210,
                    "text": "Start Shift"
                },
                "configAlternates": {
                    "layout|x": "number",
                    "layout|y": "number",
                    "text": "string",
                    "designer|displayName": "string"
                },
                "name": "MyButton"
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
                "designer|userAlias": "startshift",
                "designer|userClassName": "StartShiftViewController"
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
                "designer|userAlias": "startshift",
                "designer|userClassName": "StartShiftViewModel"
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