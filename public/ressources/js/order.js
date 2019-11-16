$(function () {

    var path = "";

    switch ($('#order_target').val()){
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

    var orderTable = $("#order_table_js").myTable({
        dataSource: $("#order_data_source").val(),
        columns: [
            { data: 'id', title: "Devis n°" },
            { data: 'CreatedAtToString', title: "date" },
            { data: 'ClientCompanyName', title: "Société" },
            { data: 'AgentFirstName', title: "Commercial", render: renderAgent },
            { data: 'id', title: "Détail", render: Renders.renderShow },
            { data: 'id', title: "Supp.", render: Renders.renderDelete },
        ]
    });


    var detailTable = $("#order_detail_table_js").myTable({
        dataSource: $("#order_detail_data_source").val(),
        columns: [
            { data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" },
            { data: 'ItemRef', title: "Référence" },
            { data: 'ItemPurchasePrice', title: "P. achat", render: inputPurchasePriceForm },
            { data: 'ItemSellPrice', title: "P. vente", render: inputSellPriceForm  },
            { data: 'Quantity', title: "Quantité", render: inputQuantityForm  },
            { data: 'ItemSellPriceTotal', title: "P. vente total" },
            { data: 'ItemSellPriceVATTotal', title: "P. TTC total"},
            { data: 'ItemROIPercent', title: "Marge (%)"},
            { data: 'ItemROICurrency', title: "Marge" },
        ]
    });


    $(function () {

        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#order_detail_table_js').on('click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = detailTable.row(tr);
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

    function format(d) {
        return 'product: ' + d.ItemRef + '<br>' +
            'sell: ' + d.ItemPurchasePrice + '<br>' +
            'The child row can contain any data you wish, including links, images, inner tables etc.';
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

});