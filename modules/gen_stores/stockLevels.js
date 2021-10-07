
// turn on validation errors beside the field globally
Ext.form.Field.prototype.msgTarget = 'side';

var fm = Ext.form;
var primaryKey='item_number'; //primary key is used several times throughout
// custom column plugin example
var selectedKeys;
var url = {
    local:  'getDrugs.php',  // static data file
    remote: 'getDrugs.php'
};
// configure whether filter query is encoded or not (initially)
var encode = false;

// configure whether filtering is performed locally or remotely (initially)
var local = true;

var itemCat=new Ext.data.JsonStore({
    url: 'getDrugs.php',
    root: 'itemsCat',
    id: 'catID',//
    baseParams:{
        task: "getCat2"
    },//This
    fields:['purchasing_class']
});

itemCat.load()

var itemsCats = new Ext.form.ComboBox({
    typeAhead: true,
    id:'purchasing_class',
    name:"purchasing_class",
    triggerAction: 'all',
    lazyRender:true,
    mode: 'local2',
    fieldLabel:'Item Category',
    store: itemCat,
    valueField: 'purchasing_class',
    displayField: 'purchasing_class'
});

//combo for the grid filter
//===========================================================================================================
var itemCat2=new Ext.data.JsonStore({
    url: 'getDrugs.php',
    root: 'itemsCat',
    id: 'catID',//
    baseParams:{
        task: "getCat2"
    },//This
    fields:['purchasing_class']
});

itemCat2.load()

var itemsCats2 = new Ext.form.ComboBox({
    typeAhead: true,
    id:'purchasing_class2',
    name:"purchasing_class2",
    triggerAction: 'all',
    lazyRender:true,
    mode: 'local2',
    fieldLabel:'Item Category',
    store: itemCat2,
    valueField: 'purchasing_class',
    displayField: 'purchasing_class',
    listeners: {
        select: function(f,r,i){
           categorySelectedId = this.getValue();
            itemsReader.load({
                params:{
                    catID:categorySelectedId
                }
            });

        }
    }
});
//end of combo to filter grid
//===================================================================================



var itemsReader=new Ext.data.JsonStore({
    url: 'getDrugs.php',
    root: 'Items',
    totalProperty: 'Total',
    baseParams:{
        task: "getLevels",
        catID:itemsCats2.getValue(),
        start: 0,
        limit: 40
    },//This
    fields:['item_number','loccode','item_Description','quantity', 'reorderlevel','item_cat','comment']
});
/*    Here is where we create the Form
                 */
itemsReader.load();
var filters = new Ext.ux.grid.GridFilters({
    // encode and local configuration options defined previously for easier reuse
    encode: encode, // json encode the filter query
    local: local,   // defaults to false (remote filtering)
    filters: [{
        type: 'string',
        dataIndex: 'item_number'
    },{
        type: 'string',
        dataIndex: 'loccode'
    },{
        type: 'string',
        dataIndex: 'item_Description'
    },{
        type: 'string',
        dataIndex: 'quantity'
    },{
        type: 'string',
        dataIndex: 'reorderlevel'
    },{
        type: 'string',
        dataIndex: 'item_cat'
    }, {
        type: 'string',
        dataIndex: 'comment'
    }]
});

//'item_id','item_number', 'partcode','item_description', 'item_description', 'unit_price','purchasing_class'
var itemsColModel=function(finish,start){
    var columns = [
    {
        id:'item_number',
        header: "item_number",
        width: 80,
        sortable: true,
        dataIndex: 'item_number',
        filterable: true

    },
    {
        header: "Store",
        width: 80,
        sortable: true,
        dataIndex: 'loccode',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Description",
        width: 200,
        sortable: true,
        dataIndex: 'item_Description',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "quantity",
        width: 80,
        sortable: true,
        dataIndex: 'quantity',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "reorderlevel",
        width: 80,
        sortable: true,
        dataIndex: 'reorderlevel',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "item_cat",
        width: 200,
        sortable: true,
        dataIndex: 'item_cat',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "comment",
        width: 120,
        sortable: true,
        dataIndex: 'comment',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    }
    ];
    return new Ext.grid.ColumnModel({
        columns: columns.slice(start || 0, finish),
        defaults: {
            sortable: true
        }
    });
}



