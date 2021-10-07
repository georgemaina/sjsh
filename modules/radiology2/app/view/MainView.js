/*
 * File: app/view/MainView.js
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

Ext.define('Radiology.view.MainView', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.mainview',

    requires: [
        'Radiology.view.MainViewViewModel',
        'Ext.menu.Menu',
        'Ext.menu.Item',
        'Ext.form.Label'
    ],

    viewModel: {
        type: 'mainview'
    },
    itemId: 'mainView',
    layout: 'border',
    defaultListenerScope: true,

    items: [
        {
            xtype: 'panel',
            region: 'west',
            split: true,
            itemId: 'menuPanel',
            width: 150,
            title: 'Menu',
            items: [
                {
                    xtype: 'menu',
                    floating: false,
                    itemId: 'menu',
                    items: [
                        {
                            xtype: 'menuitem',
                            itemId: 'home',
                            text: 'Home',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'about',
                            text: 'Test Request',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'contact',
                            text: 'Pending Requests',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'contact1',
                            text: 'Dicom Images',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'contact2',
                            text: 'Reports',
                            focusable: true
                        }
                    ],
                    listeners: {
                        click: 'onMenuClick'
                    }
                }
            ]
        },
        {
            xtype: 'panel',
            region: 'center',
            itemId: 'contentPanel',
            layout: 'card',
            items: [
                {
                    xtype: 'panel',
                    itemId: 'homePanel',
                    title: 'Radiology',
                    layout: {
                        type: 'vbox',
                        align: 'center',
                        pack: 'center'
                    },
                    items: [
                        {
                            xtype: 'label',
                            text: 'Home View'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    itemId: 'aboutPanel',
                    title: 'About Us',
                    layout: {
                        type: 'vbox',
                        align: 'center',
                        pack: 'center'
                    },
                    items: [
                        {
                            xtype: 'label',
                            text: 'About Us View'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    itemId: 'contactPanel',
                    title: 'Contact Us',
                    layout: {
                        type: 'vbox',
                        align: 'center',
                        pack: 'center'
                    },
                    items: [
                        {
                            xtype: 'label',
                            text: 'Contact Us View'
                        }
                    ]
                }
            ]
        }
    ],

    onMenuClick: function(menu, item, e, eOpts) {
        location.hash = item.itemId;
    }

});