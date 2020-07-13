$(document).ready(function () {

    /*============================================[ DataTAbles]================================ */

    $.fn.myTable  = function (options) {

        var table = null;
        var setting = $.extend({
            destroy: true,
            com: 'sync',
            dataSource: "",
            showLoding: true,
            order: [[1, "desc"]],
            ajax: {},
            columns: [],
            initComplete: function (setting, json) {},
            rowCallback: function (nRow, aData, index) { },
            fnDrawCallback: function (oSettings) { },
        }, options);
        
        if (setting.dataSource.length > 0 && setting.com == "sync" || setting.ajax && setting.com == "async") {

            $.fn.DataTable.ext.classes.sPaging = 'ul ';
            $.fn.DataTable.ext.classes.sPageButton = 'li page-item ';

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            var lang = {
                "emptyTable": "Aucune donnée dans la table",
                "lengthMenu": "Nombre de lignes _MENU_",
                "zeroRecords": "Aucun résultat trouvé",
                "loadingRecords": "Chargement...",
                "info": "Page _PAGE_ of _PAGES_",
                "infoEmpty": "Aucun résultat disponible",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search": "Recherche rapide",
                paginate: {
                    "first": "Première",
                    "last": "Dernière",
                    "next": "Suivante",
                    "previous": "Précédante"
                },
            };

            var dataTableConfig = {
                language: lang,
                lengthMenu: [5, 10, 20, 50, 100, 200, 500],
                destroy: setting.destroy,
                autoWidth: false,
                order: setting.order,
                columns: setting.columns,
                info: false,
                dom: '<"top"<"row"<"col-md-10 saveIgnore"f><"col-md-2 saveIgnore"l>>>r<"table-wrapper" t><"bottom nav-wrapper"p><"clear">',
                BeforeSend: setting.beforeSend,
                initComplete: function (settings, json) {
                    setting.initComplete(settings, json, this.api());                    
                },
                fnRowCallback: function (nRow, aData, index) {
                    setting.rowCallback(nRow, aData, index);
                },
                fnDrawCallback: function(oSettings){
                    setting.fnDrawCallback(oSettings);
                }
            }

            if (setting.com == "sync" && setting.dataSource && JSON.parse(setting.dataSource).length > 0) {
                dataTableConfig.data = JSON.parse(setting.dataSource);
            }
            else if (setting.com == "async") {
                dataTableConfig.ajax = setting.ajax;                
            }

            table = $(this).DataTable(dataTableConfig);
        }
        $.fn.dataTable.ext.errMode = 'throw';
        return table;
    };
});
