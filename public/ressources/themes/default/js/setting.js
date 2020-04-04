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

        // $('input.c-switch-input','.switch-wrapper').on('click', function(){
        //      if($(this).prop('checked')){
        //           $('.value-file', '.value-wrapper').show();
        //          $('.value-default', '.value-wrapper').hide();
        //      }
        //      else{
        //          $('.value-file', '.value-wrapper').hide();
        //          $('.value-default', '.value-wrapper').show();
        //      }
        // });
        
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
        
        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'IsEnabled', visible: false });
        col.push({ data: 'Name', title: "Nom" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "Modif.", render: oRender.renderEdit });
        }

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'null', title: "Supp.", render: oRender.renderDelete });
        }

        return col;
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

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'Name', title: "Nom" });
        col.push({ data: 'DisplayName', title: "Description" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "Modif.", render: oRender.renderEdit });
        }

        if($('#is_admin','.access_pool').length > 0 && $('#is_admin','.access_pool').val()){
            col.push({ data: 'null', title: "Supp.", render: oRender.renderDelete });
        }

        return col;
    }

    function getTaxColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_tax_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_tax_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'Type', title: "Type" });
        col.push({ data: 'Value', title: "Valeur" });
        col.push({ data: 'IsCurrent', title: "Valeur Par défaut" });
        //col.push({ data: 'CreateAt', title: "Date" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "Modif.", render: oRender.renderEdit });
        }

        if($('#is_admin','.access_pool').length > 0 && $('#is_admin','.access_pool').val()){
            col.push({ data: 'null', title: "Supp.", render: oRender.renderDelete });
        }

        return col;
    }

    function getCurrencyColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_currency_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_currency_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'Name', title: "Nom" });
        col.push({ data: 'Symbol', title: "Symbol" });
        col.push({ data: 'Rate', visible: false });
        col.push({ data: 'CountryCode', title: "Code Pays" });
        col.push({ data: 'IsDefault', title: "Sélection par défaut" });
        //col.push({ data: 'CreatedAt', visible: false });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "Modif.", render: oRender.renderEdit });
        }

        if($('#is_admin','.access_pool').length > 0 && $('#is_admin','.access_pool').val()){
            col.push({ data: 'null', title: "Supp.", render: oRender.renderDelete });
        }

        return col;
    }

    function getGeneralColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'Code', visible: false });
        col.push({ data: 'Name', visible: false });
        col.push({ data: 'DisplayName', title: "Nom" });
        col.push({ data: 'Value', title: "Valeur" });

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "Modif.", render: oRender.renderEdit });
        }

        if($('#is_admin','.access_pool').length > 0 && $('#is_admin','.access_pool').val()){
            col.push({ data: 'null', title: "Supp.", render: oRender.renderDelete });
        }

        return col;
    }

    function getRender(){

    }

});