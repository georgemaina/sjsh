/*
 * File: app/model/StockLevelsModel.js
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

Ext.define('Pharmacy.model.StockLevelsModel', {
    extend: 'Ext.data.Model',
    alias: 'model.stocklevelsmodel',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'ID'
        },
        {
            name: 'partcode'
        },
        {
            name: 'Store'
        },
        {
            name: 'item_description'
        },
        {
            name: 'Quantity'
        },
        {
            name: 'Reorderlevel'
        },
        {
            name: 'Category'
        }
    ]
});