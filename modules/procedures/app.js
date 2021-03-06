/*
 * File: app.js
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

// @require @packageOverrides
Ext.Loader.setConfig({
    enabled: true
});


Ext.application({
    models: [
        'Anesthesia_Fluids',
        'Anesthesia_Monitors',
        'Anesthesia_Vitals'
    ],
    stores: [
        'AnesthesiaVitals'
    ],
    views: [
        'MyForm3',
        'MyForm4',
        'MyForm5',
        'MyForm6',
        'MyForm7'
    ],
    controllers: [
        'Main'
    ],
    name: 'Procedures',

    launch: function() {
        Ext.create('Procedures.view.ProceduresMain');
    }

});
