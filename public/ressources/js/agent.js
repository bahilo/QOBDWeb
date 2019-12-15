$(function(){

    var Renders = new RenderMethod({
        routeShow: { route: 'agent_show', logo: 'fa-eye' },
        routeEdit: { route: 'security_edit', logo: 'fa-edit' },
        routeDelete: { route: 'security_delete', logo: 'fa-trash-alt' }
    });

    $("#agent_table_js").myTable({
        dataSource: $("#agent_data_source").val(),
        columns: [
            { data: 'id', title: "", visible: false },
            { data: 'IsActivated', title: "", visible: false },
            //{ data: 'Picture', title: "", render: Renders.renderPicture },
            { data: 'UserName', title: "Pseudo" },
            { data: 'LastName', title: "Nom" },
            { data: 'FirstName', title: "Prénom" },
            //{ data: 'Phone', title: "Téléphone" },
            { data: 'Email', title: "Email" },
            { data: 'null', title: "", render: renderActivateAgent },
            { data: 'null', title: "", render: Renders.renderShow },
            { data: 'null', title: "", render: Renders.renderEdit },
            { data: 'null', title: "", render: Renders.renderDelete },
        ]
    });   



    function renderActivateAgent(data, type, row) {
        if (row.IsActivated)
            return '<a class="btn btn-dark" href="' + Routing.generate('security_activate_agent', { id: row.id }) + '">Désactiver</a>';
        else
            return '<a class="btn btn-success" href="' + Routing.generate('security_activate_agent', { id: row.id }) + '">Activer</a>';
    }

});