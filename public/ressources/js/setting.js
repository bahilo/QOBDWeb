$(function(){
    
/*================================[ Init ]==================================*/

    api = {
        table: [],
        column: {
            currency: [
                { data: 'id', title: "Devis n°" },
                { data: 'Name', title: "Nom" },
                { data: 'Symbol', title: "Symbol" },
                { data: 'Rate', title: "Taux" },
                { data: 'CountryCode', title: "Code Pays" },
                { data: 'IsDefault', title: "Sélection par défaut" },
                { data: 'CreatedAt', title: "Date" },
                { data: 'null', title: "Modif.", render: getRender(0).renderEdit },
                { data: 'null', title: "Supp.", render: getRender(0).renderDelete },
            ],
            general: [
                { data: 'id', visible: false },
                { data: 'Code', title: "Catégorie" },
                { data: 'Name', title: "Nom" },
                { data: 'Value', title: "Valeur" },
                { data: 'null', title: "Modif.", render: getRender(0).renderEdit },
                { data: 'null', title: "Supp.", render: getRender(0).renderDelete },
            ],
            tax: [
                { data: 'id', visible: false },
                { data: 'Type', title: "Type" },
                { data: 'Value', title: "Valeur" },
                { data: 'IsCurrent', title: "Valeur Par défaut" },
                { data: 'CreateAt', title: "Date" },
                { data: 'null', title: "Modif.", render: getRender(0).renderEdit },
                { data: 'null', title: "Supp.", render: getRender(0).renderDelete },
            ],
            status: [
                { data: 'id', visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'DisplayName', title: "Description" },
                { data: 'null', title: "Modif.", render: getRender(0).renderEdit },
                { data: 'null', title: "Supp.", render: getRender(0).renderDelete },
            ],
            item: [
                { data: 'id', visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'IsEnabled', title: "Actif" },
                { data: 'null', title: "Modif.", render: getRender(0).renderEdit },
                { data: 'null', title: "Supp.", render: getRender(0).renderDelete },
            ]
        },
    };

/*==========================[ début programme ]================================*/

    $(function(){

        loadSettings();
        
    });

/*================================[ Events ]==================================*/

    $(function(){

        $('input.switch-input','.switch-wrapper').on('click', function(){
             if($(this).prop('checked')){
                 $('.value-file','.value-wrapper').hide();
                 $('.value-default','.value-wrapper').show();
             }
             else{
                 $('.value-file', '.value-wrapper').show();
                 $('.value-default', '.value-wrapper').hide();
             }
        });
        
    });

/*================================[ Functions ]==================================*/

    function loadSettings(){
        if ($('#setting_target_tables').val()) {
            var tables_target = JSON.parse($('#setting_target_tables').val());
            for (var i = 0; i < tables_target.length; i++) {
                api.table[tables_target[i]] = $("#" + tables_target[i] + "_setting_target_table_js").myTable({
                    dataSource: $("#" + tables_target[i] + "_setting_target").val(),
                    columns: getColumn(api)
                });
            }
        }
    } 

    function getRender(){
        var route = getRoute();
        var Renders = new RenderMethod({
            routeEdit: { route: route.pathEdit, logo: 'fa-edit' },
            routeDelete: { route: route.pathDelete, logo: 'fa-trash-alt' }
        });
        return Renders;
    }

    function getRoute(){
        var info = { pathEdit: "", pathDelete: "", column: []};
        
        var target = $('#setting_target').data('source');
        switch (target) {
            case "currency_data_source":
                info.pathEdit = "setting_currency_edit";
                info.pathDelete = "setting_currency_delete";
                break;
            case "tax_data_source":
                info.pathEdit = "setting_tax_edit";
                info.pathDelete = "setting_tax_delete";
                break;
            case "delivery_status_data_source":
                info.pathEdit = "setting_delivery_status_edit";
                info.pathDelete = "setting_delivery_status_delete";
                break;
            case "order_status_data_source":
                info.pathEdit = "setting_order_status_edit";
                info.pathDelete = "setting_order_status_delete";
                break;
            case "brand_data_source":
                info.pathEdit = "setting_brand_edit";
                info.pathDelete = "setting_brand_delete";
                break;
            case "group_data_source":
                info.pathEdit = "setting_group_edit";
                info.pathDelete = "setting_group_delete";
                break;
            case "provider_data_source":
                info.pathEdit = "setting_provider_edit";
                info.pathDelete = "setting_provider_delete";
                break;
            default:
                info.pathEdit = "setting_edit";
                info.pathDelete = "setting_delete";
                break;
        }
        return info;
    }

    function getColumn(api) {
        var info = [];

        var target = $('#setting_target').data('source');
        switch (target) {
            case "currency_data_source":
                if (typeof api != 'undefined')
                    info = api.column.currency;
                break;
            case "tax_data_source":
                if (typeof api != 'undefined')
                    info = api.column.tax;
                break;
            case "delivery_status_data_source":
                if (typeof api != 'undefined')
                    info = api.column.status;
                break;
            case "order_status_data_source":
                if (typeof api != 'undefined')
                    info = api.column.status;
                break;
            case "brand_data_source":
                if (typeof api != 'undefined')
                    info = api.column.item;
                break;
            case "group_data_source":
                if (typeof api != 'undefined')
                    info = api.column.item;
                break;
            case "provider_data_source":
                if (typeof api != 'undefined')
                    info = api.column.item;
                break;
            default:
                if (typeof api != 'undefined')
                    info = api.column.general;
                break;
        }
        return info;
    }

});