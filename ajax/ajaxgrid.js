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
                function createTable(response)
                {
                    var c = [];
                    for(var prop in response)
                        {
                            if(response.hasOwnProperty(prop))
                            c.push("<th>"+prop+"</th>");
                        }
                    $.each(response, function(i, item)
                        {
                            c.push("<tr>");
                            for(var prop in response)
                            {
                                if(response.hasOwnProperty(prop))
                                c.push("<td>"+response[prop]+"</td>");
                            }
                        c.push("</tr>");
                        }
                    );
                    return c;
                }
                var response = $.parseJSON(data);
                var c=createTable(response);
                $(this).html(c.join(""));
            })
        });

    };
})(jQuery);

