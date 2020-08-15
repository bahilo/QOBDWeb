$(function () {

    var pathEdit = "";
    var pathDelete = "";

    switch ($('#security_target').val()) {
        case "home":
            
            break;
        case "action":
            pathEdit = "security_action_edit";
            pathDelete = "security_action_delete";
            break;
        case "role":
            pathEdit = "security_role_edit";
            pathDelete = "security_role_delete";
            break;
    }

    var Renders = new RenderMethod({
        routeEdit: { route: pathEdit, logo: 'fa-edit' },
        routeDelete: { route: pathDelete, logo: 'fa-trash-alt' }
    });

    api = {
        table:{},
        column: {
            role: getRoleColumns(),
            action: getActionColumns(),
        },
    }  

    api.table.action = $("#action_table_js").myTable({
        dataSource: $("#action_data_source").val(),
        columns: api.column.action,
        initComplete: function (setting, json) {
            $.fn.initTooltip();
            $.fn.initEventBtnDelete();
        },
    });

    api.table.role = $("#role_table_js").myTable({
        dataSource: $("#role_data_source").val(),
        columns: api.column.role,
        initComplete: function (setting, json) {
            $.fn.initTooltip();
            $.fn.initEventBtnDelete();
        },
    });

    function getActionColumns(){

        var col = [];
        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'Name', title: "Nom" });
        col.push({ data: 'DisplayName', title: "Description" });
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return Renders.renderControl(data, type, row, meta);
            }
        });
        return col;
    }

    function getRoleColumns(){

        var col = [];
        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'Name', title: "Nom" });
        col.push({ data: 'DisplayName', title: "Description" });
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return Renders.renderControl(data, type, row, meta);
            }
        });
        return col;
    }

    
    
    
})