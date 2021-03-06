/*
 * File: app/store/EncountersStore.js
 *
 * This file was generated by Sencha Architect version 4.2.3.
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

Ext.define('hha.store.EncountersStore', {
    extend: 'Ext.data.Store',
    alias: 'store.encounterstore',

    requires: [
        'hha.model.Encounters',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            pageSize: 200,
            storeId: 'EncountersStore',
            autoLoad: true,
            model: 'hha.model.Encounters',
            proxy: {
                type: 'ajax',
                url: 'data/getDatafunctions.php?task=getEncountersList',
                reader: {
                    type: 'json',
                    rootProperty: 'encounters'
                }
            }
        }, cfg)]);
    }
});