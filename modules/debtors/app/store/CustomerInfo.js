/*
 * File: app/store/CustomerInfo.js
 * Date: Fri Sep 28 2018 10:01:15 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.store.CustomerInfo', {
    extend: 'Ext.data.Store',
    alias: 'store.customerInfo',

    requires: [
        'Debtors.model.customerInfo',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'CustomerInfo',
            autoLoad: false,
            model: 'Debtors.model.customerInfo',
            proxy: {
                type: 'ajax',
                url: './data/getDataFunctions.php?task=getCustomerInfo',
                reader: {
                    type: 'json',
                    rootProperty: 'CustomerInfo'
                }
            }
        }, cfg)]);
    }
});