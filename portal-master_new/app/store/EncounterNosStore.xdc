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
            "designer|userAlias": "encounternosstore",
            "designer|userClassName": "EncounterNosStore",
            "model": "EncounterNumbers",
            "pageSize": 20,
            "storeId": "EncounterNosStore"
        },
        "configAlternates": {
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "model": "model",
            "pageSize": "number",
            "storeId": "string",
            "autoLoad": "boolean"
        },
        "name": "BillNumbersStore1",
        "cn": [
            {
                "type": "Ext.data.proxy.Ajax",
                "reference": {
                    "name": "proxy",
                    "type": "object"
                },
                "codeClass": null,
                "userConfig": {
                    "url": "data/getDataFunctions.php?caller=getEncounterNumbers"
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
                            "rootProperty": "encounters"
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
        "99aa0a9d-633c-40ed-94bd-8fbc02dadecb": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "encounternumbers",
                "designer|userClassName": "EncounterNumbers"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "name": "BillNumbers1",
            "cn": [
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "EncounterNumbers"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField21"
                }
            ]
        }
    }
}