/* Copyright 2007 You may not modify, use, reproduce, or distribute this software except in compliance with the terms of the License at:
 http://developer.sun.com/berkeley_license.html
 $Id: component.js,v 1.0 2007/04/15 19:39:59 gmurray71 Exp $
*/

dojo.require("dojox.grid.DataGrid");
dojo.require("dojo.data.ItemFileWriteStore");

jmaki.namespace("jmaki.widgets.dojo.dijit.grid");

/*
 * A wrapper for the Dojo Dijit Grid Component
 * 
 * @author Carla Mott
 * @author Greg Murray
 */
jmaki.widgets.dojo.dijit.grid.Widget = function (wargs) {

    var _widget = this;
    var columns = [];

    var uuid = wargs.uuid;
    var topic = "/dojo/dijit/grid";
    var subscribe = ["/dojo/dijit/grid", "/table"];
    var filter = "jmaki.filters.tableModelFilter";
    
    _widget.container = document.getElementById(uuid);

    var table;
    var counter = 0;   
    var wrapper;
    var store;
    var autoWidth = false;
    var autoHeight = '';
    
    function genId() {
        return wargs.uuid + "_nid_" + counter++;
    }
       
    // private funciton to initialize the widget
   function init() {
	   
	    if (document.body) {
	    	if (!/tundra/i.test(document.body.className)){
	    		document.body.className += " tundra";
	    	}
	    }   
	   
	    var dim = jmaki.getDimensions(_widget.container.parentNode, 50);
	    var h = dim.h;
	    if (h == 0) h = 400;
	    _widget.container.style.height = h  + "px"; 
	    _widget.container.style.width = dim.w  + "px";  	 	   
  
        var subrow = [];
        if (columns) {
           for (var i=0; i< columns.length; i++) {
               var c = columns[i].label;
               var id = columns[i].id;
               var _width = 'auto';
               if (columns[i].width) _width = columns[i].width;
               var _editable = false;
               if (typeof columns[i].editable == 'boolean') {
            	   _editable = columns[i].editable;
               }
               subrow[i] = {name: c, field: id, width : _width, editable : _editable};
           }
        }   
        // set the view per Dojo's grid widget
        
        var view = {
            rows: [ subrow ]
        };

        var _structure = [
            view
        ];
        
        // make sure all rows have an id
        for (var i=0; _widget.rows && i < _widget.rows.length; i++) {
        	if (!_widget.rows[i].id) _widget.rows[i].id = genId();
        }

         var container = document.getElementById(uuid);
         model = {
 		     identifier: 'id',
		     label: 'id',
		     items : _widget.rows
         };

         if (typeof _widget.rows != "undefined") {
        	 store = new dojo.data.ItemFileWriteStore({data: model});  
             wrapper = new dojox.grid.DataGrid({
 	                                id: uuid +"_widget",
 	                               store: store,
                                   autoWidth: autoWidth,
                                   autoHeight : autoHeight,                               
                                  structure: _structure
 	                        }, document.getElementById(wargs.uuid + "_content"));
                                 
         } else {
             wrapper = new dojox.grid.DataGrid({id: uuid+"_widget", structure: _structure,
            	   autoWidth : false,
            	   elasticView : elasticView,
            	   autoHeight : autoHeight},container);
         }
         // set to sort on 1st column

        // store.save();
         dojo.byId(wargs.uuid).appendChild(wrapper.domNode);

         wrapper.startup();

         wrapper.setSortIndex(1, true);         
         // want to know if the user clicks on a row so we can publish the data
         dojo.connect(wrapper.selection, "addToSelection", _widget, "onSelect");
         dojo.connect(wrapper, "_onSet", _widget, "onUpdate");
    }

    this.clearFilters = function(){
        table.clearFilters();
    }
    
    /**
     *  Clear all the data from the grid.
     *
     */
    this.clear = function() {
    	var items =  store._arrayOfAllItems;
    	for (var i=0; i < items.length;i++) {
    		store.deleteItem(items[i]);
    	}
        wrapper.render();
        counter = 0;
    };
    
    // private function that gets the row index given the targetId.
    function getRowIndex(targetId) {
    	var index = -1;
    	var item = store._getItemByIdentity( targetId);  	
    	if (item) index = wrapper.getItemIndex(item);
    	return index;

    }
    /**
     *  removeRow removes a row from the grid.
     *
     * @param b - The targetId of the row to be removed or an object containing a
                  message.targetId property like:
                  { messsage : {targetId : 'foo'}}
     *
     */ 
    this.removeRow = function(b){
        var targetId;
        if (b.message)b = b.message;
        if (b.targetId) {
           targetId = b.targetId;
        } else {
            targetId = b;
        }    
        var item = store._getItemByIdentity( targetId);
        if (item) store.deleteItem(item);
    };
    
    /**
     *  updateRow updates a row in the grid.
     *
     * @param b - The targetId of the row to be updated or an object containing a
                  message.targetId property like:
                  { messsage : {targetId : 'foo', value: [rowdata]}}
     *
     */ 
    this.updateRow = function(b, data){

        var targetId;
        var _data = data;
        if (b.message)b = b.message;
        if (b.targetId) {
           targetId = b.targetId;
        } else {
            targetId = b;
        }            
        if (b.value) { 
            data = b.value;
        }
        
        if (!_data || !targetId) {
           jmaki.log("No data for update, exiting...");
           return;
        }
        var item = store._getItemByIdentity( targetId);
       
        if (item) {
        	for (var i in _data) {
                if (typeof _data != 'function') {
        		    store.setValue(item, i, _data[i]);
                }
        	}
        }

    };
    
    /**
     *  addRows add a set of rows to the grid.
     *
     * @param b - an array of objects containing the row data to be added.
                  the grid is sorted after rows are added.  Data is of the form:
                  { value : [{row 1 data},{row2 data]}
     *
     */ 
    this.addRows = function(b){
    	var rows = b;
        if (b.message && b.message.value) rows = b.message.value;
        for (var i=0; i < rows.length; i++) {
            _widget.addRow({ value : rows[i]}, false);
        }        
        // only sort after group added
        wrapper.sort();
    };
    
     /**
     *  addRow adds a row to the grid. The grid is sorted after the row is added.
     *
     * @param b - The row data to be added or an object containing a
                  message.value property like:
                  { messsage : {value : '[row data]}}
       @param sort - false if multiple rows are being added so the grid is only sorted 
     *             once.  If undefined the grid will be sorted.
     *
     */ 
    this.addRow = function(b, sort){
        var r;
        if (b.message)b = b.message;
        if (b.value) {
            r = b.value;
        } else {
            r = b;
        }
        var targetId;
        if (r.id) targetId = r.id;
       
        var row = getRowIndex(targetId);
        if (row != -1 ) var data = model.getRow(row);

        if ( data && data.id == targetId) {
            jmaki.log(wargs.uuid  + " : Warning. Attempt to add record to dojo.dijit.grid. with duplicate row id: " + targetId + ". Autogenerating new id.");
            r.id = genId();
        }
        
        // add an id for sorting if not defined
        if (typeof r.id == "undefined") {
            r.id = genId();
        }
        var index = store._getItemsArray.length +1;

        store.newItem(r);
        if((typeof sort == 'undefined') ||
            (typeof sort == 'boolean' && 
             sort == true))  {
             wrapper.sort();
             store.save();
             wrapper._refresh();
         }      
     };
     
    /**
     *  Select an item from the list. The Label of the item will be
     *  set as the value of the text field.  
     *
     * @param o - The targetId to select or an object containing a
                  message.targetId property like:
                  { messsage : {targetId : 'foo'}}
     *
     */
    this.select = function(e){
        var targetId = e;
        var _target;
        if (e.message)e = e.message;
        if (e.value)e = e.value;

        if (e.action && e.action.targetId) {
           targetId = e.action.targetId;
        } else if (e.targetId){
           targetId = e.targetId;
        }

        var row= getRowIndex(targetId);
        wrapper.selection.addToSelection(row);

    };
    
    this.getValue = function() {
    	var rtable = {
    			columns : columns,
    			rows : []
    			 };
    	var items =  store._arrayOfAllItems;
    	for (var i=0; i < items.length;i++) {
    		rtable.rows.push(getItemValue(items[i]));
    	}
    	return rtable;
    }
    
    
    // correct for dojo data 
    function getItemValue(item) {
        if (!item) return;
    	var _val = {};
        for (var i=0; i< columns.length; i++) {
            if (item[columns[i].id]) {
            	_val[columns[i].id] = item[columns[i].id][0];
            }
        }
        _val.id = item.id[0];
        return _val;
    }
    
    /**
     *  onUpdate event captured here so the data of the row selected can be published to a topic.
     *
     * @param e - The actual row data.
     *
     */   
    this.onUpdate = function(item, columnId, oldValue, newValue) {
        var targetId = item.id;
        var _value = getItemValue(item);
        // later we may want to support multiple selections 
        jmaki.publish(topic + "/onCellEdit", { widgetId : wargs.uuid, type : 'onCellEdit', targetId : targetId, columin : columnId, oldValue : oldValue, value : newValue, row: _value });
        _widget.getValue();
    };    
    
    
    /**
     *  onSelect event captured here so the data of the row selected can be published to a topic.
     *
     * @param e - The actual row data.
     *
     */   
    this.onSelect = function(e) {

    	var item = getItemValue(wrapper.selection.getFirstSelected());
         if (!item) {
        	 return;
         }
         var _value = getItemValue(item);
        // later we may want to support multiple selections 
        jmaki.publish(topic + "/onSelect", { widgetId : wargs.uuid, type : 'onSelect', targetId : item.id, value : _value });
    };
    
    function doSubscribe(topic, handler) {
        var i = jmaki.subscribe(topic, handler);
        _widget.subs.push(i);
    }
    
    /**
     *  destroy unsubscribe the event listeners.
     *
     */      
    this.destroy = function() {
        for (var i=0; _widget.subs && i < _widget.subs.length; i++) {
            jmaki.unsubscribe(_widget.subs[i]);
        }
    };
    
    /**
     *  postLoad set the subscribers for the event listeners, read the args if any, 
     *           get data for the grid if any and initialize the widget.
     *
     */    
    this.postLoad = function() {
    	
    	if (wargs.args) {
    		if (typeof wargs.args.autoWidth == 'boolean') {			
    			autoWidth = wargs.args.autoWidth;
    		}
    		if (typeof wargs.args.autoHeight == 'boolean' ||
    				typeof wargs.args.autoHeight == 'number') {			
    			autoHeight = wargs.args.autoHeight;
    		}
    	}
    	
        // track the subscribers so we can later remove them
        _widget.subs = [];
        for (var _i=0; _i < subscribe.length; _i++) {
            doSubscribe(subscribe[_i]  + "/clear", _widget.clear);
            doSubscribe(subscribe[_i]  + "/addRow", _widget.addRow);
            doSubscribe(subscribe[_i]  + "/addRows", _widget.addRows);
            doSubscribe(subscribe[_i]  + "/updateRow", _widget.updateRow);
            doSubscribe(subscribe[_i]  + "/removeRow", _widget.removeRow);
            doSubscribe(subscribe[_i]  + "/select", _widget.select);
        } 
        
        if (wargs.args) {
            if (wargs.args.topic) {
                topic = wargs.args.topic;
                jmaki.log("Dojo grid: widget uses deprecated topic property. Use publish instead. ");
            }

            if (wargs.args.filter) {
               filter = wargs.args.filter;
            }
        }

        if (wargs.publish ) {
            topic = wargs.publish;
         }

        if (wargs.subscribe){
            if (typeof wargs.subscribe == "string") {
                subscribe = [];
                subscribe.push(wargs.subscribe);
            } else {
                subscribe = wargs.subscribe;
            }
        }

        // set columns from the widget arguments if provided.
        if (wargs.args && wargs.args.columns) {
            columns = wargs.args.columns;     
        }    
                                // pull in the arguments
        if (wargs.value) {
            // convert value if a jmakiRSS type
            if (wargs.value.dataType == 'jmakiRSS') {
               wargs.value = jmaki.filter(wargs.value, filter);
            }
            if (!wargs.value.rows) {
                showModelDeprecation();
                return;
            }
            if (wargs.value.rows){
                _widget.rows = wargs.value.rows;
            } else if (wargs.value instanceof  Array) {
                _widget.rows = wargs.value;
            }
            if (wargs.value.columns) {
                columns = wargs.value.columns;
            }
            init();

        } else if (wargs.service) {
            jmaki.doAjax({
                url: wargs.service,
                callback: function (response) {
                    if (data == false) {
                        container.innerHTML = "Data format error loading data from " + wargs.service;
                    } else {
                        var data = eval ('('+response.responseText+')');
                        // convert value if a jmakiRSS type
                        if (data.dataType == 'jmakiRSS') {
                            data = jmaki.filter(data, filter);
                        }
                        if (!data.rows) {
                            showModelDeprecation();
                            return;
                        }
                        if (data.rows) {
                            _widget.rows = data.rows;                   
                        }
                        if (data.columns) {
                           columns = data.columns;
                        }
                    }
                    init();
                }
            });
        }  
     };
};