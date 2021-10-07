/*
 * File: app/view/Radiology.js
 * Date: Mon May 18 2020 11:00:01 GMT+0300 (E. Africa Standard Time)
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

Ext.define('CarePortal.view.Radiology', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.radiology',

    requires: [
        'CarePortal.view.RadiologyViewModel'
    ],

    viewModel: {
        type: 'radiology'
    },
    height: 250,
    itemId: 'radiology',
    resizable: true,
    resizeHandles: 's',
    scrollable: true,
    title: 'Radiology'

});