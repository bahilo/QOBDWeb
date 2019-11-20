$(function () {

    var path = "";

    switch ($('#order_target').val()) {
        case "order_data_source":
            path = "order_show";
            break;
        case "quote_data_source":
            path = "order_show_quote";
            break;
        case "preorder_data_source":
            path = "order_show_preorder";
            break;
        case "prerefund_data_source":
            path = "order_show";
            break;
        case "refund_data_source":
            path = "order_show";
            break;
        case "bill_data_source":
            path = "order_show";
            break;
        case "bill_refund_data_source":
            path = "order_show";
            break;
        case "customer_valid_data_source":
            path = "order_show_quote";
            break;
        case "closed_data_source":
            path = "order_show";
            break;
        case "refund_closed_data_source":
            path = "order_show";
            break;
    }

    var Renders = new RenderMethod({
        routeShow: { route: path, logo: 'fa-eye' },
        routeDelete: { route: 'order_delete', logo: 'fa-trash-alt' }
    });

    api = {
        table:{
            order:{},
            orderDetail: {},
        },
        column:{
            order:[
                { data: 'id', title: "Devis n°" },
                { data: 'CreatedAtToString', title: "date" },
                { data: 'ClientCompanyName', title: "Société" },
                { data: 'AgentFirstName', title: "Commercial", render: renderAgent },
                { data: 'id', title: "Détail", render: Renders.renderShow },
                { data: 'id', title: "Supp.", render: Renders.renderDelete },
            ],
            orderDetail:[
                { data: 'id', visible: false },
                { data: 'ContentComment', visible: false },
                { data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" },
                { data: 'ItemName', title: "Nom" },
                { data: 'ItemRef', title: "Référence" },
                { data: 'ItemPurchasePrice', title: "P. achat", render: inputPurchasePriceForm },
                { data: 'ItemSellPrice', title: "P. vente", render: inputSellPriceForm },
                { data: 'Quantity', title: "Quantité", render: inputQuantityForm },
                { data: 'null', title: "Qt. en attente", render: renderQtEnAttente },
                { data: 'ItemSellPriceTotal', title: "P. vente total" },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total" },
                { data: 'ItemROIPercent', title: "Marge (%)" },
                { data: 'ItemROICurrency', title: "Marge" },
            ],
            orderDetailDelivery: [
                { data: 'id', visible: false },
                { data: 'ItemName', title: "Nom" },
                { data: 'ItemRef', title: "Référence" },
                { data: 'QuantityRecieved', title: "Qt en cours" },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total" },
                { data: 'ItemROIPercent', title: "Marge (%)" },
                { data: 'null', title: "", render: renderCancel },
            ],
            orderDetailBill: [
                { data: 'id', visible: false },
                { data: 'ItemName', title: "Nom" },
                { data: 'ItemRef', title: "Référence" },
                { data: 'QuantityRecieved', title: "Qt en cours" },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total" },
                { data: 'ItemROIPercent', title: "Marge (%)" },
                { data: 'null', title: "", render: renderCancelBL },
                { data: 'null', title: "", render: renderPDF },
            ],
            orderBill: [
                { data: 'id', title: "Facture n°" },
                { data: 'CreatedAt', title: "Date" },
                { data: 'Pay', title: "Montant" },
                { data: 'LimitDateAt', title: "Date" },
                { data: 'null', title: "Facture", render: renderPDF },
                { data: 'null', title: "", render: renderCancelBL },
                { data: 'null', title: "", render: renderCancelBL },
                { data: 'null', title: "", render: renderCancelBL },
            ]
        },
    }
        

    api.table.order = $("#order_table_js").myTable({
        dataSource: $("#order_data_source").val(),
        columns: api.column.order
    });

    api.table.orderDetail = $("#order_detail_table_js").myTable({
        dataSource: $("#order_detail_data_source").val(),
        columns: api.column.orderDetail,
        initComplete: function(setting, json){
            $('#order_detail_table_js tr td.details-control').trigger("click");
            //alert('complete!');
        },
        rowCallback: function (nRow, aData, index) { 
            //alert('row  !');
            //addTableChil(nRow, aData, index);
        }
    });

    api.table.orderDetailDelivery = $("#order_detail_delivery_table_js").myTable({
        dataSource: $("#order_detail_delivery_data_source").val(),
        columns: api.column.orderDetailDelivery
    });

    api.table.orderDetailBill = $("#order_detail_bill_table_js").myTable({
        dataSource: $("#order_detail_bill_data_source").val(),
        columns: api.column.orderDetailBill
    });

    api.table.orderBill = $("#bill_table_js").myTable({
        dataSource: $("#bill_data_source").val(),
        columns: api.column.orderBill
    });


    $(function () {

        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#order_detail_table_js tr td.details-control').on('click', function () {
            var tr = $(this).closest('tr');
            var row = api.table.orderDetail.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            }
            else {
                tr.addClass('details');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });
    });
    

    function format(row) {
        return '<div class="reception-produit">' +
            '<div class="row">' +
                '<div class="col-md-6">' +
                    '<label for="comment' + row.id +'">Commentaire</label>'+
            '<textarea name="order_detail_form[tab][' + row.id + '][comment]" id="comment' + row.id + '" cols="30" rows="5" class="form-control">' + row.ContentComment+'</textarea>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label for="quantity' + row.id + '">Quantité reçu</label>' +
                    '<input type="number" name="order_detail_form[tab][' + row.id + '][quantity_recieved]" id="quantity' + row.id + '" class="form-control">' +
                '</div>' +
            '</div>' +
        '</div>'
    }

    

    function renderAgent(data, type, row){
        return row['AgentFirstName'] + " " + row['AgentLastName'];
    }

    function inputPurchasePriceForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][' + row.id + '][purchase]" class="form-control"></div>';
    }

    function inputSellPriceForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][' + row.id + '][sell]" class="form-control"></div>';
    }

    function inputQuantityForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][' + row.id + '][quantity]" class="form-control"></div>';
    }

    function rowDetail(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][' + row.id + '][quantity]" class="form-control"></div>';
    }

    function renderCancel(data, type, row) {
        return '<a class="btn btn-danger" href="#">Reinitialiser cette ref.</a>';
    }

    function renderCancelBL(data, type, row) {
        return '<a class="btn btn-danger" href="#">Annuler ce BL</a>';
    }

    function renderPDF(data, type, row) {
        return '<a class="btn btn-dark" href="#">Générer le BL</a>';
    }

    function renderQtEnAttente(data, type, row) {
        var qtDel = 0;
        var qt = 0;

        if (row.Quantity)
            qt = row.Quantity;
        if(row.QuantityDelivery)
            qtDel = row.QuantityDelivery;

        return qt -qtDel;
    }

});