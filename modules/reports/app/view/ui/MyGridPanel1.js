/*
 * File: app/view/ui/MyGridPanel1.js
 * Date: Tue Aug 02 2011 16:19:42 GMT+0300 (E. Africa Standard Time)
 *
 * This file was generated by Ext Designer version 1.2.0 Beta1.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('app.view.ui.MyGridPanel1', {
  extend: 'Ext.grid.Panel',

  height: 425,
  width: 794,
  title: 'My Grid Panel',
  store: 'MyJsonStore',

  initComponent: function() {
    var me = this;
    me.viewConfig = {

    };
    me.columns = [
      {
        xtype: 'gridcolumn',
        dataIndex: 'pid',
        text: 'Pid'
      }
    ];
    me.callParent(arguments);
  }
});