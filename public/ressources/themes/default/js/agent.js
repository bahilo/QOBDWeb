$(function(){

    var Renders = new RenderMethod({
        routeShow: { route: 'agent_show', logo: 'fa-eye' },
        routeEdit: { route: 'security_edit', logo: 'fa-edit' },
        routeDelete: { route: 'security_delete', logo: 'fa-trash-alt' }
    });

    $("#agent_table_js").myTable({
        dataSource: $("#agent_data_source").val(),
        columns: getColumn()
    });   


    function getColumn(){
        var col = [];

        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'IsActivated', title: "", visible: false });
        col.push({ data: 'UserName', title: "Pseudo" });
        col.push({ data: 'LastName', title: "Nom" });
        col.push({ data: 'FirstName', title: "Prénom" });
        col.push({ data: 'Email', title: "Email" });

        if($('#is_admin','.access_pool').length > 0 && $('#is_admin','.access_pool').val()){
            col.push({ data: 'null', title: "", render: renderActivateAgent });
        }

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'null', title: "", render: Renders.renderShow });
            col.push({ data: 'null', title: "", render: Renders.renderEdit });
        }

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'null', title: "", render: Renders.renderDelete });
        }

        return col;
    }

    function renderActivateAgent(data, type, row) {
        if (row.IsActivated)
            return '<a class="btn btn-dark" href="' + Routing.generate('security_activate_agent', { id: row.id }) + '">Désactiver</a>';
        else
            return '<a class="btn btn-success" href="' + Routing.generate('security_activate_agent', { id: row.id }) + '">Activer</a>';
    }

});