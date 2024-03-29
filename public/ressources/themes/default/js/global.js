$(document).ready(function(){

    /*============================================[ Helpers ]================================ */

    $.fn.openModal = function () {
        $('#mdlDefault').modal('show');
    }

    $.fn.closeModal = function () {
        $('#mdlDefault').modal('hide');
    }

    $.fn.displayMessage = function (param) {
        var options = {
            messageTitle: null,
            messageBody: null,
            footer: null,
            errorCode: 200,
            size: "md",
            textAlign: "left",
            isCentered: true,
        };

        options = $.extend(options, param);

        var modal = $('#mdlDefault');

        // set panel color regarding the given error code 
        var modalContentWrapper = modal.find('.modal-dialog');
        modalContentWrapper.removeClass('modal-danger modal-info modal-success');
        modalContentWrapper.find('.modal-body').addClass("text-" + options.textAlign);

        if (!options.isCentered){
            modalContentWrapper.removeClass('modal-dialog-centered');
        }

        if (options.errorCode){
           if (options.errorCode == 500) {
               modalContentWrapper.addClass('modal-danger modal-' + options.size);
           }
           else if (options.errorCode == 600) {
               modalContentWrapper.addClass('modal-warning modal-' + options.size);
           }
           else if (options.errorCode == 200) {
               modalContentWrapper.addClass('modal-success modal-' + options.size);
           }
           else {
               modalContentWrapper.addClass('modal-primary modal-' + options.size);
           }
       }

        // fill the panel content
        if (options.messageTitle)
            modal.find('.modal-title').html(options.messageTitle);
        if (options.messageBody)
            modal.find('.modal-body').html(options.messageBody);
        if (options.footer)
            modal.find('.modal-footer').html(options.footer);

        $.fn.openModal();
    }

    $.fn.loading = function (action) {
        if (action == 'show') {
            var title = 'Chargement...';
            var body = '';
            body += '<div class="spinner-border" style="width: 3rem; height: 3rem; text-align: center" role="status">';
            body += '    <span class="sr-only">Chargement...</span>';
            body += '</div>';
            $.fn.displayMessage({ messageTitle: title, messageBody: body, size: "sm", textAlign: "center", errorCode: 601 });
        }
        else if (action == 'hide') {
            $.fn.closeModal();
        }
    }

    //-----------------------------------------------------------------------------
    //-- envoi les requettes vers le server en ajax
    //-----------------------------------------------------------------------------
    $.fn.ajaxLoader = function (options) {
        $.ajax({
            url: options.url,
            data: options.data,
            type: options.type,
            success: function(result){
                options.onSuccess(result);
            },
            error: function (jqXHR, textStatus) {
                $.fn.displayMessage({ messageTitle: 'Erreur détectée', errorCode: 500, messageBody: "Une erreur s'est produite lors de la communication avec le server. Veuillez contacter un administrateur"});
                console.log('Error found: ' + textStatus);
                $.fn.loading('hide');
            }
        });
    }

    //-----------------------------------------------------------------------------
    //-- demande de confirmation de la supression
    //-----------------------------------------------------------------------------
    $.fn.initEventBtnDelete = function(){
        $('.btnDelete').on('click', function (e) {
            e.preventDefault();
            $.fn.confirmDelete($(this));
        });
    }

    //-----------------------------------------------------------------------------
    //-- demande de confirmation la validation
    //-----------------------------------------------------------------------------
    $.fn.initEventBtnValidation = function(){
        $('.btnValidation').on('click', function (e) {
            var elt = $(this);
            e.preventDefault();
            $.fn.displayConfirm('Validation', 'Comfirmez-vous votre requête?', function (response) {
                if (response) {
                    window.location = $(elt).attr('href');
                }
            }, ['valider', 'Annuler']);
        });
    }


    $.fn.confirmDelete = function (elt) {
        $.fn.displayConfirm('Suppression', 'Validez-vous la supression', function (response) {
            if (response) {
                window.location = $(elt).attr('href');
            }
        }, ['Supprimer', 'Annuler']);
    }

    //-----------------------------------------------------------------------------
    //-- modal confirmation de la suppression
    //-----------------------------------------------------------------------------
    $.fn.displayConfirm = function (titre, message, action, array = ['Valider', 'annuler']) {
        var $root = $('#mdlDefault');
        $root.find('._mvalide').unbind();
        $root.find('._mcancel').unbind();

        var footer = '';
        footer += '<button type="button" class="btn btn-primary _mvalide">' + array[0] + '</button>';
        footer += '<button type="button" class="btn btn-secondary _mcancel">' + array[1] + '</button>';

        $.fn.displayMessage({ messageTitle: titre, messageBody: message, footer: footer, errorCode: 601});

        $root.find('._mvalide').on('click', function () {
            if ($.isFunction(action)) {
                action(true);
            }
            $.fn.closeModal();
        });

        $root.find('._mcancel').on('click', function () {
            if ($.isFunction(action)) {
                action(false);
            }
            $.fn.closeModal();
        });
    }

    //-----------------------------------------------------------------------------
    //-- Tooltips - aide
    //-----------------------------------------------------------------------------
    $.fn.initTooltip = function () {
        $('[data-toggle="tooltip"]').tooltip();
    }

    //-----------------------------------------------------------------------------
    //-- vérification des droits d'accés
    //-----------------------------------------------------------------------------
    $.fn.getAccess = function() {
        return {
            update: $('#is_update', '.access_pool').length > 0 && $('#is_update', '.access_pool').val(),
            write: $('#is_write', '.access_pool').length > 0 && $('#is_write', '.access_pool').val(),
            read: $('#is_read', '.access_pool').length > 0 && $('#is_read', '.access_pool').val(),
            delete: $('#is_delete', '.access_pool').length > 0 && $('#is_delete', '.access_pool').val(),
        }
    }

});