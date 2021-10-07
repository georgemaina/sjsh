
// turn on validation errors beside the field globally
Ext.form.Field.prototype.msgTarget = 'side';

var fm = Ext.form;
var primaryKey='item_id'; //primary key is used several times throughout
// custom column plugin example
var selectedKeys;
var url = {
    local:  'return.php',  // static data file
    remote: 'return.php'
};
// configure whether filter query is encoded or not (initially)
var encode = false;

// configure whether filtering is performed locally or remotely (initially)
var local = true;

var itemsReader=new Ext.data.JsonStore({
    url: 'return.php',
    root: 'Items',
    totalProperty: 'Total',
    baseParams:{
        task: "getList",
        catID:itemsCats2.getValue(),
        start: 0,
        limit: 100
    },//This
    fields:['item_number','desc', 'cost','qty','total']
});
/*    Here is where we create the Form
                 */
itemsReader.load();


//'item_id','item_number', 'partcode','item_description', 'item_description', 'unit_price','purchasing_class'
var itemsColModel=function(finish,start){
    var columns = [
    {
        header: "item_number",
        width: 80,
        sortable: true,
        dataIndex: 'item_number',
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
        dataIndex: 'desc',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Cost",
        width: 80,
        sortable: true,
        dataIndex: 'cost',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Quantitity",
        width: 200,
        sortable: true,
        dataIndex: 'qty',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Total",
        width: 80,
        sortable: true,
        dataIndex: 'total',
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



var itemsGridForm=new Ext.grid.EditorGridPanel({
    title: 'Returns from Patients',
    store: itemsReader,
    columns:itemsColModel,
    frame:true,
    minWidth:400,
    height:350,
    collapsible:true,
    clicksToEdit: 2,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect:false
    }),
    stripeRows: true

});


function refreshGrid() {
    itemsReader.reload();//
} // end refresh
