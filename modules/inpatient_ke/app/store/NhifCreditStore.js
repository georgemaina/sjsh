/*
 * File: app/store/NhifCreditStore.js
 * Date: Tue Jul 28 2020 15:01:37 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.4.
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

Ext.define('Inpatient.store.NhifCreditStore', {
    extend: 'Ext.data.Store',
    alias: 'store.nhifcreditstore',

    requires: [
        'Inpatient.model.NhifCredits',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'NhifCreditStore',
            autoLoad: true,
            model: 'Inpatient.model.NhifCredits',
            proxy: {
                type: 'ajax',
                url: '../../data/getDataFunctions.php?task=getNHIFCredits',
                reader: {
                    type: 'json',
                    rootProperty: 'nhifcredits'
                }
            }
        }, cfg)]);
    }
});