$(document).ready(function ($) {

/*================================[ Init ]==================================*/

    Routing.setBaseUrl($('#base_dir').val());
    var api = {};

/*==========================[ début programme ]================================*/

    $(function () {
        
        $.fn.initTooltip();
        displayPoolMessage();
        
    });

/*================================[ Events ]==================================*/

    $(function(){

        $.fn.initEventBtnDelete();
        $.fn.initEventBtnValidation();

        protectOnChange();

    });
    
/*================================[ Functions ]==================================*/

    function protectOnChange(){

        $('.c-main').on('change', '*', function(e){
            if (!$(this).attr('data-changed')){
               $(this).attr('data-changed', 'true');
                e.stopPropagation();
           }
        });

        $('.c-sidebar, .c-header').on('click', 'a', function(e){
            if ($('[data-changed="true"]').length > 0){
                e.preventDefault();
                $('[data-changed="true"]').parent().css({'border':'2px solid red'});
                var elt = $(this);
                $.fn.displayConfirm('Attentions: élements non sauvegardés', 'Voulez-continuer et annuler vos mofifications?', function (response) {
                    if (response) {
                        window.location = elt.attr('href');
                    }
                }, ['Oui', 'Non']);
            }
        });
    }

    function displayPoolMessage() {
        var $messagePool = $(".messagePool");
        displayMessage('Succés requête', $messagePool.find('input[data-status="200"]'), 200);
        displayMessage('Erreur requête', $messagePool.find('input[data-status="500"]'), 500);
        displayMessage('Attention requête', $messagePool.find('input[data-status="600"]'), 600);

        /*if ($feedback.length > 0 && $feedback.val()) {

            var title = '';
            var $status = $('input[name="report-status"]');

            if ($status.length > 0 && $status.val() == 200)
                title = 'Votre requête a été executé avec succès';            
            else if ($status.length > 0 && $status.val() == 500)
                title = '/!\\ Erreur lors du traitement de votre requête';                
            
            $.fn.displayMessage({ messageTitle: title, messageBody: $feedback.val(), errorCode: $status.val() });
            $status.val('');
            $feedback.val('');

        }*/
    }

    function displayMessage(title, elts, status){
        if (elts.length > 0){
            var message = '<ul>';
            $.each(elts, function (index, elt) {
                message += '<li>' + $(elt).val() + '</li>';
                $(elt).val('');
                $(elt).attr('data-status', '');
            });
            message += '</ul>';

            $.fn.displayMessage({ messageTitle: title, messageBody: message, errorCode: status });
        }
    }


});