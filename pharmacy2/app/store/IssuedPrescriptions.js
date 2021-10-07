/*
 * File: app/store/IssuedPrescriptions.js
 * Date: Tue Feb 18 2020 17:51:55 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Pharmacy.store.IssuedPrescriptions', {
    extend: 'Ext.data.Store',
    alias: 'store.IssuedPrescriptions',

    requires: [
        'Pharmacy.model.IssueDrugsModel',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'IssuedPrescriptions',
            model: 'Pharmacy.model.IssueDrugsModel',
            proxy: {
                type: 'ajax',
                url: '../../data/getDataFunctions.php?task=getIssuedPrescriptions',
                reader: {
                    type: 'json'
                }
            }
        }, cfg)]);
    }
});