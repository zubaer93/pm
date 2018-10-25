var getCancelUrl = $('.transaction-data').attr("data-cancel-url");


$("body").delegate('.cancel_transaction', 'click', function () {
    $('#confirm-cancel-transaction').modal('show');
    $('.cancle-transaction').attr('data-id', $(this).data('id'));
});
$(".modal").delegate('.cancle-transaction', 'click', function () {
    $('#confirm-cancel-transaction').modal('hide');
    var id = $(this).attr('data-id');
    $.ajax({
        type: "get",
        url: getCancelUrl,
        data: {
            'id': id
        },

        success: function (response) {
            $(".cancel-transaction-" + id + "").remove();
        },
    });
});
