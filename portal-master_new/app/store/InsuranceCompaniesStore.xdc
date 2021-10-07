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
            "autoLoad": false,
            "designer|userAlias": "insurancecompaniesstore",
            "designer|userClassName": "InsuranceCompaniesStore",
            "model": "InsuranceCompanies",
            "pageSize": 500,
            "storeId": "InsuranceCompaniesStore"
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
                    "url": "data/getDataFunctions.php?caller=getInsuranceCompanies"
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
                            "rootProperty": "insurancecompanies"
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
        "09bdf139-bfc4-428f-88eb-2da55e58d2bb": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "insurancecompanies",
                "designer|userClassName": "InsuranceCompanies"
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
                        "name": "Accno"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField40"
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
                    "name": "MyField41"
                }
            ]
        }
    }
}