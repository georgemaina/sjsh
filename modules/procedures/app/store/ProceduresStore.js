/*
 * File: app/store/ProceduresStore.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Procedures.store.ProceduresStore', {
    extend: 'Ext.data.Store',
    alias: 'store.procedurestore',

    requires: [
        'Procedures.model.ProceduresList',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            autoLoad: true,
            model: 'Procedures.model.ProceduresList',
            storeId: 'ProceduresStore',
            proxy: {
                type: 'ajax',
                url: '../data/getDataFunctions.php?task=getItemsList',
                reader: {
                    type: 'json',
                    root: 'itemsList'
                }
            }
        }, cfg)]);
    }
});