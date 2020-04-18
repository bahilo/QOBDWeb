$(function () {
/*================================[ Global ]==================================*/

    var detailRows = [];
    var billDetailRows = [];
    var path = "";
    var Renders = {};
    var api = {};  

/*================================[ Init ]==================================*/
          
    $(function(){
        init();
        loadTables();

    });
   
/*================================[ Events ]==================================*/

    $(function () {

        $("#global_search_form").submit(function (event) {
            event.preventDefault(); //prevent default action
            $.fn.loading('show');
            api.table.order.ajax.reload();
        });

        $('#order_detail_table_js').on('click', 'td.details-control', function () {
            openTableChildRow(this, { TableApi: api.table.orderDetail, reccordRowTable: detailRows, rowFormat: format });
        });


        $('#bill_table_js tr td.details-control').on('click', function () {
            openTableChildRow(this, { TableApi: api.table.orderBill, reccordRowTable: billDetailRows, rowFormat: billDetailformat });
        });

    });

/*================================[ Functions ]==================================*/

    //-----------------------------------------------------------------------------
    //-- initialise les variables globales
    //-----------------------------------------------------------------------------
    function init(){

        initGlobal();

        api = {
            table: {
                order: {},
                orderDetail: {},
            },
            column: {
                order: getOrderColumn(),
                orderDetail: getOrderDetailColumn(),
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
                    { data: 'Quantity', title: "Qt en cours" },
                    { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                    { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                    { data: 'null', title: "", render: renderCancelBL },
                    { data: 'null', title: "", render: renderPDF },
                ],
                orderBill: [
                    { data: 'PayReceived', visible: false },
                    { data: 'PayedAt', visible: false },
                    { data: 'PayMode', visible: false },
                    { data: 'BillPrivateComment', visible: false },
                    { data: 'BillPublicComment', visible: false },
                    { data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" },
                    { data: 'id', title: "Facture n°" },
                    { data: 'CreatedAt', title: "Date", render: renderDate },
                    { data: 'ItemSellPriceVATTotal', title: "Montant" },
                ],
                orderDelivery: [
                    { data: 'id', title: "BL n°" },
                    { data: 'CreatedAt', title: "Date", render: renderDate },
                    { data: 'Package', title: "Produit(s) expédié(s)" },
                    { data: 'null', title: "", render: renderDeliveryPDF },
                ]
            },
        } 
    }

    function initGlobal(){

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

        Renders = new RenderMethod({
            routeShow: { route: path, logo: 'fa-eye' },
            routeDelete: { route: 'order_delete', logo: 'fa-trash-alt' }
        });
    }

    //-----------------------------------------------------------------------------
    //-- charge les données des tableaux
    //-----------------------------------------------------------------------------
    function loadTables(){

        loadOrderTable();
        
        loadOrderDetailTable();
        
        loadOrderDetailDeliveryTable();
        
        loadOrderDetailBillTable();

        loadOrderDelivery()

        loadOrderBill();
        
    }

    function getParams(){
        var params = [];
        params['order'] = $('search[order]', "#global_search_form").val();
        params['bill'] = $('search[bill]', "#global_search_form").val();
        params['client'] = $('search[client]', "#global_search_form").val();
        params['agent'] = $('search[agent]', "#global_search_form").val();
        params['dtDebut'] = $('search[dtDebut]', "#global_search_form").val();
        params['dtFin'] = $('search[dtFin]', "#global_search_form").val();

        var data = { 
            search: {
                order: params['order'],
                bill: params['bill'],
                client: params['client'],
                agent: params['agent'],
                dtDebut: params['dtDebut'],
                dtFin: params['dtFin'], 
            }   
        } 

        return data;
    }

    //-----------------------------------------------------------------------------
    //-- charge les données des tableaux pour les commandes
    //-----------------------------------------------------------------------------
    function loadOrderTable(){
        if ($("#order_table_js").length > 0) {

            
            if ($('#order_data_source').length > 0){
                api.table.order = $("#order_table_js").myTable({
                    com: 'sync',
                    destroy: true,
                    columns: api.column.order,
                    dataSource: $('#order_data_source').val(),
                });
            }
            // recherche commande
            else{
                $('#order_table_js', '.tbResult').show();
                $('.no-result', '.tbResult').hide();

                api.table.order = $("#order_table_js").myTable({
                    com: 'async',
                    destroy: true,
                    columns: api.column.order,
                    ajax: {
                        type: "POST",
                        url: Routing.generate('order_search'),
                        data: function (d) {
                            d.search = {
                                order: $('[name="search[order]"]', "#global_search_form").val(),
                                bill: $('[name="search[bill]"]', "#global_search_form").val(),
                                client: $('[name="search[client]"]', "#global_search_form").val(),
                                agent: $('[name="search[agent]"]', "#global_search_form").val(),
                                dtDebut: $('[name="search[dtDebut]"]', "#global_search_form").val(),
                                dtFin: $('[name="search[dtFin]"]', "#global_search_form").val(),
                            }
                        }
                    },
                });
            }
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour le détail d'une commande
    //-----------------------------------------------------------------------------
    function loadOrderDetailTable(){
        if ($("#order_detail_table_js").length > 0) {
            if (JSON.parse($("#order_detail_data_source").val()).length > 0) {
                api.table.orderDetail = $("#order_detail_table_js").myTable({
                    destroy: true,
                    dataSource: $("#order_detail_data_source").val(),
                    columns: api.column.orderDetail,
                    initComplete: function (setting, json, api) {
                        $(api.$('td.details-control')).each(function (index) {
                            if ($("#control_can_open_row").length > 0)
                                openTableChildRow(this, { TableApi: api, reccordRowTable: detailRows, rowFormat: format });
                        });
                    },
                    rowCallback: function (nRow, aData, index) {
                    }
                });
            }
            else {
                $("#order_detail_table_js").html('<tr><td>Aucune donnée trouvée</td></tr>');
            }
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour les bons de livraison en cours de création
    //-----------------------------------------------------------------------------
    function loadOrderDetailDeliveryTable(){
        if ($("#order_detail_delivery_table_js").length > 0) {
            api.table.orderDetailDelivery = $("#order_detail_delivery_table_js").myTable({
                dataSource: $("#order_detail_delivery_data_source").val(),
                columns: api.column.orderDetailDelivery,
                initComplete: function (setting, json, api) {
                    $.fn.initEventBtnDelete();
                }
            });
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour les facture en cours de création
    //-----------------------------------------------------------------------------
    function loadOrderDetailBillTable(){
        if ($("#order_detail_bill_table_js").length > 0) {
            api.table.orderDetailBill = $("#order_detail_bill_table_js").myTable({
                dataSource: $("#order_detail_bill_data_source").val(),
                columns: api.column.orderDetailBill,
                initComplete: function (setting, json, api) {
                    $.fn.initEventBtnDelete();
                }
            });
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour les factures créer
    //-----------------------------------------------------------------------------
    function loadOrderBill(){
        if ($("#bill_table_js").length > 0) {
            api.table.orderBill = $("#bill_table_js").myTable({
                dataSource: $("#bill_data_source").val(),
                columns: api.column.orderBill,
                initComplete: function (setting, json, api) {
                    $(api.$('td.details-control')).each(function (index) {
                        if ($("#control_can_open_row").length > 0)
                            openTableChildRow(this, { TableApi: api, reccordRowTable: billDetailRows, rowFormat: billDetailformat });
                    });
                    $.fn.initEventBtnDelete();
                },
            });
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour les bons de livraison créer
    //-----------------------------------------------------------------------------
    function loadOrderDelivery(){
        if ($("#delivery_table_js").length > 0) {
            api.table.orderDelivery = $("#delivery_table_js").myTable({
                dataSource: $("#delivery_data_source").val(),
                columns: api.column.orderDelivery,
                initComplete: function (setting, json, api) {
                    $.fn.initEventBtnDelete();
                }
            });
        }
    }

/*================================[ Utlities ]==================================*/

    function getOrderColumn(){
        var col = [];

        col.push({ data: 'id', title: "Devis n°" });
        col.push({ data: 'CreatedAtToString', title: "date" });
        col.push({ data: 'ClientCompanyName', title: "Société" });
        col.push({ data: 'AgentFirstName', title: "Commercial", render: renderAgent });
        col.push({ data: 'id', title: "Détail", render: Renders.renderShow });

        if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'id', title: "Supp.", render: Renders.renderDelete });
        }

        return col;
    }

    function getOrderDetailColumn(){
        var col = [];
        
        col.push({ data: 'id', visible: false });
        col.push({ data: 'ContentComment', visible: false });
        col.push({ data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" });
        col.push({ data: 'ItemName', title: "Nom" });
        if($('#is_read_sensible','.access_pool').length > 0 && $('#is_read_sensible','.access_pool').val()){
            col.push({ data: 'ItemPurchasePrice', title: "P. achat" });
        }
        col.push({ data: 'ItemSellPrice', title: "P. vente", render: inputSellPriceForm });
        col.push({ data: 'Quantity', title: "Quantité", render: inputQuantityForm });
        col.push({ data: 'null', title: "Qt. en attente", render: renderQtEnAttente });
        col.push({ data: 'ItemSellPriceTotal', title: "P. vente total", render: renderDigit });
        col.push({ data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit });
        if ($('#is_read_sensible', '.access_pool').length > 0 && $('#is_read_sensible', '.access_pool').val()) {
            col.push({ data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit });
            col.push({ data: 'ItemROICurrency', title: "Marge", render: renderDigit });
        }
        
        return col;
    }

    //-----------------------------------------------------------------------------
    //-- page détail: affiche le détail de chaque ligne produit
    //-----------------------------------------------------------------------------
    function openTableChildRow(elt, params){
        var tr = $(elt).closest('tr');
        var row = params.TableApi.row(tr);
        var idx = $.inArray(tr.attr('id'), params.reccordRowTable);

        var data = row.data();

        if (typeof data.ItemSellPrice == typeof undefined || renderQtEnAttente(null, null, data) > 0){
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
    }

    function getPreOrderPreRefundRender(){
        if ($('#status_refund').length() == 0)
            return Renders.renderShow;
        else
            return 
    }
    
/*================================[ Renders ]==================================*/

    function format(row) {
        return '<div class="reception-produit">' +
            '<div class="row">' +
                '<div class="col-md-6">' +
                    '<label for="comment' + row.id +'">Commentaire</label>'+
            '<textarea name="order_detail_form[tab][items][' + row.id + '][comment]" id="comment' + row.id + '" cols="30" rows="5" class="form-control">' + row.ContentComment+'</textarea>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label for="quantity' + row.id + '">Quantité reçu</label>' +
                    '<input type="number" name="order_detail_form[tab][items][' + row.id + '][quantity_recieved]" id="quantity' + row.id + '" class="form-control">' +
                '</div>' +
            '</div>' +
        '</div>'
    }
    

    function billDetailformat(row) {
        var payedDate = moment(row.PayedAt).format('YYYY-MM-DD');

        return '<div class="bill-produit">' +
            
            '<div class="row">' +
                '<div class="col-md-6">' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<label for="payed_amount' + row.id +'">Montant réglé</label>'+
                            '<input type="number" name="order_detail_form[tab][bill][' + row.id + '][payed_amount]" id="payed_amount' + row.id + '" class="form-control" value="'+ row.PayReceived +'">' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<label for="pay_date' + row.id + '">Date de paiement</label>' +
            '<input type="date" name="order_detail_form[tab][bill][' + row.id + '][pay_date]" id="pay_date' + row.id + '" class="form-control" value="' + payedDate + '">' +
                        '</div>' +                        
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-md-12">' +
                            '<label for="pay_mode' + row.id + '">Sous la forme</label>' +
                            '<input type="text" name="order_detail_form[tab][bill][' + row.id + '][pay_mode]" id="pay_mode' + row.id + '" class="form-control" value="'+ row.PayMode +'">' +
                        '</div>' +
                    '</div>' +                    
                '</div>' +
                '<div class="col-md-6">' +
                    '<div class="form-check">'+
                        '<input type="checkbox" name="order_detail_form[tab][bill][' + row.id + '][ref_visible]" id="ref_visible' + row.id + '" class="form-check-input">' +
                        '<label for="ref_visible' + row.id + '" class="form-check-label">Référence Visisble </label>' +
                    '</div>'+
                    '<a href="'+  Routing.generate('order_pdf_bill', { id: row.id }) +'" class="btn btn-primary">Générer PDF</a><br/>' +
                    '<a href="mailto:toto@yahoo.fr" class="">Ouvrir un mail</a><br/>' +
            '<a href="' + Routing.generate('order_bill_cancel', { id: row.id }) +'" class="btn btn-danger btnDelete">Annuler cette facture</a>' +
                '</div>' +
            '</div>' +
        '</div>'+
        '<br />' +
        '<div class="row">' +
            '<div class="col-md-6">' +
                '<label for="comment_private' + row.id +'">Commentaire interne</label>'+
                '<textarea name="order_detail_form[tab][bill][' + row.id + '][comment_private]" id="comment_private' + row.id + '" cols="30" rows="5" class="form-control">' + row.BillPrivateComment+'</textarea>' +
            '</div>' +
            '<div class="col-md-6">' +
                '<label for="comment_public' + row.id + '">Commentaire public</label>' +
                '<textarea name="order_detail_form[tab][bill][' + row.id + '][comment_public]" id="comment_public' + row.id + '" cols="30" rows="5" class="form-control">' + row.BillPublicComment+'</textarea>' +
            '</div>' +
        '</div>';        
    }

    

    function renderAgent(data, type, row){
        return row['AgentFirstName'] + " " + row['AgentLastName'];
    }

    function inputPurchasePriceForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][items][' + row.id + '][purchase]" class="form-control"></div>';
    }

    function inputSellPriceForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][items][' + row.id + '][sell]" class="form-control"></div>';
    }

    function inputQuantityForm(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][items][' + row.id + '][quantity]" class="form-control"></div>';
    }

    function renderDigit(data, type, row) {
        var num = parseFloat(data);
        if(!isNaN(num))
            return num.toFixed(2);
        return data;
    }

    function rowDetail(data, type, row) {
        return '<div class="form-group"><input type="text" value="' + data +'" name="order_detail_form[tab][items][' + row.id + '][quantity]" class="form-control"></div>';
    }

    function renderCancel(data, type, row) {
        return '<a class="btn btn-danger btnDelete" href="' + Routing.generate("order_delivery_reset", { id: row.id }) +'">Reinitialiser cette ref.</a>';
    }

    function renderCancelBL(data, type, row) {
        return '<a class="btn btn-danger btnDelete" href="' + Routing.generate("order_delivery_cancel", { id: row.id }) +'">Annuler ce BL</a>';
    }

    function renderPDF(data, type, row) {
        return '<a class="btn btn-primary" href="#">Générer le BL</a>';
    }

    function renderDeliveryPDF(data, type, row) {
        return '<a class="btn btn-primary" href="' + Routing.generate('order_pdf_delivery', { id: row.id }) +'">Générer le BL</a>';
    }

    function renderPreRefundShow(data, type, row) {
        return "<a href='" + Routing.generate('order_show_prerefund', { id: row.id }) + "'><i class='fa fa-eye'></i></a>";
    }

    function renderDate(data, type, row) {
        var myDate = new Date(data);
        return myDate.getDate() + '/' + (myDate.getMonth()+1) + '/' + myDate.getFullYear();
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