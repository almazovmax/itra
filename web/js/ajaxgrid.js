/**
 * Created by 31.75 on 18.11.2016.
 */

(function( $ ) {
    var settings =  {
        'dataURL'          : '/product/ajax?sortbyfield=id&order=asc&filterbyfield=name&pattern=',
        'sortableColumns'  : '[“id”, “name”, “dateCreate”]',
        'filterableColumns': '[“name”, "dateCreate"]',
        'rowsPerPage'      : '20',
        'page'             : '1'
    };
    var methods = {
        init : function( params ) {
            function getURL(obj,mark) {
                switch (mark) {
                    case '1':
                        return "<a href=" + window.location.href + obj.id + "/edit>" + "<span class='glyphicon glyphicon-pencil'></span>" + "";
                        break;
                    default:
                        return "<a href=" + window.location.href + obj.id + ">" + obj.name + "";
                        break;
                }
            }
            var options = $.extend({}, settings, params);
            var buffDOM=this;
            $.getJSON(options.dataURL,function (data) {
                function createTable(response)
                {
                    var c = [];
                    c+=("<thead class='dataColumns'>");
                    for(var prop in response[0])
                    {
                        if(response[0].hasOwnProperty(prop))
                        {
                            if((options.filterableColumns.includes(prop))&&(options.sortableColumns.includes(prop))){
                                c+=("<th class='filterableColumn sortableColumn'"+"id='"+prop+"'>" + "<input class='filter' placeholder='Type to filter'>"+"<br/>"+prop+"<buttom style='float: right' class='sort btn btn-info btn-xs'><span class='glyphicon glyphicon-resize-vertical'></span></buttom>"+ "</th>");
                            }
                            else if(options.sortableColumns.includes(prop)){
                                c+=("<th class='sortableColumn'"+"id='"+prop+"'>" +prop+"<buttom style='float: right' class='sort btn btn-info btn-xs'><span class='glyphicon glyphicon-resize-vertical'></span></buttom>" + "</th>");
                            }else if (options.filterableColumns.includes(prop)){
                                c+=("<th  id='"+prop+"' class='filterableColumn'>" +"<input class='filter' placeholder='Type to filter'>"+"<br/>"+prop+"</th>");
                            }else
                                c+=("<th>" + prop + "</th>");
                        }
                    }
                    c+=("<th>Edit</th>");
                    c+=("</thead>");
                    c+=("<tbody id='dataRows'>");


                    for (var prop in response)
                    {
                        c+=("<tr>");
                        for(var objProp in response[[prop]])
                        {
                            if(response[prop].hasOwnProperty(objProp))
                                if(Array.isArray(response[prop][objProp])){
                                    var cellCode=[];
                                    for (var el in response[prop][objProp]) {
                                        cellCode+=(getURL(response[prop][objProp][el]));
                                        cellCode+=("<br/>");
                                    }
                                    c+=("<td>"+cellCode+"</td>");
                                }else if (objProp=="name"){
                                    c+=("<td>"+getURL(response[prop])+"</td>");
                                }else
                                    c+=("<td>"+response[prop][objProp]+"</td>");
                        }
                        c+=("<td>"+getURL(response[prop],"1")+"</td>");
                        c+=("</tr>");
                    }
                    c+=("</tbody>");
                    return c;
                }
                var response = $.parseJSON(data);
                var c=createTable(response);
                $(buffDOM).append("<table class='sortable table table-hover table-bordered table-responsive'>"+c+"</table>");
                $(buffDOM).append("<div id='paging'>"
                    +"<ul class='pagination'>"
                    +"<li id='prev'><a href='javascript:prev()'>Prev</a></li>"
                    +"<li class='active'>"
                    +"<span>"
                    +options.page
                    +"</span>"
                    +"</li>"
                    +"<li id='next' onclick=''><a href='javascript:next()'>Next</a></li>"
                    +"</ul>"
                    +"</div>");
            })
        },
        update : function( params ) {
            function getURL(obj,mark) {
                switch (mark) {
                    case '1':
                        return "<a href=" + window.location.href + obj.id + "/edit>" + "<span class='glyphicon glyphicon-pencil'></span>" + "";
                        break;
                    default:
                        return "<a href=" + window.location.href + obj.id + ">" + obj.name + "</a>";
                        break;
                }
            }
            var options = $.extend({}, settings, params);
            $.getJSON(options.dataURL,function (data) {
                $("#dataRows").remove();
                $("#paging").remove();
                function createTable(response)
                {
                    var c = [];
                    c+=("<tbody id='dataRows'>");
                    for (var prop in response)
                    {
                        c+=("<tr>");
                        for(var objProp in response[[prop]])
                        {
                            if(response[prop].hasOwnProperty(objProp))
                                if(Array.isArray(response[prop][objProp])){
                                    var cellCode=[];
                                    for (var el in response[prop][objProp]) {
                                        cellCode+=(getURL(response[prop][objProp][el]));
                                        cellCode+=("<br/>");
                                    }
                                    c+=("<td>"+cellCode+"</td>");
                                }else if (objProp=="name"){
                                    c+=("<td>"+getURL(response[prop])+"</td>");
                                }else
                                    c+=("<td>"+response[prop][objProp]+"</td>");
                        }
                        c+=("<td>"+getURL(response[prop],"1")+"</td>");
                        c+=("</tr>");
                    }
                    c+=("</tbody>");
                    return c;
                }
                var response = $.parseJSON(data);
                var c=createTable(response);
                $(c).insertAfter(".dataColumns");
                $("<div id='paging'>"
                    +"<ul class='pagination'>"
                    +"<li id='prev'><a href='javascript:prev()'>Prev</a></li>"
                    +"<li class='active'>"
                    +"<span>"
                    +options.page
                    +"</span>"
                    +"</li>"
                    +"<li id='next'><a href='javascript:next()'>Next</a></li>"
                    +"</ul>"
                    +"</div>").insertAfter('.sortable');
            })
        },
    };
    $.fn.ajaxGrid= function( method) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method named' +  method + 'does not exist for jquery.ajaxGrid');
        }
    };
})(jQuery);