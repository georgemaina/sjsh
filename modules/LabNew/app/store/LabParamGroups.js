/*
 * File: app/store/LabParamGroups.js
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

Ext.define('Lab.store.LabParamGroups', {
    extend: 'Ext.data.Store',
    alias: 'store.labparamsgroups',

    requires: [
        'Lab.model.ParameterGroup',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'LabParamGroups',
            autoLoad: true,
            model: 'Lab.model.ParameterGroup',
            proxy: {
                type: 'ajax',
                url: '../../data/getDataFunctions.php?task=getLabParamGroups',
                reader: {
                    type: 'json'
                }
            }
        }, cfg)]);
    }
});