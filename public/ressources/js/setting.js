$(function(){
    
/*================================[ Init ]==================================*/
    var ETarget = {
        currency: 0,
        general: 1,
        tax: 2,
        delivery_status: 3,
        order_status: 4,
        item_brand: 5,
        item_group: 6,
        item_provider: 7,
    };

    api = {
        table: [],
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
                    columns: getColumn(api, tables_target[i])
                });
            }
        }
    } 

    function getColumn(api, target) {
        var info = [];

        var target = $('#'+ target +'_setting_target').data('source');
        switch (target) {
            case "currency_data_source":
                info = getCurrencyColumn();
                break;
            case "tax_data_source":
                info = getTaxColumn();
                break;
            case "delivery_status_data_source":               
                info = getStatusColumn(ETarget.delivery_status);
                break;
            case "order_status_data_source":                
                info = getStatusColumn(ETarget.order_status);
                break;
            case "brand_data_source":
                info = getItemColumn(ETarget.item_brand);
                break;
            case "group_data_source":
                info = getItemColumn(ETarget.item_group);
                break;
            case "provider_data_source":                
                info = getItemColumn(ETarget.item_provider);
                break;
            default:                
                info = getGeneralColumn();
                break;
        }
        return info;
    }

    /*================================[ Renders ]==================================*/

    function getItemColumn(target){
        var oRender = null;
        switch(target){
            case ETarget.item_group:
                oRender = new RenderMethod({
                    routeEdit: { route: "setting_group_edit", logo: 'fa-edit' },
                    routeDelete: { route: "setting_group_delete", logo: 'fa-trash-alt' }
                });
                break;
            case ETarget.item_brand:
                oRender = new RenderMethod({
                    routeEdit: { route: "setting_brand_edit", logo: 'fa-edit' },
                    routeDelete: { route: "setting_brand_delete", logo: 'fa-trash-alt' }
                });
                break;
            case ETarget.item_provider:
                oRender = new RenderMethod({
                    routeEdit: { route: "setting_provider_edit", logo: 'fa-edit' },
                    routeDelete: { route: "setting_provider_delete", logo: 'fa-trash-alt' }
                });
                break;
        }
        return [
            { data: 'id', visible: false },
            { data: 'IsEnabled', visible: false },
            { data: 'Name', title: "Nom" },
            { data: 'null', title: "Modif.", render: oRender.renderEdit },
            { data: 'null', title: "Supp.", render: oRender.renderDelete },
        ];
    }

    function getStatusColumn(target){

        var oRender = null;
        switch(target){
            case ETarget.delivery_status:
                oRender = new RenderMethod({
                    routeEdit: { route: "setting_delivery_status_edit", logo: 'fa-edit' },
                    routeDelete: { route: "setting_delivery_status_delete", logo: 'fa-trash-alt' }
                });
                break;
            case ETarget.order_status:
                oRender = new RenderMethod({
                    routeEdit: { route: "setting_order_status_edit", logo: 'fa-edit' },
                    routeDelete: { route: "setting_order_status_delete", logo: 'fa-trash-alt' }
                });
                break;
        }
        return [
            { data: 'id', visible: false },
            { data: 'Name', title: "Nom" },
            { data: 'DisplayName', title: "Description" },
            { data: 'null', title: "Modif.", render: oRender.renderEdit },
            { data: 'null', title: "Supp.", render: oRender.renderDelete },
        ];
    }

    function getTaxColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_tax_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_tax_delete", logo: 'fa-trash-alt' }
        });
        return[
            { data: 'id', visible: false },
            { data: 'Type', title: "Type" },
            { data: 'Value', title: "Valeur" },
            { data: 'IsCurrent', title: "Valeur Par défaut" },
            { data: 'CreateAt', title: "Date" },
            { data: 'null', title: "Modif.", render: oRender.renderEdit },
            { data: 'null', title: "Supp.", render: oRender.renderDelete },
        ];
    }

    function getCurrencyColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_currency_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_currency_delete", logo: 'fa-trash-alt' }
        });
        return [
            { data: 'id', title: "Devis n°" },
            { data: 'Name', title: "Nom" },
            { data: 'Symbol', title: "Symbol" },
            { data: 'Rate', title: "Taux" },
            { data: 'CountryCode', title: "Code Pays" },
            { data: 'IsDefault', title: "Sélection par défaut" },
            { data: 'CreatedAt', title: "Date" },
            { data: 'null', title: "Modif.", render: oRender.renderEdit },
            { data: 'null', title: "Supp.", render: oRender.renderDelete },
        ];
    }

    function getGeneralColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_delete", logo: 'fa-trash-alt' }
        });
        return [
            { data: 'id', visible: false },
            { data: 'Code', title: "Catégorie" },
            { data: 'Name', title: "Nom" },
            { data: 'Value', title: "Valeur" },
            { data: 'null', title: "Modif.", render: oRender.renderEdit },
            { data: 'null', title: "Supp.", render: oRender.renderDelete },
        ];
    }

    function getRender(){

    }

});