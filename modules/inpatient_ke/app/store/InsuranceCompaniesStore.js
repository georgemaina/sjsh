/*
 * File: app/store/InsuranceCompaniesStore.js
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

Ext.define('Inpatient.store.InsuranceCompaniesStore', {
    extend: 'Ext.data.Store',
    alias: 'store.insurancecompaniesstore',

    requires: [
        'Inpatient.model.InsuranceCompanies',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            pageSize: 500,
            storeId: 'InsuranceCompaniesStore',
            autoLoad: false,
            model: 'Inpatient.model.InsuranceCompanies',
            proxy: {
                type: 'ajax',
                url: '../../data/getDataFunctions.php?task=getInsuranceCompanies',
                reader: {
                    type: 'json',
                    rootProperty: 'insurancecompanies'
                }
            }
        }, cfg)]);
    }
});