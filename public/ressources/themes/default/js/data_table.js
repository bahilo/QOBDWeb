$(document).ready(function () {

    /*============================================[ DataTAbles]================================ */

    $.fn.myTable  = function (options) {

        var table = null;
        var setting = $.extend({
            destroy: true,
            com: 'sync',
            dataSource: "",
            ajax: {},
            columns: [],
            beforeSend: function (jqXHR, settings){},
            initComplete: function (setting, json) {},
            rowCallback: function (nRow, aData, index) { },
        }, options);
        
        if (setting.dataSource.length > 0 && setting.com == "sync" || setting.ajax && setting.com == "async") {

            $.fn.DataTable.ext.classes.sPaging = 'ul ';
            $.fn.DataTable.ext.classes.sPageButton = 'li page-item ';

            if (setting.com == "sync" && setting.dataSource && JSON.parse(setting.dataSource).length > 0) {
                var dataSource = JSON.parse(setting.dataSource);
                table = $(this).DataTable({
                    destroy: setting.destroy,
                    autoWidth: false,
                    data: dataSource,
                    columns: setting.columns,
                    info: false,
                    dom: '<"top"<"row"<"col-md-10"f><"col-md-2"l>>>rt<"bottom nav-wrapper"p><"clear">',
                    /*columnDefs: [
                        { width: '20%', targets: 9 }
                    ],*/
                    initComplete: function (settings, json) {
                        setting.initComplete(settings, json, this.api());
                    },
                    fnRowCallback: function (nRow, aData, index) {
                        setting.rowCallback(nRow, aData, index);
                    },
                });
            }
            else if (setting.com == "async") {
                table = $(this).DataTable({
                    destroy: setting.destroy,
                    autoWidth: false,
                    ajax: setting.ajax,
                    columns: setting.columns,
                    info: false,
                    dom: '<"top"<"row"<"col-md-10"f><"col-md-2"l>>>rt<"bottom nav-wrapper"p><"clear">',
                    beforeSend: function (jqXHR, settings) {
                        $.fn.loading('show');
                        setting.beforeSend(jqXHR, settings);
                    },
                    initComplete: function (settings, json) {
                        setting.initComplete(settings, json, this.api());
                    },
                    fnRowCallback: function (nRow, aData, index) {
                        $.fn.loading('hide');
                        setting.rowCallback(nRow, aData, index);
                    },
                });
            }
        }
        
        return table;
    };
});
