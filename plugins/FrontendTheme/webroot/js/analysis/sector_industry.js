var symbol = $('.ticker-container').attr("data-symbol");
var user_id = $('.ticker-container').attr("data-user-id");
var company_id = $('.ticker-container').attr("data-company-id");
var trader_id = $('.ticker-container').attr("data-trader-id");
var getWatchlistUrl = $('.ticker-container').attr("data-watchlist-url");
var getSectorModalUrl = $('.ticker-container').attr("data-sector-modal-url");
var getSectorUrl = $('.ticker-container').attr("data-sector-url");
var getIndustryUrl = $('.ticker-container').attr("data-industry-url");
var toggleWatchlistUrl = $('.ticker-container').attr("data-watchlist-toggle-url");
var i = 0;

function _setStocksInfoSector(data, class_name, createTopAndWorst)
{
    $.post(getStocksInfoUrl, {data: data}, function (response) {
        var symbols = response.response;
        $(class_name).html("");
        for (index = 0; index < symbols.length; ++index) {
            var percent = 0;
            var change = symbols[index].open - symbols[index].close;
            if (symbols[index].open > 0) {
                percent = (change * 100) / symbols[index].open;
            }

            var status = checkStatus(change);
            var item = {
                symbol: symbols[index].symbol,
                status: status,
                bid: parseFloat(symbols[index].open).toFixed(2),
                change: parseFloat(change).toFixed(2),
                percent_change: parseFloat(percent).toFixed(2),
                name: symbols[index].company.name,
            };
            var template = $('#watchlist-template').html();
            var output = Mustache.render(template, item);
            $(class_name).prepend(output);
        }
        if (createTopAndWorst) {
            _setTop(symbols);
            _setWorst(symbols);
        }
    }, "json");
}

function checkStatus(change)
{
    return (change >= 0) ? 'positive' : 'negative';
}

function _setTop(stocks) {
    for (index = 0; index < stocks.length; ++index) {
        var percent = 0;
        var change = stocks[index].open - stocks[index].close;
        if (stocks[index].open > 0) {
            percent = (change * 100) / stocks[index].open;
        }
        if (change < 0) {
            continue;
        }

        var status = checkStatus(change);
        var item = {
            symbol: stocks[index].symbol,
            status: status,
            bid: parseFloat(stocks[index].open).toFixed(2),
            change: parseFloat(change).toFixed(2),
            percent_change: parseFloat(percent).toFixed(2),
            name: stocks[index].company.name,
        };

        var template = $('#watchlist-template').html();
        var output = Mustache.render(template, item);
        $('ul.nano-top-content').prepend(output);
    }
}

function _setWorst(stocks) {
    for (index = 0; index < stocks.length; ++index) {

        var percent = 0;
        var change = stocks[index].open - stocks[index].close;
        if (stocks[index].open > 0) {
            percent = (change * 100) / stocks[index].open;
        }

        if (change >= 0) {
            continue;
        }

        var status = checkStatus(change);
        var item = {
            symbol: stocks[index].symbol,
            status: status,
            bid: parseFloat(stocks[index].open).toFixed(2),
            change: parseFloat(change).toFixed(2),
            percent_change: parseFloat(percent).toFixed(2),
            name: stocks[index].company.name,
        };
        var template = $('#watchlist-template').html();
        var output = Mustache.render(template, item);
        $('ul.nano-worst-content').prepend(output);
    }
}

$(document).ready(function () {
    buildIndustrylist();
    buildSectorlist();

    function buildSectorlist()
    {
        $.get(getSectorUrl, function (response) {
            if (response.sector) {
                if (response.sector.length >= 1) {
                    $('.sector_ul').show();
                    $("#box-watchlist-content-sector").hide();
                    var symbols = [];
                    $(response.sector).each(function () {
                        symbols.push(this.symbol);
                    });
                    var class_name = 'ul.nano-sector-content';
                    _setStocksInfoSector(symbols, class_name, true);
                } else {
                    $('.sector_ul').hide();
                    $("#box-watchlist-content-sector").show();
                }
            } else {
                $('.sector_ul').hide();
                $("#box-watchlist-content-sector").show();
            }
        }, "json");
    }
    function buildIndustrylist()
    {
        $.get(getIndustryUrl, function (response) {

            if (response.sector) {
                if (response.sector.length >= 1) {
                    $('.industry_ul').show();
                    $("#box-watchlist-content-industry").hide();
                    var symbols = [];
                    $(response.sector).each(function () {
                        symbols.push(this.symbol);
                    });
                    var class_name = 'ul.nano-industry-content';
                    _setStocksInfoSector(symbols, class_name, false);
                } else {
                    $('.industry_ul').hide();
                    $("#box-watchlist-content-industry").show();
                }
            } else {
                $('.industry_ul').hide();
                $("#box-watchlist-content-industry").show();
            }
        }, "json");
    }

    $('.sector_modal').click(function () {
        $.get(getSectorModalUrl, function (response) {
            $('.sector_modal_body').html(response);
        }, "html");

    });
});