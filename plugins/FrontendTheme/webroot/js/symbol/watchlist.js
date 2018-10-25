var getWatchlistUrl = $('.ticker-container').attr("data-watchlist-url");
var getWatchlistAllUrl = $('.ticker-container').attr("data-watchlist-all-url");
var getStocksInfoUrl = $('.ticker-container').attr("data-stocks-info-url");

function _setStocksInfo(data)
{
    $.post(getStocksInfoUrl, {data: data}, function (response) {
        var symbols = response.response;
        for (index = 0; index < symbols.length; ++index) {
            build(symbols[index]);
        }
    }, "json");
}

function build(symbols) {
    var percent = 0;
    var change = symbols.open - symbols.close;
    if (symbols.open > 0) {
        percent = change * 100 / symbols.open;
    }
    var status = checkIfCompanyIsPositiveOrNegative(change);
    var item = {
        symbol: symbols.symbol,
        status: status,
        bid: parseFloat(symbols.open).toFixed(2),
        change: parseFloat(change).toFixed(2),
        percent_change: parseFloat(percent).toFixed(2),
        name: symbols.company.name,
    };
    var template = $('#watchlist-template').html();
    var output = Mustache.render(template, item);
    $('ul.nano-content').prepend(output);
}

function checkIfCompanyIsPositiveOrNegative(change) {
    return (change >= 0) ? 'positive' : 'negative';
}

$(document).ready(function () {
    $.get(getWatchlistAllUrl, function (response) {
        if (response.watchlist) {
            if (response.watchlist.length >= 1) {
                $('.wl-list').show();
                $("#box-watchlist-content").hide();
                var symbols = [];
                $(response.watchlist).each(function () {
                    symbols.push(this.symbol);
                });

                _setStocksInfo(symbols);
            } else {
                $('.wl-list').hide();
                $("#box-watchlist-content").show();
            }
        } else {
            $('.wl-list').hide();
            $("#box-watchlist-content").show();
        }
    }, "json");
});