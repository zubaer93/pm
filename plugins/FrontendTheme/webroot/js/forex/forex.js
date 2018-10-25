var getTraderlistUrl = $('.fx-table').attr("data-trader-url");

function checkIfCompanyIsPositiveOrNegative(change) {
    return (change >= 0) ? 'positive' : 'negative';
}

$(document).ready(function () {
    buildTraderlist();
    function buildTraderlist() {
        $.ajax({
            type: "POST",
            url: getTraderlistUrl,
            success: function (response) {
                if (response.trader) {
                    if (response.trader.length > 0) {
                        var trader = response.trader;
                        for (index = 0; index < trader.length; ++index) {
                            build(trader[index]);
                        }
                    }
                }

                buildTraderlist();
            },
            error: function (e) {
                if (e.status == 401) {
                    var template = $('#validate-user-logged').html();
                    var output = Mustache.render(template);
                    $('.message_alert').html(output);
                } else {

                }

                buildTraderlist();
            }
        });

    }
});
function build(trader) {
    var percent = 0;
    var change = trader.exchange_rate - trader.high;
    if (trader.exchange_rate > 0) {
        percent = change * 100 / trader.exchange_rate;
    }
    var status = checkIfCompanyIsPositiveOrNegative(change);
    $('#trader-' + trader.id).find('.exchange_rate').text(ReplaceNumberWithCommas(parseFloat(trader.exchange_rate).toFixed(5)));
    $('#trader-' + trader.id).find('.high_exchange_rate').text(ReplaceNumberWithCommas(parseFloat(trader.high).toFixed(5)));
    $('#trader-' + trader.id).find('.low_exchange_rate').text(ReplaceNumberWithCommas(parseFloat(trader.low).toFixed(5)));
    var change_tooltip = change;
    if (change > 0 || (change * (-1) > 0)) {
        change = ReplaceNumberWithCommas(parseFloat(change).toFixed(5));
    } else {
        change = ReplaceNumberWithCommas(parseFloat(change).toFixed(5));
    }
    var item = {
        status: status,
        change_tooltip: change_tooltip,
        change: change,
        percent_change: parseFloat(percent).toFixed(2),
        to_currency_code: trader.to_currency_code
    };
    var template = $('#trader-template').html();
    var output = Mustache.render(template, item);
    $('#trader-' + trader.id).find('.metric-change').html('');
    $('#trader-' + trader.id).find('.metric-change').prepend(output);
}

function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var n = yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}
