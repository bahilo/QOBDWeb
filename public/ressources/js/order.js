$(function () {

    var detailRows = [];
    var billDetailRows = [];
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
            path = "order_show_prerefund";
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
                { data: 'ItemSellPriceTotal', title: "P. vente total", render: renderDigit },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                { data: 'ItemROICurrency', title: "Marge", render: renderDigit },
            ],
            orderDetailDelivery: [
                { data: 'id', visible: false },
                { data: 'ItemName', title: "Nom" },
                { data: 'ItemRef', title: "Référence" },
                { data: 'QuantityRecieved', title: "Qt en cours" },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                { data: 'null', title: "", render: renderCancel },
            ],
            orderDetailBill: [
                { data: 'id', visible: false },
                { data: 'ItemName', title: "Nom" },
                { data: 'ItemRef', title: "Référence" },
                { data: 'QuantityRecieved', title: "Qt en cours" },
                { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                { data: 'null', title: "", render: renderCancelBL },
                { data: 'null', title: "", render: renderPDF },
            ],
            orderBill: [
                { data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" },
                { data: 'id', title: "Facture n°" },
                { data: 'CreatedAt', title: "Date" },
                { data: 'Pay', title: "Montant" },
            ],
            orderDelivery: [
                { data: 'id', title: "BL n°" },
                { data: 'CreatedAt', title: "Date" },
                { data: 'Package', title: "Produit(s) expédié(s)" },
                { data: 'null', title: "", render: renderDeliveryPDF },
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
        initComplete: function(setting, json, api){            
            $(api.$('td.details-control')).each(function(index){
                openTableChildRow(this, { TableApi: api, reccordRowTable: detailRows, rowFormat: format});
            });
            //$('#order_detail_table_js tr td.details-control')
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
        columns: api.column.orderBill,
        initComplete: function (setting, json, api) {
            $(api.$('td.details-control')).each(function (index) {
                openTableChildRow(this, { TableApi: api, reccordRowTable: billDetailRows, rowFormat: billDetailformat});
            });
            //$('#order_detail_table_js tr td.details-control')
            //alert('complete!');
        },
    });

    api.table.orderDelivery = $("#delivery_table_js").myTable({
        dataSource: $("#delivery_data_source").val(),
        columns: api.column.orderDelivery
    });


    $(function () {

        // Array to track the ids of the details displayed rows
        

        $('#order_detail_table_js').on('click','td.details-control', function () {
            openTableChildRow(this, { TableApi: api.table.orderDetail, reccordRowTable: detailRows, rowFormat: format});            
        });

        
        $('#bill_table_js tr td.details-control').on('click', function () {
            openTableChildRow(this, { TableApi: api.table.orderBill, reccordRowTable: billDetailRows, rowFormat: billDetailformat});
        });

    });

    function openTableChildRow(elt, params){
        var tr = $(elt).closest('tr');
        var row = params.TableApi.row(tr);
        var idx = $.inArray(tr.attr('id'), params.reccordRowTable);

        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Remove from the 'open' array
            params.reccordRowTable.splice(idx, 1);
        }
        else {
            tr.addClass('details');
            row.child(params.rowFormat(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                params.reccordRowTable.push(tr.attr('id'));
            }
        }
    }

    function getPreOrderPreRefundRender(){
        if ($('#status_refund').length() == 0)
            return Renders.renderShow;
        else
            return 
    }
    

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
    

    function billDetailformat(row) {
        return '<div class="bill-produit">' +
            
            '<div class="row">' +
                '<div class="col-md-6">' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<label for="payed_amount' + row.id +'">Montant réglé</label>'+
                            '<input type="number" name="order_detail_form[tab][bill][' + row.id + '][payed_amount]" id="payed_amount' + row.id + '" class="form-control">' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<label for="pay_date' + row.id + '">Date de paiement</label>' +
                            '<input type="date" name="order_detail_form[tab][bill][' + row.id + '][pay_date]" id="pay_date' + row.id + '" class="form-control">' +
                        '</div>' +                        
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-12">' +
                            '<label for="pay_mode' + row.id + '">Sous la forme</label>' +
                            '<input type="text" name="order_detail_form[tab][bill][' + row.id + '][pay_mode]" id="pay_mode' + row.id + '" class="form-control">' +
                        '</div>' +
                    '</div>' +                    
                '</div>' +
                '<div class="col-md-6">' +
                    '<div class="form-check">'+
                        '<input type="checkbox" name="order_detail_form[tab][bill][' + row.id + '][ref_visible]" id="ref_visible' + row.id + '" class="form-check-input">' +
                        '<label for="ref_visible' + row.id + '" class="form-check-label">Référence Visisble </label>' +
                    '</div>'+
                    '<a href="'+  Routing.generate('order_pdf_bill', { id: row.id }) +'" class="btn btn-dark">Générer PDF</a><br/>' +
                    '<a href="mailto:toto@yahoo.fr" class="">Ouvrir un mail</a><br/>' +
                    '<a href="'+  Routing.generate('order_bill_cancel', { id: row.id }) +'" class="btn btn-danger">Annuler cette facture</a>' +
                '</div>' +
            '</div>' +
        '</div>'+
        '<br />' +
        '<div class="row">' +
            '<div class="col-md-6">' +
                '<label for="comment_private' + row.id +'">Commentaire interne</label>'+
                '<textarea name="order_detail_form[tab][bill][' + row.id + '][comment_private]" id="comment_private' + row.id + '" cols="30" rows="5" class="form-control">' + row.ContentComment+'</textarea>' +
            '</div>' +
            '<div class="col-md-6">' +
                '<label for="comment_public' + row.id + '">Commentaire public</label>' +
                '<textarea name="order_detail_form[tab][bill][' + row.id + '][comment_public]" id="comment_public' + row.id + '" cols="30" rows="5" class="form-control">' + row.ContentComment+'</textarea>' +
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

    function renderDigit(data, type, row) {
        var num = parseFloat(data);
        if(!isNaN(num))
            return num.toFixed(2);
        return data;
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

    function renderDeliveryPDF(data, type, row) {
        return '<a class="btn btn-dark" href="' + Routing.generate('order_pdf_delivery', { id: row.id }) +'">Générer le BL</a>';
    }

    function renderPreRefundShow(data, type, row) {
        return "<a href='" + Routing.generate('order_show_prerefund', { id: row.id }) + "'><i class='fa fa-eye'></i></a>";
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