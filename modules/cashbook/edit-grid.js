/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){
    Ext.QuickTips.init();

    function formatDate(value){
        return value ? value.dateFormat('M d, Y') : '';
    }
    // shorthand alias
    var fm = Ext.form;

    // custom column plugin example
    var checkColumn = new Ext.grid.CheckColumn({
       header: 'Indoor?',
       dataIndex: 'indoor',
       width: 55
    });

    // the column model has information about grid columns
    // dataIndex maps the column to the specific data field in
    // the data store (created below)
    var cm = new Ext.grid.ColumnModel([{
           id: 'rev_code',
           header: 'Revenue code',
           dataIndex: 'rev_code',
           width: 220,
           // use shorthand alias defined above
           editor: new fm.TextField({
               allowBlank: false
           })
        },{
           header: 'rev_Desc',
           dataIndex: 'rev_desc',
           width: 130,
           editor: new fm.ComboBox({
               typeAhead: true,
               triggerAction: 'all',
               transform:'light',
               lazyRender: true,
               listClass: 'x-combo-list-small'
            })
        },{
           header: 'Proc_code',
           dataIndex: 'Procedure Code',
           width: 70,
           align: 'right',
           renderer: 'usMoney',
           editor: new fm.NumberField({
               allowBlank: false,
               allowNegative: false,
               maxValue: 100000
           })
        },{
           header: 'proc_desc',
           dataIndex: 'Description',
           width: 95,
           renderer: formatDate,
           editor: new fm.DateField({
                format: 'm/d/y',
                minValue: '01/01/06',
                disabledDays: [0, 6],
                disabledDaysText: 'Plants are not available on the weekends'
            })
        },{
           header: 'Amount',
           dataIndex: 'Amount',
           width: 95,
           renderer: formatDate,
           editor: new fm.DateField({
                format: 'm/d/y',
                minValue: '01/01/06',
                disabledDays: [0, 6],
                disabledDaysText: 'Plants are not available on the weekends'
            })
        },
        checkColumn
    ]);

    // by default columns are sortable
    cm.defaultSortable = true;

    // create the Data Store
    var store = new Ext.data.Store({
        // load remote data using HTTP
        url: 'plants.xml',

        // specify a XmlReader (coincides with the XML format of the returned data)
        reader: new Ext.data.XmlReader(
            {
                // records will have a 'plant' tag
                record: 'plant'
            },
            // use an Array of field definition objects to implicitly create a Record constructor
            [
                // the 'name' below matches the tag name to read, except 'availDate'
                // which is mapped to the tag 'availability'
                {name: 'rev_code', type: 'string'},
                {name: 'rev_desc', type: 'string'},
                {name: 'proc_code'},
                {name: 'description', type: 'float'},
                // dates can be automatically converted by specifying dateFormat
                {name: 'availDate', mapping: 'availability', type: 'date', dateFormat: 'm/d/Y'},
                {name: 'indoor', type: 'bool'}
            ]
        ),

        sortInfo: {field:'common', direction:'ASC'}
    });

    // create the editor grid
    var grid = new Ext.grid.EditorGridPanel({
        store: store,
        cm: cm,
        renderTo: 'editor-grid',
        width: 800,
        height: 250,
        autoExpandColumn: 'common',
        title: 'Edit Sale details?',
        frame: false,
        plugins: checkColumn,
        clicksToEdit: 1,
        tbar: [{
            text: 'Add Sale',
            handler : function(){
                // access the Record constructor through the grid's store
                var Plant = grid.getStore().recordType;
                var p = new Plant({
                    common: 'New Plant 1',
                    light: 'Mostly Shade',
                    price: 0,
                    availDate: (new Date()).clearTime(),
                    indoor: false
                });
                grid.stopEditing();
                store.insert(0, p);
                grid.startEditing(0, 0);
            }
        }]
    });

    // trigger the data store load
    store.load();
});