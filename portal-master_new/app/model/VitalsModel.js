/*
 * File: app/model/VitalsModel.js
 * Date: Tue Nov 20 2018 13:21:20 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Sencha Architect version 4.2.4.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.6.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.6.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Inpatient.model.VitalsModel', {
    extend: 'Ext.data.Model',
    alias: 'model.vitalsmodel',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'ID'
        },
        {
            name: 'Weight'
        },
        {
            name: 'Height'
        },
        {
            name: 'HeadCircumference'
        },
        {
            name: 'BP'
        },
        {
            name: 'PulseRate'
        },
        {
            name: 'Respiratory'
        },
        {
            name: 'Temperature'
        },
        {
            name: 'HTC'
        },
        {
            name: 'BMI'
        },
        {
            name: 'SPO2'
        }
    ]
});