/*
 * File: app/view/OrdersTemplateViewModel.js
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

Ext.define('Pharmacy.view.OrdersTemplateViewModel', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.orderstemplate',

    requires: [
        'Ext.data.Store',
        'Ext.data.proxy.Memory'
    ],

    stores: {
        ordersTemplates: {
            model: 'Pharmacy.model.OrdersTemplate',
            data: [
                {
                    PartCode: 'et',
                    Description: 'quia',
                    PurchasingUnit: 'fugit',
                    Level: 'dolores',
                    MonthlyUsage: 'qui',
                    ReorderLevel: 'ut'
                },
                {
                    PartCode: 'quam',
                    Description: 'qui',
                    PurchasingUnit: 'ea',
                    Level: 'asperiores',
                    MonthlyUsage: 'aut',
                    ReorderLevel: 'aut'
                },
                {
                    PartCode: 'laboriosam',
                    Description: 'sequi',
                    PurchasingUnit: 'dolorem',
                    Level: 'quasi',
                    MonthlyUsage: 'maiores',
                    ReorderLevel: 'mollitia'
                },
                {
                    PartCode: 'tenetur',
                    Description: 'repellat',
                    PurchasingUnit: 'ipsam',
                    Level: 'itaque',
                    MonthlyUsage: 'ea',
                    ReorderLevel: 'voluptate'
                },
                {
                    PartCode: 'et',
                    Description: 'qui',
                    PurchasingUnit: 'ad',
                    Level: 'sunt',
                    MonthlyUsage: 'voluptas',
                    ReorderLevel: 'voluptatum'
                },
                {
                    PartCode: 'sed',
                    Description: 'numquam',
                    PurchasingUnit: 'esse',
                    Level: 'et',
                    MonthlyUsage: 'quia',
                    ReorderLevel: 'fugit'
                },
                {
                    PartCode: 'necessitatibus',
                    Description: 'distinctio',
                    PurchasingUnit: 'aut',
                    Level: 'sit',
                    MonthlyUsage: 'qui',
                    ReorderLevel: 'enim'
                },
                {
                    PartCode: 'error',
                    Description: 'necessitatibus',
                    PurchasingUnit: 'amet',
                    Level: 'quia',
                    MonthlyUsage: 'qui',
                    ReorderLevel: 'et'
                },
                {
                    PartCode: 'quia',
                    Description: 'et',
                    PurchasingUnit: 'deserunt',
                    Level: 'placeat',
                    MonthlyUsage: 'minus',
                    ReorderLevel: 'voluptatem'
                },
                {
                    PartCode: 'ut',
                    Description: 'assumenda',
                    PurchasingUnit: 'culpa',
                    Level: 'rem',
                    MonthlyUsage: 'qui',
                    ReorderLevel: 'velit'
                },
                {
                    PartCode: 'quaerat',
                    Description: 'sit',
                    PurchasingUnit: 'est',
                    Level: 'odio',
                    MonthlyUsage: 'totam',
                    ReorderLevel: 'quis'
                },
                {
                    PartCode: 'ullam',
                    Description: 'doloremque',
                    PurchasingUnit: 'ut',
                    Level: 'ut',
                    MonthlyUsage: 'laborum',
                    ReorderLevel: 'ullam'
                },
                {
                    PartCode: 'et',
                    Description: 'libero',
                    PurchasingUnit: 'repellendus',
                    Level: 'nobis',
                    MonthlyUsage: 'fugiat',
                    ReorderLevel: 'consectetur'
                },
                {
                    PartCode: 'hic',
                    Description: 'quia',
                    PurchasingUnit: 'voluptatem',
                    Level: 'vitae',
                    MonthlyUsage: 'numquam',
                    ReorderLevel: 'qui'
                },
                {
                    PartCode: 'doloremque',
                    Description: 'incidunt',
                    PurchasingUnit: 'quia',
                    Level: 'sed',
                    MonthlyUsage: 'consequatur',
                    ReorderLevel: 'distinctio'
                },
                {
                    PartCode: 'illum',
                    Description: 'voluptatem',
                    PurchasingUnit: 'fugiat',
                    Level: 'quos',
                    MonthlyUsage: 'ut',
                    ReorderLevel: 'corrupti'
                },
                {
                    PartCode: 'reiciendis',
                    Description: 'nam',
                    PurchasingUnit: 'eos',
                    Level: 'ut',
                    MonthlyUsage: 'quasi',
                    ReorderLevel: 'doloremque'
                },
                {
                    PartCode: 'earum',
                    Description: 'voluptatem',
                    PurchasingUnit: 'laboriosam',
                    Level: 'quis',
                    MonthlyUsage: 'quisquam',
                    ReorderLevel: 'alias'
                },
                {
                    PartCode: 'illum',
                    Description: 'quod',
                    PurchasingUnit: 'et',
                    Level: 'distinctio',
                    MonthlyUsage: 'velit',
                    ReorderLevel: 'ad'
                },
                {
                    PartCode: 'dicta',
                    Description: 'consequatur',
                    PurchasingUnit: 'ratione',
                    Level: 'et',
                    MonthlyUsage: 'beatae',
                    ReorderLevel: 'beatae'
                },
                {
                    PartCode: 'dolor',
                    Description: 'consectetur',
                    PurchasingUnit: 'et',
                    Level: 'explicabo',
                    MonthlyUsage: 'qui',
                    ReorderLevel: 'voluptatem'
                },
                {
                    PartCode: 'dolorem',
                    Description: 'reiciendis',
                    PurchasingUnit: 'culpa',
                    Level: 'omnis',
                    MonthlyUsage: 'dolorem',
                    ReorderLevel: 'ut'
                },
                {
                    PartCode: 'porro',
                    Description: 'asperiores',
                    PurchasingUnit: 'reiciendis',
                    Level: 'dolorum',
                    MonthlyUsage: 'molestiae',
                    ReorderLevel: 'omnis'
                },
                {
                    PartCode: 'et',
                    Description: 'laboriosam',
                    PurchasingUnit: 'qui',
                    Level: 'minus',
                    MonthlyUsage: 'vel',
                    ReorderLevel: 'dignissimos'
                },
                {
                    PartCode: 'esse',
                    Description: 'enim',
                    PurchasingUnit: 'quia',
                    Level: 'esse',
                    MonthlyUsage: 'quis',
                    ReorderLevel: 'id'
                },
                {
                    PartCode: 'enim',
                    Description: 'eveniet',
                    PurchasingUnit: 'sapiente',
                    Level: 'in',
                    MonthlyUsage: 'quia',
                    ReorderLevel: 'enim'
                },
                {
                    PartCode: 'asperiores',
                    Description: 'reiciendis',
                    PurchasingUnit: 'animi',
                    Level: 'aut',
                    MonthlyUsage: 'aliquid',
                    ReorderLevel: 'voluptatem'
                },
                {
                    PartCode: 'temporibus',
                    Description: 'quo',
                    PurchasingUnit: 'aut',
                    Level: 'minima',
                    MonthlyUsage: 'quis',
                    ReorderLevel: 'voluptatibus'
                },
                {
                    PartCode: 'ducimus',
                    Description: 'vero',
                    PurchasingUnit: 'nam',
                    Level: 'qui',
                    MonthlyUsage: 'corrupti',
                    ReorderLevel: 'culpa'
                },
                {
                    PartCode: 'rerum',
                    Description: 'minima',
                    PurchasingUnit: 'est',
                    Level: 'ipsum',
                    MonthlyUsage: 'quibusdam',
                    ReorderLevel: 'vero'
                }
            ],
            proxy: {
                type: 'memory'
            }
        }
    }

});