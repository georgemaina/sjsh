/*!
 * Ext JS Library 3.1.1
 * Copyright(c) 2006-2010 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
 // this will be our datastore
Ext.onReady(function(){

    // create the Data Store
    var InvoicesDataStore = new Ext.data.Store( {
		id : 'InvoicesDataStore',
		proxy : new Ext.data.HttpProxy( {
			url : 'pendingInvoices.php', // file to connect to
			method : 'POST'
		}),
		baseParams : {
			task : 'LISTINGS'
		}, // this parameter asks for listing
		reader : new Ext.data.JsonReader( {
			// we tell the datastore where to get his data from
			root : 'results',
			totalProperty : 'total',
			id : 'id'
		}, [ {
			name : 'PID',
			type : 'string',
			mapping : 'pid'
		}, {
			name : 'First_Name',
			type : 'string',
			mapping : 'name_first'
		}, {
			name : 'Last_Name',
			type : 'string',
			mapping : 'name_last'
		}, {
			name : 'Surname',
			type : 'string',
			mapping : 'name_2'
		}, {
			name : 'Total',
			type : 'string',
			mapping : 'total'
		}, {
			name : 'Bill_Number',
			type : 'string',
			mapping : 'bill_number'
		}])
	});

//Column model
InvoicesColumnModel = new Ext.grid.ColumnModel( [ {
		header : 'Pid',
		readOnly : true,
		dataIndex : 'PID',// this is where the mapped name is important
		width : 100,
		renderer: function(value, cell){
        	cell.css = "readonlycell";
        	return value;
			},
		hidden : false
	}, {
		header :'First Name',
		dataIndex: 'First_Name',
		width: 150,
		editor: new Ext.form.TextField({
			allowBlank: false,
			maxLength: 20,
			maskRe: /([a-zA-Z0-9\s]+)$/   // alphanumeric + spaces allowed
		})
	},{
		header : 'Last Name',
		dataIndex: 'Last_Name',
		width : 150,
		editor: new Ext.form.TextField({
			allowBlank: false,
			maxLength: 20,
			maskRe: /([a-zA-Z0-9\s]+)$/   // alphanumeric + spaces allowed
		})
	},{
        header: 'Surname',
        readOnly: true,
        dataIndex: 'Surname',
        width: 150,
        renderer: function(value, cell){
        	cell.css = "readonlycell";
        	return value;
       		},
        hidden: false                      // we don't necessarily want to see this...
      },{
        header: 'bill number',
        dataIndex: 'Bill_Number',
        width: 100
	},{
        header: "Total",
        dataIndex: 'Total',
        width: 100,
        renderer: function(value, cell){
            var str = '';
            if(value > 1000){
               str = "<span style='color:#336600;'>Ksh. " + value + "</span>";
            } else if (value > 2000 && value < 5000){
               str = "<span style='color:#FF9900;'>Ksh. " + value + "</span>";
            } else {
               str = "<span style='color:#CC0000;'>Ksh. " + value + "</span>";
            }
            return str;
           }

      }]
    );
    
    InvoicesColumnModel.defaultSortable= true;
    // create the grid
    var grid = new Ext.grid.GridPanel({
        store: InvoicesDataStore,
        cm: InvoicesColumnModel,
		sm: new Ext.grid.RowSelectionModel({singleSelect: true}),
		viewConfig: {
			forceFit: true
		},
        width:540,
        height:300,
		split: true,
		region: 'north'
    });

	// define a template to use for the detail view
	var bookTplMarkup = [
		'Title: <a href="{DetailPageURL}" target="_blank">{Title}</a><br/>',
		'Author: {Author}<br/>',
		'Manufacturer: {Manufacturer}<br/>',
		'Product Group: {ProductGroup}<br/>'
	];
	var bookTpl = new Ext.Template(bookTplMarkup);

	var ct = new Ext.Panel({
		renderTo: 'binding-example',
		frame: true,
		title: 'Pending Invoice List',
		width: 1000,
		height: 500,
		layout: 'border',
		items: [
			grid,
			{
				id: 'detailPanel',
				region: 'center',
				bodyStyle: {
					background: '#ffffff',
					padding: '20px'
				},
				html: 'Please select an Invoice to see additional details.'
			}
		]
	})
	grid.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		var detailPanel = Ext.getCmp('detailPanel');
		bookTpl.overwrite(detailPanel.body, r.data);
	});
    InvoicesDataStore.load();
    //PresidentListingWindow.show();

    FirstNameField = new Ext.form.TextField({
        id: 'FirstName',
        fieldLabel: 'First Name',
        maxLength: 20,
        allowBlank: false,
        anchor : '95%',
        maskRe: /([a-zA-Z0-9\s]+)$/
          });

      LastNameField = new Ext.form.TextField({
        id: 'lastname',
        fieldLabel: 'Last Name',
        maxLength: 20,
        allowBlank: false,
        anchor : '95%',
        maskRe: /([a-zA-Z0-9\s]+)$/
          });

      EnteringOfficeField = new Ext.form.DateField({
        id:'name_2',
        fieldLabel: 'Surname',
        format : 'm/d/Y',
        allowBlank: false,
        anchor:'95%',
         maskRe: /([a-zA-Z0-9\s]+)$/  
        });

      LeavingOfficeField = new Ext.form.DateField({
        id:'bill_number',
        fieldLabel: 'Bill_Number',
        allowBlank: false,
        anchor:'95%'
        });

      IncomeField = new Ext.form.NumberField({
        id:'Total',
        fieldLabel: 'total',
        allowNegative: false,
        allowBlank: false,
        anchor:'95%'
        });

});