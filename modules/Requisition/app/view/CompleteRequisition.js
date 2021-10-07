/*
 * File: app/view/CompleteRequisition.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
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

Ext.define('Requisition.view.CompleteRequisition', {
    extend: 'Ext.form.Panel',
    alias: 'widget.completerequisition',

    requires: [
        'Requisition.view.CompleteRequisitionViewModel',
        'Ext.button.Button',
        'Ext.form.field.Display'
    ],

    viewModel: {
        type: 'completerequisition'
    },
    frame: true,
    height: 207,
    width: 400,
    layout: 'absolute',
    bodyPadding: 10,

    items: [
        {
            xtype: 'button',
            x: 5,
            y: 95,
            height: 40,
            itemId: 'cmdCompleteOrder',
            width: 140,
            text: 'Complete Order'
        },
        {
            xtype: 'button',
            x: 275,
            y: 150,
            height: 40,
            itemId: 'cmdClose',
            width: 115,
            text: 'Close'
        },
        {
            xtype: 'button',
            x: 150,
            y: 95,
            height: 40,
            itemId: 'cmdExport',
            width: 115,
            text: 'Export to Excel'
        },
        {
            xtype: 'button',
            x: 275,
            y: 95,
            height: 40,
            itemId: 'cmdGenerateLPO',
            width: 115,
            text: 'Generate LPO'
        },
        {
            xtype: 'displayfield',
            x: 10,
            y: 15,
            shrinkWrap: 1,
            width: 360,
            value: 'This process will close the MEDS order template and generate LPO',
            fieldStyle: 'font-size: small;font-weight: bold;color: green;text-align:center'
        }
    ]

});