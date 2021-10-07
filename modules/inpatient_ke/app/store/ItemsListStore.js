/*
 * File: app/store/ItemsListStore.js
 * Date: Fri Mar 06 2020 13:00:43 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Inpatient.store.ItemsListStore', {
    extend: 'Ext.data.Store',
    alias: 'store.itemsliststore',

    requires: [
        'Inpatient.model.ItemsList',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            pageSize: 3000,
            storeId: 'ItemslistStore',
            autoLoad: true,
            model: 'Inpatient.model.ItemsList',
            proxy: {
                type: 'ajax',
                url: '../../data/getDataFunctions.php?task=getItemsList&sParams=\'Drug_list\',\'medical-supplies\'',
                reader: {
                    type: 'json'
                }
            }
        }, cfg)]);
    }
});