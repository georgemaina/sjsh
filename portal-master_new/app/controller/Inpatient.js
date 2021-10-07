/*
 * File: app/controller/Inpatient.js
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

Ext.define('CarePortal.controller.Inpatient', {
    extend: 'Ext.app.Controller',
    alias: 'controller.inpatient',

    models: [
        'DebitDetails',
        'BillNumbers',
        'InsuranceCompanies',
        'NhifRates',
        'ClinicalRooms',
        'WardInfo',
        'ItemsList',
        'EncounterDetails',
        'OccupancyList',
        'ReceiptModel',
        'Wards',
        'Vitals',
        'BillModel'
    ],
    stores: [
        'ReceiptStore',
        'WardInfoStore',
        'BillNumbersStore',
        'NhifRates',
        'EncounterNosStore',
        'VitalsStore',
        'Invoices',
        'EncounterDetailStore',
        'DebitStore',
        'InsuranceCompaniesStore',
        'ItemsListStore',
        'OccupancyStore'
    ],
    views: [
        'FinaliseInvoice',
        'Discharge',
        'Credits',
        'Invoices',
        'ItemsList',
        'WaitingList',
        'VitalsForm'
    ],

    refs: {
        debit: {
            selector: 'debit',
            xtype: 'debit'
        },
        credit: {
            selector: 'credit',
            xtype: 'credit'
        },
        bills: {
            selector: 'bills',
            xtype: 'bills'
        },
        chargebeds: {
            selector: 'chargebeds',
            xtype: 'chargebeds'
        },
        itemslist: {
            selector: 'itemslist',
            xtype: 'itemslist'
        },
        nhifcredit: {
            selector: 'nhifcredit',
            xtype: 'nhifcredit'
        },
        finaliseinvoice: {
            selector: 'finaliseinvoice',
            xtype: 'finaliseinvoice'
        },
        interiminvoice: {
            selector: 'interiminvoice',
            xtype: 'interiminvoice'
        },
        invoicedetails: {
            selector: 'invoicedetails',
            xtype: 'invoicedetails'
        },
        receipts: {
            selector: 'receipts',
            xtype: 'receipts'
        },
        insurancecredit: {
            selector: 'insurancecredit',
            xtype: 'insurancecredit'
        },
        vitals: {
            selector: 'vitals',
            xtype: 'vitals'
        },
        invoices: {
            selector: 'invoices',
            xtype: 'invoices'
        },
        waitinglist: {
            selector: 'waitinglist',
            xtype: 'waitinglist'
        },
        vitalsform: {
            selector: 'vitalsform',
            xtype: 'vitalsform'
        },
        admissiondata: {
            selector: 'admissiondata',
            xtype: 'admissiondata'
        }
    },

    init: function(application) {
        this.control({
            '#cmdItemsList':{
                click:this.getItemsList
            },
            '#itemsList':{
                itemdblclick:this.addSelectedItem
            },
            '#cmdAddRow':{
                click:this.addRow
            },
            '#cmdSaveDebits':{
                click:this.saveDebits
            },
            '#txtPid':{
                blur:this.getEncounter
            },
            '#cmdSearchBills':{
                click:this.searchBills
            },
            '#cmdCombineBills':{
                click:this.combineBills
            },
            '#txtSearch':{
                specialkey:this.getPressedKey
            },
            '#cmdUpdateBill':{
                click:this.updateBillChanges
            },
            '#deleteItem':{
                click:this.deleteBillItem
            },
            'bills actioncolumn[id=deletebill2]':{
                click:this.deleteBillItem
            },
            '#cmdPreviewInterim':{
                click:this.previewInterimInvoice
            },
            '#txtSearchInterim':{
                blur:this.getBillNumbers
            },
            '#cmdDeleteSelected':{
                click:this.removeSelectedItems
            },
            '#cmdDeleteReceipts':{
                click:this.removeSelectedReceipts
            },
            '#cmdUpdateReceipts':{
                click:this.updateReceipts
            },
            '#txtPid2':{
                blur:this.getEncounterDetails
            },
            '#creditAmount':{
                blur:this.getBalance
            },
            '#cmdSave':{
                click:this.saveInsuranceCredit
            },
            '#cmdSearchReceipt':{
                click:this.searchReceipt
            },
            '#txtPid3':{
                blur:this.getEncounterDetails2
            },
            '#nhifClientType':{
                change:this.getNhifRates
            },
            '#cmdSaveNhif':{
                click:this.saveNifCredit
            },
            '#cmdDeleteRow':{
                click:this.deleteRow
            },
            '#SaveVitals':{
                click:this.saveVitals
            },
            'wardoccupancy actioncolumn[id=openVitals]':{
                click:this.openVitals
            },
            '#cmdSearchItems':{
                click:this.searchItems
            },
            '#searchParam':{
               specialkey:this.getPressedKey

            },
            '#cmdClose':{
                click:this.closewindow
            },
            'wardoccupancy actioncolumn[id=discharge]':{
                click:this.openDischargeForm
            },
            '#saveDischarge':{
                click:this.discharge
            }

        });
    },

    getItemsList: function(button) {
        var itemsList=Ext.create('CarePortal.view.ItemsList', {});
        var itemsListWindow=Ext.create('Ext.window.Window', {
                    title: 'Items List',
                    resizable:true,
                    minWidth:300,
                    minHeight:300
                });

        itemsListWindow.add(itemsList);
        itemsListWindow.show();

    },

    addSelectedItem: function(gridpanel, record, item, index, e, options) {
        var store =Ext.data.StoreManager.lookup('ItemsListStore');
        var store1 =Ext.data.StoreManager.lookup('DebitStore');
        store.remove(record);
        store1.add(record);
    },

    addRow: function(button) {
         var invoice_item = new Inpatient.model.ItemsList({
             PartCode: 'wes',
             Description: 'sds',
             Category: 'sds',
             Price:'12'
         });
    },

    saveDebits: function(button) {
        var form=button.up('form');

        var debitStore =Ext.data.StoreManager.lookup('DebitStore');
        var debitRecord = debitStore.getRange();

        var pid=this.getDebit().down('#txtPid').getValue();
        var debitDate=this.getDebit().down('#debitDate').getValue();
        var debitno=this.getDebit().down('#debitNo').getValue();
        var billNumber=this.getDebit().down('#billNumber').getValue();
        var encounterNo=this.getDebit().down('#encounterNo').getValue();


        var gridData = Array();

        Ext.each(debitRecord, function (record) {
            gridData.push(record.data);
        });

        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=saveDebit',
            params: {
                pid:pid,
                debitDate:debitDate,
                debitNo:debitno,
                billNumber:billNumber,
                encounterNo:encounterNo,
                gridData: Ext.util.JSON.encode(gridData)
            },success: function(response){
                  //var encDetails= Ext.util.JSON.decode(response.responseText);
                  Ext.Msg.alert("Debits","Debit Saved Successfully");
                  form.reset();
                  debitStore.load({});
            },
            scope:this
        });

    },

    getEncounter: function(textfield, The, eOpts) {
        var pid=this.getDebit().down('#txtPid').getValue();

        Ext.Ajax.request({
             url: 'data/getDataFunctions.php?caller=getEncounter',
            params: {
                pid: pid
            },
            success: function(response){

                var encDetails= Ext.util.JSON.decode(response.responseText);

                 pnames=encDetails.encounterNr[0].FirstName+" "+encDetails.encounterNr[0].LastName+" "+encDetails.encounterNr[0].SurName;
               //  Ext.ComponentQuery.query("#pname")[0].setValue(pnames);
                this.getDebit().down('#pname').setValue(pnames);
                this.getDebit().down('#billNumber').setValue(encDetails.encounterNr[0].BillNumber);
                this.getDebit().down('#encounterNo').setValue(encDetails.encounterNr[0].EncounterNr);

                this.getDebitNo();
            },
            scope:this
        });
    },

    searchBills: function(button) {
        var searchParam=this.getBills().down('#txtSearch').getValue();
        var billNo=this.getBills().down('#cmbBill1').getValue();
        var billStore =Ext.data.StoreManager.lookup('BillStore');
        billStore.load({
            params: {
                pid: searchParam,
                bill_number:billNo
            },
            callback: function(records, operation, success) {

            },
            scope: this

        });

        var billStore2 =Ext.data.StoreManager.lookup('BillNumbersStore');
                    billStore2.load({
                        params: {
                            pid: searchParam
                        },
                        callback: function(records, operation, success) {

                        },
                        scope: this

                    });


    },

    combineBills: function(button) {
        var pid=this.getBills().down('#txtSearch').getValue();
        var bill1=this.getBills().down('#cmbBill1').getValue();
        var bill2=this.getBills().down('#cmbBill2').getValue();
        var enc1=this.getBills().down('#cmbEnc1').getValue();
        var enc2=this.getBills().down('#cmbEnc2').getValue();

        Ext.Msg.show({
            title:'Merge Bills?',
            message: 'Are you sure you want to MERGE the two Bills?',
            buttons: Ext.Msg.YESNOCANCEL,
            icon: Ext.Msg.QUESTION,
            fn: function(btn) {
                if (btn === 'yes') {
                    Ext.Ajax.request({
                            url: 'data/getDataFunctions.php?caller=combineBills',
                            params: {
                                pid:pid,
                                bill1:bill1,
                                bill2:bill2,
                                enc1:enc1,
                                enc2:enc2,
                            }
                        });
                } else if (btn === 'no') {
                    console.log('No pressed');
                } else {
                    console.log('Cancel pressed');
                }
            }
        });




    },

    getPressedKey: function(field, e, options) {
            if (e.getKey() == e.ENTER && field.getItemId()==='txtSearch') {
             //   Ext.Msg.alert('Test',field.getItemId());
               this.searchBills();
            }else  if (e.getKey() == e.ENTER && field.getItemId()==='searchParam') {
             //   Ext.Msg.alert('Test',field.getItemId());
                this.searchItems();
            }
    },

    updateBillChanges: function(button) {
        var billStore=Ext.data.StoreManager.lookup('BillStore');

        var updatedRecords = this.getBills().getStore().getUpdatedRecords();
        params=[];
        Ext.each(updatedRecords,function(record){
         params.push(record.data);
        });


        billStore.proxy.extraParams = {
            selectedCount:params.length,
            pid:this.getBills().down('#txtSearch').getValue()
        };

        billStore.sync({
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                Ext.Msg.alert('Update','Successfully updated Bill');
                var billStore=Ext.data.StoreManager.lookup('BillStore');
                billStore.load({});

            },
            failure:function(response){
                var resp = JSON.parse(response.responseText);
                Ext.Msg.alert('Error','Error Updating Bill');

            }
        });
    },

    deleteBillItem: function(view, rowIndex, colIndex, item, e, record, row) {
        // var rec = button.up('grid').getViewModel().get('record');
        // Ext.Msg.alert("Button clicked", "Hey! " + record.get('ID'));
        var ID=record.get('ID');

        Ext.Msg.show({
            title:'Delete Bill Item?',
            msg: 'Are you sure you want to delete',
            buttons: Ext.Msg.YESNOCANCEL,
            icon: Ext.Msg.QUESTION,
            fn: function(rec) {
                if (rec === "yes") {
                    Ext.Ajax.request({
                        url: 'data/getDataFunctions.php?caller=deleteBillItem',
                        params: {
                            ID:record.get('ID'),
                            pid:record.get('Pid')
                        },
                        waitMsg: 'Deleting Item ...',
                        success: function(response){
                            var resp = Ext.JSON.decode(response.responseText);
                            Ext.Msg.alert('Delete',resp.Error);
                            var billStore=Ext.data.StoreManager.lookup('BillStore');
                            billStore.load({});

                        },
                        failure:function(response){
                            var resp = JSON.parse(response);
                            Ext.Msg.alert('Error',resp.Error);

                            //                     Ext.Msg.alert('Error','There was a problem deleting the Part Locations, Contact System Administrator');
                        }
                    });

                }
            }
        });
    },

    previewInterimInvoice: function(button) {
        //reportswindow=Ext.create('Inpatient.view.InvoiceDetails', {});
        var invoiceDetails=this.getInteriminvoice().down('#invoiceDetails');

        var pid=this.getInteriminvoice().down('#txtSearchInterim').getValue();
        var billNumber=this.getInteriminvoice().down('#cmbBillNumbers').getValue();

        var billStore=Ext.data.StoreManager.lookup("Invoices");

        billStore.load({
            params: {
                pid:pid,
                bill_number:billNumber
            },
            callback: function(records, operation, success) {

            },
            scope: this
        });


        var billSum=0;
        var data = [];

        billStore.each(function(record){
          //  console.log(record);
            data.push(record.getData());
            billSum += record.get('Total');
        });

         //var data1= billStore.proxy.reader.transform;

         var tpl=new Ext.XTemplate(
                        '<Table id=billDetails>',
                        '<tr><th>Date</th><th>Service Description</th><th>Ref No</th><th>Price</th><th>Qty</th><th>Total</th></tr>',
                        '<tpl for=".">',
                            '<tr><td>{Bill_Date}</td>',
                            '<td>{Description}</td>',
                            '<td>{ID}</td>',
                            '<td align=right>{Price}</td>',
                            '<td>{Qty}</td>',
                            '<td align=right>{Total}</td></tr>',
                        '</tpl>',
                        '<tr><td colspan=2 class=totals>Totals</td><td class=totals></td><td class=totals> 0</td><td>{[this.getBillTotal() ]}</td></tr>',
                        '</table>',
                      {
                        getBillTotal:function(){
                            return billSum.toFixed(2);
                        }
                      }
         );


        tpl.overwrite(invoiceDetails.body,data);





    },

    getBillNumbers: function(field, the, eOpts) {
        //var searchParam=this.getBills().down('#txtSearch').getValue();
        var billStore2 =Ext.data.StoreManager.lookup('BillNumbersStore');
        billStore2.load({
            params: {
                pid: field.getValue()
            },
            callback: function(records, operation, success) {

            },
            scope: this

        });


    },

    removeSelectedItems: function(button) {
        var grid=button.up('grid');

        var srecords=grid.getSelectionModel().getSelection();

        var selectedRowIndexes = [];
        var pids='';

        Ext.iterate(srecords,function(record,index){

            selectedRowIndexes.push(grid.getStore().indexOf(record));

            if(grid.getSelectionModel().hasSelection()){
                pids=pids+record.get('ID')+",";
                Ext.Msg.show({
                    title:'Delete Bill Items?',
                    msg: 'Are you sure you want to delete',
                    buttons: Ext.Msg.YESNOCANCEL,
                    icon: Ext.Msg.QUESTION,
                    fn: function(rec) {
                        if (rec === "yes") {
                            Ext.Ajax.request({
                                url: 'data/getDataFunctions.php?caller=deleteBillItem',
                                params: {
                                    ID:pids,
                                    pid:record.get('Pid')
                                },
                                waitMsg: 'Deleting Item ...',
                                success: function(response){
                                    var resp = Ext.JSON.decode(response.responseText);
                                    Ext.Msg.alert('Delete',resp.Error);
                                    var billStore=Ext.data.StoreManager.lookup('BillStore');
                                    billStore.load({});

                                },
                                failure:function(response){
                                    var resp = JSON.parse(response);
                                    Ext.Msg.alert('Error',resp.Error);

                                    //                     Ext.Msg.alert('Error','There was a problem deleting the Part Locations, Contact System Administrator');
                                }
                            });

                        }
                    }
                });

            }else{
                 Ext.Msg.alert('Test','You have not Selected any Item to Remove');
            }

        });
    },

    removeSelectedReceipts: function(button) {
         var grid=button.up('grid');

                var srecords=grid.getSelectionModel().getSelection();

                var selectedRowIndexes = [];
                var pids='';

                Ext.iterate(srecords,function(record,index){

                    selectedRowIndexes.push(grid.getStore().indexOf(record));

                    if(grid.getSelectionModel().hasSelection()){
                        pids=pids+record.get('Sale_ID')+",";
                        Ext.Msg.show({
                            title:'Delete Receipt Items?',
                            msg: 'Are you sure you want to delete',
                            buttons: Ext.Msg.YESNOCANCEL,
                            icon: Ext.Msg.QUESTION,
                            fn: function(rec) {
                                if (rec === "yes") {
                                    Ext.Ajax.request({
                                        url: 'data/getDataFunctions.php?caller=deleteReceiptItem',
                                        params: {
                                            ID:pids,
                                            pid:record.get('Pid')
                                        },
                                        waitMsg: 'Deleting Receipt ...',
                                        success: function(response){
                                            var resp = Ext.JSON.decode(response.responseText);
                                            Ext.Msg.alert('Delete',resp.Error);
                                            var receiptStore=Ext.data.StoreManager.lookup('ReceiptStore');
                                            receiptStore.load({});

                                        },
                                        failure:function(response){
                                            var resp = JSON.parse(response);
                                            Ext.Msg.alert('Error',resp.Error);

                                            //                     Ext.Msg.alert('Error','There was a problem deleting the Part Locations, Contact System Administrator');
                                        }
                                    });

                                }
                            }
                        });

                    }else{
                         Ext.Msg.alert('Test','You have not Selected any Item to Remove');
                    }

                });
    },

    updateReceipts: function(button) {


        var receiptStore=Ext.data.StoreManager.lookup('ReceiptStore');

        var updatedRecords = this.getReceipts().getStore().getUpdatedRecords();
        params=[];
        Ext.each(updatedRecords,function(record){
         params.push(record.data);
        });


        receiptStore.proxy.extraParams = {
            selectedCount:params.length,
            pid:this.getReceipts().down('#txtSearchReceipt').getValue()
        };

        Ext.Msg.alert('Update','Successfully updated Receipt');

        receiptStore.sync({
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                Ext.Msg.alert('Update','Successfully updated Receipt');
                var receiptStore=Ext.data.StoreManager.lookup('ReceiptStore');
                receiptStore.load({});

            },
            failure:function(response){
                var resp = JSON.parse(response.responseText);
                Ext.Msg.alert('Error','Error Updating Receipt');

            }
        });
    },

    getEncounterDetails: function(textField, the, eOpts) {
        //Ext.Msg.alert('current Form',textField.getValue());


        var pid=textField.getValue();
        // var pnames='as';
        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=getEncounter',
            params: {
                pid: pid
            },
            success: function(response){
                var encDetails= Ext.util.JSON.decode(response.responseText);
                pnames=encDetails.encounterNr[0].FirstName+" "+encDetails.encounterNr[0].LastName+" "+encDetails.encounterNr[0].SurName;

                this.getInsurancecredit().down('#pname').setValue(pnames);
                this.getInsurancecredit().down('#admissionDate').setValue(encDetails.encounterNr[0].AdmissionDate);
                this.getInsurancecredit().down('#dischargeDate').setValue(encDetails.encounterNr[0].DischargeDate);
                this.getInsurancecredit().down('#releaseDate').setValue(encDetails.encounterNr[0].ReleaseDate);
                this.getInsurancecredit().down('#days').setValue(encDetails.encounterNr[0].BedDays);
                this.getInsurancecredit().down('#encounterNr').setValue(encDetails.encounterNr[0].EncounterNr);
                this.getInsurancecredit().down('#billNumber').setValue(encDetails.encounterNr[0].BillNumber);

                var billnumberStore=Ext.data.StoreManager.lookup('BillNumbersStore');
                billnumberStore.load({
                    params:{
                        pid:pid
                    }
                });

                this.generateCreditNo();
                this.getBillAmount(pid,encDetails.encounterNr[0].BillNumber);

            },
            scope: this
        });
    },

    generateCreditNo: function() {
         // var pnames='as';
                Ext.Ajax.request({
                    url: 'data/getDataFunctions.php?caller=getCreditNo',
                    success: function(response){
                         var respText= Ext.util.JSON.decode(response.responseText);
                         if(respText.credits[0].creditNo==1){
                             creditNo=1001;
                         }else{
                             creditNo=respText.credits[0].creditNo;
                         }

                        this.getInsurancecredit().down('#creditNo').setValue(creditNo);

                    },
                    scope: this
                });
    },

    getBillAmount: function(pid, billNumber) {
        var pid=this.getInsurancecredit().down('#txtPid2').getValue();
        var billNumber=this.getInsurancecredit().down('#billNumber').getValue();

        Ext.Ajax.request({
             url: 'data/getDataFunctions.php?caller=getTotalBill',
             params:{
                 pid:pid,
                 bill_number:billNumber
             },
             success: function(response){
                 var respText= Ext.util.JSON.decode(response.responseText);

                 this.getInsurancecredit().down('#invoiceAmount').setValue(respText.invoiceAmount[0].amount);

             },
             scope: this
         });
    },

    getBalance: function(textField,the,eOpts) {
             var invoiceAmount=this.getInsurancecredit().down('#invoiceAmount').getValue();
             var creditAmount=textField.getValue();
             var balance=parseInt(invoiceAmount-creditAmount);

             this.getInsurancecredit().down('#balance').setValue(balance);
    },

    saveInsuranceCredit: function(button) {
        var form = button.up('form'); // get the form panel
        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                success: function(form, action) {
                    Ext.Msg.alert('Success', action.result.msg);

                    form.reset();
                },
                failure: function(form, action) {
                    Ext.Msg.alert('Failed',  action.result.msg);
                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
        }
    },

    searchReceipt: function(button) {
        var searchParam=this.getReceipts().down('#txtSearchReceipt').getValue();

        var receiptStore =Ext.data.StoreManager.lookup('ReceiptStore');
        receiptStore.load({
            params: {
                pid: searchParam
            },
            callback: function(records, operation, success) {

            },
            scope: this

        });


    },

    getEncounterDetails2: function(textField, the, eOpts) {
                var pid=textField.getValue();
                // var pnames='as';
                Ext.Ajax.request({
                    url: 'data/getDataFunctions.php?caller=getEncounter',
                    params: {
                        pid: pid
                    },
                    success: function(response){
                        var encDetails= Ext.util.JSON.decode(response.responseText);
                        pnames=encDetails.encounterNr[0].FirstName+" "+encDetails.encounterNr[0].LastName+" "+encDetails.encounterNr[0].SurName;

                        this.getNhifcredit().down('#pname').setValue(pnames);
                        this.getNhifcredit().down('#admissionDate').setValue(encDetails.encounterNr[0].AdmissionDate);
                        this.getNhifcredit().down('#dischargeDate').setValue(encDetails.encounterNr[0].DischargeDate);
                        this.getNhifcredit().down('#releaseDate').setValue(encDetails.encounterNr[0].ReleaseDate);
                        this.getNhifcredit().down('#days').setValue(encDetails.encounterNr[0].BedDays);
                        this.getNhifcredit().down('#encounterNr').setValue(encDetails.encounterNr[0].EncounterNr);
                        this.getNhifcredit().down('#billNumber').setValue(encDetails.encounterNr[0].BillNumber);
                        this.getNhifcredit().down('#ward').setValue(encDetails.encounterNr[0].Ward);

                        var billnumberStore=Ext.data.StoreManager.lookup('BillNumbersStore');
                        billnumberStore.load({
                            params:{
                                pid:pid
                            }
                        });

                       this.generateNhifCreditNo();
                       this.getBillAmount2(pid,encDetails.encounterNr[0].BillNumber);

                    },
                    scope: this
                });
    },

    generateNhifCreditNo: function() {
        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=getNhifCreditNo',
            success: function(response){
                var respText= Ext.util.JSON.decode(response.responseText);
                if(respText.credits[0].creditNo==1){
                    creditNo=1001;
                }else{
                    creditNo=respText.credits[0].creditNo;
                }

                this.getNhifcredit().down('#creditNo').setValue(creditNo);

            },
            scope: this
        });
    },

    getBillAmount2: function(pid, billNumber) {
        var pid=this.getNhifcredit().down('#txtPid3').getValue();
        var billNumber=this.getNhifcredit().down('#billNumber').getValue();

        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=getTotalBill',
            params:{
                pid:pid,
                bill_number:billNumber
            },
            success: function(response){
                var respText= Ext.util.JSON.decode(response.responseText);

                this.getNhifcredit().down('#invoiceAmount').setValue(respText.invoiceAmount[0].amount);

            },
            scope: this
        });
    },

    getNhifRates: function(comboField, newValue, oldValue, eOpts) {
        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=getNhifRates',
            params:{
                rate:newValue
            },
            success: function(response){
                var respText= Ext.util.JSON.decode(response.responseText);

                var rateCalc=respText.nhifRates[0].rateCalc;
                this.getNhifcredit().down('#creditPerDay').setValue(respText.nhifRates[0].RateValue);
                this.getNhifcredit().down('#rateCalc').setValue(rateCalc);

                var rateValue;
                if(rateCalc==1){
                    rateValue=respText.nhifRates[0].RateValue;

                }else{
                    var days=this.getNhifcredit().down('#days').getValue();
                    var rates=respText.nhifRates[0].RateValue;
                    rateValue=parseInt(rates * days);
                }
                 this.getNhifcredit().down('#creditAmount').setValue(rateValue);

                var invoiceAmount= this.getNhifcredit().down('#invoiceAmount').getValue();

                var balance=parseInt(invoiceAmount-rateValue);
                this.getNhifcredit().down('#balance').setValue(balance);


            },
            scope: this
        });
    },

    saveNifCredit: function(button) {
        var form = button.up('form'); // get the form panel
        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                success: function(form, action) {
                    Ext.Msg.alert('Success', action.result.msg);

                    form.reset();
                },
                failure: function(form, action) {
                    Ext.Msg.alert('Failed',  action.result.msg);
                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
        }
    },

    deleteRow: function(button) {
        var itemsGrid=button.up('form').down('#itemsGrid');
        itemToDelete=itemsGrid.getView().getSelectionModel().getSelection();
        itemsStore=Ext.data.StoreManager.lookup('DebitStore');

        itemsStore.remove(itemToDelete);
    },

    getDebitNo: function() {
        Ext.Ajax.request({
            url: 'data/getDataFunctions.php?caller=getDebitNo',

            success: function(response){
                var respText= Ext.util.JSON.decode(response.responseText);
                var debitNo=respText.debits[0].debitNo;

                this.getDebit().down('#debitNo').setValue(debitNo);


            },
            scope: this
        });
    },

    saveVitals: function(button) {
        //Ext.Msg.alert('Test',button.getItemId());

        var form = button.up('panel').getForm(); // get the basic form
                    if (form.isValid()) { // make sure the form contains valid data before submitting
                        form.submit({
                            params:{
                               pid:button.up('form').down('#pid').getValue(),
                               encounterNo:button.up('form').down('#encounterNo').getValue(),
                            },
                            success: function (form, action) {
                                Ext.Msg.alert('Thank you!', 'The Vitals has been saved Successfully.');
                                button.up('form').getForm().reset();
                                button.up('window').hide();

                            },
                            failure: function (form, action) {
                                var jsonResp = Ext.decode(action.response.responseText);

                                Ext.Msg.alert('Failed', 'Could not save Vitals. \n Error=' + jsonResp.error);
                            }
                        });
                    } else { // display error alert if the data is invalid
                        Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
                    }
    },

    openVitals: function(view, rowIndex, colIndex, item, e, record, row) {
        var vitals=Ext.create('CarePortal.view.VitalsForm', {});
        var vitalsWindow=Ext.create('Ext.window.Window', {
            title: 'Patients Vitals',
            resizable:false
        });

        vitals.down('#pid').setValue(record.get('PID'));
        vitals.down('#names').setValue(record.get('Names'));
        vitals.down('#encounterNo').setValue(record.get('EncounterNo'));
        vitals.down('#Dob').setValue(record.get('BirthDate'));

        vitalsWindow.add(vitals);
        vitalsWindow.show();

    },

    searchItems: function(button) {
         var searchParam=this.getItemslist().down('#searchParam').getValue();

                var itemsListStore =Ext.data.StoreManager.lookup('ItemsListStore');
                itemsListStore.load({
                    params: {
                        searchParam: searchParam
                    },
                    callback: function(records, operation, success) {

                    },
                    scope: this

                });
    },

    closewindow: function(button) {
            button.up('window').close();
    },

    openDischargeForm: function(view, rowIndex, colIndex, item, e, record, row) {
        var discharge=Ext.create('CarePortal.view.Discharge', {});
        var dischargeWindow=Ext.create('Ext.window.Window', {
            title: 'Discharge Patient',
            resizable:false
        });

        var d=new Date();

        discharge.down('#pid').setValue(record.get('PID'));
        discharge.down('#names').setValue(record.get('Names'));
        discharge.down('#encounterNo').setValue(record.get('EncounterNo'));
        discharge.down('#Dob').setValue(record.get('BirthDate'));
        discharge.down('#dischargeDate').setValue(d);
        discharge.down('#dischargeTime').setValue(d);

        dischargeWindow.add(discharge);
        dischargeWindow.show();

    },

    discharge: function(button) {
        Ext.Msg.alert('Test',button.getItemId());

        var form = button.up('form').getForm(); // get the basic form

        Ext.Msg.show({
            title:'Save Changes?',
            message: 'You are closing a tab that has unsaved changes. Do you want to save changes?',
            buttons: Ext.Msg.YESNOCANCEL,
            icon: Ext.Msg.QUESTION,
            fn: function(btn) {
                if (btn === 'yes') {
                    console.log('Yes pressed');
                } else if (btn === 'no') {
                    console.log('No pressed');
                } else {
                    console.log('Cancel pressed');
                }
            }
        });

        if (form.isValid()) { // make sure the form contains valid data before submitting
            Ext.Msg.show({
            title:'Discharge Patient?',
            message: 'Are you sure you want to Discharge this Patient?',
            buttons: Ext.Msg.YESNOCANCEL,
            icon: Ext.Msg.QUESTION,
            fn: function(btn) {
                if (btn === 'yes') {
                    form.submit({
                            success: function (form, action) {
                                Ext.Msg.alert('Thank you!', 'Patient Discharged Successfully.');
                                button.up('form').getForm().reset();
                                button.up('window').hide();

                            },
                            failure: function (form, action) {
                                var jsonResp = Ext.decode(action.response.responseText);

                                Ext.Msg.alert('Failed', 'Could not Discharge patient \n Error=' + jsonResp.error);
                            }
                        });
                } else if (btn === 'no') {
                     button.up('form').getForm().reset();
                     button.up('window').hide();
                } else {
                    console.log('Cancel pressed');
                }
            }
        });

        } else { // display error alert if the data is invalid
               Ext.Msg.alert('Invalid Data', 'Please correct form errors.');
        }
    },

    getWaitingList: function(wardNo) {
        var waitingPanel=this.getWaitinglist();


        var waitingStore=Ext.data.StoreManager.lookup("WaitingListStore");

        waitingStore.load({
            params: {
                wardNo:wardNo
            },
            callback: function(records, operation, success) {
                    var tpl=new Ext.XTemplate(
                        '<Table id=notes>',
                        '<tr><td colspan=2 class=titles>Waiting List</td></tr>',
                                '<tr><td class=titles>Pid</td>',
                                    '<td class=titles>Names</td>',
                         '<tpl for=".">',
                                '<tr>',
                                    '<td>{Pid}</td>',
                                    '<td><div class=content>xzx{Names}</div></td>',
                                '</tr>',
                        '</tpl>',
                        '</table>'
                    );

                var data = [];

                waitingStore.each(function(record) {
                    data.push(record.getData());
                });

                 //var data1=notesStore.proxy.reader.rawData;
                tpl.overwrite(waitingPanel.body,data);
            },
            scope: this
        });
    }

});
