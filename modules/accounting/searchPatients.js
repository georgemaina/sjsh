/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '../ux/');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.form.field.Number',
    'Ext.form.field.Date',
    'Ext.tip.QuickTipManager'
]);

Ext.onReady(function(){
    /**
     * Returns an array of fake data
     * @param {Number} count The number of fake rows to create data for
     * @return {Array} The fake record data, suitable for usage with an ArrayReader
     */
    

Ext.define(
        'opVisits',{
            extend:'Ext.data.Model',
            fields:[{
                name:'PID',
                type:'string'
            },
            {
                name:'selian_pid',
                type:'string'
            },{
                name:'name_first',
                type:'string'
            },
            {
                name:'name_last',
                type:'string'
            },
            {
                name:'name_2',
                type:'string'
            },
            {
                name:'addr_zip',
                type:'string'
            }]
        });


    // create the Data Store

var vStore=new Ext.data.Store({
        id:'store',
        model:'sPatients',
        groupField: 'parent',
        pageSize: 50,
        purgePageCount: 0,
        proxy:{
            type:'ajax',
            url: 'getReports.php?task=opVisits',
            reader:{
                type:'json',
                root:'opVisits',
                param:{
                    task:'opVisits'
                }
            }
        },
        autoLoad:true
    });



    var grid = Ext.create('Ext.grid.Panel', {
        width: 700,
        height: 500,
        title: 'Bufffered Grid of 5,000 random records',
        store: vStore,
        verticalScroller: {
            xtype: 'paginggridscroller',
            activePrefetch: false
        },
        loadMask: true,
        disableSelection: true,
        invalidateScrollerOnRefresh: false,
        viewConfig: {
            trackOver: false
        },
        // grid columns
        columns:[{
            xtype: 'rownumberer',
            width: 40,
            sortable: false
        },{
            text: 'PID',
            flex:1 ,
            sortable: true,
            dataIndex: 'pid'
        },{
            text: 'File No',
            width: 125,
            sortable: true,
            dataIndex: 'selian_pid'
        },{
            text: 'First Name',
            width: 125,
            sortable: true,
            dataIndex: 'name_first',
            align: 'right'
        },{
            text: 'Last Name',
            width: 125,
            sortable: true,
            dataIndex: 'name_last',
            align: 'right'
        },{
            text: 'Surname',
            width: 125,
            sortable: true,
            dataIndex: 'name_2',
            align: 'right'
        },{
            text: 'Address',
            width: 125,
            sortable: true,
            dataIndex: 'addr_zip',
            align: 'right'
        }],
        renderTo: 'opVisits'
//        renderTo: Ext.getBody()
    });

 
    store.cacheRecords(records);

    store.guaranteeRange(0, 49);
});


