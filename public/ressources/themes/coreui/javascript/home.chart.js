$(document).ready(function(){

    /*================================[ Init ]==================================*/

    Routing.setBaseUrl($('#base_dir').val());
    var api = {
        backgroundColor: [
            '#187da0',//'rgba(220, 220, 220, 1)',
            'orange',//'rgba(220, 220, 210, 1)',
            'red',//'rgba(220, 220, 200, 1)'
        ],
        routing: {
            order: {
                week: Routing.generate('statistic_order_week'),
                month: Routing.generate('statistic_order_month'),
                year: Routing.generate('statistic_order_year'),
            },
            countOrder: {
                week: Routing.generate('statistic_count_order_week'),
                month: Routing.generate('statistic_count_order_month'),
                year: Routing.generate('statistic_count_order_year'),
            },
            countQuote: {
                week: Routing.generate('statistic_count_quote_week'),
                month: Routing.generate('statistic_count_quote_month'),
                year: Routing.generate('statistic_count_quote_year'),
            },
            countRefund: {
                week: Routing.generate('statistic_count_refund_week'),
                month: Routing.generate('statistic_count_refund_month'),
                year: Routing.generate('statistic_count_refund_year'),
            },
            countValid: {
                week: Routing.generate('statistic_count_valid_week'),
                month: Routing.generate('statistic_count_valid_month'),
                year: Routing.generate('statistic_count_valid_year'),
            },
        }
    };

    Chart.defaults.global.pointHitDetectionRadius = 1;
    Chart.defaults.global.tooltips.enabled = true;
    Chart.defaults.global.tooltips.mode = 'index';
    Chart.defaults.global.tooltips.position = 'nearest';
    //Chart.defaults.global.tooltips.custom = coreui.ChartJS.customTooltips;
    Chart.defaults.global.defaultFontColor = '#646470';
    Chart.defaults.global.responsiveAnimationDuration = 1; 

    /*==========================[ début programme ]================================*/

    $(function () {
        //$('[data-toggle="tooltip"]').tooltip();
        loadChart();
        loadOrderChart();
        loadQuoteChart();
        loadRefundChart();
        loadValidChart();        
    });

    /*================================[ Events ]==================================*/

    $(function () {

        $('[name="options"]').change(function () {

            if ($(this).attr('id') == 'optionWeek')
                generateGeneralChart(api.routing.order.week);
            else if ($(this).attr('id') == 'optionMonth')
                generateGeneralChart(api.routing.order.month);
            else if ($(this).attr('id') == 'optionYear')
                generateGeneralChart(api.routing.order.year);
        });

        // document.body.addEventListener('classtoggle', function (event) {
        //     if (event.detail.className === 'c-dark-theme') {
        //         if (document.body.classList.contains('c-dark-theme')) {
        //             cardChart1.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--primary-dark-theme');
        //             cardChart2.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--info-dark-theme');
        //             Chart.defaults.global.defaultFontColor = '#fff';
        //         } else {
        //             cardChart1.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--primary');
        //             cardChart2.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--info');
        //             Chart.defaults.global.defaultFontColor = '#646470';
        //         }
        //         cardChart1.update(); cardChart2.update(); mainChart.update();
        //     }
        // });

    });
    
    /*================================[ Functions ]==================================*/

    function loadChart() {
        if ($('.home-wrapper').length > 0) {
            generateGeneralChart(api.routing.order.month);
        }
    }

    function loadOrderChart() {
        if ($('#card-chart1').length > 0) {
            orderChart(api.routing.countOrder.month);
        }
    }

    function loadQuoteChart() {
        if ($('#card-chart1').length > 0) {
            quoteChart(api.routing.countQuote.month);
        }
    }

    function loadRefundChart() {
        if ($('#card-chart1').length > 0) {
            refundChart(api.routing.countRefund.month);
        }
    }

    function loadValidChart() {
        if ($('#card-chart1').length > 0) {
            validChart(api.routing.countValid.month);
        }
    }

    function generateGeneralChart(routePeriode) {
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if (data.length > 0) {
                    new Chart($('#myChart0'), {
                        type: 'line',
                        data: {
                            labels: data[0]['axisX'],
                            datasets: extractDataSets(data)
                        },
                        options: {
                            responsive: true,
                            tooltips: {
                                mode: 'index'
                            }
                        }
                    });
                }
                $.fn.loading('hide');
            },
        });
    }

    function orderChart(routePeriode) {
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if (data) {
                    new Chart($('#card-chart1'),
                        {
                            type: 'line',
                            data: {
                                labels: data['axisX'],
                                datasets: [
                                    {
                                        label: data['title'],
                                        backgroundColor: 'transparent',
                                        borderColor: 'rgba(255,255,255,.55)',
                                        pointBackgroundColor: coreui.Utils.getStyle('--primary'),
                                        data: _.map(data['axisX'], function (elt) {
                                            return data.data[elt];
                                        })
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                legend: { display: false },
                                scales: {
                                    xAxes: [
                                        {
                                            gridLines: {
                                                color: 'transparent',
                                                zeroLineColor: 'transparent'
                                            },
                                            ticks: {
                                                fontSize: 2,
                                                fontColor: 'transparent'
                                            }
                                        }
                                    ],
                                    yAxes: [{
                                        display: false, ticks: {
                                            display: false, 
                                            min: _.min(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                            , 
                                            max: _.max(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            })) 
                                        } 
                                    }]
                                },
                                elements: {
                                    line: { borderWidth: 1 },
                                    point: {
                                        radius: 4,
                                        hitRadius: 10,
                                        hoverRadius: 4
                                    }
                                }
                            }
                        }); 
                }
                $.fn.loading('hide');
            },
        });
    }

    function quoteChart(routePeriode) {
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if (data) {
                    new Chart($('#card-chart2'),
                        {
                            type: 'line',
                            data: {
                                labels: data['axisX'],
                                datasets: [
                                    {
                                        label: data['title'],
                                        backgroundColor: 'transparent',
                                        borderColor: 'rgba(255,255,255,.55)',
                                        pointBackgroundColor: coreui.Utils.getStyle('--info'),
                                        data: _.map(data['axisX'], function (elt) {
                                            return data.data[elt];
                                        })
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                legend: { display: false },
                                scales: {
                                    xAxes: [
                                        {
                                            gridLines: {
                                                color: 'transparent',
                                                zeroLineColor: 'transparent'
                                            },
                                            ticks: { fontSize: 2, fontColor: 'transparent' }
                                        }
                                    ],
                                    yAxes: [{
                                        display: false, ticks: {
                                            display: false,
                                            min: _.min(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                            ,
                                            max: _.max(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                        }
                                    }]
                                },
                                elements: {
                                    line: {
                                        tension: 0.00001,
                                        borderWidth: 1
                                    },
                                    point: {
                                        radius: 4,
                                        hitRadius: 10,
                                        hoverRadius: 4
                                    }
                                }
                            }
                        });
                }
                $.fn.loading('hide');
            },
        });
    }

    function refundChart(routePeriode) {
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if (data) {
                    new Chart($('#card-chart3'),
                        {
                            type: 'line',
                            data: {
                                labels: data['axisX'],
                                datasets: [
                                    {
                                        label: data['title'],
                                        backgroundColor: 'rgba(255,255,255,.2)',
                                        borderColor: 'rgba(255,255,255,.55)',
                                        data: _.map(data['axisX'], function (elt) {
                                            return data.data[elt];
                                        })
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                legend: { display: false },
                                scales: {
                                    xAxes: [{ display: false }],
                                    yAxes: [{
                                        display: false, ticks: {
                                            display: false,
                                            min: _.min(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                            ,
                                            max: _.max(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                        }
                                    }]
                                },
                                elements: {
                                    line: { borderWidth: 2 },
                                    point: { radius: 0, hitRadius: 10, hoverRadius: 4 }
                                }
                            }
                        }); 
                }
                $.fn.loading('hide');
            },
        });
    }

    function validChart(routePeriode) {
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if (data) {
                    new Chart($('#card-chart4'),
                        {
                            type: 'bar',
                            data: {
                                labels: data['axisX'],
                                datasets: [
                                    {
                                        label: data['title'],
                                        backgroundColor: 'rgba(255,255,255,.2)',
                                        borderColor: 'rgba(255,255,255,.55)',
                                        barPercentage: 0.6,
                                        data: _.map(data['axisX'], function (elt) {
                                            return data.data[elt];
                                        })
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                legend: { display: false },
                                scales: {
                                    xAxes: [{ display: false }],
                                    yAxes: [{
                                        display: false, ticks: {
                                            display: false,
                                            min: _.min(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                            ,
                                            max: _.max(_.map(data['axisX'], function (elt) {
                                                return data.data[elt];
                                            }))
                                        }
                                    }]
                                }
                            }
                        }); 
                }
                $.fn.loading('hide');
            },
        });
    }

    function extractDataSets(result) {
        var dataSet = [];
        $.each(result, function (index, elt) {
            dataSet.push({
                label: elt['title'],
                backgroundColor: 'rgba(220, 220, 220, 0.2)',
                borderColor: api.backgroundColor[index],
                pointBackgroundColor: api.backgroundColor[index],
                pointBorderColor: '#fff',
                data: $.map(elt['data'], function (elt2, index) {
                    return elt2;
                })
            });
        });

        return dataSet;
    }

    // document.body.addEventListener('classtoggle', function (event) {
    //     if (event.detail.className === 'c-dark-theme') {
    //         if (document.body.classList.contains('c-dark-theme')) { 
    //             cardChart1.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--primary-dark-theme'); 
    //             cardChart2.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--info-dark-theme'); 
    //             Chart.defaults.global.defaultFontColor = '#fff'; 
    //         } 
    //         else { 
    //             cardChart1.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--primary'); 
    //             cardChart2.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--info'); 
    //             Chart.defaults.global.defaultFontColor = '#646470'; 
    //         }
    //     }
   
    // var cardChart1 = new Chart(document.getElementById('card-chart1'), 
    //     { 
    //         type: 'line', 
    //         data: { 
    //             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], 
    //             datasets: [
    //                 { 
    //                     label: 'My First dataset', 
    //                     backgroundColor: 'transparent', 
    //                     borderColor: 'rgba(255,255,255,.55)', 
    //                     pointBackgroundColor: coreui.Utils.getStyle('--primary'), 
    //                     data: [65, 59, 84, 84, 51, 55, 40] 
    //                 }
    //             ] 
    //         }, 
    //         options: { 
    //             maintainAspectRatio: false, 
    //             legend: { display: false }, 
    //             scales: { 
    //                 xAxes: [
    //                     { 
    //                         gridLines: { 
    //                             color: 'transparent', 
    //                             zeroLineColor: 'transparent' 
    //                         }, 
    //                         ticks: { 
    //                             fontSize: 2, 
    //                             fontColor: 'transparent' 
    //                         } 
    //                     }
    //                 ], 
    //                 yAxes: [{ display: false, ticks: { display: false, min: 35, max: 89 } }] 
    //             }, 
    //             elements: { 
    //                 line: { borderWidth: 1 }, 
    //                 point: { 
    //                     radius: 4, 
    //                     hitRadius: 10, 
    //                     hoverRadius: 4 
    //                 } 
    //             } 
    //         } 
    //     }); 

    // var cardChart2 = new Chart(document.getElementById('card-chart2'), 
    //     { 
    //         type: 'line', 
    //         data: { 
    //             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], 
    //             datasets: [
    //                 { 
    //                     label: 'My First dataset', 
    //                     backgroundColor: 'transparent', 
    //                     borderColor: 'rgba(255,255,255,.55)',
    //                     pointBackgroundColor: coreui.Utils.getStyle('--info'), 
    //                     data: [1, 18, 9, 17, 34, 22, 11] 
    //                 }
    //             ] 
    //         }, 
    //         options: { 
    //             maintainAspectRatio: false, 
    //             legend: { display: false }, 
    //             scales: { 
    //                 xAxes: [
    //                     { 
    //                         gridLines: { 
    //                             color: 'transparent', 
    //                             zeroLineColor: 'transparent' }, 
    //                             ticks: { fontSize: 2, fontColor: 'transparent' } 
    //                         }
    //                     ], 
    //                     yAxes: [
    //                         { 
    //                             display: false, 
    //                             ticks: { display: false, min: -4, max: 39 } 
    //                         }
    //                     ] 
    //                 }, 
    //                 elements: { 
    //                     line: { 
    //                         tension: 0.00001, 
    //                         borderWidth: 1 
    //                     }, 
    //                     point: { 
    //                         radius: 4, 
    //                         hitRadius: 10, 
    //                         hoverRadius: 4 
    //                     } 
    //                 } 
    //         } 
    //     }); 
    
    // var cardChart3 = new Chart(document.getElementById('card-chart3'), 
    //     { 
    //         type: 'line', 
    //         data: { 
    //             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], 
    //             datasets: [
    //                 { 
    //                     label: 'My First dataset', 
    //                     backgroundColor: 'rgba(255,255,255,.2)', 
    //                     borderColor: 'rgba(255,255,255,.55)', 
    //                     data: [78, 81, 80, 45, 34, 12, 40] 
    //                 }
    //             ] 
    //         }, 
    //         options: { 
    //             maintainAspectRatio: false, 
    //             legend: { display: false }, 
    //             scales: {
    //                  xAxes: [{ display: false }], 
    //                  yAxes: [{ display: false }] 
    //             }, 
    //                 elements: { 
    //                     line: { borderWidth: 2 }, 
    //                     point: { radius: 0, hitRadius: 10, hoverRadius: 4 } 
    //                 } 
    //         } 
    //     }); 
            
    // var cardChart4 = new Chart(document.getElementById('card-chart4'), 
    //     { 
    //         type: 'bar', 
    //         data: { 
    //             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April'], 
    //             datasets: [
    //                 { 
    //                     label: 'My First dataset', 
    //                     backgroundColor: 'rgba(255,255,255,.2)', 
    //                     borderColor: 'rgba(255,255,255,.55)', 
    //                     data: [78, 81, 80, 45, 34, 12, 40, 85, 65, 23, 12, 98, 34, 84, 67, 82], 
    //                     barPercentage: 0.6 
    //                 }
    //             ] 
    //         }, 
    //         options: { 
    //             maintainAspectRatio: false, 
    //             legend: { display: false }, 
    //             scales: { 
    //                 xAxes: [{ display: false }], 
    //                 yAxes: [{ display: false }] 
    //             } 
    //         } 
    //     }); 
        
    // var mainChart = new Chart(document.getElementById('main-chart'), 
    //     { 
    //         type: 'line', 
    //         data: { 
    //             labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S'], 
    //             datasets: [
    //                 { 
    //                     label: 'My First dataset', 
    //                     backgroundColor: coreui.Utils.hexToRgba(coreui.Utils.getStyle('--info'), 10), 
    //                     borderColor: coreui.Utils.getStyle('--info'), 
    //                     pointHoverBackgroundColor: '#fff', 
    //                     borderWidth: 2, 
    //                     data: [165, 180, 70, 69, 77, 57, 125, 165, 172, 91, 173, 138, 155, 89, 50, 161, 65, 163, 160, 103, 114, 185, 125, 196, 183, 64, 137, 95, 112, 175] 
    //                 }, 
    //                 { 
    //                     label: 'My Second dataset', 
    //                     backgroundColor: 'transparent', 
    //                     borderColor: coreui.Utils.getStyle('--success'), 
    //                     pointHoverBackgroundColor: '#fff', 
    //                     borderWidth: 2, 
    //                     data: [92, 97, 80, 100, 86, 97, 83, 98, 87, 98, 93, 83, 87, 98, 96, 84, 91, 97, 88, 86, 94, 86, 95, 91, 98, 91, 92, 80, 83, 82] 
    //                 }, 
    //                 { 
    //                     label: 'My Third dataset', 
    //                     backgroundColor: 'transparent', 
    //                     borderColor: coreui.Utils.getStyle('--danger'), 
    //                     pointHoverBackgroundColor: '#fff', 
    //                     borderWidth: 1, 
    //                     borderDash: [8, 5], 
    //                     data: [65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65, 65] 
    //                 }
    //             ] 
    //         }, 
    //         options: { 
    //             maintainAspectRatio: false, 
    //             legend: { display: false }, 
    //             scales: {
    //                     xAxes: [{ gridLines: { drawOnChartArea: false } }], 
    //                     yAxes: [
    //                         { 
    //                             ticks: { 
    //                                 beginAtZero: true, 
    //                                 maxTicksLimit: 5, 
    //                                 stepSize: Math.ceil(250 / 5), 
    //                                 max: 250 
    //                         } 
    //                     }
    //                 ] 
    //             }, 
    //             elements: { 
    //                 point: { 
    //                     radius: 0, 
    //                     hitRadius: 10, 
    //                     hoverRadius: 4, 
    //                     hoverBorderWidth: 3 
    //                 } 
    //             } 
    //         } 
    //     });
});