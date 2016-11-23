/**
 * Created by 31.75 on 18.11.2016.
 */

(function( $ ) {
        var settings = $.extend( {
            'dataUrl'          : 'http://mylocalserver/ajax/products',
            'sortableColumns'  : '[“id”, “title”, “created_date”]',
            'filterableColumns': '[“id”, “title”]',
            'rowsPerPage'      : '20'
        }, options);

        var methods = {
            init : function( params ) {
                var options = $.extend({}, settings, params);
                $.getJSON(options.dataUrl,function (data) {
                    function createTable(response)
                    {
                        var c = [];
                        c.push("<table class='sortable'>");
                        c.push("<thead class='dataColumns'>");
                        for(var prop in response)
                        {
                            if(response.hasOwnProperty(prop))
                            {
                                if((options.filterableColumns.includes(prop))&&(options.sortableColumns.includes(prop))){
                                    c.push("<th class='sortableColumn filterableColumns'>" + prop + "<input id='searchInput' placeholder='Type to filter'>"+ "</th>");
                                    this.getElementById('searchInput').id=prop;
                                }
                                else if(options.sortableColumns.includes(prop)){
                                    c.push("<th class='sortableColumn'>" + prop + "</th>");
                                }else if (options.filterableColumns.includes(prop)){
                                    c.push("<th class='filterableColumns'>" + prop + "<input id='searchInput' placeholder='Type to filter'>"+"</th>");
                                    this.getElementById('searchInput').id=prop;
                                }
                                else
                                    c.push("<th>" + prop + "</th>");
                            }
                        }
                        c.push("</thead>");
                        c.push("<tbody id='dataRows'>");
                        $.each(response, function()
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
                        c.push("</tbody>");
                        c.push("</table>");
                        c.push("<div id='paging'>"+"Showing"+"<span id='begin'>"+"</span>"+"-"+"<span id='end'>"+
                        "</span>"+"of"+"<span id='size'>"+"</span>"+"<input id='last' class='btn' type='Button' value='Last' onclick='javascript:last()' />"+
                        "<input id='next' class='btn' type='button' value='Next' onclick='javascript:next()'/>"+"</div>");
                        return c;
                    }
                    var response = $.parseJSON(data);
                    var c=createTable(response);
                    $(this).after().html(c.join(""));
                })
            },
            update : function( params ) {
                var options = $.extend({}, settings, params);
                $.getJSON(options.dataUrl,function (data) {
                    function createTable(response)
                    {
                        var c = [];
                        c.push("<tbody id='dataRows'>");
                        $.each(response, function()
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
                        c.push("</tbody>");
                        return c;
                    }
                    var response = $.parseJSON(data);
                    var c=createTable(response);
                    $(this).html(c.join(""));
                })
            },
        };
    $.fn.ajaxGrid= function( method ) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method named' +  method + 'does not exist for jquery.ajaxGrid');
        }
    };
})(jQuery);

