$(document).ready(function() {
    var customerName = $(".customerName").val();
    $ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/getlist",
        type: "POST",
        data: {
            customer: customerName,
        },
        success: function (data) {
            console.log(data);
            $(".selectdatalist").html(data);
        },
    });
});