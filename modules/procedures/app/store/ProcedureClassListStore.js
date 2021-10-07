/*
 * File: app/store/ProcedureClassListStore.js
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

Ext.define('Procedures.store.ProcedureClassListStore', {
    extend: 'Ext.data.Store',
    alias: 'store.procedureclassliststore',

    requires: [
        'Procedures.model.ProcedureClassList',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            model: 'Procedures.model.ProcedureClassList',
            storeId: 'ProcedureClassListStore',
            proxy: {
                type: 'ajax',
                url: '../data/getDataFunctions.php?task=getProcedureClassList',
                reader: {
                    type: 'json',
                    root: 'procedureClassLists'
                },
                writer: {
                    type: 'json',
                    encode: true,
                    root: 'data'
                }
            }
        }, cfg)]);
    }
});