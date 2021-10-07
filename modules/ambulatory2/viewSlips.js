
// turn on validation errors beside the field globally
Ext.form.Field.prototype.msgTarget = 'side';
Ext.BLANK_IMAGE_URL="../../include/Extjs/resources/images/default/s.gif";
 Ext.onReady(function(){
     
 
var fm = Ext.form;
var primaryKey='id'; //primary key is used several times throughout
// custom column plugin example
var selectedKeys;
var url = {
    local:  'getSlips.php',  // static data file
    remote: 'getSlips.php'
};
// configure whether filter query is encoded or not (initially)
var encode = false;

// configure whether filtering is performed locally or remotely (initially)
var local = true;



var itemsReader=new Ext.data.JsonStore({
    url: 'getSlips.php',
    root: 'slips',
    totalProperty: 'Total',
    autoLoad:true,
    baseParams:{
        task: "getSlips",
        start: 0,
        limit: 4000
    },//This
    fields:['id','pid','slipNo', 'slipDate','Names','accNo','accName']
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
        dataIndex: 'pid'
    },{
        type: 'string',
        dataIndex: 'slipDate'
    },{
        type: 'string',
        dataIndex: 'Names'
    }]
});

//'item_id','item_number', 'partcode','item_description', 'item_description', 'unit_price','purchasing_class'
var itemsColModel=function(finish,start){
    var columns = [
    {
        id:'id',
        header: "ID",
        width: 55,
        sortable: true,
        dataIndex: 'id',
        filterable: true
        

    },
    {
        id:'pid',
        header: "pid",
        width: 80,
        sortable: true,
        dataIndex: 'pid',
        filterable: true
        

    },
    {
        header: "slip No",
        width: 80,
        sortable: true,
        dataIndex: 'slipNo',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Slip Date",
        width: 80,
        sortable: true,
        dataIndex: 'slipDate',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Names",
        width: 200,
        sortable: true,
        dataIndex: 'Names',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Account ID",
        width: 200,
        sortable: true,
        dataIndex: 'accNo',
        filter: {
            type: 'string'
        // specify disabled to disable the filter menu
        //, disabled: true
        }
    },
    {
        header: "Account",
        width: 200,
        sortable: true,
        dataIndex: 'accName',
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



var slipsGridForm = new Ext.grid.GridPanel({
    id: 'slipsGridForm',
    name:'slipsGridForm',
    frame: true,
    labelAlign: 'left',
    store: itemsReader,
    colModel: itemsColModel(9),
    bodyStyle:'padding:5px',
    width: 800,
    height:500,
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
                    sparam:sParam,
                    start: 0,
                    limit: 4000
                }
            });
        }
    },'->', // next fields will be aligned to the right
    {
        text: 'Refresh',
        tooltip: 'Click to Refresh the table',
        handler: refreshGrid,
        iconCls:'refresh'
    },{
        xtype: 'exportbutton',
        store: itemsReader

    }, '-',{
        text: 'Clear Filter Data',
        handler: function () {
            grid.filters.clearFilters();
        }
    }, '-',{
        text: 'Reprint Slip',
        handler: function () {
             var gridrecord = slipsGridForm.getSelectionModel().getSelected();
             if (gridrecord == undefined){
                    alert('Please select a record');
                }else{
                     var strPid=gridrecord.get('pid');
                     var strSlip=gridrecord.get('slipNo');
                     var slipDate=gridrecord.get('slipDate');
                    reprintSlip(strPid,strSlip,slipDate);
                }
        }},],

    border: true,
    listeners: {
        render: {
            fn: function(g){
                itemsReader.load({
                    params: {
                start: 0,
                limit: 200
                }
                });
                g.getSelectionModel().selectRow(0);
                    delay: 10
            }

        }
    // Allow rows to be rendered.
    },

    view: new Ext.ux.grid.BufferView({
        // custom row height
        rowHeight: 20,
        // render rows as they come into viewable area.
        scrollDelay: false
    })
    ,
    plugins: [filters],
    bbar: new Ext.PagingToolbar({
        store: itemsReader,
        pageSize: 200,
        plugins: [filters]

    })

});




function refreshGrid() {
    itemsReader.reload();//
} // end refresh


function reprintSlip(pid,slipNO,slipDate) {
    //window.open('http://localhost:82/receipt/slip.php?enc_no='+pid+'&slipno='+slipNO+'&slipdate='+slipDate+'&reprint=1'
    //,"Label","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
    
    window.open('reports/newcreditslip.php?pid='+pid+'&slipNo='+slipNO+'&reprint=1'
            ,"receipt","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
}

slipsGridForm.render('itemList');
 })
