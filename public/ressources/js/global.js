$(document).ready(function(){

    /*============================================[ Helpers ]================================ */

    $.fn.openModal = function () {
        $('#mdlDefault').modal('show');
    }

    $.fn.closeModal = function () {
        $('#mdlDefault').modal('hide');
    }

    $.fn.displayMessage = function (messageTitle, messageBody, footer = "") {
        var modal = $('#mdlDefault');
        modal.find('.modal-title').html(messageTitle);
        modal.find('.modal-body').html(messageBody);
        modal.find('.modal-footer').html(footer);
        $.fn.openModal();
    }

    $.fn.loading = function (action) {
        if (action == 'show') {
            var title = 'Chargement...';
            var body = '';
            body += '<div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">';
            body += '    <span class="sr-only">Chargement...</span>';
            body += '</div>';
            $.fn.displayMessage(title, body);
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
            success: options.onSuccess,
            error: function (jqXHR, textStatus) {
                $.fn.displayMessage('Erreur détectée', "Une erreur s'est produite lors de la communication avec le server. Veuillez contacter un administrateur");
                console.log('Error found: ' + textStatus);
                $.fn.loading('hide');
            }
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

        $.fn.displayMessage(titre, message, footer);

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

});