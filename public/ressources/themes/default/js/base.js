$(document).ready(function ($) {

/*================================[ Init ]==================================*/

    Routing.setBaseUrl($('#base_dir').val());
    var api = {};

/*==========================[ début programme ]================================*/

    $(function () {
        
        $('[data-toggle="tooltip"]').tooltip();
        displayPoolMessage();
        
    });

/*================================[ Events ]==================================*/

    $(function(){

        $.fn.initEventBtnDelete();
        $.fn.initEventBtnValidation();

    });
    
/*================================[ Functions ]==================================*/

    function displayPoolMessage() {
        var $feedback = $('input[name="report-feedback"]');
        if ($feedback.length > 0 && $feedback.val()) {

            var title = '';
            var $status = $('input[name="report-status"]');

            if ($status.length > 0 && $status.val() == 200)
                title = 'Votre requête a été executé avec succès';            
            else if ($status.length > 0 && $status.val() == 500)
                title = '/!\\ Erreur lors du traitement de votre requête';                
            
            $.fn.displayMessage({ messageTitle: title, messageBody: $feedback.val(), errorCode: $status.val() });
            $status.val('');
            $feedback.val('');

        }
    }


});