{
    "xdsVersion": "2.2.3",
    "frameworkVersion": "ext42",
    "internals": {
        "type": "Ext.data.Store",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "autoLoad": true,
            "model": "PayrollMonths",
            "storeId": "MyStore",
            "designer|userClassName": "PayMonthStore",
            "designer|userAlias": "paymonthstore",
            "data": [
                "[['January','January'],['February','February'],['March','March'],['April','April'],['May','May'],['June','June'],\r",
                "['July','July'],['August','August'],['September','September'],['October','October'],['November','November'],['December','December']]"
            ]
        }
    },
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {
        "f6a8fcb6-ffbf-44fe-87df-443d3cc7b395": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userClassName": "PayrollMonths"
            },
            "cn": [
                {
                    "type": "Ext.data.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "ID"
                    }
                },
                {
                    "type": "Ext.data.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "month"
                    }
                }
            ]
        }
    }
}