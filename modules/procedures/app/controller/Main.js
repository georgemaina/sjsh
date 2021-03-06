/*
 * File: app/controller/Main.js
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

Ext.define('Procedures.controller.Main', {
    extend: 'Ext.app.Controller',

    models: [
        'ProcedureClass',
        'StaffList',
        'ClassCodes',
        'ProcedureNames',
        'TheatreRooms',
        'TheatreList',
        'ProceduresList',
        'DiagnosisCodes',
        'PatientDetails',
        'ProcedureClassList',
        'ProcedureStatus',
        'SurgicalChecklist',
        'AnesthesiaCharts',
        'AnesPatientSafety',
        'SpongeCount'
    ],
    stores: [
        'ProceduresStore',
        'ProcedureClassStore',
        'ClassCodes',
        'StaffStore',
        'TheatreRoomsStore',
        'TheatreListStore',
        'DiagnosisStore',
        'PatientDetailsStore',
        'ProcedureClassListStore',
        'SurgicalChecklistStore',
        'ProcedureNamesStore',
        'ProcedureStatusStore',
        'AnesthesiaChartMonitors',
        'AnesthesiaChartFluids',
        'AnesthesiaChartAgents',
        'PatientSafetyStore',
        'SpongeCountStore'
    ],
    views: [
        'ProceduresMain',
        'ProcMenus',
        'BookingWindow',
        'ProceduresList',
        'BookingForm',
        'TheatreListGrid',
        'CheckList',
        'AnaesthesiaRecord',
        'CheckListGrid',
        'AnaesthesiaRecordGrid',
        'ProcedureClassGrid',
        'SpongeCountGrid',
        'PatientDetails'
    ],

    refs: [
        {
            ref: 'viewport',
            selector: 'proceduresmain',
            xtype: 'proceduresmain'
        },
        {
            ref: 'bookingform',
            selector: 'bookingform',
            xtype: 'bookingform'
        },
        {
            ref: 'procmenus',
            selector: 'procmenus',
            xtype: 'procmenus'
        },
        {
            ref: 'bookingwindow',
            selector: 'bookingwindow',
            xtype: 'bookingwindow'
        },
        {
            ref: 'theatrelist',
            selector: 'theatrelist',
            xtype: 'theatrelist'
        },
        {
            ref: 'procedureslist',
            selector: 'procedureslist',
            xtype: 'procedureslist'
        },
        {
            ref: 'checklist',
            selector: 'checklist',
            xtype: 'checklist'
        },
        {
            ref: 'checklistgrid',
            selector: 'checklistgrid',
            xtype: 'checklistgrid'
        },
        {
            autoCreate: true,
            ref: 'procedureclassgrid',
            selector: 'procedureclassgrid',
            xtype: 'procedureclassgrid'
        },
        {
            ref: 'patientdetails',
            selector: 'patientdetails',
            xtype: 'patientdetails'
        },
        {
            ref: 'spongecountgrid',
            selector: 'spongecountgrid',
            xtype: 'spongecountgrid'
        }
    ],

    init: function(application) {
        this.control({
            'bookingform button[id=cmdClose]': {
                click: this.closeForm
            },
            'debtorDetails': {
                itemclick: this.loadDebtorInfo
            },
            "procmenus menuitem[id=mnuNewBooking]": {
                click: this.openNewBooking
            },
            "procmenus menuitem[id=mnuTheatreList]": {
                click: this.openTheatreList
            },
            "procmenus menuitem[id=mnuProceduresList]": {
                click: this.openProceduresList
            },
            "procmenus menuitem[id=mnuCheckList]": {
                click: this.openCheckList
            },
            "procmenus menuitem[id=mnuAnaesthesia]": {
                click: this.openAnaesthesia
            },
            'bookingform button[id=cmdSave]': {
                click: this.saveBooking
            },
            'theatrelistgrid':{
                itemdblclick:this.loadBookingForm
            },
            'bookingform textfield[id=pid]':{
                blur:this.getPatientDetails
            },
            'theatrelistgrid actioncolumn[id=checklist]':{
                click:this.openCheckListForm
            },
            'theatrelistgrid actioncolumn[id=anaesthesia]':{
                click:this.openAnaesthesiaForm
            },
            'theatrelistgrid actioncolumn[id=spongeCountsRec]':{
                click:this.openSpongeCounts
            },
            'checklist button[id=cmdCreateChecklist]': {
                click: this.createCheckList1
            },
            'checklist button[id=cmdCreateChecklist2]': {
                click: this.createCheckList1
            },
            'checklist button[id=cmdCreateChecklist3]': {
                click: this.createCheckList1
            },
            'checklistgrid actioncolumn[id=editList]':{
                click:this.openCheckListForm2
            },
            'procmenus menuitem[id=procClassList]':{
                click:this.openProcedureClassList
            },
            'procedureclassgrid button[id=newClass]':{
                click:this.createNewClass
            },
            'procedureclassgrid button[id=cmdSaveClass]':{
                click:this.SaveNewClass
            },
            '#cmdSaveAnesthesia':{
                click:this.CreateAnesthesiaForm
            },
            '#cmdRefresh':{
                click:this.ReloadChartData
            },
            '#cmdSaveSponge':{
                click:this.saveSpongeCount
            }
        });
    },

    onLaunch: function() {
        procmenus=Ext.create("Procedures.view.ProcMenus",{});
        centerContainer=this.getViewport().down("container[region=west]");

        centerContainer.add(procmenus);
        //westContainer.hidden=true;

        _myAppGlobal = this;
    },

    openNewBooking: function() {
        var bookingform=Ext.create('Procedures.view.BookingForm', {});
        var bookingWindow=Ext.create('Procedures.view.BookingWindow', {});


        bookingWindow.add(bookingform);
        bookingWindow.show();

        bookingform.query('textfield[name="formStatus"]')[0].setValue('insert');
    },

    openTheatreList: function() {
        theatrelist = Ext.create('Procedures.view.TheatreListGrid', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(theatrelist);

        var theatreliststore=Ext.data.StoreManager.lookup('TheatreListStore');
        theatreliststore.load({});

    },

    openProceduresList: function() {
        procedureslist = Ext.create('Procedures.view.ProceduresList', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(procedureslist);
    },

    saveBooking: function(button) {
        //alert('test');
        var form = button.up('form').getForm(); // get the basic form
        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                success: function(form, action) {
                    Ext.Msg.alert('Success', 'Saved new Item successfully.');

                    // var win = button.up('window');
                    // win.removeAll();
                    // win.close();

                },
                failure: function(form, action) {
                    if(action.result.errNo==1){
                        Ext.Msg.alert('Failed', 'Booking with Pid '+action.result.errNo+' already exists');
                    }else{
                        Ext.Msg.alert('Failed', 'Could not save Booking. Error='+action.result.errN);
                    }

                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors and continue');
        }

    },

    closeForm: function(button) {
        var win = button.up('bookingwindow');
        win.destroy();
    },

    loadBookingForm: function(gridpanel, record, item, index, e, eOpts) {
        //alert("Test Register");
        var bookingwindow=Ext.create('Procedures.view.BookingWindow', {});
        var bookingform=Ext.create('Procedures.view.BookingForm', {});

        //this.getForm().loadRecord(record);


        //Ext.getCmp('formStatus').setValue('update');
        bookingform.query('textfield[name="formStatus"]')[0].setValue('update');

        //dbRegisterForm.show();
        bookingwindow.add(bookingform);
        bookingwindow.show();
        bookingform.getForm().loadRecord(record);
    },

    getPatientDetails: function(record) {

        var patientsInfoStore=Ext.data.StoreManager.lookup('PatientDetailsStore');
        var pid=Ext.getCmp('pid').getValue();
        //alert(pid);

        patientsInfoStore.load({
            params: {
                pid: pid
            },
            callback: function(records, operation, success) {
                //alert(records[0].data.pnames);
                Ext.getCmp('selian_pid').setValue(records[0].data.selian_pid);
                Ext.getCmp('pnames').setValue(records[0].data.pnames);
                Ext.getCmp('date_birth').setValue(records[0].data.date_birth);
                Ext.getCmp('sex').setValue(records[0].data.sex);
                //Ext.getCmp('encounter_nr').setValue(records[0].data.encounter_nr);

                this.getBookingform().down("textfield[name=encounter_nr]").setValue(records[0].data.encounter_nr);
                // this.getBookingform().down("textfield[name=BoookingNo]").setValue(records[0].data.BookingNo);
            },
            scope: this

        });
    },

    openCheckList: function() {
        checklist = Ext.create('Procedures.view.CheckListGrid', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(checklist);


        var checkliststore=Ext.data.StoreManager.lookup('SurgicalChecklistStore');
        checkliststore.load({});

    },

    openAnaesthesia: function() {
        anaesthesia = Ext.create('Procedures.view.AnaesthesiaRecordGrid', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(anaesthesia);
    },

    openCheckListForm: function(view, rowIndex, colIndex, item, e, record, row) {
        checklistform = Ext.create('Procedures.view.CheckList', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        console.log(record.get('pnames'));

        checklistform.getForm().findField("pnamesDisp").setValue(record.get('pnames'));
        checklistform.getForm().findField("pid").setValue(record.get('pid'));
        checklistform.getForm().findField("procedure_type").setValue(record.get('procedure_type'));
        checklistform.getForm().findField("procedure_date").setValue(record.get('procedure_date'));

        checklistform.getForm().findField("pname").setValue(record.get('pnames'));
        checklistform.getForm().findField("pid2").setValue(record.get('pid'));
        checklistform.getForm().findField("procedure_name").setValue(record.get('procedure_type'));
        checklistform.getForm().findField("procedure_date2").setValue(record.get('procedure_date'));
        checklistform.getForm().findField("encounter_nr").setValue(record.get('encounter_nr'));

        checklistform.getForm().findField("pname3").setValue(record.get('pnames'));
        checklistform.getForm().findField("pid3").setValue(record.get('pid'));
        checklistform.getForm().findField("procedure_name3").setValue(record.get('procedure_type'));
        checklistform.getForm().findField("procedure_date3").setValue(record.get('procedure_date'));
        checklistform.getForm().findField("encounter_nr2").setValue(record.get('encounter_nr'));

        checklistform.getForm().findField("pname4").setValue(record.get('pnames'));
        checklistform.getForm().findField("pid4").setValue(record.get('pid'));
        checklistform.getForm().findField("procedure_name4").setValue(record.get('procedure_type'));
        checklistform.getForm().findField("procedure_date4").setValue(record.get('procedure_date'));
        checklistform.getForm().findField("encounter_nr4").setValue(record.get('encounter_nr'));


        center_container.add(checklistform);

    },

    openAnaesthesiaForm: function(view, rowIndex, colIndex, item, e, record, row) {
        anesthesiaform = Ext.create('Procedures.view.AnaesthesiaRecord', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(anesthesiaform);

        //anesthesiaform.getForm().loadRecord(record);

        anesthesiaform.getForm().findField("pnames").setValue(record.get('pnames'));
        anesthesiaform.getForm().findField("pid").setValue(record.get('pid'));
        anesthesiaform.getForm().findField("procedure_type").setValue(record.get('procedure_type'));
        anesthesiaform.getForm().findField("procedure_date").setValue(record.get('procedure_date'));
        anesthesiaform.getForm().findField("encounter_nr").setValue(record.get('encounter_nr'));
        anesthesiaform.getForm().findField("allergies").setValue(record.get('allergies'));
        anesthesiaform.getForm().findField("scrub_nurse").setValue(record.get('scrub_nurse'));
        anesthesiaform.getForm().findField("surgeon").setValue(record.get('surgeon'));


        var anesthesiaChartAgentsStore=Ext.data.StoreManager.lookup('AnesthesiaChartAgents');
        anesthesiaChartAgentsStore.load({
            params:{
                encNr:record.get('encounter_nr')
            },
            scope: this
        });

        var anesthesiaChartFluidsStore=Ext.data.StoreManager.lookup('AnesthesiaChartFluids');
        anesthesiaChartFluidsStore.load({
            params:{
                encNr:record.get('encounter_nr')
            },
            scope: this
        });

        var anesthesiaChartMonitorsStore=Ext.data.StoreManager.lookup('AnesthesiaChartMonitors');
        anesthesiaChartMonitorsStore.load({
            params:{
                encNr:record.get('encounter_nr')
            },
            scope: this
        });

        var anesthesiaChartVitalsStore=Ext.data.StoreManager.lookup('AnesthesiaVitals');
        anesthesiaChartVitalsStore.load({
            params:{
                encNr:record.get('encounter_nr')
            },
            scope: this
        });

        var patientSafetyStore=Ext.data.StoreManager.lookup('PatientSafetyStore');
        patientSafetyStore.load({
            params:{
                encNr:record.get('encounter_nr')
            },
            scope: this
        });

        anesthesiaform.getForm().loadRecord(patientSafetyStore.data.first());
    },

    createCheckList1: function(button) {
        //alert('test');
        var form = button.up('form').getForm(); // get the basic form

        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                //params : {pid : pid},
                success: function(form, action) {
                    Ext.Msg.alert('Success', 'Saved new Item successfully.');

                    var win = button.up('window');
                    win.removeAll();
                    win.close();

                },
                failure: function(form, action) {
                    if(action.result.errNo==1){
                        Ext.Msg.alert('Failed', 'Checklist for patient '+action.result.errNo+' already exists');
                    }else{
                        Ext.Msg.alert('Failed', 'Could not save Checklist. Error='+action.result.errNo);
                    }

                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors and continue');
        }

    },

    openCheckListForm2: function(view, rowIndex, colIndex, item, e, record, row) {
        checklistform = Ext.create('Procedures.view.CheckList', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(checklistform);

        checklistform.getForm().loadRecord(record);

    },

    openProcedureClassList: function() {
        procedureclassgrid = Ext.create('Procedures.view.ProcedureClassGrid', {});
        center_container = this.getViewport().down('container[region=center]');
        center_container.removeAll();

        center_container.add(procedureclassgrid);

        procedureclassstore=Ext.data.StoreManager.lookup('ProcedureClassListStore');
        procedureclassstore.load({});
    },

    createNewClass: function(button) {
        var newItem;
        var procedureclassstore=Ext.data.StoreManager.lookup('ProcedureClassListStore');

        procedureclassstore.add({
            ID: procedureclassstore.getTotalCount( )+1,
            proc_class:'',
            class_value:'',
            cost:0
        });

    },

    SaveNewClass: function(button) {
        var procedureclassstore=Ext.data.StoreManager.lookup('ProcedureClassListStore');
        procedureclassstore.sync({
            success: function(batch) {
                Ext.Msg.alert('Success!', 'Changes saved successfully '+batch.operations[0].request.scope.reader.jsonData.errNo);
            },
            failure: function(batch) {
                Ext.Msg.alert("Failed",'failed to save');
            }
        });
    },

    CreateAnesthesiaForm: function(button) {
        //alert('test');
        var form = button.up('form').getForm(); // get the basic form

        if (form.isValid()) { // make sure the form contains valid data before submitting
            form.submit({
                //params : {pid : pid},
                success: function(form, action) {
                    Ext.Msg.alert('Success', 'Saved new Item successfully.');

                },
                failure: function(form, action) {
                    if(action.result.errNo==1){
                        Ext.Msg.alert('Failed', 'Checklist for patient '+action.result.errNo+' already exists');
                    }else{
                        Ext.Msg.alert('Failed', 'Could not save Anesthesia. Error='+action.result.errNo);
                    }

                }
            });
        } else { // display error alert if the data is invalid
            Ext.Msg.alert('Invalid Data', 'Please correct form errors and continue');
        }

    },

    ReloadChartData: function(button) {
        //var form = button.up('form').getForm(); // get the basic form
        var encNr = button.up('form').getForm().findField("encounter_nr").getValue();
        //var encNr=Ext.getCmp('encounter_nr').getValue();

        var anesthesiaChartAgentsStore=Ext.data.StoreManager.lookup('AnesthesiaChartAgents');
        anesthesiaChartAgentsStore.load({
            params:{
                encNr:encNr
            },
            scope: this
        });

        var anesthesiaChartFluidsStore=Ext.data.StoreManager.lookup('AnesthesiaChartFluids');
        anesthesiaChartFluidsStore.load({
            params:{
                encNr:encNr
            },
            scope: this
        });

        var anesthesiaChartMonitorsStore=Ext.data.StoreManager.lookup('AnesthesiaChartMonitors');
        anesthesiaChartMonitorsStore.load({
            params:{
                encNr:encNr
            },
            scope: this
        });

        var anesthesiaChartVitalsStore=Ext.data.StoreManager.lookup('AnesthesiaVitals');
        anesthesiaChartVitalsStore.load({
            params:{
                encNr:encNr
            },
            scope: this
        });
    },

    openSpongeCounts: function(view, rowIndex, colIndex, item, e, record, row) {
        //center_container = this.getViewport().down('container[region=center]');
        //center_container.removeAll();

        patiendetails = Ext.create('Procedures.view.PatientDetails', {});
        spongeCountGrid = Ext.create('Procedures.view.SpongeCountGrid', {});

        var spongeCountStore=Ext.data.StoreManager.lookup('SpongeCountStore');
        spongeCountStore.load({
            params:{
                pid:record.get('Pid'),
                pnames:record.get('Pnames'),
                encNr:record.get('encounter_nr'),
                procedureNo:record.get('BookingNo'),
                procedureType:record.get('procedure_type')
            },
            scope: this
        });

        patiendetails.getForm().loadRecord(record);

        var win=Ext.create('Ext.window.Window',{
        	title: 'Payment Types'
        });

        win.add(patiendetails,spongeCountGrid);

        win.show();
    },

    saveSpongeCount: function(button) {
        var spongeCountStore=Ext.data.StoreManager.lookup('SpongeCountStore');
        spongeCountStore.sync({
            success: function(batch) {
                Ext.Msg.alert('Success!', 'Changes saved successfully '+batch.operations[0].request.scope.reader.jsonData.errNo);
            },
            failure: function(batch) {
                Ext.Msg.alert("Failed",'failed to save');
            }
        });
    }

});
