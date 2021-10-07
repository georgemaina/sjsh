{
    "xdsVersion": "4.1.1",
    "frameworkVersion": "ext62",
    "internals": {
        "type": "jsonstore",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "designer|userAlias": "orderstempstore",
            "designer|userClassName": "OrdersTempStore",
            "model": "OrdersTemplate",
            "storeId": "OrdersTempStore"
        },
        "configAlternates": {
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "model": "model",
            "storeId": "string"
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
                    "url": "data/getDataFunctions.php?task=getOrdersTemplate"
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
                            "rootProperty": "orders"
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
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {
        "0ba9be86-2e46-4236-a7a5-ffe0548e1d08": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "orderstemplate",
                "designer|userClassName": "OrdersTemplate"
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
                    "name": "MyField"
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
                    "name": "MyField1"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "PurchasingUnit"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField2"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Level"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField3"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "MonthlyUsage"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField4"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "ReorderLevel"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField5"
                }
            ]
        }
    }
}