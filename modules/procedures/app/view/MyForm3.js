/*
 * File: app/view/MyForm3.js
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

Ext.define('Procedures.view.MyForm3', {
    extend: 'Ext.form.Panel',

    requires: [
        'Ext.form.Label',
        'Ext.form.field.Text'
    ],

    height: 489,
    width: 977,
    layout: 'absolute',
    bodyPadding: 10,
    title: 'My Form',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'label',
                    width: 100,
                    text: ''
                },
                {
                    xtype: 'textfield',
                    width: 165,
                    fieldLabel: 'Label'
                },
                {
                    xtype: 'textfield',
                    y: 60,
                    width: 200,
                    fieldLabel: 'Label'
                },
                {
                    xtype: 'textfield',
                    y: 65,
                    width: 200,
                    fieldLabel: 'Label'
                }
            ]
        });

        me.callParent(arguments);
    }

});