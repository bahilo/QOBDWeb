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
            role: [
                { data: 'id', title: "", visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'DisplayName', title: "Description" },
                { data: 'null', title: "", render: Renders.renderEdit },
                { data: 'null', title: "", render: Renders.renderDelete },
            ],
            action: [
                { data: 'id', title: "", visible: false },
                { data: 'Name', title: "Nom" },
                { data: 'DisplayName', title: "Description" },
                { data: 'null', title: "", render: Renders.renderEdit },
                { data: 'null', title: "", render: Renders.renderDelete },
            ]
        },
    }  

    api.table.action = $("#action_table_js").myTable({
        dataSource: $("#action_data_source").val(),
        columns: api.column.action
    });

    api.table.role = $("#role_table_js").myTable({
        dataSource: $("#role_data_source").val(),
        columns: api.column.role
    });

    
    
})