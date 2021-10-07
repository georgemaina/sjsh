/*
 * File: app/store/DebitStore.js
 *
 * This file was generated by Sencha Architect version 4.2.4.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.2.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.2.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('CarePortal.store.DebitStore', {
    extend: 'Ext.data.Store',
    alias: 'store.debitstore',

    requires: [
        'CarePortal.model.DebitDetails',
        'Ext.data.proxy.Ajax',
        'Ext.data.writer.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            pageSize: 500,
            storeId: 'DebitStore',
            autoLoad: false,
            model: 'CarePortal.model.DebitDetails',
            proxy: {
                type: 'ajax',
                url: '../data/getPatientFunctions.php?caller=saveDebit',
                writer: {
                    type: 'json'
                }
            }
        }, cfg)]);
    }
});