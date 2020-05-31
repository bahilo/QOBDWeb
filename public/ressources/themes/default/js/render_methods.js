var RenderMethod = function(args){

    var vars = {
        cartDefaultValue: 1,
        routeShow:{},
        routeEdit: {},
        routeDelete: {}
    };

    this.construct = function(args){
        $.extend(vars, args);
    }

    /*_________________________________________________________________________ */
    /*____________________________[ Fonctions ]________________________________ */

    this.renderShow = function (data, type, row) {
        if (!vars.routeShow.route)
            return '';
        return '<a href="' + Routing.generate(vars.routeShow.route, { id: row.id }) + '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Page détail">'+
                    '<i class="fa ' + vars.routeShow.logo +'"></i>'+
                '</a>';
    }

    this.renderEdit = function (data, type, row) {
        if (!vars.routeEdit.route)
            return '';
        return '<a href="' + Routing.generate(vars.routeEdit.route, { id: row.id }) + '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Modifier">'+
                    '<i class="fa ' + vars.routeEdit.logo +'"></i>'
                +'</a>';
    }

    this.renderDelete = function (data, type, row) {
        var canDelete = true;

        if (typeof row.IsErasable != 'undefined' && !row.IsErasable)
            canDelete = false;

        if (!vars.routeDelete.route || !canDelete)
            return '<a href="#" class="btn btn-danger"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Produit utilisé dans une commande"></i></a>';

        return '<a href="' + Routing.generate(vars.routeDelete.route, { id: row.id }) + '" class="btnDelete btn btn-danger" data-toggle="tooltip" data-placement="top" title="Supprimer">'+
                    '<i class="fa ' + vars.routeDelete.logo + '"></i>'+
                '</a>';
    }

    this.renderControl = function (data, type, row, meta) {
        var showCtn = '';
        var editCtn = '';
        var deleteCtn = '';

        var access = meta.settings.oInit.customParam ? meta.settings.oInit.customParam.access : {};
        if (vars.routeShow.route && access && access.read){
            showCtn = '<a href="' + Routing.generate(vars.routeShow.route, { id: row.id }) + '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Page détail">' +
                '<i class="fa ' + vars.routeShow.logo + '"></i>' +
                '</a>';
        }

        if (vars.routeEdit.route && access && access.update){
            editCtn = '<a href="' + Routing.generate(vars.routeEdit.route, { id: row.id }) + '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Modifier">' +
                '<i class="fa ' + vars.routeEdit.logo + '"></i>'
                + '</a>';
        }

        if (access && access.delete){
            var canDelete = true;
            if (typeof row.IsErasable != 'undefined' && !row.IsErasable)
                canDelete = false;

            if (!vars.routeDelete.route || !canDelete) {
                deleteCtn = '<a href="#" class="btn btn-danger"><i class="btnDelete fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Produit utilisé dans une commande"></i></a>';
            }
            else {
                deleteCtn = '<a href="' + Routing.generate(vars.routeDelete.route, { id: row.id }) + '" class="btnDelete btn btn-danger" data-toggle="tooltip" data-placement="top" title="Supprimer">' +
                    '<i class="fa ' + vars.routeDelete.logo + '"></i>' +
                    '</a>';
            }
        }

        return '<div class="btn-group">' + showCtn + editCtn + deleteCtn + '</div>';
    }
    /*
    <button type="button" class="btn btn-brand btn-behance">
        <i class="fa fa-behance"></i><span>Behance</span>
    </button>
    */

    this.renderAddCart = function (data, type, row) {
        if (!vars.routeDelete)
            return '';
        
        var regex = /\$\((.+?)\)/;
        var compiledTemplate = _.template($('#template-cart_btn').html(), { interpolate: regex });
        return compiledTemplate({ 
            cart_route: Routing.generate('cart_add', { id: row.id, quantity: vars.cartDefaultValue }),
            cart_default_value: vars.cartDefaultValue,
            cart_item_id: row.id
        });
    }

    this.renderPicture = function (data, type, row) {
        if (!data)
            return "";

        return "<img src='" + data + "' class='agent_pict_css'/>";
    }

    this.renderCheckbox = function (data, type, row) {
        
        return '<input type="checkbox" data-id="' + row['id'] +'" class="cbx"/>';
    }

    this.renderSelect = function (data, type, row) {
        
        return '<a href="' + Routing.generate('client_select', { id: row.id })
            + '" class="bx_select" data-toggle="tooltip" data-placement="top" title="Sélectionner la société ' + row.CompanyName +' pour un devis"><i data-id="' + row['id'] 
            +'" class="fa fa-handshake"></i></a>';
    }

    this.renderImei = function (data, type, row, meta) {
        if (data) {
            return data.Code
        }
        return "";
    }

    this.renderEAN = function (data, type, row, meta) {
        if (data && data.EanCode) {
            return data.EanCode.Code
        }
        return "";
    }

    this.renderItemName = function (data, type, row, meta) {
        if (data && data.Item.Name) {
            return data.Item.Name
        }
        return "";
    }

    this.renderItemRef = function (data, type, row, meta) {
        if (data && data.Item.Ref) {
            return data.Item.Ref
        }
        return "";
    }

    this.construct(args);
}