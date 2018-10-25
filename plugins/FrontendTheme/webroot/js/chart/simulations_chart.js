var getDeleteUrl = $('.table-vertical-middle').attr("data-delete-url");

function chart(id) {
    var symbol = $('.simulation-ajax-' + id).attr("data-stock");
    var data_url = $('.simulation-ajax-' + id).attr("data-sim-url");
    var compony_id = $('.simulation-ajax-' + id).attr("data-compony-id");
    var price = $('.simulation-ajax-' + id).attr("data-price");
    var created_at = $('.simulation-ajax-' + id).attr("data-time");
    var quantity = $('.simulation-ajax-' + id).attr("data-quantity");
    var gainLoss = $('.simulation-ajax-' + id).attr("data-gain-Loss");
    var total = $('.simulation-ajax-' + id).attr("data-total");
    var fees = $('.simulation-ajax-' + id).attr("data-fees");
    var broker = $('.simulation-ajax-' + id).attr("data-broker");

    $.ajax({
        url: data_url,
        type: "get",
        data:
                {
                    id: id,
                    symbol: symbol,
                    compony_id: compony_id,
                    date: created_at,
                    price: price,
                    quantity: quantity,
                    gainLoss: gainLoss,
                    total: total,
                    fees: fees,
                    broker: broker
                },
        success: function (data) {
            $('.sim_chart-' + id).html(data);
        }
    });

}

$('.checkAll').on('click', function () {
    $(this).closest('table').find('tbody :checkbox')
            .prop('checked', this.checked)
            .closest('tr').toggleClass('selected', this.checked);

    deleteButton(getCheckedCount());
});

$('tbody :checkbox').on('click', function () {
    $(this).closest('tr').toggleClass('selected', this.checked); //Classe de seleção na row

    $(this).closest('table').find('.checkAll').prop('checked',
            ($(this).closest('table').find('tbody :checkbox:checked').length == $(this).closest('table').find('tbody :checkbox').length));
    deleteButton(getCheckedCount());
});

function deleteButton(count) {
    if (count == 0) {
        $('.delete-simulation-button').addClass("disabled").css('cursor', 'not-allowed');
        $('.checked_count').text('');
    } else {
        $('.delete-simulation-button').removeClass('disabled').css('cursor', '');
        $('.checked_count').text(count);
    }
}

function getCheckedCount() {
    var count = $('.checkbox_analysis:checked').length;
    return count;
}

function getCheckedCheckBoxValue() {
    var chkArray = [];

    $(".checkbox_analysis:checked").each(function () {
        chkArray.push($(this).val());
    });
    return chkArray;
}

function deleteSimulationRow() {
    var data = getCheckedCheckBoxValue();
    $.ajax({
        type: "get",
        url: getDeleteUrl,
        data: {
            data: data
        },
        success: function (response) {
            $.each(response.data, function (index, value) {
                $('.simulation-ajax-' + value).remove();
                $('.simulation-' + value).remove();
            });
        },
    });
}