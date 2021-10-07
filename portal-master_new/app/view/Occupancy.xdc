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
            "designer|userAlias": "wardoccupancy",
            "designer|userClassName": "WardOccupancy",
            "minHeight": 500,
            "store": "OccupancyStore",
            "title": "Ward Occupancy",
            "viewModel": "wardoccupancy"
        },
        "configAlternates": {
            "columnLines": "boolean",
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "minHeight": "number",
            "store": "store",
            "title": "string",
            "viewModel": "string"
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
                    "dataIndex": "RoomNo",
                    "groupable": true,
                    "hidden": true,
                    "text": "Room No",
                    "width": 45
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto",
                    "groupable": "boolean",
                    "hidden": "boolean"
                },
                "name": "MyColumn31"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "dataIndex": "BedNo",
                    "summaryType": "count",
                    "text": "Bed No",
                    "width": 60
                },
                "configAlternates": {
                    "align": "string",
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto",
                    "summaryType": "string"
                },
                "name": "MyColumn32"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "dataIndex": "Sex",
                    "text": "Sex",
                    "width": 41
                },
                "configAlternates": {
                    "align": "string",
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyColumn33",
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
                                "if(value =='m'){",
                                "    return '<img src=../../gui/img/common/default/spm.gif>';",
                                "}else if(value=='f'){",
                                "    return '<img src=../../gui/img/common/default/spf.gif>';",
                                "}else{",
                                "    return '<img src=../../gui/img/common/default/plus2.gif>';",
                                "}",
                                "return value;"
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
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "Names",
                    "text": "Names",
                    "width": 187
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyColumn34"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "BirthDate",
                    "text": "Birth Date"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string"
                },
                "name": "MyColumn35"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "PID",
                    "text": "Pid"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string"
                },
                "name": "MyColumn36"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "AdmissionDate",
                    "text": "Admission Date"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string"
                },
                "name": "MyColumn37"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "PaymentMode",
                    "text": "Payment Mode",
                    "width": 207
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto"
                },
                "name": "MyColumn38"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "BillNumber",
                    "hidden": true,
                    "text": "EncounterNo",
                    "width": 207
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "width": "auto",
                    "hidden": "boolean"
                },
                "name": "MyColumn12"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "WardID",
                    "hidden": true,
                    "text": "Ward Id"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "hidden": "boolean"
                },
                "name": "MyColumn39"
            },
            {
                "type": "Ext.grid.column.Column",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dataIndex": "BillNumber",
                    "hidden": true,
                    "text": "BilNumber"
                },
                "configAlternates": {
                    "dataIndex": "datafield",
                    "text": "string",
                    "hidden": "boolean"
                },
                "name": "MyColumn"
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
                    "store": "OccupancyStore",
                    "width": 360
                },
                "configAlternates": {
                    "displayInfo": "boolean",
                    "dock": "string",
                    "store": "store",
                    "width": "auto"
                },
                "name": "MyPagingToolbar2"
            },
            {
                "type": "Ext.panel.Panel",
                "reference": {
                    "name": "dockedItems",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "bodyStyle": "background:#386d87",
                    "designer|snapToGrid": 5,
                    "dock": "top",
                    "height": 41,
                    "layout": "absolute",
                    "width": 100
                },
                "configAlternates": {
                    "dock": "string",
                    "fieldLabel": "string",
                    "height": "auto",
                    "width": "auto",
                    "designer|snapToGrid": "number",
                    "frame": "boolean",
                    "layout": "string",
                    "bodyStyle": "string"
                },
                "name": "MyPanel6",
                "cn": [
                    {
                        "type": "Ext.form.field.Display",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldStyle": "font-family: serif;     font-size: 20px;     font-weight: bold;     font-variant: normal;     color:white;",
                            "layout|x": 180,
                            "layout|y": -1,
                            "value": "Ward Occupancy",
                            "width": 182
                        },
                        "configAlternates": {
                            "fieldStyle": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "value": "string",
                            "width": "auto"
                        },
                        "name": "MyDisplayField7"
                    },
                    {
                        "type": "Ext.form.field.Display",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fieldStyle": "font-family: serif;     font-size: 20px;     font-weight: bold;     font-variant: normal;     color:white;",
                            "layout|x": 340,
                            "layout|y": 0,
                            "reference": "wardTitle",
                            "value": "Ward Occupancy",
                            "width": 490
                        },
                        "configAlternates": {
                            "fieldStyle": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "value": "string",
                            "width": "auto",
                            "reference": "string"
                        },
                        "name": "MyDisplayField11"
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "iconCls": "x-fa fa-home",
                            "layout|x": 5,
                            "layout|y": 5,
                            "reference": "wardsButton",
                            "text": "Wards list",
                            "textAlign": "left",
                            "width": 160
                        },
                        "configAlternates": {
                            "iconCls": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "reference": "string",
                            "text": "string",
                            "textAlign": "string",
                            "width": "auto"
                        },
                        "name": "MyButton8",
                        "cn": [
                            {
                                "type": "Ext.menu.Menu",
                                "reference": {
                                    "name": "menu",
                                    "type": "object"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "id": "wards-menu",
                                    "width": 200
                                },
                                "configAlternates": {
                                    "width": "auto",
                                    "id": "string"
                                },
                                "name": "MyMenu"
                            }
                        ]
                    },
                    {
                        "type": "Ext.button.Button",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "iconCls": "x-fa fa-arrow-down",
                            "layout|x": 945,
                            "layout|y": 5,
                            "text": "Waiting List",
                            "width": 220
                        },
                        "configAlternates": {
                            "iconCls": "string",
                            "layout|x": "number",
                            "layout|y": "number",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyButton11"
                    }
                ]
            },
            {
                "type": "Ext.grid.feature.Grouping",
                "reference": {
                    "name": "features",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "hideGroupedHeader": true,
                    "showSummaryRow": false
                },
                "configAlternates": {
                    "hideGroupedHeader": "boolean",
                    "showSummaryRow": "boolean"
                },
                "name": "MyGroupingFeature",
                "cn": [
                    {
                        "type": "Ext.XTemplate",
                        "reference": {
                            "name": "groupHeaderTpl",
                            "type": "object"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "designer|displayName": "groupHeaderTpl",
                            "implHandler": [
                                "{columnName}: {name}"
                            ]
                        },
                        "configAlternates": {
                            "designer|displayName": "string",
                            "implHandler": "code"
                        },
                        "name": "groupHeaderTpl"
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Registration",
                    "reference": "registration",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string",
                    "reference": "string"
                },
                "name": "MyActionColumn2",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Patien Registration",
                            "icon": "../../gui/img/common/default/pdata.gif",
                            "tooltip": "Patien Registration"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "view",
                                        "rowIndex",
                                        "colIndex",
                                        "item",
                                        "e",
                                        "record",
                                        "row"
                                    ],
                                    "designer|viewControllerFn": "openRegistration",
                                    "fn": "handler",
                                    "implHandler": [
                                        "//http://192.168.1.243/litein/modules/registration_admission/aufnahme_pass.php?sid=fdoueeor5klkqq32iq1irl8kh7&ntid=false&lang=en&target=search&fwd_nr=10472908",
                                        "var sid=\"?sid=fdoueeor5klkqq32iq1irl8kh7&ntid=false&lang=en\";",
                                        "var encNo=record.get('encounterNr');",
                                        "var url=\"../registration_admission/aufnahme_pass.php\"+sid+\"&target=search&fwd_nr=10472908\";// +encNo;",
                                        "",
                                        "window.location.href = url;"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "designer|viewControllerFn": "string",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "handler"
                            },
                            {
                                "type": "fixedfunction",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "designer|params": [
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "ChartFolder",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string"
                },
                "name": "MyActionColumn3",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Chart Folder",
                            "icon": "../../gui/img/common/default/open.gif",
                            "tooltip": "Chart Folder"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "view",
                                        "rowIndex",
                                        "colIndex",
                                        "item",
                                        "e",
                                        "record",
                                        "row"
                                    ],
                                    "designer|viewControllerFn": "openChartFolder",
                                    "fn": "handler",
                                    "implHandler": [
                                        "var sid=record.get('UrlAppend');",
                                        "var pn=record.get('EncounterNo');",
                                        "var ward=this.lookupReference('wardTitle').getValue();",
                                        "",
                                        "",
                                        "var urlholder=\"../nursing/nursing-station-patientdaten.php\"+sid+\"&pn=\" + pn + \"&pday=26&pmonth=10&pyear=2018&edit=1&station=\"+ward;",
                                        "patientwin=window.open(urlholder,pn,\"width=700,height=600,menubar=no,resizable=yes,scrollbars=yes\");"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "designer|viewControllerFn": "string",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "handler"
                            },
                            {
                                "type": "fixedfunction",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "designer|params": [
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        " if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Notes",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string"
                },
                "name": "MyActionColumn4",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Patient Notes",
                            "icon": "../../gui/img/common/default/bubble2.gif",
                            "tooltip": "Patient Notes"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Invoice",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string"
                },
                "name": "MyActionColumn8",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Patient Invoice",
                            "icon": "../../gui/img/common/default/show.gif",
                            "tooltip": "Patient Invoice"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            },
                            {
                                "type": "fixedfunction",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "designer|params": [
                                        "view",
                                        "rowIndex",
                                        "colIndex",
                                        "item",
                                        "e",
                                        "record",
                                        "row"
                                    ],
                                    "fn": "handler",
                                    "implHandler": [
                                        "var sid=record.get('UrlAppend');",
                                        "var pid=record.get('PID');",
                                        "var billNumber=record.get('BillNumber');",
                                        "",
                                        "",
                                        "var urlholder=\"../accounting/reports/detail_invoice_pdf.php?pid=\"+pid+\"&billNumber=\"+billNumber+\"&receipt=1\";",
                                        "invwin=window.open(urlholder,\"transwin<?php echo $sid ?>\",\"width=650,height=600,menubar=no,resizable=yes,scrollbars=yes\");"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "handler"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Vitals",
                    "id": "openVitals",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string",
                    "id": "string"
                },
                "name": "MyActionColumn5",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Patient Vitals",
                            "icon": "../../gui/img/common/default/chart.gif",
                            "tooltip": "Patient Vitals"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Transfer",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string"
                },
                "name": "MyActionColumn6",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Transfer Patient",
                            "icon": "../../gui/img/common/default/xchange.gif",
                            "tooltip": "Transfer Patient"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.grid.column.Action",
                "reference": {
                    "name": "columns",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "align": "center",
                    "designer|displayName": "Discharge",
                    "id": "discharge",
                    "width": 37
                },
                "configAlternates": {
                    "align": "string",
                    "width": "auto",
                    "designer|displayName": "string",
                    "id": "string"
                },
                "name": "MyActionColumn7",
                "cn": [
                    {
                        "type": "actioncolumnitem",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "altText": "Discharge",
                            "icon": "../../gui/img/common/default/bestell.gif",
                            "tooltip": "Discharge Patient"
                        },
                        "configAlternates": {
                            "altText": "string",
                            "icon": "string",
                            "tooltip": "string"
                        },
                        "name": "MyActionColumnItem1",
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
                                        "v",
                                        "metadata",
                                        "r",
                                        "rowIndex",
                                        "colIndex",
                                        "store"
                                    ],
                                    "fn": "getClass",
                                    "implHandler": [
                                        "if(r.get('PID') === '') {",
                                        "     return 'x-hidden-visibility';",
                                        " }"
                                    ]
                                },
                                "configAlternates": {
                                    "designer|params": "typedarray",
                                    "fn": "string",
                                    "implHandler": "code"
                                },
                                "name": "getClass"
                            }
                        ]
                    }
                ]
            },
            {
                "type": "Ext.container.Container",
                "reference": {
                    "name": "dockedItems",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "dock": "right",
                    "width": 202
                },
                "configAlternates": {
                    "dock": "string",
                    "width": "auto"
                },
                "name": "MyContainer3",
                "cn": [
                    {
                        "type": "Ext.panel.Panel",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "height": 203,
                            "itemId": "waitingList",
                            "reference": "waitingList",
                            "title": "Waiting List"
                        },
                        "configAlternates": {
                            "height": "auto",
                            "title": "string",
                            "itemId": "string",
                            "reference": "string"
                        },
                        "name": "MyPanel8"
                    },
                    {
                        "type": "Ext.panel.Panel",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "height": 203,
                            "title": "Quick Informer"
                        },
                        "configAlternates": {
                            "height": "auto",
                            "title": "string"
                        },
                        "name": "MyPanel7"
                    },
                    {
                        "type": "Ext.panel.Panel",
                        "reference": {
                            "name": "items",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "height": 233,
                            "title": "Legends"
                        },
                        "configAlternates": {
                            "height": "auto",
                            "title": "string"
                        },
                        "name": "MyPanel9"
                    }
                ]
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {
        "48a5bf14-e63f-412e-9ad9-31c40d44edb9": {
            "type": "jsonstore",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "autoLoad": false,
                "designer|userAlias": "occupancystore",
                "designer|userClassName": "OccupancyStore",
                "groupField": "RoomNo",
                "model": "OccupancyList",
                "pageSize": 500,
                "storeId": "OccupancyStore"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string",
                "model": "model",
                "pageSize": "number",
                "storeId": "string",
                "autoLoad": "boolean",
                "groupField": "datafield"
            },
            "name": "WardListStore1",
            "cn": [
                {
                    "type": "Ext.data.proxy.Ajax",
                    "reference": {
                        "name": "proxy",
                        "type": "object"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "url": "data/getDataFunctions.php?caller=getPatientsInWard"
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
                                "rootProperty": "occupancy"
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
        "17ef9587-d756-4e24-8d59-4db4e388fda4": {
            "type": "Ext.data.Model",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "occupancylist",
                "designer|userClassName": "OccupancyList"
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
                        "name": "RoomNo"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField46"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "BedNo"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField47"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Sex"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField48"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "Names"
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
                        "name": "BirthDate"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField49"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "PID"
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
                        "name": "AdmissionDate"
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
                        "name": "PaymentMode"
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
                        "name": "WardID"
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
                        "name": "EncounterNo"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField28"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "UrlAppend"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyField29"
                },
                {
                    "type": "Ext.data.field.Field",
                    "reference": {
                        "name": "fields",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "BillNumber"
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
                "designer|userAlias": "wardoccupancy",
                "designer|userClassName": "WardOccupancyViewController"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "cn": [
                {
                    "type": "basicfunction",
                    "reference": {
                        "name": "items",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "designer|params": [
                            "item"
                        ],
                        "fn": "onWardsItemClick",
                        "implHandler": [
                            "var view = this.getView();",
                            "var wardsMenu = this.lookupReference('wardsButton').menu;",
                            "",
                            "//Ext.Msg.alert('Test',item.value);",
                            "",
                            "view.store.load({",
                            "    params:{",
                            "        wardId:item.value",
                            "    }",
                            "});",
                            "",
                            "var d = new Date();",
                            "this.lookupReference('wardTitle').setValue(item.text + \"(\"+ d.toLocaleString() +\")\");",
                            "",
                            "this.getWaitingList(item.value);",
                            ""
                        ]
                    },
                    "configAlternates": {
                        "fn": "string",
                        "implHandler": "code",
                        "designer|params": "typedarray"
                    },
                    "name": "onWardsItemClick"
                },
                {
                    "type": "basicfunction",
                    "reference": {
                        "name": "items",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "designer|params": [
                            "wrdNr"
                        ],
                        "fn": "getWaitingList",
                        "implHandler": [
                            "//vitalsPanel = Ext.create('CarePortal.view.Vitals', {});",
                            "//waitingListPanel=this.getWardoccupancy().down(\"#waitingList\");",
                            "waitingListPanel=this.lookupReference('waitingList');",
                            "",
                            "",
                            "var waitinStore=Ext.data.StoreManager.lookup(\"WaitingListStore\");",
                            "",
                            "waitinStore.load({",
                            "    params: {",
                            "        wardNo:wrdNr",
                            "    },",
                            "    callback: function(records, operation, success) {",
                            "            var tpl=new Ext.XTemplate(",
                            "                '<Table id=Waitinglist>',",
                            "               '<tpl for=\"patients\">',",
                            "                '<tr>',",
                            "                '<td>{Pid}</td>',",
                            "                '<td>{Names}</td>',",
                            "                '</tpl>',",
                            "                '</table>'",
                            "            );",
                            "",
                            "        tpl.overwrite(waitingListPanel.body,records);",
                            "    },",
                            "    scope: this",
                            "});",
                            ""
                        ]
                    },
                    "configAlternates": {
                        "designer|params": "typedarray",
                        "fn": "string",
                        "implHandler": "code"
                    },
                    "name": "getWaitingList"
                }
            ]
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
                "designer|userAlias": "wardoccupancy",
                "designer|userClassName": "WardOccupancyViewModel"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "cn": [
                {
                    "type": "jsonstore",
                    "reference": {
                        "name": "stores",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "autoLoad": true,
                        "model": "Wards",
                        "name": "wardsListStore"
                    },
                    "configAlternates": {
                        "autoLoad": "boolean",
                        "model": "model",
                        "name": "string"
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
                                "url": "data/getDataFunctions.php?caller=getWardInfo"
                            },
                            "configAlternates": {
                                "url": "string"
                            },
                            "name": "MyAjaxProxy1",
                            "cn": [
                                {
                                    "type": "Ext.data.reader.Json",
                                    "reference": {
                                        "name": "reader",
                                        "type": "object"
                                    },
                                    "codeClass": null,
                                    "userConfig": {
                                        "rootProperty": "wards"
                                    },
                                    "configAlternates": {
                                        "rootProperty": "string"
                                    },
                                    "name": "MyJsonReader1"
                                }
                            ]
                        },
                        {
                            "type": "viewcontrollereventbinding",
                            "reference": {
                                "name": "listeners",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "fn": "onWardsListLoad",
                                "implHandler": [
                                    "//Ext.Msg.alert('Test','Test');",
                                    "var me = this,",
                                    "    references = me.getReferences(),",
                                    "    view = me.getView(),",
                                    "    items = [],",
                                    "    columns = [ view.nameColumn ];",
                                    "",
                                    "",
                                    "   // iterate each record in the store",
                                    "store.each(function (metaRecord) {",
                                    "    items.push(Ext.apply({",
                                    "        text  : metaRecord.data.Name,",
                                    "        value : metaRecord.data.Nr,",
                                    "        listeners : {",
                                    "            click : me.onWardsItemClick,",
                                    "            scope : me",
                                    "        }",
                                    "    }));",
                                    "",
                                    "});",
                                    " references.wardsButton.menu.add(items);",
                                    ""
                                ],
                                "name": "load",
                                "scope": "me"
                            },
                            "configAlternates": {
                                "fn": "string",
                                "implHandler": "code",
                                "name": "string",
                                "scope": "string"
                            },
                            "name": "onWardsListLoad"
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