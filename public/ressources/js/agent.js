$(function(){

    if($("#agent_data_source").length > 0){

        var data = JSON.parse($("#agent_data_source").val()) ;
        if(data && data.length > 0){
            $("#agent_table_js").DataTable({
                data: data,
                columns:[
                    { data: 'id', title: "", visible: false },
                    { data: 'Picture', title: "", render: renderPicture },
                    { data: 'UserName', title: "Pseudo" },
                    { data: 'LastName', title: "Nom" },
                    { data: 'FirstName', title: "Prénom" },
                    { data: 'Phone', title: "Téléphone" },
                    { data: 'Email', title: "Email" },
                    { data: 'Fax', title: "Fax" },
                    { data: 'Fax', title: "", render: renderShow },
                    { data: 'Fax', title: "", render: renderEdit },
                    { data: 'Fax', title: "", render: renderDelete },
                ]
            });
        }
    }

    /*_________________________________________________________________________ */
    /*____________________________[ Fonctions ]________________________________ */

    function renderShow(data, type, row){
        return "<a href='"+ Routing.generate('agent_show', {id: row.id}) +"'><i class='fa fa-eye'></i></a>";
    }

    function renderEdit(data, type, row){
        return "<a href='"+ Routing.generate('agent_edit', {id: row.id}) +"'><i class='fa fa-edit'></i></a>";
    }

    function renderDelete(data, type, row){
        return "<a href='"+ Routing.generate('agent_delete', {id: row.id}) +"'><i class='fa fa-trash-alt'></i></a>";
    }

    function renderPicture(data, type, row){
        if(!data)
            return "";

        return "<img src='"+ data +"' class='agent_pict_css'/>";
    }

})