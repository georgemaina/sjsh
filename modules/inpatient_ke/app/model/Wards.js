/*
 * File: app/model/Wards.js
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

Ext.define('Inpatient.model.Wards', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'Nr'
        },
        {
            name: 'WardID'
        },
        {
            name: 'Name'
        },
        {
            name: 'room_nr_first'
        },
        {
            name: 'room_nr_last'
        }
    ]
});