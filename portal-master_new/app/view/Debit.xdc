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
            "designer|userAlias": "debit",
            "designer|userClassName": "Debit",
            "height": null,
            "itemId": "debit",
            "layout": "absolute",
            "title": "Debit Patients",
            "width": null
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
            "layout": "string"
        },
        "name": "MyForm",
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
                    "designer|uiInterfaceName": "default-framed",
                    "draggable": true,
                    "frame": true,
                    "height": 565,
                    "itemId": "debitsForm",
                    "layout|x": 150,
                    "layout|y": 5,
                    "padding": "0 0 0 0",
                    "title": "Debits",
                    "width": 860
                },
                "configAlternates": {
                    "bodyStyle": "string",
                    "designer|uiInterfaceName": "string",
                    "draggable": "boolean",
                    "height": "auto",
                    "layout|x": "number",
                    "layout|y": "number",
                    "title": "string",
                    "width": "auto",
                    "itemId": "string",
                    "frame": "boolean",
                    "padding": "auto"
                },
                "name": "MyPanel4",
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
                            "height": 112,
                            "layout": "absolute",
                            "padding": "0 0 0 0",
                            "title": null,
                            "width": 1258
                        },
                        "configAlternates": {
                            "designer|snapToGrid": "number",
                            "height": "auto",
                            "layout": "string",
                            "title": "string",
                            "width": "auto",
                            "padding": "auto"
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
                                    "allowBlank": false,
                                    "fieldLabel": "Patient No (PID)",
                                    "itemId": "txtPid",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 5,
                                    "name": "pid",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "name": "string",
                                    "width": "auto",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean"
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
                                    "allowBlank": false,
                                    "fieldLabel": "Debit No",
                                    "itemId": "debitNo",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 40,
                                    "name": "debitdate",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "itemId": "string",
                                    "name": "string",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean"
                                },
                                "name": "MyTextField12"
                            },
                            {
                                "type": "Ext.form.field.Date",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "Date",
                                    "itemId": "debitDate",
                                    "labelAlign": "right",
                                    "layout|x": 10,
                                    "layout|y": 75,
                                    "name": "debitdate",
                                    "width": 225
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "width": "auto",
                                    "itemId": "string",
                                    "name": "string",
                                    "labelAlign": "string",
                                    "allowBlank": "boolean"
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
                                    "allowBlank": false,
                                    "fieldLabel": null,
                                    "id": "pname",
                                    "itemId": "pname",
                                    "layout|x": 235,
                                    "layout|y": 5,
                                    "name": "pname",
                                    "width": 295
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "id": "string",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto",
                                    "allowBlank": "boolean"
                                },
                                "name": "MyTextField11"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "Encounter No",
                                    "itemId": "encounterNo",
                                    "labelAlign": "right",
                                    "layout|x": 280,
                                    "layout|y": 75,
                                    "name": "pname",
                                    "width": 250
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto",
                                    "allowBlank": "boolean",
                                    "labelWidth": "number",
                                    "labelAlign": "string"
                                },
                                "name": "MyTextField44"
                            },
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "allowBlank": false,
                                    "fieldLabel": "BillNumber",
                                    "itemId": "billNumber",
                                    "labelAlign": "right",
                                    "labelWidth": 80,
                                    "layout|x": 300,
                                    "layout|y": 40,
                                    "name": "pname",
                                    "width": 230
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "itemId": "string",
                                    "name": "string",
                                    "width": "auto",
                                    "allowBlank": "boolean",
                                    "labelAlign": "string",
                                    "labelWidth": "number"
                                },
                                "name": "MyTextField45"
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
                            "columnLines": true,
                            "designer|uiInterfaceName": "default-framed",
                            "frame": true,
                            "height": 308,
                            "itemId": "itemsGrid",
                            "margin": "0 0 0 0",
                            "store": [
                                "DebitStore"
                            ],
                            "title": null
                        },
                        "configAlternates": {
                            "store": "binding",
                            "columnLines": "boolean",
                            "designer|uiInterfaceName": "string",
                            "frame": "boolean",
                            "height": "auto",
                            "title": "string",
                            "itemId": "string",
                            "margin": "auto"
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
                                    "text": "Part Code"
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string"
                                },
                                "name": "MyColumn13",
                                "cn": [
                                    {
                                        "type": "Ext.grid.filters.filter.String",
                                        "reference": {
                                            "name": "filter",
                                            "type": "object"
                                        },
                                        "codeClass": null,
                                        "name": "MyStringFilter"
                                    }
                                ]
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
                                    "width": 307
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string",
                                    "width": "auto"
                                },
                                "name": "MyColumn14",
                                "cn": [
                                    {
                                        "type": "Ext.grid.filters.filter.String",
                                        "reference": {
                                            "name": "filter",
                                            "type": "object"
                                        },
                                        "codeClass": null,
                                        "name": "MyStringFilter1"
                                    }
                                ]
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
                                    "text": "Category"
                                },
                                "configAlternates": {
                                    "dataIndex": "datafield",
                                    "text": "string"
                                },
                                "name": "MyColumn15"
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
                                "name": "MyColumn16"
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
                                "name": "MyColumn17",
                                "cn": [
                                    {
                                        "type": "Ext.form.field.Number",
                                        "reference": {
                                            "name": "editor",
                                            "type": "object"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "value": [
                                                "1"
                                            ]
                                        },
                                        "configAlternates": {
                                            "value": "object"
                                        },
                                        "name": "MyNumberField"
                                    }
                                ]
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
                                "name": "MyColumn18",
                                "cn": [
                                    {
                                        "type": "fixedfunction",
                                        "reference": {
                                            "name": "items",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "designer|params": [
                                                "value",
                                                "metaData",
                                                "record",
                                                "rowIndex",
                                                "colIndex",
                                                "store",
                                                "view"
                                            ],
                                            "fn": "renderer",
                                            "implHandler": [
                                                "return record.get('Price')*record.get('Qty');"
                                            ]
                                        },
                                        "configAlternates": {
                                            "designer|params": "typedarray",
                                            "fn": "string",
                                            "implHandler": "code"
                                        },
                                        "name": "renderer"
                                    }
                                ]
                            },
                            {
                                "type": "Ext.grid.plugin.CellEditing",
                                "reference": {
                                    "name": "plugins",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "name": "MyCellEditingPlugin"
                            },
                            {
                                "type": "Ext.grid.filters.Filters",
                                "reference": {
                                    "name": "plugins",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "name": "MyGridFiltersPlugin"
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
                            "height": 88,
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
                                    "itemId": "cmdSaveDebits",
                                    "layout|x": 580,
                                    "layout|y": 40,
                                    "text": "Save",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "itemId": "string",
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
                                    "itemId": "cmdDeleteRow",
                                    "layout|x": 440,
                                    "layout|y": 40,
                                    "text": "Romove Row",
                                    "width": 120
                                },
                                "configAlternates": {
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "width": "auto",
                                    "height": "auto"
                                },
                                "name": "MyButton19"
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
                                    "itemId": "cmdClose",
                                    "layout|x": 710,
                                    "layout|y": 40,
                                    "text": "Close",
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
                                    "itemId": "txtTotal",
                                    "labelWidth": 50,
                                    "layout|x": 600,
                                    "layout|y": 0
                                },
                                "configAlternates": {
                                    "fieldLabel": "string",
                                    "labelWidth": "number",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "itemId": "string"
                                },
                                "name": "MyTextField14"
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
                                    "layout|x": 10,
                                    "layout|y": 10,
                                    "text": "Get Items List",
                                    "width": 130
                                },
                                "configAlternates": {
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "height": "auto",
                                    "width": "auto"
                                },
                                "name": "MyButton7"
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "height": 33,
                                    "hidden": true,
                                    "itemId": "cmdAddRow",
                                    "layout|x": 312,
                                    "layout|y": 4,
                                    "text": "Add Row",
                                    "width": 89
                                },
                                "configAlternates": {
                                    "itemId": "string",
                                    "layout|x": "number",
                                    "layout|y": "number",
                                    "text": "string",
                                    "height": "auto",
                                    "hidden": "boolean",
                                    "width": "auto"
                                },
                                "name": "MyButton9"
                            }
                        ]
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
                "designer|userAlias": "debit",
                "designer|userClassName": "DebitViewController"
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
                "designer|userAlias": "debit",
                "designer|userClassName": "DebitViewModel"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "cn": [
                {
                    "type": "Ext.data.Store",
                    "reference": {
                        "name": "stores",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "data": [
                            "[{\"PartCode\":\"dolorem\",\"Description\":\"error\",\"Category\":\"soluta\",\"Price\":\"inventore\",\"Qty\":\"molestiae\",\"Total\":\"incidunt\"},{\"PartCode\":\"incidunt\",\"Description\":\"deserunt\",\"Category\":\"ipsam\",\"Price\":\"quisquam\",\"Qty\":\"id\",\"Total\":\"cum\"},{\"PartCode\":\"optio\",\"Description\":\"eum\",\"Category\":\"harum\",\"Price\":\"deleniti\",\"Qty\":\"quia\",\"Total\":\"similique\"},{\"PartCode\":\"quasi\",\"Description\":\"architecto\",\"Category\":\"ipsum\",\"Price\":\"cumque\",\"Qty\":\"voluptates\",\"Total\":\"ut\"},{\"PartCode\":\"sit\",\"Description\":\"quo\",\"Category\":\"necessitatibus\",\"Price\":\"voluptas\",\"Qty\":\"eum\",\"Total\":\"et\"},{\"PartCode\":\"culpa\",\"Description\":\"labore\",\"Category\":\"et\",\"Price\":\"nemo\",\"Qty\":\"eum\",\"Total\":\"nisi\"},{\"PartCode\":\"qui\",\"Description\":\"non\",\"Category\":\"quam\",\"Price\":\"commodi\",\"Qty\":\"soluta\",\"Total\":\"eius\"},{\"PartCode\":\"nesciunt\",\"Description\":\"et\",\"Category\":\"eos\",\"Price\":\"quod\",\"Qty\":\"fuga\",\"Total\":\"laborum\"},{\"PartCode\":\"voluptatem\",\"Description\":\"ratione\",\"Category\":\"blanditiis\",\"Price\":\"quia\",\"Qty\":\"id\",\"Total\":\"facilis\"},{\"PartCode\":\"nihil\",\"Description\":\"numquam\",\"Category\":\"quo\",\"Price\":\"ab\",\"Qty\":\"voluptatem\",\"Total\":\"ut\"},{\"PartCode\":\"id\",\"Description\":\"ut\",\"Category\":\"voluptatem\",\"Price\":\"labore\",\"Qty\":\"consequuntur\",\"Total\":\"repellat\"},{\"PartCode\":\"cupiditate\",\"Description\":\"rerum\",\"Category\":\"quam\",\"Price\":\"sed\",\"Qty\":\"praesentium\",\"Total\":\"eos\"},{\"PartCode\":\"temporibus\",\"Description\":\"voluptatem\",\"Category\":\"reiciendis\",\"Price\":\"porro\",\"Qty\":\"hic\",\"Total\":\"magnam\"},{\"PartCode\":\"numquam\",\"Description\":\"fugiat\",\"Category\":\"occaecati\",\"Price\":\"nisi\",\"Qty\":\"sed\",\"Total\":\"illo\"},{\"PartCode\":\"id\",\"Description\":\"quo\",\"Category\":\"placeat\",\"Price\":\"quo\",\"Qty\":\"nam\",\"Total\":\"molestiae\"},{\"PartCode\":\"eaque\",\"Description\":\"pariatur\",\"Category\":\"ea\",\"Price\":\"quo\",\"Qty\":\"tempore\",\"Total\":\"ducimus\"},{\"PartCode\":\"quaerat\",\"Description\":\"perspiciatis\",\"Category\":\"voluptatem\",\"Price\":\"expedita\",\"Qty\":\"consequatur\",\"Total\":\"aliquid\"},{\"PartCode\":\"ut\",\"Description\":\"ut\",\"Category\":\"explicabo\",\"Price\":\"exercitationem\",\"Qty\":\"aut\",\"Total\":\"et\"},{\"PartCode\":\"tenetur\",\"Description\":\"quod\",\"Category\":\"est\",\"Price\":\"dolores\",\"Qty\":\"reprehenderit\",\"Total\":\"mollitia\"},{\"PartCode\":\"est\",\"Description\":\"et\",\"Category\":\"nulla\",\"Price\":\"in\",\"Qty\":\"modi\",\"Total\":\"possimus\"},{\"PartCode\":\"quasi\",\"Description\":\"natus\",\"Category\":\"totam\",\"Price\":\"nostrum\",\"Qty\":\"sit\",\"Total\":\"corporis\"},{\"PartCode\":\"vel\",\"Description\":\"magnam\",\"Category\":\"sapiente\",\"Price\":\"et\",\"Qty\":\"sed\",\"Total\":\"excepturi\"},{\"PartCode\":\"optio\",\"Description\":\"iusto\",\"Category\":\"accusantium\",\"Price\":\"aspernatur\",\"Qty\":\"est\",\"Total\":\"enim\"},{\"PartCode\":\"quisquam\",\"Description\":\"impedit\",\"Category\":\"voluptate\",\"Price\":\"veniam\",\"Qty\":\"molestias\",\"Total\":\"similique\"},{\"PartCode\":\"laboriosam\",\"Description\":\"nostrum\",\"Category\":\"accusamus\",\"Price\":\"inventore\",\"Qty\":\"porro\",\"Total\":\"minus\"},{\"PartCode\":\"voluptatum\",\"Description\":\"laboriosam\",\"Category\":\"aperiam\",\"Price\":\"qui\",\"Qty\":\"eum\",\"Total\":\"voluptas\"},{\"PartCode\":\"quisquam\",\"Description\":\"qui\",\"Category\":\"occaecati\",\"Price\":\"neque\",\"Qty\":\"eum\",\"Total\":\"asperiores\"},{\"PartCode\":\"ipsam\",\"Description\":\"voluptas\",\"Category\":\"nobis\",\"Price\":\"vel\",\"Qty\":\"cumque\",\"Total\":\"necessitatibus\"},{\"PartCode\":\"velit\",\"Description\":\"eum\",\"Category\":\"illo\",\"Price\":\"nihil\",\"Qty\":\"vero\",\"Total\":\"sit\"},{\"PartCode\":\"dolores\",\"Description\":\"eaque\",\"Category\":\"blanditiis\",\"Price\":\"dolores\",\"Qty\":\"sint\",\"Total\":\"temporibus\"}]"
                        ],
                        "model": "DebitDetails",
                        "name": "debitDetails"
                    },
                    "configAlternates": {
                        "data": "array",
                        "model": "model",
                        "name": "string"
                    },
                    "name": "debitDetails",
                    "cn": [
                        {
                            "type": "Ext.data.proxy.Memory",
                            "reference": {
                                "name": "proxy",
                                "type": "object"
                            },
                            "codeClass": null,
                            "name": "MyMemoryProxy2"
                        }
                    ]
                }
            ]
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}