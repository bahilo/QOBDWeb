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
            { data: 'Picture', title: "", render: Renders.renderPicture },
            { data: 'UserName', title: "Pseudo" },
            { data: 'LastName', title: "Nom" },
            { data: 'FirstName', title: "Prénom" },
            { data: 'Phone', title: "Téléphone" },
            { data: 'Email', title: "Email" },
            { data: 'Fax', title: "Fax" },
            { data: 'Fax', title: "", render: Renders.renderShow },
            { data: 'Fax', title: "", render: Renders.renderEdit },
            { data: 'Fax', title: "", render: Renders.renderDelete },
        ]
    });   

});