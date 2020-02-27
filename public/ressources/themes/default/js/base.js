$(document).ready(function ($) {

/*================================[ Init ]==================================*/
    Routing.setBaseUrl($('#base_dir').val());
    var api = {
        backgroundColor: [
            '#187da0',//'rgba(220, 220, 220, 1)',
            'orange',//'rgba(220, 220, 210, 1)',
            'red',//'rgba(220, 220, 200, 1)'
        ],
        order: {
            week: Routing.generate('statistic_order_week'),
            month: Routing.generate('statistic_order_month'),
            year: Routing.generate('statistic_order_year'),
        }
    };

/*==========================[ début programme ]================================*/

    $(function () {
        
        $('[data-toggle="tooltip"]').tooltip();
        loadChart();
        displayPoolMessage();
        
    });

/*================================[ Events ]==================================*/

    $(function(){

        $('[name="options"]').change(function () {

            if ($(this).attr('id') == 'optionWeek')
                generateGeneralChart(api.order.week);
            else if ($(this).attr('id') == 'optionMonth')
                generateGeneralChart(api.order.month);
            else if ($(this).attr('id') == 'optionYear')
                generateGeneralChart(api.order.year);
        });

        $.fn.initEventBtnDelete();
        $.fn.initEventBtnValidation();

    });
    
/*================================[ Functions ]==================================*/

    function loadChart(){
        if ($('.home-wrapper').length > 0){
           generateGeneralChart(api.order.month);
       }
    }    

    function generateGeneralChart(routePeriode){
        $.fn.loading('show');
        $.fn.ajaxLoader({
            type: "post",
            data: {},
            url: routePeriode,
            onSuccess: function (result) {
                var data = JSON.parse(result);
                if(data.length > 0){
                    var lineChart = new Chart($('#myChart0'), {
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

    function extractDataSets(result){
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

    function displayPoolMessage() {
        var $feedback = $('input[name="report-feedback"]');
        if ($feedback.length > 0 && $feedback.val()) {
            var $status = $('input[name="report-status"]');
            if ($status.length > 0 && $status.val() == 200) {
                $.fn.displayMessage('Votre requête a été executé avec succès', $feedback.val());
                $status.val('');
                $feedback.val('');
            }
            else if ($status.length > 0 && $status.val() == 500) {
                $.fn.displayMessage('/!\\ Erreur lors du traitement de votre requête', $feedback.val());
                $status.val('');
                $feedback.val('');
            }
        }
    }


});