/*
 * File: app/store/HtcReasonStore.js
 * Date: Mon Mar 09 2020 09:46:13 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.store.HtcReasonStore', {
    extend: 'Ext.data.Store',
    alias: 'store.htcreasonstore',

    requires: [
        'ReportsMain.model.HTCReason',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            groupField: 'Group',
            pageSize: 200,
            storeId: 'HtcReasonStore',
            autoLoad: true,
            model: 'ReportsMain.model.HTCReason',
            proxy: {
                type: 'ajax',
                simpleGroupMode: true,
                url: 'data/getReportsData.php?task=getHtcReasons',
                reader: {
                    type: 'json',
                    rootProperty: 'htcreason'
                }
            }
        }, cfg)]);
    }
});