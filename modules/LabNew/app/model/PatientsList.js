/*
 * File: app/model/PatientsList.js
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

Ext.define('Lab.model.PatientsList', {
    extend: 'Ext.data.Model',
    alias: 'model.patientslist',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'Pid'
        },
        {
            name: 'EncounterNo'
        },
        {
            name: 'FileNo'
        },
        {
            name: 'LabNo'
        },
        {
            name: 'Names'
        },
        {
            name: 'Sex'
        },
        {
            name: 'Dob'
        },
        {
            name: 'Phone'
        },
        {
            name: 'PayMode'
        },
        {
            name: 'Priority'
        },
        {
            name: 'CreateID'
        }
    ]
});