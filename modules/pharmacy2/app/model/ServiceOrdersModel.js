/*
 * File: app/model/ServiceOrdersModel.js
 * Date: Fri Mar 06 2020 12:16:49 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Pharmacy.model.ServiceOrdersModel', {
    extend: 'Ext.data.Model',
    alias: 'model.serviceordersmodel',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'date'
        },
        {
            name: 'time'
        },
        {
            name: 'req_no'
        },
        {
            name: 'store'
        },
        {
            name: 'store_desc'
        },
        {
            name: 'sup_storeid'
        },
        {
            name: 'sup_storedesc'
        },
        {
            name: 'status'
        },
        {
            name: 'input_user'
        }
    ]
});