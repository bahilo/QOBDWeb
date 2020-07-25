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
        item_ean: 8,
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
        $('input[name="setting_registration[switch]"]', '.setting-wrapper').on('change', function(){
            var root = $('.value-wrapper', '.setting-wrapper');
            if($(this).is(':checked')){
                root.find('.value-default').hide();
                root.find('.value-file').show();
            }
            else{
                root.find('.value-default').show();
                root.find('.value-file').hide();
            }
        });
    });

/*================================[ Functions ]==================================*/

    function loadSettings(){
        if ($('#setting_target_tables').val()) {
            var tables_target = JSON.parse($('#setting_target_tables').val());
            for (var i = 0; i < tables_target.length; i++) {
                var params = getColumn(api, tables_target[i]);
                api.table[tables_target[i]] = $("#" + tables_target[i] + "_setting_target_table_js").myTable({
                    com: "async",
                    order: [[0, "asc"]],
                    ajax: {
                        type: "POST",
                        url: Routing.generate(params.sourcePath, { code: tables_target[i]}),
                    },
                    columns: params.column,
                    initComplete: function (setting, json, api) {
                        $.fn.initEventBtnDelete();
                        $.fn.initTooltip();
                    },
                });
            }
        }
    } 

    function getColumn(api, target) {
        var output = {
            target: target,
            column: [],
            sourcePath: "",
        };

        var target = $('#'+ target +'_setting_target').data('source');
        switch (target) {
            case "currency_data_source":
                output.column = getCurrencyColumn();
                output.sourcePath = 'setting_data_currency';
                break;
            case "tax_data_source":
                output.column = getTaxColumn();
                output.sourcePath = 'setting_data_tax';
                break;
            case "delivery_status_data_source":               
                output.column = getStatusColumn(ETarget.delivery_status);
                output.sourcePath = 'setting_data_delivery_status';
                break;
            case "order_status_data_source":                
                output.column = getStatusColumn(ETarget.order_status);
                output.sourcePath = 'setting_data_order_status';
                break;
            case "brand_data_source":
                output.column = getItemColumn(ETarget.item_brand);
                output.sourcePath = 'setting_data_brand';
                break;
            case "group_data_source":
                output.column = getItemColumn(ETarget.item_group);
                output.sourcePath = 'setting_data_group';
                break;
            case "provider_data_source":                
                output.column = getItemColumn(ETarget.item_provider);
                output.sourcePath = 'setting_data_provider';
                break;
            case "ean_data_source":                
                output.column = getItemEanColumn();
                output.sourcePath = 'setting_data_ean';
                break;
            case "site_data_source":                
                output.column = getSiteColumn();
                output.sourcePath = 'setting_data_sites';
                break;
            default:                
                output.column = getGeneralColumn();
                output.sourcePath = 'setting_data';
                break;
        }
        return output;
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
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            }
        });
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
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            }
        });
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
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            }
        });

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
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            }
        });

        return col;
    }

    function getGeneralColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'nRang', visible: false, render: renderRang });
        col.push({ data: 'id', visible: false });
        col.push({ data: 'IsFile', visible: false });
        col.push({ data: 'Code', visible: false });
        col.push({ data: 'Name', visible: false });
        col.push({ data: 'DisplayName', title: "Nom" });
        col.push({ data: 'Value', title: "Valeur", render: renderValue });
        col.push({ data: null, title: "", 
            render: function (data, type, row, meta){
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            } 
        });

        return col;
    }

    function getItemEanColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_ean_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_ean_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'Code', title: "Code" });
        col.push({ data: 'Country', title: "Pays", render: rendCountry });
        col.push({ data: null, title: "", 
            render: function (data, type, row, meta){
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            } 
        });

        return col;
    }

    function getSiteColumn(){
        
        var oRender = new RenderMethod({
            routeEdit: { route: "setting_sites_edit", logo: 'fa-edit' },
            routeDelete: { route: "setting_sites_delete", logo: 'fa-trash-alt' }
        });

        var col = [];

        col.push({ data: 'id', visible: false });
        col.push({ data: 'DisplayName', title: "Nom" });
        col.push({ data: null, title: "", 
            render: function (data, type, row, meta){
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return oRender.renderControl(data, type, row, meta);
            } 
        });

        return col;
    }

    function rendCountry(data, type, row, meta){
        if (data){
            return data.Name;
        }
        return "";
    }

    function renderRang(data, type, row, meta){
        if (!data){
            return 55555555555;
        }
        return data;
    }

    function renderValue(data, type, row, meta){
        if (row.IsFile){
            return '<img src="' + $('#base_dir').val() +'/ressources/download/setting/image/' + data +'" width="89" height="25" alt="Logo"/>';
        }
        return data;
    }

});