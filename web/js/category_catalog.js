
$(document).ready(function() {
    $('#li').click(function (e) {
        e.preventDefault();

        var myData = $(this).attr("data-id");
        $(".product-one").empty();

        jQuery.ajax({
            type: "POST",
            url: "/catalog",
            dataType:"text",
            data:myData,
            success:function(response){
                $(".product-one").append(response);
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError);
            }
        });
    });
});