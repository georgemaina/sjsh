/*
 * File: app/view/BillingCustInfo.js
 * Date: Fri Sep 28 2018 10:01:07 GMT+0300 (E. Africa Standard Time)
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

Ext.define('Debtors.view.BillingCustInfo', {
    extend: 'Ext.form.Panel',
    alias: 'widget.billingcustinfo',

    requires: [
        'Debtors.view.BillingCustInfoViewModel',
        'Ext.form.FieldContainer',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Display',
        'Ext.menu.Menu',
        'Ext.menu.Item'
    ],

    viewModel: {
        type: 'billingcustinfo'
    },
    height: 185,
    width: 833,
    closeAction: 'hide',
    defaultListenerScope: true,

    layout: {
        type: 'hbox',
        align: 'stretchmax'
    },
    items: [
        {
            xtype: 'fieldcontainer',
            height: 185,
            width: 318,
            items: [
                {
                    xtype: 'textfield',
                    frame: false,
                    itemId: 'pid',
                    width: 306,
                    fieldLabel: 'PID',
                    name: 'pid'
                },
                {
                    xtype: 'combobox',
                    itemId: 'billNumber',
                    width: 305,
                    fieldLabel: 'Bill Number',
                    name: 'billNumber',
                    displayField: 'billNumber',
                    store: 'BillNumbers',
                    valueField: 'encounterNr',
                    listeners: {
                        select: 'onBillNumberSelect'
                    }
                },
                {
                    xtype: 'displayfield',
                    id: 'pnames',
                    width: 307,
                    fieldLabel: 'Names',
                    name: 'pnames'
                },
                {
                    xtype: 'displayfield',
                    width: 307,
                    fieldLabel: 'Address',
                    name: 'addr_zip'
                },
                {
                    xtype: 'displayfield',
                    width: 305,
                    fieldLabel: 'Address2',
                    name: 'addr_zip2'
                },
                {
                    xtype: 'displayfield',
                    id: 'cellPhone',
                    width: 305,
                    fieldLabel: 'Phone',
                    name: 'phone_1_nr'
                },
                {
                    xtype: 'displayfield',
                    id: 'email',
                    width: 305,
                    fieldLabel: 'Email',
                    name: 'email'
                },
                {
                    xtype: 'displayfield',
                    id: 'location',
                    width: 305,
                    fieldLabel: 'Location',
                    name: 'location'
                }
            ]
        },
        {
            xtype: 'fieldcontainer',
            height: 180,
            width: 319,
            labelAlign: 'top',
            items: [
                {
                    xtype: 'displayfield',
                    width: 305,
                    fieldLabel: 'Admission Date',
                    name: 'admDate'
                },
                {
                    xtype: 'displayfield',
                    width: 305,
                    fieldLabel: 'Discharge Date',
                    name: 'disDate'
                },
                {
                    xtype: 'displayfield',
                    id: 'encNr',
                    width: 305,
                    fieldLabel: 'Encounter No',
                    name: 'encounter_nr'
                },
                {
                    xtype: 'displayfield',
                    id: 'accno',
                    width: 307,
                    fieldLabel: 'accno',
                    name: 'accno'
                },
                {
                    xtype: 'displayfield',
                    id: 'encClass',
                    width: 307,
                    fieldLabel: 'IP-OP',
                    name: 'encClass'
                }
            ]
        },
        {
            xtype: 'menu',
            flex: 1,
            floating: false,
            width: 120,
            items: [
                {
                    xtype: 'menuitem',
                    id: 'mnuPrintInvoice2',
                    text: 'Print INvoice',
                    focusable: true,
                    listeners: {
                        click: 'onMnuPrintInvoice2Click'
                    }
                },
                {
                    xtype: 'menuitem',
                    id: 'mnuCloseInvoice',
                    text: 'Close Invoice',
                    focusable: true,
                    listeners: {
                        click: 'onMnuCloseInvoiceClick'
                    }
                }
            ]
        }
    ],

    onBillNumberSelect: function(combo, record, eOpts) {
        var CustomerBillItems=Ext.data.StoreManager.lookup('CustomerBill');
        var pid=this.getForm().findField("pid").getValue();
        var encNr=combo.getValue();
        var billNo=combo.getRawValue();

        //alert(pid);

        CustomerBillItems.load({
            params: {
                pid: pid,
                encNr: encNr,
                billNumber:billNo

            },
            callback: function(records, operation, success) {


            },
            scope: this

        });


        var CustomerInfo=Ext.data.StoreManager.lookup('CustomerInfo');
        CustomerInfo.load({
            params: {
                pid: this.getForm().findField("pid").getValue(),
                encNr: encNr,
                billNumber:billNo

            },
            callback: function(records, operation, success) {
                // var data3=this.getStore('CustomerInfo').getAt(0);
                // customerinfos.loadRecord(this);
                var cust = CustomerInfo.first();
                var billingcustinfo = Ext.widget('billingcustinfo');
                //customerinfos.getForm().findField("pid").setValue(cust.get('pid'));
                this.getForm().findField("pnames").setValue(cust.get('pnames'));
                this.getForm().findField("addr_zip").setValue(cust.get('addr_zip'));
                this.getForm().findField("addr_zip2").setValue(cust.get('addr_zip2'));
                this.getForm().findField("cellPhone").setValue(cust.get('cellPhone'));
                this.getForm().findField("email").setValue(cust.get('email'));
                this.getForm().findField("location").setValue(cust.get('location'));
                this.getForm().findField("admDate").setValue(cust.get('admDate'));
                this.getForm().findField("disDate").setValue(cust.get('disDate'));
                // billingcustinfo.getForm().findField("billNumber").setValue(record.get('billNumber'));
                this.getForm().findField("encNr").setValue(cust.get('encNr'));
                //billingcustinfo.getForm().findField("accno").setValue(record.get('accno'));
                this.getForm().findField("encClass").setValue(cust.get('encClass'));

                // alert(cust.get('admDate'));


            },
            scope: this

        });
    },

    onMnuPrintInvoice2Click: function(item, e, eOpts) {


        var pid = this.getForm().findField("pid").value;
        var billNumber=this.getForm().findField("billNumber").getRawValue();
        var encClass=this.getForm().findField("encClass").value;

        window.open('reports/detail_invoice_pdf.php?pid='+pid+"&receipt=1&final=Final&billNumber="+billNumber+"&encClass="+encClass ,
        "Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    },

    onMnuCloseInvoiceClick: function(item, e, eOpts) {
        var pid = this.getForm().findField("pid").value;
        var billNumber=this.getForm().findField("billNumber").getRawValue();
        var encounter_nr=this.getForm().findField("encounter_nr").value;

        Ext.Ajax.request({
            url: './data/getDataFunctions.php?task=closeInvoice',
            params: {
                pid: pid,
                encounterNo: encounter_nr
            },
            success: function(response){
                var text = response.responseText;
                alert('Successfully Closed invoice, Proceed to Finalize the Invoice');
            }
        });

    }

});