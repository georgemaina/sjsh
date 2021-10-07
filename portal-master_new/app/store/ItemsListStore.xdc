{
    "xdsVersion": "4.2.4",
    "frameworkVersion": "ext62",
    "internals": {
        "type": "jsonstore",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "autoLoad": true,
            "designer|userAlias": "itemsliststore",
            "designer|userClassName": "ItemsListStore",
            "model": "ItemsList",
            "pageSize": 1000,
            "storeId": "ItemsListStore"
        },
        "configAlternates": {
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "model": "model",
            "pageSize": "number",
            "storeId": "string",
            "autoLoad": "boolean"
        },
        "name": "BillStore2",
        "cn": [
            {
                "type": "Ext.data.proxy.Ajax",
                "reference": {
                    "name": "proxy",
                    "type": "object"
                },
                "codeClass": null,
                "userConfig": {
                    "url": "data/getDataFunctions.php?caller=getItemsList"
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
                            "rootProperty": "items"
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
        "a27ac328-5956-4905-948a-fa16bca1d747": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "itemsList",
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
                        "name": "PartCode"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField17"
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
                    "name": "MyField18"
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
                    "name": "MyField19"
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
                    "name": "MyField20"
                }
            ]
        }
    }
}