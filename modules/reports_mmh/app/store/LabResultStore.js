/*
 * File: app/store/LabResultStore.js
 * Date: Mon Oct 22 2018 10:41:58 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.store.LabResultStore', {
    extend: 'Ext.data.Store',
    alias: 'store.labresultstore',

    requires: [
        'ReportsMain.model.LabResultsModel',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            groupField: 'Group',
            storeId: 'LabResultStore',
            autoLoad: true,
            model: 'ReportsMain.model.LabResultsModel',
            proxy: {
                type: 'ajax',
                simpleGroupMode: true,
                url: 'data/getReportsData.php?task=getLabResults',
                reader: {
                    type: 'json',
                    rootProperty: 'labtests'
                }
            }
        }, cfg)]);
    }
});