$(function () {

    var Renders = new RenderMethod({
        routeShow: { route: 'client_show', logo: 'fa-eye'},
        routeEdit: { route: 'client_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_delete', logo: 'fa-trash-alt' }
        });

    $("#client_table_js").myTable({
        dataSource: $("#client_data_source").val(),
        columns: [
            { data: 'id', title: "", render: Renders.renderSelect },
            { data: 'CompanyName', title: "Société" },
            { data: 'LastName', title: "Nom" },
            { data: 'FirstName', title: "Prénom" },
            { data: 'Phone', title: "Téléphone" },
            { data: 'Email', title: "Email" },
            { data: 'id', title: "", render: Renders.renderShow },
            { data: 'id', title: "", render: Renders.renderEdit },
            { data: 'id', title: "", render: Renders.renderDelete },
        ]
    });

    var contactRenders = new RenderMethod({
        routeShow: { route: 'client_contact_edit', logo: 'fa-eye' },
        routeEdit: { route: 'client_contact_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_contact_delete', logo: 'fa-trash-alt' }
    });

    $("#contact_table_js").myTable({
        dataSource: $("#contact_data_source").val(),
        columns: [
            { data: 'id', title: "", visible: false },
            { data: 'FirstName', title: "Prénom" },
            { data: 'LastName', title: "Nom" },
            { data: 'Phone', title: "Téléphone" },
            { data: 'Email', title: "Email" },
            { data: 'id', title: "", render: contactRenders.renderShow },
            { data: 'id', title: "", render: contactRenders.renderEdit },
            { data: 'id', title: "", render: contactRenders.renderDelete },
        ]
    });

    $(function(){

        $(".bx_select").on('click', function(e){
            e.preventDefault();
            confirmSelection(this);
        });



    });

    function confirmSelection(elt) {
        $.fn.displayConfirm('Sélection client', 'Confirmez-vous la sélection de ce client pour un devis ?', function (response) {
            if (response) {
                window.location = $(elt).attr('href');
            }
        }, ['Valider', 'Annuler']);
    }

});