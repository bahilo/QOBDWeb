$(function () {

    var Renders = new RenderMethod({
        routeShow: { route: 'client_show', logo: 'fa-eye'},
        routeEdit: { route: 'client_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_delete', logo: 'fa-trash-alt' }
        });

    $("#client_table_js").myTable({
        dataSource: $("#client_data_source").val(),
        columns: getCLientColumn()
    });

    var contactRenders = new RenderMethod({
        routeShow: { route: 'client_contact_edit', logo: 'fa-eye' },
        routeEdit: { route: 'client_contact_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_contact_delete', logo: 'fa-trash-alt' }
    });

    $("#contact_table_js").myTable({
        dataSource: $("#contact_data_source").val(),
        columns: getContactColumn()
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

    function getContactColumn(){
        var col = [];

        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'FirstName', title: "Prénom" });
        col.push({ data: 'LastName', title: "Nom" });
        col.push({ data: 'IsActivated', title: "", visible: false });
        col.push({ data: 'Phone', title: "Téléphone" });
        col.push({ data: 'Email', title: "Email" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'id', title: "", render: contactRenders.renderShow });
            col.push({ data: 'id', title: "", render: contactRenders.renderEdit });
        }

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'id', title: "", render: contactRenders.renderDelete });
        }

        return col;
    }

    function getCLientColumn(){
        var col = [];

        if($('#is_quote_write','.access_pool').length > 0 && $('#is_quote_write','.access_pool').val()){
            col.push({ data: 'id', title: "", render: Renders.renderSelect });
        }
        else{
            col.push({ data: 'id', visible: false });
        }
        
        col.push({ data: 'CompanyName', title: "Société" });
        col.push({ data: 'LastName', title: "Nom" });
        col.push({ data: 'FirstName', title: "Prénom" });
        col.push({ data: 'Phone', title: "Téléphone" });
        col.push({ data: 'Email', title: "Email" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'id', title: "", render: Renders.renderShow });
            col.push({ data: 'id', title: "", render: Renders.renderEdit });
        }

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'id', title: "", render: Renders.renderDelete });
        }

        return col;
    }

});