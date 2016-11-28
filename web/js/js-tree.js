(function( $ ) {
    var settings = {
        'dataURL': '/category/ajax'
    };
    function getURL(obj) {
        return "<i class='edit' id="+ obj.id + "/edit>" + "<span class='glyphicon glyphicon-pencil'></span></i>";
    }
    function createTree(data) {
        var c=[];
        for(var prop in data)
            if(data.hasOwnProperty(prop))
            {
                var subUL=[];
                if((Array.isArray(data[prop].children))&&((data[prop].children).length!==0)){
                    subUL+=("<ul class='list-group submenu' style='display:none' >");
                    subUL+= createTree(data[prop].children);
                    subUL+=("</ul>");
                    c += ("<li class='list-group-item expanded'>");
                    c+=("<a class='on'><span class='glyphicon glyphicon-folder-close'></span></a>"+data[prop].name);
                    c+=(getURL(data[prop]));
                    c += (subUL);
                    c += ("</li>");
                }else{
                    c+=("<li class='list-group-item'>"+data[prop].name);
                    c+=(getURL(data[prop]));
                    c+=("</li>");
                }

            }
        return c;
    }
    var methods = {
        init : function( params ) {
            var options = $.extend({}, settings, params);
            var buffDOM=this;
            $.getJSON(options.dataURL,function (data) {
                var response = $.parseJSON(data);
                console.log(response);
                var c = createTree(response);
                $(buffDOM).append("<ul class='list-group'id='nav'>"+c+"</ul>");
            });
        },
        update : function( params ) {
            var options = $.extend({}, settings, params);

        },
    };
    $.fn.jsTree= function( method) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method named' +  method + 'does not exist for jquery.ajaxGrid');
        }
    };
})(jQuery);