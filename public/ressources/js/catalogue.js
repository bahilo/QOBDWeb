$(function () {

    var Renders = new RenderMethod({
        routeShow: {},
        routeEdit: { route: 'catalogue_item_edit', logo: 'fa-edit' },
        routeDelete: {}
    });

    $("#item_table_js").myTable({
        dataSource: $("#item_data_source").val(),
        columns: [
            { data: 'id', title: "", visible: false },
            { data: 'Picture', title: "", render: Renders.renderPicture },
            { data: 'Ref', title: "Réf." },
            { data: 'Name', title: "Désignation" },
            { data: 'ItemBrandName', title: "Marque" }, 
            { data: 'ItemGroupeName', title: "Sous famille" },
            { data: 'PurchasePrice', title: "P. Achat" },
            { data: 'SellPrice', title: "P. Vente" },
            { data: 'id', title: "Modif.", render: Renders.renderEdit },
            { data: 'id', title: "Ajouter", render: Renders.renderAddCart },
        ]
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

});