$(function () {
/*================================[ Global ]==================================*/

    var detailRows = [];
    var billDetailRows = [];
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
            event.preventDefault();
            $.fn.loading('show');
            api.table.order.ajax.reload(searchTableInitComplete);
        });

        $('#order_detail_table_js').on('click', 'td.details-control', function () {
            openTableChildRow(this, { TableApi: api.table.orderDetail, reccordRowTable: detailRows, rowFormat: format });
        });

        $('#bill_table_js').on('click','td.details-control', function () {
            openTableChildRow(this, { TableApi: api.table.orderBill, reccordRowTable: billDetailRows, rowFormat: billDetailformat });
        });

        $('#btnAddItem').on('click', function(){
            initTableAddItem();            
        });        

    });

/*================================[ Functions ]==================================*/

    //-----------------------------------------------------------------------------
    //-- popup Catalogue
    //-----------------------------------------------------------------------------    
    function displayItemTable(){
        var $root = $('#mdlDefault');
        var footer = '<button type="button" class="btn btn-secondary _mvalide">Valider</button>';

        $.fn.displayMessage({
            messageTitle: "Ajout d'un nouveau produit",
            messageBody: getAddItemTable(),
            footer: footer,
            size: 'lg',
            isCentered: false,
            errorCode: 601
        });

        $("#tblNewItemToOrder").myTable({
            com: 'async',
            showLoding: false,
            ajax: {
                url: Routing.generate('catalogue_home_data'),
            },
            columns: getItemColumn(),
            initComplete: function(){                
                validateQuoteItemsOnClick();
                resetCartOnClick();
                $.fn.initTooltip();
            },
            fnDrawCallback: function(){
                getAddItemOnCartBtnClick();
            }
        });  
       
    }

    //-----------------------------------------------------------------------------
    //-- Traitement action table ajout article dans le devis/commande
    //----------------------------------------------------------------------------- 
    function getAddItemOnCartBtnClick(){
        $('#mdlDefault').find('.cart_add_js').on('click', function (e) {
            e.preventDefault();
            
            $('.addToOrderMessage', '.addToOrderWrapper').empty();
            $id = $(this).data('id');
            $quantity = $('input[data-id="' + $id + '"]').val();
            if (!isNaN(parseInt($quantity)) && parseInt($quantity) > 0) {
                $.fn.ajaxLoader({
                    url: Routing.generate('cart_quote_add', { id: $id, quantity: $quantity }),
                    type: 'POST',
                    data: [],
                    onSuccess: function (result) {
                        var data = JSON.parse(result);
                        var rowDat = $('input[data-id="' + $id + '"]').parents('tr').find('td');
                        $('#mdlDefault').find('.cart_total').text(data.total);
                        if(data.code == 200)
                            $('.addToOrderMessage', '.addToOrderWrapper').prepend('<div class="alert alert-success">Votre Article ref. ' + $(rowDat.get(1)).text() +' a été ajouté au panier avec succés!</div>');
                        else if(data.code == 500)
                            $('.addToOrderMessage', '.addToOrderWrapper').prepend('<div class="alert alert-danger">Erreur: Une erreur est survenue lors du traitement de votre requête!</div>');
                        
                    },
                });
            }
            else {
                alert('Veuillez renseigner une quantité supérieur à 0!');
            }
        });
    }

    function validateQuoteItemsOnClick(){
        $('#mdlDefault').find('._mvalide').on('click', function(){
            window.location = Routing.generate('order_quote_add', { id: $('#order_id').val(), status: $('#btnAddItem').data('status') });
        });
    }

    function initTableAddItem(){
        $.fn.ajaxLoader({
            url: Routing.generate('cart_reset'),
            type: 'POST',
            data: [],
            onSuccess: function (result) {
                displayItemTable();                
            },
        });
    }

    function resetCartOnClick(){
        $('#mdlDefault').find('.btnCartReset').on('click', function (e) {
            e.preventDefault();
            $('.addToOrderMessage', '.addToOrderWrapper').empty();
            resetCart();
        });
    }

    function resetCart(){
        $.fn.ajaxLoader({
            url: Routing.generate('cart_reset'),
            type: 'POST',
            data: [],
            onSuccess: function (result) {
                var data = JSON.parse(result);

                if (data.code == 200) {
                    $('#mdlDefault').find('.cart_total').text(0);
                    $('.addToOrderMessage', '.addToOrderWrapper').prepend('<div class="alert alert-success">Votre panier a été vidé avec succés!</div>');
                }
                else if (data.code == 500)
                    $('.addToOrderMessage', '.addToOrderWrapper').prepend('<div class="alert alert-danger">Erreur: Une erreur est survenue lors la réinitialisation de votre panier!</div>');

            },
        });
    }

    //-----------------------------------------------------------------------------
    //-- initialise les variables globales
    //-----------------------------------------------------------------------------
    function init(){

        initGlobal();

        var renders = new RenderMethod();

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
                    { data: 'Item.Name', title: "Nom" },
                    { data: 'Item.Ref', title: "Référence" },
                    { data: 'QuantityRecieved', title: "Qt en cours" },
                    { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                    { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                    { data: 'null', title: "", render: renderCancel },
                ],
                orderDetailBill: [
                    { data: 'id', visible: false },
                    { data: 'OrderDetail', title: "Nom", render: renders.renderItemName },
                    { data: 'OrderDetail', title: "Référence", render: renders.renderItemRef },
                    { data: 'Quantity', title: "Qt en cours" },
                    { data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit },
                    { data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit },
                    //{ data: 'null', title: "", render: renderCancelBL },
                    //{ data: 'null', title: "", render: renderPDF },
                ],
                orderBill: [
                    { data: 'PayReceived', visible: false },
                    { data: 'PayedAt', visible: false },
                    { data: 'PayMode', visible: false },
                    { data: 'IsRefVisible', visible: false },
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

        var info = getTargetInfo();
        Renders = new RenderMethod({
            ajaxSource: info.source,
            routeShow: { route: info.showPath, logo: 'fa-eye' },
            routeDelete: { route: info.deletePath, logo: 'fa-trash-alt' }
        });
    }

    //-----------------------------------------------------------------------------
    //-- charge les données des tableaux
    //-----------------------------------------------------------------------------
    function loadTables(){

        loadSearchOrderTable();

        loadOrderTable();
        
        loadOrderDetailTable();
        
        loadOrderDetailDeliveryTable();
        
        loadOrderDetailBillTable();

        loadOrderDelivery()

        loadOrderBill();
        
    }

    //-----------------------------------------------------------------------------
    //-- charge les données des tableaux pour les commandes
    //-----------------------------------------------------------------------------
    function loadOrderTable(){
        if ($("#order_table_js").length > 0 && $("#CmdSearch").length == 0) {

            var info = getTargetInfo();
            if (info.sourcePath){
                api.table.order = $("#order_table_js").myTable({
                    com: 'async',
                    destroy: true,
                    columns: api.column.order,
                    ajax: {
                        type: "POST",
                        url: Routing.generate(info.sourcePath),
                    },
                });
            }
        }
    }



    //-----------------------------------------------------------------------------
    //-- Recherche: charge les données des tableaux pour les commandes
    //-----------------------------------------------------------------------------
    function loadSearchOrderTable() {
        if ($("#CmdSearch").length > 0) {

            $('#order_table_js', '.tbResult').show();
            $('.no-result', '.tbResult').hide();

            api.table.order = $("#order_table_js").myTable({
                com: 'async',
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
                initComplete: searchTableInitComplete,
            });
        }
    }

    function searchTableInitComplete(){
        $.fn.loading('hide');
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour le détail d'une commande
    //-----------------------------------------------------------------------------
    function loadOrderDetailTable(){
        if ($("#order_detail_table_js").length > 0) {
            api.table.orderDetail = $("#order_detail_table_js").myTable({
                com: 'async',
                ajax: {
                    type: 'POST',
                    url: Routing.generate('order_data_detail', { id: $('#order_id').val() })
                },
                //dataSource: $("#order_detail_data_source").val(),
                columns: api.column.orderDetail,
                initComplete: function (setting, json, api) {
                    $(api.$('td.details-control')).each(function (index) {
                        if ($("#control_can_open_row").length > 0)
                            openTableChildRow(this, { TableApi: api, reccordRowTable: detailRows, type: "detail", rowFormat: format });
                    });
                    $.fn.initEventBtnDelete();
                    $.fn.initTooltip();
                },
                rowCallback: function (nRow, aData, index) {
                }
            });
        }
    }

    //-----------------------------------------------------------------------------
    //-- charge les données du tableau pour les bons de livraison en cours de création
    //-----------------------------------------------------------------------------
    function loadOrderDetailDeliveryTable(){
        if ($("#order_detail_delivery_table_js").length > 0) {
            api.table.orderDetailDelivery = $("#order_detail_delivery_table_js").myTable({
                com: 'async',
                ajax:{
                    type: 'POST',
                    url: Routing.generate('order_data_delivery_processing_receipt', {id: $('#order_id').val()})
                },
                //dataSource: $("#order_detail_delivery_data_source").val(),
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
                com: 'async',
                ajax:{
                    type: 'POST',
                    url: Routing.generate('order_data_bill_processing_receipt', {id: $('#order_id').val()})
                },
                //dataSource: $("#order_detail_bill_data_source").val(),
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
                com: 'async',
                ajax: {
                    type: 'POST',
                    url: Routing.generate('order_data_bill_receipt', { id: $('#order_id').val() })
                },
                //dataSource: $("#bill_data_source").val(),
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
                com: 'async',
                ajax: {
                    type: 'POST',
                    url: Routing.generate('order_data_delivery_receipt', { id: $('#order_id').val() })
                },
                //dataSource: $("#delivery_data_source").val(),
                columns: api.column.orderDelivery,
                initComplete: function (setting, json, api) {
                    $.fn.initEventBtnDelete();
                }
            });
        }
    }

/*================================[ Utlities ]==================================*/

    function getTargetInfo(){
        var output = {
            status: $('#order_status').val(),
            showPath: "",
            deletePath: 'order_delete',
            sourcePath: "",
        };

        switch (output.status) {
            case "STATUS_ORDER":
                output.showPath = "order_show";
                output.sourcePath = 'order_data';
                break;
            case "STATUS_QUOTE":
                output.showPath = "order_show_quote";
                output.sourcePath = "order_data_quote";
                break;
            case "STATUS_PREORDER":
                output.showPath = "order_show_preorder";
                output.sourcePath = 'order_data_preorder';
                break;
            case "STATUS_PREREFUND":
                output.showPath = "order_show_prerefund";
                output.sourcePath = 'order_data_prerefund';
                break;
            case "STATUS_REFUND":
                output.showPath = "order_show_refund";
                output.sourcePath = 'order_data_refund';
                break;
            case "STATUS_BILL":
                output.showPath = "order_show_order_bill";
                output.sourcePath = 'order_data_bill';
                break;
            case "STATUS_REFUNDBILL":
                output.showPath = "order_show_refund_bill";
                output.sourcePath = 'order_data_bill_refund';
                break;
            case "STATUS_VALID":
                output.showPath = "order_show_valid";
                output.sourcePath = 'order_data_valid';
                break;
            case "STATUS_CLOSED":
                output.showPath = "order_show_closed";
                output.sourcePath = 'order_data_closed';
                break;
            case "STATUS_REFUNDCLOSED":
                output.showPath = "order_show_refund_closed";
                output.sourcePath = 'order_data_refund_closed';
                break;
        }

        return output;
    }

    function getItemColumn() {
        var col = [];

        col.push({ data: 'id', title: "", visible: false });
        col.push({ data: 'IsErasable', title: "", visible: false });
        col.push({ data: 'FullPathPicture', title: "", render: Renders.renderPicture });
        col.push({ data: 'Ref', title: "N° Serie" });
        col.push({ data: 'Name', title: "Désignation" });
        col.push({ data: 'ImeiCode', title: "EAN", render: Renders.renderEAN });
        col.push({ data: 'ImeiCode', title: "IMEI", render: Renders.renderImei });
        col.push({ data: 'SellPrice', title: "P. Vente" });  
        col.push({ data: 'id', title: "Ajouter", render: renderAddCart });

        return col;
    }
    
    function getOrderColumn(){
        var col = [];

        col.push({ data: 'id', title: "Devis n°" });
        col.push({ data: 'CreatedAtToString', title: "date" });
        col.push({ data: 'ClientCompanyName', title: "Société" });
        col.push({ data: 'AgentFirstName', title: "Commercial", render: renderAgent });
        //col.push({ data: 'id', title: "Détail", render: Renders.renderShow });
        col.push({
            data: 'id', title: "",
            render: function (data, type, row, meta) {
                meta.settings.oInit.customParam = {
                    access: $.fn.getAccess()
                };
                return Renders.renderControl(data, type, row, meta);
            }
        });
        /*if($('#is_delete','.access_pool').length > 0 && $('#is_delete','.access_pool').val()){
            col.push({ data: 'id', title: "Supp.", render: Renders.renderDelete });
        }*/

        return col;
    }

    function getOrderDetailColumn(){
        var col = [];
        
        col.push({ data: 'id', visible: false });
        col.push({ data: 'Item', visible: false });
        if ($("#control_can_open_row").length > 0){
            col.push({ data: 'null', title: "", class: "details-control", orderable: false, defaultContent: "" });
        }
        col.push({ data: 'Item.Ref', title: "Ref." });
        col.push({ data: 'Item.Name', title: "Nom" });
        if($('#is_read_sensible','.access_pool').length > 0 && $('#is_read_sensible','.access_pool').val()){
            col.push({ data: 'ItemPurchasePrice', title: "P. achat" });
        }
        col.push({ data: 'ItemSellPrice', title: "P. vente" });
        col.push({ data: 'Quantity', title: "Quantité" });
        col.push({ data: 'null', title: "Qt. en attente", render: renderQtEnAttente });
        col.push({ data: 'ItemSellPriceTotal', title: "P. vente total", render: renderDigit });
        col.push({ data: 'ItemSellPriceVATTotal', title: "P. TTC total", render: renderDigit });
        if ($('#is_read_sensible', '.access_pool').length > 0 && $('#is_read_sensible', '.access_pool').val()) {
            col.push({ data: 'ItemROIPercent', title: "Marge (%)", render: renderDigit });
        }

        if ($('#is_delete', '.access_pool').val()){
            col.push({ data: 'id', title: "Supp.", render: renderDelete });
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

        if (!params.type || (data.Quantity - data.QuantityDelivery) > 0){
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

    
/*================================[ Renders ]==================================*/


    //-----------------------------------------------------------------------------
    //-- Table des produits pour l'ajout d'un nouvel élément
    //-----------------------------------------------------------------------------
    function getAddItemTable() {
        return '<div class="addToOrderWrapper">' +
            '<div class="row">' +
            '<div class="col-md-10">' +
            '<div class="addToOrderMessage"></div>' +
            '</div>' +
            '<div class="col-md-2">' +
            '<a href="#" class="btn"><i class="fa fa-shopping-cart"></i> <span class="cart_total">0</span></a>' +
            '<a href="' + Routing.generate('cart_reset') + '" class="btn btn-danger btnCartReset"><i class="fa fa-trash"></i></a>' +
            '</div>' +
            '</div>' +

            '<table id="tblNewItemToOrder" class="table table-responsive-md table-bordered table-striped table-md"></table>' +
            '</div>'
    }
  
    function format(row) {
        return '<div class="reception-produit">' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<label for="comment' + row.id +'">Commentaire</label>'+
                            '<textarea name="order_detail_form[tab][items][' + row.id + '][comment]" id="comment' + row.id + '" cols="30" rows="5" class="form-control">' + ( (row.Item.Comment && row.Item.Comment.Content != null) ? row.Item.Comment.Content : "" )+'</textarea>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="form-group">'+
                                '<label for="quantity_recieved' + row.id + '">Quantité reçu</label>' +
                                '<input type="number" name="order_detail_form[tab][items][' + row.id + '][quantity_recieved]" id="quantity_recieved' + row.id + '" class="form-control">' +
                            '</div>' +
                            '<div class="row">'+
                                '<div class="col-md-6">'+
                                    '<div class="form-group">'+
                                        '<label for="quantity' + row.id + '">Quantité</label>' +
                                        '<input type="text" value="' + row.Quantity +'" name="order_detail_form[tab][items][' + row.id + '][quantity]" id="quantity' + row.id + '" class="form-control">' +
                                    '</div>' +
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<div class="form-group">'+
                                        '<label for="sell' + row.id + '">Prix de vente</label>' +
                                        '<input type="text" value="' + row.ItemSellPrice +'" name="order_detail_form[tab][items][' + row.id + '][sell]" id="sell' + row.id + '" class="form-control">' +
                                    '</div>' +                            
                                '</div>'+
                            '</div>'+
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
                    /*'<div class="form-check">'+
                        '<input type="checkbox" name="order_detail_form[tab][bill][' + row.id + '][ref_visible]" id="ref_visible' + row.id + '" class="form-check-input" ' + (row.IsRefVisible ? "checked" : "") +'>' +
                        '<label for="ref_visible' + row.id + '" class="form-check-label">Référence Visisble </label>' +
                    '</div>'+*/
                    '<a href="'+  Routing.generate('order_pdf_bill', { id: row.id }) +'" class="btn btn-primary">Générer PDF</a><br/>' +
                    '<a href="mailto:'+ (row.Contact ? row.Contact.Email : "") +'" class="">Ouvrir un mail</a><br/>' +
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

    function renderDelete(data, type, row) {
        var route = Routing.generate('order_quote_item_delete', { id: $('#order_id').val(), idItem: row.Item.id, status: $('#order_status').val() });
        
        return '<a href="' + route + '" class="btnDelete btn btn-danger" data-toggle="tooltip" data-placement="top" title="Supprimer">' +
            '<i class="fa fa-trash"></i>' +
            '</a>';
    }

    function renderQtEnAttente(data, type, row) {
        var qtDel = 0;
        var qt = 0;

        if (row.Quantity)
            qt = row.Quantity;
        if(row.QuantityDelivery)
            qtDel = row.QuantityDelivery;

        return qt - qtDel;
    }

    function renderAddCart(data, type, row) {
        
        var regex = /\$\((.+?)\)/;
        var compiledTemplate = _.template($('#template-cart_btn').html(), { interpolate: regex });
        return compiledTemplate({
            cart_route: '',
            cart_default_value: 1,
            cart_item_id: row.id
        });
    }

});