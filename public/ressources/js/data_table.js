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

            $.fn.DataTable.ext.classes.sPaging = 'ul ';
            $.fn.DataTable.ext.classes.sPageButton = 'li page-item ';

            table = $(this).DataTable({
                autoWidth: false,
                data: dataSource,
                columns: setting.columns,
                info: false, 
                dom: '<"top"<"row"<"col-md-10"f><"col-md-2"l>>>rt<"bottom nav-wrapper"p><"clear">',
                /*columnDefs: [
                    { width: '20%', targets: 9 }
                ],*/
                initComplete: function (settings, json){
                   var api = this.api();
                     
                    var paginationRoot = $(this).next('.nav-wrapper');
                    var navHtml = paginationRoot;
                    navHtml.html('<nav>' + navHtml.html()+'</nav>');
                    $ulHtml = $('.ul', paginationRoot);                    
                    $ulHtml.html('<ul class="pagination">' + $ulHtml.html() +'</ul>');
                    $('div.ul', paginationRoot).removeClass('ul');

                    $liRoot = $('ul.pagination', paginationRoot);
                    $('.pagination a.li', paginationRoot).each(function(index){
                        $li = {};
                        if(index == 0){
                            $liRoot.append('<li class="page-item" data-id="_1"></li>');
                            $li = $('li[data-id="_1"]', $liRoot);
                        }
                        else if ($('.pagination a', paginationRoot).length - 1 == index){
                            $liRoot.append('<li class="page-item" data-id="1_"></li>');
                            $li = $('li[data-id="1_"]', $liRoot);
                        }
                        else{
                            if(index == 1)
                                $liRoot.append('<li class="page-item active" data-id="' + (index - 1) + '"></li>'); 
                            else
                                $liRoot.append('<li class="page-item" data-id="'+ (index - 1) +'"></li>');                        
                            $li = $('li[data-id="' + (index - 1) +'"]', $liRoot);
                        }
                        
                        $(this).detach();                           
                        $(this).appendTo($li);

                        $(this).removeClass('page-item li');
                        $(this).addClass('page-link');
                    });
                    
                    $('.page-link', paginationRoot).on('click', function () {
                        var index = $(this).parent('li').data('id');
                        
                        $('li.page-item', paginationRoot).removeClass('active');
                        if (index === '_1'  ){
                            $('li[data-id="0"]', paginationRoot).addClass('active');
                            if (api.page() > 0){
                                $('li.page-item', paginationRoot).removeClass('active');                  
                                $('li[data-id="' + (api.page() - 1) + '"]', paginationRoot).addClass('active');              
                                api.page('previous').draw('page'); 
                            } 
                        }
                        else if (index === '1_' ){
                            $('li[data-id="' + ($('.pagination a', paginationRoot).length - 3) + '"]', paginationRoot).addClass('active');
                            if (api.page() < $('.pagination a', paginationRoot).length - 3){
                                $('li.page-item', paginationRoot).removeClass('active'); 
                                $('li[data-id="' + (api.page() + 1) + '"]', paginationRoot).addClass('active');                              
                                api.page('next').draw('page');
                            }
                        }
                        else if (index != api.page() && index != '1_' && index != '_1'){
                            $('li[data-id="' + index + '"]', paginationRoot).addClass('active');
                            api.page(index).draw('page');
                        }
                    });

                    setting.initComplete(settings, json, this.api());
                },
                fnRowCallback: function (nRow, aData, index) { 
                    setting.rowCallback(nRow, aData, index);
                },
            });

        }
    }

    

    return table;

};