$(function () {

    var actionRenders = new RenderMethod({
        routeShow:'security_action_edit',
        routeEdit: 'security_action_edit',
        routeDelete: 'security_action_delete'
    });

    $("#action_table_js").myTable({
        dataSource: $("#action_data_source").val(),
        columns: [
            { data: 'id', title: "", visible: false },
            { data: 'Name', title: "Nom" },
            { data: 'DisplayName', title: "Description" },
            { data: 'id', title: "", render: actionRenders.renderShow },
            { data: 'id', title: "", render: actionRenders.renderEdit },
            { data: 'id', title: "", render: actionRenders.renderDelete },
        ]
    });

    var roleRenders = new RenderMethod({
        routeShow: 'security_role_edit',
        routeEdit: 'security_role_edit',
        routeDelete: 'security_role_delete'
    });

    $("#role_table_js").myTable({
        dataSource: $("#role_data_source").val(),
        columns: [
            { data: 'id', title: "", visible: false },
            { data: 'Name', title: "Nom" },
            { data: 'id', title: "", render: roleRenders.renderShow },
            { data: 'id', title: "", render: roleRenders.renderEdit },
            { data: 'id', title: "", render: roleRenders.renderDelete },
        ]
    });
    
})