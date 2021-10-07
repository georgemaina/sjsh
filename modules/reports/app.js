/*
 * File: app.js
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

// @require @packageOverrides
Ext.Loader.setConfig({

});


Ext.application({
    models: [
        'Diseases',
        'Wards',
        'DischargeTypes',
        'NhifClaims',
        'FinalisedInvoices',
        'StockMovement',
        'XrayPatientStatement',
        'PaymentPlans',
        'TransTypes',
        'StockLevels',
        'LabTestResultsModel'
    ],
    stores: [
        'DiseasesStore',
        'TopDiseases',
        'WardStore',
        'DischargesStore',
        'LabActivities',
        'NhifClaimStore',
        'FinalisedStore',
        'StockMovementStore',
        'LabParams',
        'RevenueByCatStore',
        'PaymentPlanStore',
        'TransTypeStore',
        'StockLevelsStore',
        'LabTestResultStore'
    ],
    views: [
        'StockLevels',
        'PatientLabResults'
    ],
    controllers: [
        'Main'
    ],
    name: 'ReportsMain',

    launch: function() {
        Ext.create('ReportsMain.view.ReportsMain');
    }

});
