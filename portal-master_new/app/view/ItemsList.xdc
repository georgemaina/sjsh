{
    "xdsVersion": "4.2.4",
    "frameworkVersion": "ext62",
    "internals": {
        "type": "Ext.grid.Panel",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "columnLines": true,
            "designer|userAlias": "itemslist",
            "designer|userClassName": "ItemsList",
            "height": 538,
            "itemId": "itemsList",
            "maxHeight": 700,
            "resizable": true,
            "store": "ItemsListStore",
            "title": null,
            "width": 822
        },
        "configAlternates": {
            "columnLines": "boolean",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "itemId": "string",
            "store": "store",
            "title": "string",
            "width": "auto",
            "designer|extend": "string",
            "maxHeight": "number",
            "resizable": "boolean"
        },
        "name": "MyGridPanel1",
        "cn": [
            {
                "type": "Ext.view.Table",
                "reference": {
                    "name": "viewConfig",
                    "type": "object"
                },
                "codeClass": null,
                "name": "MyTable3"
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
                    "text": "Part Code"
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
                    "dataIndex": "Description",
                    "text": "Description",
                    "width": 268
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyColumn27"
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
                    "width": 176
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyColumn28"
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
                "name": "MyColumn29"
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
                    "text": "Qty"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string"
                },
                "name": "MyColumn30"
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
                    "store": "ItemsListStore",
                    "width": 360
                },
                "configAlternates": {
                    "displayInfo": "boolean",
                    "dock": "string",
                    "store": "store",
                    "width": "auto"
                },
                "name": "MyPagingToolbar1"
            },
            {
                "type": "Ext.selection.RowModel",
                "reference": {
                    "name": "selModel",
                    "type": "object"
                },
                "codeClass": null,
                "name": "MyRowSelectionModel"
            },
            {
                "type": "Ext.form.FieldContainer",
                "reference": {
                    "name": "dockedItems",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "designer|snapToGrid": 5,
                    "dock": "top",
                    "height": 40,
                    "layout": "absolute",
                    "width": 100
                },
                "configAlternates": {
                    "designer|snapToGrid": "number",
                    "dock": "string",
                    "height": "auto",
                    "layout": "string",
                    "width": "auto"
                },
                "name": "MyFieldContainer",
                "cn": [
                    {
                        "type": "Ext.form.field.Text",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "emptyText": "Enter the Partcode or Description here...",
                            "frame": true,
                            "itemId": "searchParam",
                            "layout|x": 5,
                            "layout|y": 5,
                            "width": 265
                        },
                        "configAlternates": {
                            "emptyText": "string",
                            "itemId": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "width": "auto",
                            "frame": "boolean"
                        },
                        "name": "MyTextField31"
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "iconCls": "x-fa fa-search",
                            "itemId": "cmdSearchItems",
                            "layout|x": 270,
                            "layout|y": 5,
                            "text": "Search Items"
                        },
                        "configAlternates": {
                            "iconCls": "string",
                            "itemId": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "text": "string"
                        },
                        "name": "MyButton21"
                    }
                ]
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {
        "6eeda063-641c-4270-990d-fd6f250cf411": {
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
        }
    },
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
                "designer|userAlias": "itemslist",
                "designer|userClassName": "ItemsListViewController"
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
                "designer|userAlias": "itemslist",
                "designer|userClassName": "ItemsListViewModel"
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