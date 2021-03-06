/*
 * File: app/view/HhaMain.js
 *
 * This file was generated by Sencha Architect version 4.2.3.
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

Ext.define('hha.view.HhaMain', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.hhamain',

    requires: [
        'hha.view.HhaMainViewModel',
        'Ext.menu.Menu',
        'Ext.menu.Item',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.toolbar.Paging',
        'Ext.form.FieldContainer',
        'Ext.form.field.Text',
        'Ext.button.Button',
        'Ext.grid.column.Column'
    ],

    viewModel: {
        type: 'hhamain'
    },
    layout: 'border',

    items: [
        {
            xtype: 'container',
            region: 'center'
        },
        {
            xtype: 'panel',
            region: 'west',
            autoRender: true,
            resizable: true,
            width: 308,
            layout: 'anchor',
            animCollapse: true,
            collapsible: true,
            title: 'HHA Menus',
            items: [
                {
                    xtype: 'menu',
                    floating: false,
                    height: 150,
                    items: [
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuInitialEncounter',
                            text: 'HTN Initial Encounter',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuContinuationCare',
                            text: 'HTN Continuation of Care',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuPatientsList',
                            text: 'Patients List',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuTreatmentRegister',
                            text: 'Treatment Register',
                            focusable: true
                        },
                        {
                            xtype: 'menuitem',
                            itemId: 'mnuSyncdata',
                            text: 'Sync With Server',
                            focusable: true
                        }
                    ]
                },
                {
                    xtype: 'gridpanel',
                    height: 403,
                    itemId: 'encountersGrid',
                    minHeight: 400,
                    title: 'Current Encounters',
                    columnLines: true,
                    store: 'EncountersStore',
                    dockedItems: [
                        {
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'EncountersStore'
                        },
                        {
                            xtype: 'fieldcontainer',
                            dock: 'top',
                            height: 36,
                            width: 100,
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [
                                {
                                    xtype: 'textfield',
                                    itemId: 'txtSearch',
                                    width: 185
                                },
                                {
                                    xtype: 'button',
                                    itemId: 'cmdSearch',
                                    width: 90,
                                    text: '<b>Search >>></b>'
                                }
                            ]
                        }
                    ],
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            width: 46,
                            dataIndex: 'PID',
                            text: 'Pid'
                        },
                        {
                            xtype: 'gridcolumn',
                            width: 137,
                            dataIndex: 'PatientName',
                            text: 'Patient Name'
                        },
                        {
                            xtype: 'gridcolumn',
                            width: 62,
                            dataIndex: 'EncounterNo',
                            text: 'Encounter No'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'EncounterTime',
                            text: 'Encounter Time'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Sex',
                            text: 'Sex'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'DOB',
                            text: 'Dob'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ScreeningDate',
                            text: 'Screening Date'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'MobileNumber',
                            text: 'Mobile Number'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'NationalID',
                            text: 'National Id'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'UniqueID',
                            text: 'Unique Id'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'County',
                            text: 'County'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Location',
                            text: 'Location'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ClinicianName',
                            text: 'Clinician Name'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'FacilityID',
                            text: 'Facility Id'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'FacilityName',
                            text: 'Facility Name'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ScreeningCode',
                            text: 'Screening Code'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ScreeningSite',
                            text: 'Screening Site'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPInitial1',
                            text: 'Bpinitial1'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPInitial2',
                            text: 'Bpinitial2'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Weight',
                            text: 'Weight'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Height',
                            text: 'Height'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BMI',
                            text: 'Bmi'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPFirstReading1',
                            text: 'Bpfirst Reading1'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPFirstReading2',
                            text: 'Bpfirst Reading2'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPSecondReading1',
                            text: 'Bpsecond Reading1'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'BPSecondReading2',
                            text: 'Bpsecond Reading2'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Normal',
                            text: 'Normal'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Pre_hypertensive',
                            text: 'Pre Hypertensive'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Hypertensive',
                            text: 'Hypertensive'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ReturnPatient',
                            text: 'Return Patient'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Observations',
                            text: 'Observations'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'LMP',
                            text: 'Lmp'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'DrugAllergies',
                            text: 'Drug Allergies'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'AllergiesDetails',
                            text: 'Allergies Details'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'AdheringMedications',
                            text: 'Adhering Medications'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'AdheringMedicationsDetails',
                            text: 'Adhering Medications Details'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'MildHypertensionLife',
                            text: 'Mild Hypertension Life'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'MildHypertensionDiuretic',
                            text: 'Mild Hypertension Diuretic'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'MildHypertensionCcbs',
                            text: 'Mild Hypertension Ccbs'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'MildHypertensionOthers',
                            text: 'Mild Hypertension Others'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ModerateHypetensionLife',
                            text: 'Moderate Hypetension Life'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ModerateHypetensionDiuretic',
                            text: 'Moderate Hypetension Diuretic'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ModerateHypetensionCcbs',
                            text: 'Moderate Hypetension Ccbs'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ModerateHypetensionOthers',
                            text: 'Moderate Hypetension Others'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'ModerateHypetensionAce',
                            text: 'Moderate Hypetension Ace'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'FollowupPlan',
                            text: 'Followup Plan'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Clinician',
                            text: 'Clinician'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'Designation',
                            text: 'Designation'
                        }
                    ]
                }
            ]
        }
    ]

});