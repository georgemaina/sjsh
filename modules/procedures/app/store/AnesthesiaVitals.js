/*
 * File: app/store/AnesthesiaVitals.js
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

Ext.define('Procedures.store.AnesthesiaVitals', {
    extend: 'Ext.data.Store',
    alias: 'store.anesthesiavitals',

    requires: [
        'Procedures.model.Anesthesia_Vitals',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            autoLoad: false,
            autoSync: true,
            model: 'Procedures.model.Anesthesia_Vitals',
            storeId: 'AnesthesiaVitals',
            proxy: {
                type: 'ajax',
                url: '../data/getDataFunctions.php?task=getAnesthesiaCharts&groupField=vitals',
                reader: {
                    type: 'json',
                    root: 'chartsItems'
                },
                writer: {
                    type: 'json',
                    encode: true,
                    root: 'editedData'
                }
            }
        }, cfg)]);
    }
});