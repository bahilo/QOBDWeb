$(function(){
    

    
    api = {
        table: {
            order: {},
            orderDetail: {},
        },
        column: {
            currency: [
                { data: 'id', title: "Devis n°" },
                { data: 'Name', title: "Nom" },
                { data: 'Symbol', title: "Symbol" },
                { data: 'Rate', title: "Taux" },
                { data: 'CountryCode', title: "Code Pays" },
                { data: 'IsDefault', title: "Sélection par défaut" },
                { data: 'CreatedAt', title: "Date" },
                { data: 'null', title: "Modif.", render: getRender().renderEdit },
                { data: 'null', title: "Supp.", render: getRender().renderDelete },
            ],
            general: [
                { data: 'id', visible: false },
                { data: 'Code', title: "Catégorie" },
                { data: 'Name', title: "Nom" },
                { data: 'Value', title: "Valeur" },
                { data: 'null', title: "Modif.", render: getRender().renderEdit },
                { data: 'null', title: "Supp.", render: getRender().renderDelete },
            ],
            tax: [
                { data: 'id', visible: false },
                { data: 'Type', title: "Type" },
                { data: 'Value', title: "Valeur" },
                { data: 'IsCurrent', title: "Valeur Par défaut" },
                { data: 'CreateAt', title: "Date" },
                { data: 'null', title: "Modif.", render: getRender().renderEdit },
                { data: 'null', title: "Supp.", render: getRender().renderDelete },
            ],
            status: [
                { data: 'id', visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'null', title: "Modif.", render: getRender().renderEdit },
                { data: 'null', title: "Supp.", render: getRender().renderDelete },
            ],
            item: [
                { data: 'id', visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'IsEnabled', title: "Actif" },
                { data: 'null', title: "Modif.", render: getRender().renderEdit },
                { data: 'null', title: "Supp.", render: getRender().renderDelete },
            ]
        },
    };


    $(function(){

        if ($('#setting_target').val()) {
            api.table.order = $("#setting_target_table_js").myTable({
                dataSource: $("#setting_target").val(),
                columns: getInfo().column
            });
        }

    });


    function getRender(){
        var route = getInfo();
        var Renders = new RenderMethod({
            routeEdit: { route: route.pathEdit, logo: 'fa-edit' },
            routeDelete: { route: route.pathDelete, logo: 'fa-trash-alt' }
        });
        return Renders;
    }

    function getInfo(){
        var info = { pathEdit: "", pathDelete: "", column: []};
        
        var target = $('#setting_target').data('source');
        switch (target) {
            case "general_data_source":
                info.pathEdit = "setting_edit";
                info.pathDelete = "setting_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.general;
                break;
            case "currency_data_source":
                info.pathEdit = "setting_currency_edit";
                info.pathDelete = "setting_currency_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.currency;
                break;
            case "tax_data_source":
                info.pathEdit = "setting_tax_edit";
                info.pathDelete = "setting_tax_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.tax;
                break;
            case "delivery_status_data_source":
                info.pathEdit = "setting_delivery_status_edit";
                info.pathDelete = "setting_delivery_status_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.status;
                break;
            case "order_status_data_source":
                info.pathEdit = "setting_order_status_edit";
                info.pathDelete = "setting_order_status_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.status;
                break;
            case "brand_data_source":
                info.pathEdit = "setting_brand_edit";
                info.pathDelete = "setting_brand_delete";
                if(typeof api != 'undefined')
                    info.column = api.column.item;
                break;
            case "group_data_source":
                info.pathEdit = "setting_group_edit";
                info.pathDelete = "setting_group_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.item;
                break;
            case "provider_data_source":
                info.pathEdit = "setting_provider_edit";
                info.pathDelete = "setting_provider_delete";
                if (typeof api != 'undefined')
                    info.column = api.column.item;
                break;
        }
        return info;
    }


});