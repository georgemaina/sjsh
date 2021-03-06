/*
 * File: app/model/debtorDetails.js
 * Date: Mon Aug 16 2021 14:09:45 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.model.debtorDetails', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            mapping: 'Accno',
            name: 'Accno'
        },
        {
            mapping: 'accName',
            name: 'accName'
        },
        {
            mapping: 'accCategory',
            name: 'accCategory'
        },
        {
            mapping: 'accAddress1',
            name: 'accAddress1'
        },
        {
            mapping: 'accAddress2',
            name: 'accAddress2'
        },
        {
            mapping: 'accPhone',
            name: 'accPhone'
        },
        {
            mapping: 'accAltphone',
            name: 'accAltphone'
        },
        {
            mapping: 'accContact',
            name: 'accContact'
        },
        {
            mapping: 'accEmail',
            name: 'accEmail'
        },
        {
            mapping: 'accFax',
            name: 'accFax'
        }
    ]
});