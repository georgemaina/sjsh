/*
 * File: app/store/VitalsStore.js
 * Date: Fri Feb 28 2020 05:39:35 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.5.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.5.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Inpatient.store.VitalsStore', {
    extend: 'Ext.data.Store',
    alias: 'store.vitalsstore',

    requires: [
        'Inpatient.model.OccupancyList',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            groupField: 'RoomNo',
            pageSize: 500,
            storeId: 'VitalsStore',
            autoLoad: false,
            model: 'Inpatient.model.OccupancyList',
            proxy: {
                type: 'ajax',
                url: '../data/getDataFunctions.php?task=getVitals',
                reader: {
                    type: 'json',
                    rootProperty: 'vitals'
                }
            }
        }, cfg)]);
    }
});