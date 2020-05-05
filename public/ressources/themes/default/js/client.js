$(document).ready(function ($) {

/*================================[ Init ]==================================*/

    var Renders = new RenderMethod({
        routeShow: { route: 'client_show', logo: 'fa-eye'},
        routeEdit: { route: 'client_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_delete', logo: 'fa-trash-alt' }
        });

    var contactRenders = new RenderMethod({
        routeShow: { route: 'client_contact_edit', logo: 'fa-eye' },
        routeEdit: { route: 'client_contact_edit', logo: 'fa-edit' },
        routeDelete: { route: 'client_contact_delete', logo: 'fa-trash-alt' }
    });

    var orderRenders = new RenderMethod({
        routeShow: { route: 'order_show', logo: 'fa-eye' },
    });

    var quoteRenders = new RenderMethod({
        routeShow: { route: 'order_show_quote', logo: 'fa-eye' },
    });

/*==========================[ début programme ]================================*/

    $(function () {

        $("#client_table_js").myTable({
            //dataSource: $("#client_data_source").val(),
            com: 'async',
            ajax:{
                url: Routing.generate('client_home_data'),
            },
            columns: getCLientColumn(),
            initComplete: function (setting, json) {
                $.fn.initTooltip();
            },
        });

        $("#contact_table_js").myTable({
            //dataSource: $("#contact_data_source").val(),
            com: 'async',
            order: [[1, "desc"]],
            ajax: {
                url: Routing.generate('client_contact_data', {id: $('#client_id').val()}),
            },
            columns: getContactColumn(),
            initComplete: function (setting, json) {
                $.fn.initTooltip();
            },
        });

        $("#quote_table_js").myTable({
            //dataSource: $("#contact_data_source").val(),
            com: 'async',
            ajax: {
                url: Routing.generate('client_quote_data', { id: $('#client_id').val()}),
            },
            columns: getQuoteColumn(),
            initComplete: function (setting, json) {
                $.fn.initTooltip();
            },
        });

        $("#order_table_js").myTable({
            //dataSource: $("#contact_data_source").val(),
            com: 'async',
            ajax: {
                url: Routing.generate('client_order_data', { id: $('#client_id').val()}),
            },
            columns: getOrderColumn(),
            initComplete: function (setting, json) {
                $.fn.initTooltip();
            },
        });

    });

    /*================================[ Events ]==================================*/
  
    $(function(){

        $(".bx_select").on('click', function(e){
            e.preventDefault();
            confirmSelection(this);
        });

    });

    /*================================[ Functions ]==================================*/

    function confirmSelection(elt) {
        $.fn.displayConfirm('Sélection client', 'Confirmez-vous la sélection de ce client pour un devis ?', function (response) {
            if (response) {
                window.location = $(elt).attr('href');
            }
        }, ['Valider', 'Annuler']);
    }

    function getContactColumn(){
        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'IsPrincipal', visible: false });
        col.push({ data: 'FirstName', title: "Prénom" });
        col.push({ data: 'LastName', title: "Nom" });
        col.push({ data: 'Phone', title: "Téléphone" });
        col.push({ data: 'Email', title: "Email" });

        col.push({ data: 'id', title: "", render: contactRenders.renderEdit });
        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'id', title: "", render: renderShow });
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
            // col.push({ data: 'id', title: "", render: Renders.renderEdit });
        }

        // if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
        //     col.push({ data: 'id', title: "", render: Renders.renderDelete });
        // }

        return col;
    }

    function getQuoteColumn() {
        var col = [];

        col.push({ data: 'id', title: "Devis n°" });        

        return getOrderColumn(col);
    }

    function getOrderColumn(quoteCols = []) {
        var col = quoteCols;

        if (quoteCols.length == 0)
            col.push({ data: 'id', title: "Commande n°" });

        col.push({ data: 'CreatedAtToString', title: "date" });
        col.push({ data: 'ClientCompanyName', title: "Société" });
        col.push({ data: 'AgentFirstName', title: "Commercial", render: renderAgent });
        if (quoteCols.length > 0)
            col.push({ data: 'id', title: "Détail", render: quoteRenders.renderShow });
        else
            col.push({ data: 'id', title: "Détail", render: orderRenders.renderShow });
            
        // if ($('#is_delete', '.access_pool').length > 0 && $('#is_delete', '.access_pool').val()) {
        //     col.push({ data: 'id', title: "Supp.", render: Renders.renderDelete });
        // }

        return col;
    }



    /*================================[ Renders ]==================================*/

    function renderAgent(data, type, row) {
        return row['AgentFirstName'] + " " + row['AgentLastName'];
    }

    function renderShow(data, type, row) {
        if (!vars.routeShow)
            return '';
        return '<a href="' + Routing.generate(vars.routeShow.route, { id: row.id, idClient: $('#client_id').val() }) + '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Page détail">' +
            '<i class="fa ' + vars.routeShow.logo + '"></i>' +
            '</a>';
    }

});