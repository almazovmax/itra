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
                        c.push("<thead>");
                        for(var prop in response)
                        {
                            if(response.hasOwnProperty(prop))
                            {
                                if(options.sortableColumns.includes(prop))
                                    c.push("<th class='sortableColumn'>" + prop + "</th>");
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
                        // c.push("<div id='paging'>"+"Showing"+"<span id='begin'>"+"</span>"+"-"+"<span id='end'>"+
                        // "</span>"+"of"+"<span id='size'>"+"</span>"+"<input id='last' class='btn' type='Button' value='Last' onclick='javascript:last()' />"+
                        // "<input id='next' class='btn' type='button' value='Next' onclick='javascript:next()'/>"+"</div>");
                        return c;
                    }
                    var response = $.parseJSON(data);
                    var c=createTable(response);
                    $(this).html(c.join(""));
                })
            },
            filter:function( params ) {
                var options = $.extend({}, settings, params);
                $(this).keyup(function () {
                    var rows = $("#dataRows").find("tr").hide();
                    if (this.value.length) {
                        var data = this.value.split(" ");
                        $.each(data, function (i, v) {
                            rows.filter(":contains('" + v + "')").show();
                        });
                    } else rows.show();
                });
            },
            sort( params ) {
                var options = $.extend({}, settings, params);
                $('table.sortable th.sortableColumn').click(function(){
                    var table = $(this).parents('table').eq(0);
                    var rows = table.find('#dataRows').toArray().sort(compareParams($(this).index()));
                    this.asc = !this.asc;
                    if (!this.asc){rows = rows.reverse()}
                    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
                });
                function compareParams(index) {
                    return function(a, b) {
                        var valA = getCellValue(a, index), valB = getCellValue(b, index);
                        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
                    }
                }
                function getCellValue(row, index){ return $(row).children('td').eq(index).html() }

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