var itemsGridForm = new Ext.FormPanel({
    id: 'itemList',
    name:'itemList',
    frame: true,
    labelAlign: 'left',
    //                    title: 'Items List',
    bodyStyle:'padding:5px',
    width: 1200,
    height:650,
    //                    record: null,
    //                    applyTo:'itemList',
    layout: 'column',    // Specifies that the items will now be arranged in columns
    items: [{
        columnWidth: 0.65,
        layout: 'fit',
        items: {
            xtype: 'grid',
            id:'grid',
            store: itemsReader,
            colModel: itemsColModel(7),
            tbar: [{
                tag: 'input',
                type: 'text',
                size: '30',
                value: '',
                id:'searchParam',
                style: 'background: #F0F0F9;'
            },{
                text: 'Search',
                iconCls:'remove',
                handler: function () {
                    var sParam=document.getElementById('searchParam').value;
                    //                   Ext.MessageBox.alert('Message', sParam);
                    itemsReader.load({
                        params:{
                            itemName:sParam,
                            start: 0,
                            limit: 100
                        }
                    });
                }
            },itemsCats2, '->', // next fields will be aligned to the right
            {
                text: 'Refresh',
                tooltip: 'Click to Refresh the table',
                handler: refreshGrid,
                iconCls:'refresh'
            }, '-',{
                text: 'Clear Filter Data',
                handler: function () {
                    grid.filters.clearFilters();
                }
            }, '-',{
                text: 'Delete Item',
                handler :handleDelItem

            },{
                text: 'print Grid',
                handler :function(){
                    Ext.ux.Printer.print(grid);
                }

            },{
                xtype: 'exportbutton',
                store: itemsReader

            }],
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                    rowselect: function(sm, row, rec) {
                        Ext.getCmp("itemList").getForm().loadRecord(rec);
                    }
                }
            }),
            //                autoExpandColumn: 'FirstName',
            height: 500,
            //                                title:'Items Data',
            border: true,
            listeners: {
                render: {
                    fn: function(g){
                        itemsReader.load({
                            params: {
                        //start: 0,
                        //limit: 20
                        }
                        });
                        g.getSelectionModel().selectRow(0);
                            delay: 10
                    }

                }
            // Allow rows to be rendered.
            },

            plugins: [filters],
            bbar: new Ext.PagingToolbar({
                store: itemsReader,
                pageSize: 40,
                plugins: [filters]

            })

        }
    },{
        columnWidth: 0.35,
        xtype: 'fieldset',
        title:'Items details',
        autoHeight: true,
        bodyStyle: Ext.isIE ? 'padding:0 0 5px 15px;' : 'padding:10px 15px;',
        border: false,
        style: {
            "margin-left": "10px", // when you add custom margin in IE 6...
            "margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0"  // you have to adjust for it somewhere else
        },
        items: [{//'item_id','item_number', 'partcode','item_description', 'item_description', 'unit_price','purchasing_class'
            xtype: 'textfield',
            fieldLabel: 'item_number',
            name: 'item_number'
        },{
            xtype: 'textfield',
            fieldLabel: 'loccode',
            name: 'loccode'
        },{
            xtype: 'textfield',
            fieldLabel: 'Description',
            name: 'item_Description'
        },{
            xtype: 'textfield',
            fieldLabel: 'Quantity',
            name: 'quantity'
        },{
            xtype: 'textfield',
            fieldLabel: 'reorderlevel',
            name: 'reorderlevel'
        },{
            xtype: 'textfield',
            fieldLabel: 'Unit item_cat',
            name: 'item_cat'
        },{
            xtype: 'textfield',
            fieldLabel: 'comment',
            name: 'comment'
        }],
        buttons: [{
            text: 'Save Stock Adjustment',
            iconCls:'icon-save',
            handler: function(){
                itemsGridForm.form.submit({
                    url:'getDrugs.php?task=adjustStock', //php
                    baseParams:{
                        task: "adjustStock"
                    },
                    waitMsg:'Saving Data...',
                    success: function (form, action) {
                        Ext.MessageBox.alert('Message', 'Stock Adjustment Successful');

                    },
                    failure:function(form, action) {
                        Ext.MessageBox.alert('Message', 'Save failed, Check that all values are OK ');
                    }
                })
            //                refreshGrid();
            }
        }]//,

    }]

});

function handleDelItem() {

    //returns array of selected rows ids only
    //    var selectedKeys = grid.selModel.selections.keys;
    var sItem=itemsGridForm.getForm().findField('item_id').value
    if(sItem.length > 0)
    {
        Ext.MessageBox.confirm('Message','Do you really want to Remove the Item?', deleteItem);
    }
    else
    {
        Ext.MessageBox.alert('Message','Please select at least one item to delete');
    }//end if/else block
} // end

function deleteItem(btn) {
    var sItem=itemsGridForm.getForm().findField('item_id').value
    if(btn=='yes')
    {
        //submit to server
        Ext.Ajax.request( //alternative to Ext.form.FormPanel? or Ext.BasicForm.submit
        {   //specify options (note success/failure below that receives these same options)
            waitMsg: 'Saving changes...',
            //url where to send request (url to server side script)
            url: 'getDrugs.php',
            //params will be available via $_POST or $_REQUEST:
            params: {
                task: "deleteItem", //pass task to do to the server script
                ID: sItem,//the unique id(s)
                key: sItem//pass to server same 'id' that the reader used
            },
            callback: function (options, success, response) {
                if (success) { //success will be true if the request succeeded
                    Ext.MessageBox.alert('OK',response.responseText);//you won't see this alert if the next one pops up fast
                    var json = Ext.util.JSON.decode(response.responseText);
                    Ext.MessageBox.alert('OK',' Item diactivated successfully.');
                } else{
                    Ext.MessageBox.alert('Sorry, please try again. [Q304]',response.responseText);
                }
            },

            //the function to be called upon failure of the request (server script, 404, or 403 errors)
            failure:function(response,options){
                Ext.MessageBox.alert('Warning','Oops...');
            //ds.rejectChanges();//undo any changes
            },
            success:function(response,options){
                //Ext.MessageBox.alert('Success','Yeah...');
                //commit changes and remove the red triangle which
                //indicates a 'dirty' field
                itemsReader.reload();
            }
        } //end Ajax request config
        )// end Ajax request initialization
    }//end if click 'yes' on button
}// end deleteRecord


function refreshGrid() {
    itemsReader.reload();//
} // end refresh

//                itemReader.load();
                //  Create Panel view code. Ignore.
                //    createCodePanel('form-grid.js', 'View code to create this Form');
