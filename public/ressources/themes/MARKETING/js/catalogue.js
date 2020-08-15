$(function () {

/*================================[ Init ]==================================*/
    var Renders = new RenderMethod({
        routeShow: {},
        routeEdit: { route: 'catalogue_item_edit', logo: 'fa-edit' },
        routeDelete: { route: 'catalogue_delete', logo: 'fa-trash' }
    });

/*==========================[ début programme ]================================*/

    $(function(){

        $("#item_table_js").myTable({
            //dataSource: $("#item_data_source").val(),
            com: "async",
            ajax: {
                url: Routing.generate("catalogue_home_data"),
            },
            columns: getColumn(),
            initComplete: function (setting, json, api) {
                $.fn.initEventBtnDelete();
                $.fn.initTooltip();
            },
        });

    });

/*================================[ Events ]==================================*/

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

/*================================[ Functions ]==================================*/

    function getColumn(){
        var col = [];

        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'IsErasable', title: "", visible: false });
        col.push({ data: 'Ref', title: "Ref." });
        col.push({ data: 'Name', title: "Désignation" });

        /*if($('#is_read_sensible','.access_pool').length > 0 && $('#is_read_sensible','.access_pool').val()){
            col.push({ data: 'PurchasePrice', title: "P. Achat" });
        }*/        

        col.push({ data: 'SellPrice', title: "P. Vente" });
        //col.push({ data: 'Stock', title: "Stock" });

        if ($('#is_quote_write', '.access_pool').length > 0 && $('#is_quote_write', '.access_pool').val()) {
            col.push({ data: 'id', title: "Ajouter", render: Renders.renderAddCart });
        }      

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


});