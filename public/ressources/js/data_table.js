$.fn.myTable  = function (options) {

    var table = null;
    var setting = $.extend({
        dataSource: "",
        columns: []
    }, options);
    
    if (setting.dataSource.length > 0) {

        var dataSource = JSON.parse(setting.dataSource);
        if (dataSource && dataSource.length > 0) {
            table = $(this).DataTable({
                data: dataSource,
                columns: setting.columns
            });
            return table;
        }
    }

    return this;

};