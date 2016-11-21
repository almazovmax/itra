/**
 * Created by 31.75 on 18.11.2016.
 */

(function( $ ) {
    $.fn.ajaxGrid = function() {

        var settings = $.extend( {
            'dataUrl'          : 'http://mylocalserver/ajax/products',
            'sortableColumns'  : '[“id”, “title”, “created_date”]',
            'filterableColumns': '[“id”, “title”]',
            'rowsPerPage'      : '20'
        }, options);

        return this.each(function() {

            $.getJSON(settings.dataUrl,function (data) {
                var response = $.parseJSON(data);
                responseHandler(response);

                function responseHandler(response)
                {
                    var c = [];
                    $.each(response, function(i, item) {
                        c.push("<tr><td>" + item.id + "</td>");
                        c.push("<td>" + item.title + "</td>");
                        c.push("<td>" + item.created_date + "</td></tr>");
                    });

                    $('#entities-grid').html(c.join(""));
                }

            })
        });

    };
})(jQuery);

