var symbol = $('.ticker-container').attr("data-symbol");
var user_id = $('.ticker-container').attr("data-user-id");
var company_id = $('.ticker-container').attr("data-company-id");
var trader_id = $('.ticker-container').attr("data-trader-id");
var getWatchlistUrl = $('.ticker-container').attr("data-watchlist-url");
var verifyWatchlistUrl = $('.ticker-container').attr("data-watchlist-verify-url");
var toggleWatchlistUrl = $('.ticker-container').attr("data-watchlist-toggle-url");
var getSubWatchlist = $('.ticker-container').attr("data-sub-watchlist-url");
var bool = $('.feed-container').attr("data-show-chart");
var getStocksInfoUrl = $('.ticker-container').attr("data-stocks-info-url");
var getStockUrl = $('.ticker-container').attr("data-stock-toggle-url");
var getOptionsUrl = $('.ticker-container').attr("data-options-toggle-url");
var i = 0;

$(document).ready(function () {

    $('.modal-watch-list').hide();
    $('.js-wl-list').hide();
    $('.ticker-container').each(function () {
        buildWatchlist($(this));
    });

    $(".hidden-div").hide();
    customizeCompanyInfo();

    if (user_id !== null && user_id !== '') {
        customizeWatchlistButton();
    }

    $('.btn-price-change').on('click', function () {
        if ($(this).hasClass('watching')) {
            watchlistToggle(this);
        } else {
            $("#select-watch-list-group").modal();
        }
    });

    $('.btn-price-change').hover(
        function () {
            if ($(this).hasClass('watching')) {
                var template = $('#watchlist-button-unwatch-hover-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $(this).html(output);
            }
        },
        function () {
            if ($(this).hasClass('watching')) {
                var template = $('#watchlist-button-watch-hover-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $(this).html(output);
            }
        }
    );

    function customizeWatchlistButton()
    {
        var data = {company_id: company_id};
        $.post(verifyWatchlistUrl, data, function (response) {
            if (response.status == 'error') {
                var template = $('#watchlist-button-unwatch-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $('.btn-price-change').html(output);
                $('.btn-price-change').removeClass('watching');
                $('.btn-price-change').addClass('unwatching');
            } else if (response.status == 'success') {
                var template = $('#watchlist-button-watch-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $('.btn-price-change').html(output);
                $('.btn-price-change').removeClass('unwatching');
                $('.btn-price-change').addClass('watching');
            }
        }, 'json');
    }

    function customizeCompanyInfo()
    {
        var change = $(".ticker-header .ticker-price .pricing").attr("data-stock-change");
        if (change < 0) {
            $('.change-image').addClass('negative');
            $('.change').addClass('negative');
            $('.btn-price-change').addClass('negative');
        } else {
            $('.change-image').addClass('positive');
            $('.change').addClass('positive');
            $('.btn-price-change').addClass('positive');
        }
    }

    $(".zoom").on("click", function (e) {
        $('li.zoom').each(function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
        });

        $(this).addClass('selected');

        var chart = Highcharts.charts[0];
        var zoom = $(this).text();

        chart.showLoading('Loading data from server...');

        if (zoom == '1d') { // 1 DAY
            var url = 'https://ql.stocktwits.com/intraday?symbol=' + symbol + '';
        } else if (zoom == '1w') { // 1 WEEK
            var url = 'https://ql.stocktwits.com/chart?zoom=1w&symbol=' + symbol + '';
        } else if (zoom == '1m') { // 1 MONTH
            var url = 'https://ql.stocktwits.com/chart?symbol=' + symbol + '&zoom=1m';
        } else if (zoom == '3m') { // 3 MONTHS
            var url = 'https://ql.stocktwits.com/chart?symbol=' + symbol + '&zoom=3m';
        } else if (zoom == '6m') { // 6 MONTHS
            var url = 'https://ql.stocktwits.com/chart?symbol=' + symbol + '&zoom=6m';
        } else if (zoom == '1y') { // 1 YEAR
            var url = 'https://ql.stocktwits.com/chart?symbol=' + symbol + '&zoom=1y';
        } else if (zoom == 'All') { // ALL
            var url = 'https://ql.stocktwits.com/chart?symbol=' + symbol + '&zoom=All';
        }

        $.getJSON(url, function (data) {
            // split the data set into ohlc and volume
            var ohlc = [],
                    volume = [],
                    dataLength = data.length,
                    // set the allowed units for data grouping
                    groupingUnits = [
                        [
                            'day',
                            [1]
                        ],
                        [
                            'week', // unit name
                            [1]     // allowed multiples
                        ],
                        [
                            'month',
                            [1, 2, 3, 4, 6]
                        ]
                    ];

            for (var key in data) {

                if (data[key].Open == 0) {
                    continue;
                }

                if (zoom == '1d') {
                    var date = new Date(data[key].EndDate + ' ' + data[key].EndTime).getTime();
                } else if (zoom == '1w') {
                    if (data[key].StartDate) {
                        var date = new Date(data[key].StartDate + ' ' + data[key].StartTime).getTime();
                    } else {
                        continue;
                    }
                } else {
                    var date = new Date(data[key].Date).getTime();
                }

                ohlc.push([
                    date, // the date
                    data[key].Open, // open
                    data[key].High, // high
                    data[key].Low, // low
                    (zoom == '1d' || zoom == '1w') ? data[key].Close : data[key].Last, // close
                ]);
                volume.push([
                    date, // the date
                    data[key].Volume, // the volume
                ]);
            }
            chart.series[0].setData(ohlc);
            chart.series[1].setData(volume);
            // chart.series[2].setData(ohlc);
            chart.hideLoading();
        });
    });

    $('.switch').click(function () {
        var currentLanguage = $(this).data('current-language');
        if (currentLanguage == 'JMD') {
            $('#alert-danger-options').fadeIn(1000).delay(2000).fadeOut(2000)
                .find('.alert-msg').text('Options are not available for JMD stocks');
        } else {
            if ($('.option_stock_switch').is(':checked') && i == 0) {
                console.log(getOptionsUrl);
                location.href = getOptionsUrl;
            } else {
                console.log(getStockUrl);
                location.href = getStockUrl;
            }
        }
    });

    $('.create_watch_list').click(function () {
        createNewWatchList = $('.watchlist_list').data('create-new-watchlist');
        var data = $('.watch_list_name').val();
        $.post(createNewWatchList, {data: data}, function (response) {
            var item = {
                id: response.watchlist.id,
                name: response.watchlist.name,
                url: response.watchlist.url
            };

            $('.watch_list_name').val('');
            var template = $('#watchlist-template-new').html();
            var output = Mustache.render(template, item);

            $('.watchlist_list').append(output);

        }, "json");
    });

    popoverAllWatchlist();
});

$('.add_new_group_checkbox').click(function () {
    if ($(this).is(':checked')) {
        $('.modal-watch-list').show();
    } else {
        $('.modal-watch-list').hide();
    }
});

$('.button_save_group').click(function () {
    if ($('.add_new_group_checkbox').is(':checked')) {
        createNewWatchList = $('.add_in_watch_list').data('create-new-watchlist');

        var data = $('.modal-watch-list').val();

        $.post(createNewWatchList, {data: data}, function (response) {
            $('.watch_list_name').val('');
            var button = $('.btn-price-change');
            var selected_id = response.watchlist.id;
            watchlistToggle(button, selected_id);
            $("#select-watch-list-group").modal('hide');

        }, "json");
    } else {
        var button = $('.btn-price-change');
        var selected_id = $('select[name=watch_list] :selected').val();
        watchlistToggle(button, selected_id);
        $("#select-watch-list-group").modal('hide');
    }
});

function watchlistToggle(item, group_id) {
    if ($(item).hasClass('watching')) {
        var data = {
            user_id: user_id,
            symbol: symbol,
            company_id: company_id,
            group_id: group_id,
            type: 'delete'
        };
        $.post(toggleWatchlistUrl, data, function (response) {
            if (response.status == 'success') {
                $('#alert-success-watchlist').fadeIn(1000).delay(2000).fadeOut(2000)
                    .find('.alert-msg').text('Watchlist Removed  Successfully');
                $(item).removeClass('watching');
                $(item).addClass('unwatching');
                var template = $('#watchlist-button-unwatch-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $('.btn-price-change').html(output);
            }
        }, 'json');
    } else {
        $(".hidden-div").hide();
        var data = {
            user_id: user_id,
            symbol: symbol,
            company_id: company_id,
            group_id: group_id,
            type: 'update'
        };
        $.post(toggleWatchlistUrl, data, function (response) {
            if (response.status == 'success') {
                $('#alert-success-watchlist').fadeIn(1000).delay(2000).fadeOut(2000)
                        .find('.alert-msg').text('Watchlist Created Successfully');
                $(item).removeClass('unwatching');
                $(item).addClass('watching');
                var template = $('#watchlist-button-watch-template').html();
                var output = Mustache.render(template, {symbol: symbol});
                $('.btn-price-change').html(output);
            }
        }, 'json');
    }
    updateWatchlist(data, group_id);

}

function updateWatchlist(data, group_id) {
    if (data.type == 'delete') {
        $('li.wl-item[data-symbol="' + data.symbol + '"]').remove();
        if ($('ul.group-' + group_id + 'li').length == 0) {
            $('.js-wl-list' + group_id).hide();
            $("#box-watchlist-content" + group_id).show();
        }
    } else if (data.type == 'update') {
        if ($('.js-wl-list' + group_id).is(":hidden")) {
            $('.js-wl-list' + group_id).show();
            $("#box-watchlist-content" + group_id).hide();
        }
        var symbols = [];
        symbols.push(symbol);
        _setStocksInfo(symbols, group_id, $('.ticker-container').first());
    }
}

function _setStocksInfo(data, group_id, item) {
    var getStocksInfoUrl = item.attr("data-stocks-info-url");

    $.post(getStocksInfoUrl, {data: data}, function (response) {
        var symbols = response.response;
        for (key in symbols) {
            build(symbols[key], group_id);
        }
        $('.wl-list' + group_id).show();
        $('.js-wl-list-' + group_id).show();
        $("#box-watchlist-content" + group_id).hide();
    }, "json");
}


function buildWatchlist(item) {
    getWatchlistAllUrl = item.attr("data-watchlist-group-url");

    $.get(getWatchlistAllUrl, function (response) {
        if (response.watchlist) {
            if (response.watchlist.length >= 1) {
                var symbols = [];
                var group_id = 0;
                $(response.watchlist).each(function () {
                    symbols.push(this.symbol);
                    group_id = this.group_id;
                });
                $('.js-wl-list' + group_id).show();
                $("#box-watchlist-content" + group_id).hide();

                _setStocksInfo(symbols, group_id, item);
            }
        }
    }, "json");
}

function _setYahooFinanceInfo(data) {
    $.post(yahooQueryUrl, {data: data}, function (response) {
        $.getJSON(response.response, function (result) {
            if (result.query.count == 1) {
                build(result.query.results.quote);
            } else if (result.query.count > 1) {
                var data = result.query.results.quote;
                for (var key in data) {
                    build(data[key]);
                }
            }
        });
    }, "json");
}

function build(symbols, group_id) {
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

    $('.group-' + group_id).prepend(output);
}

function buildWatchlist(item) {
    getWatchlistAllUrl = item.attr("data-watchlist-group-url");
    $.get(getWatchlistAllUrl, function (response) {
        if (response.watchlist && response.watchlist.length >= 1) {
            var symbols = [];
            var group_id = 0;
            $(response.watchlist).each(function () {
                symbols.push(this.symbol);
                group_id = this.group_id;
            });

            _setStocksInfo(symbols, group_id, item);
        }
    }, "json");
}

function checkIfCompanyIsPositiveOrNegative(change) {
    return (change >= 0) ? 'positive' : 'negative';
}

function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var day = days[a.getDay()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = day + ', ' + month + ' ' + date + ', ' + year;
    return time;
}

function  popoverAllWatchlist() {
    getAllWatchlistUrl = $('.watchlist_div').data("watchlist-page-url");
    var $elements = $('.list-popover');
    var i = 1;
    $elements.each(function () {
        var $element = $(this);
        var button_class = 'btn-primary';
        $element.popover({
            html: true,
            trigger: "manual",
            placement: 'top',
            container: $('body'),
            content: '<a role="button" href="' + getAllWatchlistUrl + '" tabindex="-1" class="btn ' + button_class + ' follow_button ">All Watch list</a>'
        }).on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 300);
        });
    });
}

function countChar(val) {
    var len = val.value.length;
}

function checkIfCompanyIsPositiveOrNegative(change) {
    return (change >= 0) ? 'positive' : 'negative';
}
