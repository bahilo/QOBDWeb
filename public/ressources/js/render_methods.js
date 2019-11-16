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
        if (!vars.routeShow)
            return '';
        return "<a href='" + Routing.generate(vars.routeShow.route, { id: row.id }) + "'><i class='fa " + vars.routeShow.logo +"'></i></a>";
    }

    this.renderEdit = function (data, type, row) {
        if (!vars.routeEdit)
            return '';
        return "<a href='" + Routing.generate(vars.routeEdit.route, { id: row.id }) + "'><i class='fa " + vars.routeEdit.logo +"'></i></a>";
    }

    this.renderDelete = function (data, type, row) {
        if (!vars.routeDelete)
            return '';
        return "<a href='" + Routing.generate(vars.routeDelete.route, { id: row.id }) + "'><i class='fa " + vars.routeDelete.logo +"'></i></a>";
    }

    this.renderAddCart = function (data, type, row) {
        if (!vars.routeDelete)
            return '';
        
        var regex = /\$\((.+?)\)/;
        var compiledTemplate = _.template($('#cart_btn').html(), { interpolate: regex });
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
        
        return '<a href="' + Routing.generate('client_select', { id: row.id })+'" class="bx_select"><i data-id="' + row['id'] +'" class="fa fa-plus-square"></i></a>';
    }

    /*this.renderDate = function (data, type, row) {
        var d = new Date();

        return '<a href="' + Routing.generate('client_select', { id: row.id })+'" class="bx_select"><i data-id="' + row['id'] +'" class="fa fa-plus-square"></i></a>';
    }*/

    this.construct(args);
}

/*
<div class="input-group">
    <input type="text" class="form-control" placeholder="Input group example" aria-label="Input group example" aria-describedby="btnGroupAddon">
    <div class="input-group-append">
      <div class="input-group-text" id="btnGroupAddon"><i class="fa fa-cart-plus"></i></div>
    </div>
</div>
*/