$.fn.myTable  = function (options) {

    var table = null;
    var setting = $.extend({
        dataSource: "",
        columns: [],
        initComplete: function (setting, json) { },
        rowCallback: function (nRow, aData, index) {},
    }, options);
    
    if (setting.dataSource.length > 0) {

        var dataSource = JSON.parse(setting.dataSource);
        if (dataSource && dataSource.length > 0) {
            table = $(this).DataTable({
                data: dataSource,
                columns: setting.columns,
                initComplete: setting.initComplete,
                fnRowCallback: function (nRow, aData, index) { 
                    setting.rowCallback(nRow, aData, index);
                },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: 'none',
                        target: ''
                    }
                }
            });
            return table;
        }
    }

    return this;

};