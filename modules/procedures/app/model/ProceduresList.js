/*
 * File: app/model/ProceduresList.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Procedures.model.ProceduresList', {
    extend: 'Ext.data.Model',
    alias: 'model.procedureslist',

    requires: [
        'Ext.data.Field'
    ],

    fields: [
        {
            mapping: 'partcode',
            name: 'partcode'
        },
        {
            mapping: 'item_description',
            name: 'Item_Description'
        },
        {
            mapping: 'item_full_description',
            name: 'item_full_description'
        },
        {
            mapping: 'purchasing_class',
            name: 'purchasing_class'
        },
        {
            mapping: 'category',
            name: 'category'
        },
        {
            mapping: 'unit_price',
            name: 'unit_price'
        }
    ]
});