/*
 * File: app/view/Occupancy.js
 * Date: Mon Oct 22 2018 10:41:52 GMT+0300 (E. Africa Standard Time)
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

Ext.define('ReportsMain.view.Occupancy', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.occupancy',

    requires: [
        'ReportsMain.view.OccupancyViewModel',
        'Ext.chart.CartesianChart',
        'Ext.chart.axis.Category3D',
        'Ext.chart.axis.Numeric3D',
        'Ext.chart.series.Bar3D'
    ],

    viewModel: {
        type: 'occupancy'
    },
    height: 615,
    title: 'Occupancy Report',

    items: [
        {
            xtype: 'cartesian',
            height: 250,
            insetPadding: 20,
            store: 'WardInfoStore',
            theme: 'muted',
            axes: [
                {
                    type: 'category3d',
                    fields: [
                        'Ward'
                    ],
                    grid: true,
                    position: 'bottom',
                    title: 'List of Wards'
                },
                {
                    type: 'numeric3d',
                    renderer: function(axis, label, layoutContext, lastLabel) {
                        // Custom renderer overrides the native axis label renderer.
                        // Since we don't want to do anything fancy with the value
                        // ourselves except adding a thousands separator, but at the same time
                        // don't want to loose the formatting done by the native renderer,
                        // we let the native renderer process the value first.
                        return Ext.util.Format.number(layoutContext.renderer(label) / 1000, '0,000');

                    },
                    fields: [
                        'Occupied'
                    ],
                    grid: {
                        odd: {
                            fillStyle: 'rgba(255, 255, 255, 0.06)'
                        },
                        even: {
                            fillStyle: 'rgba(0, 0, 0, 0.03)'
                        }
                    },
                    maximum: 150,
                    position: 'left',
                    title: 'Occupancy'
                }
            ],
            series: [
                {
                    type: 'bar3d',
                    highlightCfg: {
                        saturationFactor: 1.5
                    },
                    label: {
                        field: 'Occupied',
                        display: 'insideEnd'
                    },
                    style: {
                        minGapWidth: 20
                    },
                    xField: 'Ward',
                    yField: [
                        'Occupied'
                    ]
                }
            ]
        },
        {
            xtype: 'panel',
            title: 'Occupancy Data'
        }
    ]

});