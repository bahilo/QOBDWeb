$(function () {

    var Renders = new RenderMethod({
        routeShow: {},
        routeEdit: { route: 'catalogue_item_edit', logo: 'fa-edit' },
        routeDelete: { route: 'catalogue_delete', logo: 'fa-trash' }
    });

    $("#item_table_js").myTable({
        dataSource: $("#item_data_source").val(),
        columns: getColumn()
    });

    $(function(){

        $('.cart_add_js').on('click', function(e){
            
            $id = $(this).data('id');
            $quantity = $('input[data-id="' + $id + '"]').val();
            if (!isNaN(parseInt($quantity)) && parseInt($quantity) > 0){
                if ($quantity > 1) {
                    e.preventDefault();
                    window.location.href = Routing.generate('cart_add', { id: $id, quantity: $quantity });
                } 
            } 
            else{
                e.preventDefault();       
                alert('Veuillez renseigner une quantité supérieur à 0!');
            }

        });

    });

    function getColumn(){
        var col = [];

        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'IsErasable', title: "", visible: false });
        col.push({ data: 'FullPathPicture', title: "", render: Renders.renderPictur });
        col.push({ data: 'Ref', title: "Réf." });
        col.push({ data: 'Name', title: "Désignation" });
        col.push({ data: 'ItemBrandName', title: "Marque" });
        col.push({ data: 'ItemGroupeName', title: "Sous famille" });

        if($('#is_read_sensible','.access_pool').length > 0 && $('#is_read_sensible','.access_pool').val()){
            col.push({ data: 'PurchasePrice', title: "P. Achat" });
        }        

        col.push({ data: 'SellPrice', title: "P. Vente" });        

        if($('#is_update','.access_pool').length > 0 && $('#is_update','.access_pool').val()){
            col.push({ data: 'id', title: "Modif.", render: Renders.renderEdit });
        }

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'id', title: "Supp.", render: Renders.renderDelete });
        }

        if($('#is_quote_write','.access_pool').length > 0 && $('#is_quote_write','.access_pool').val()){
            col.push({ data: 'id', title: "Ajouter", render: Renders.renderAddCart });
        }

        return col;
    }

});