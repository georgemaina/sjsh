/*
 * File: app/view/MenuDiagram.js
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

Ext.define('CarePortal.view.MenuDiagram', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.menudiagram',

    requires: [
        'CarePortal.view.MenuDiagramViewModel',
        'Ext.form.Label',
        'Ext.Img'
    ],

    viewModel: {
        type: 'menudiagram'
    },
    margin: '5 0 0 0',
    layout: 'absolute',

    items: [
        {
            xtype: 'label',
            x: 130,
            y: 0,
            style: 'color:green; font-size:14px;font-weight:bold;',
            text: 'Chart Folder'
        },
        {
            xtype: 'image',
            x: 60,
            y: 10,
            frame: false,
            height: 120,
            itemId: 'btnVitals',
            width: 115,
            src: 'resources/vitals.fw.png',
            title: 'Vitals'
        },
        {
            xtype: 'image',
            x: 125,
            y: 100,
            height: 120,
            itemId: 'btnDiagnosis',
            width: 115,
            src: 'resources/diagnosis.fw.png'
        },
        {
            xtype: 'image',
            x: 245,
            y: 95,
            height: 120,
            itemId: 'btnRadiology',
            width: 115,
            src: 'resources/radiology.fw.png'
        },
        {
            xtype: 'image',
            x: 185,
            y: 5,
            height: 120,
            itemId: 'btnLabtest',
            width: 115,
            src: 'resources/labtest.fw.png'
        },
        {
            xtype: 'image',
            x: 0,
            y: 100,
            height: 120,
            itemId: 'btnNotes',
            width: 115,
            src: 'resources/notes.fw.png'
        },
        {
            xtype: 'image',
            x: 65,
            y: 195,
            height: 120,
            itemId: 'btnPrescriptions',
            width: 115,
            src: 'resources/prescriptions.fw.png'
        },
        {
            xtype: 'image',
            x: 190,
            y: 190,
            height: 120,
            itemId: 'btnServices',
            width: 115,
            src: 'resources/services.fw.png'
        },
        {
            xtype: 'image',
            x: 250,
            y: 275,
            height: 120,
            itemId: 'btnMch',
            width: 115,
            src: 'resources/mch.fw.png'
        },
        {
            xtype: 'image',
            x: 130,
            y: 285,
            height: 120,
            itemId: 'btnMch1',
            width: 115,
            src: 'resources/mch.fw.png'
        }
    ]

});