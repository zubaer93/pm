$(document).ready(function () {
    companyPrice = 0;
    percent = 0;
    fee = 0;
    limit = 0;
    exchange_fee = 0;
    trade_fee = 0;
    Limit();
    Company();
    Profit();
    InvestmentGlance();

    var getBrokerListUrl = $('.potfolio_data').attr("data-broker-url");

    function InvestmentCalculate() {
        var count = $('.quantity_to_buy').val();

        Price = companyPrice;
        if (limit) {
            Price = limitPrice;
        }
        if (percent) {
            fee = (parseFloat(Price) * parseFloat(fee)) * count / 100;
        }

        var total_fee = (parseFloat(exchange_fee) + parseFloat(trade_fee) + parseFloat(fee));
        var price = (parseFloat(Price) * count) + parseFloat(total_fee);
        $('.total').val(price);
        $('.company-price').val(Price);
        $('.total-fee').val(total_fee);

    }

    function getBrokerInfo() {
        var broker = $(".broker option:selected").val();
        var market = $(".select_market option:selected").val();
        var getBrokerFee = $('.potfolio_data').attr("data-broker-fee");
        if (typeof broker !== "undefined") {
            $.ajax({
                type: "GET",
                url: getBrokerFee,
                async: false,
                data: {
                    broker: broker,
                    market: market
                },
                success: function (response) {

                    percent = response.data.percent;
                    fee = response.data.fee;
                    trade_fee = response.data.trade_fee;
                    exchange_fee = response.data.exchange_fee;
                },
                error: function (e) {
                }
            });
        }
    }

    function InvestmentGlance() {
        var getCompanyPrice = $('.potfolio_data').attr("data-company-price");

        $('.select_market').change(function () {
            getBrokerList();
            getBrokerInfo();
            InvestmentCalculate();
        });

        $('.broker').change(function () {
            getBrokerInfo();
            InvestmentCalculate();
        });


        $('.company').change(function () {
            var $el = $(".company");
            var companies = [];
            $el.find('option:selected').each(function () {
                companies.push($(this).val());
            });

            $.ajax({
                type: "GET",
                url: getCompanyPrice,
                data: {
                    companies_id: companies
                },
                success: function (response) {
                    companyPrice = response.price;
                    getBrokerInfo();
                    InvestmentCalculate();
                },
                error: function (e) {
                    $('.total').val(0);
                }
            });


        });

        $('.quantity_to_buy').on("change paste keyup", function () {

            var $el = $(".company");
            var companies = [];
            $el.find('option:selected').each(function () {
                companies.push($(this).val());
            }).promise().done(function () {
                if (companies.length) {
                    getBrokerInfo();
                    stockInfo();
                    InvestmentCalculate();
                } else {
                    $('.total').val(0);
                }
            });
        });

    }

    function getBrokerList() {
        var selectMarket = $('.select_market').val();
        $.ajax({
            type: "GET",
            url: getBrokerListUrl,
            async: false,
            data: {
                market: selectMarket
            },
            success: function (response) {
                console.log(response);
                $('.broker').children('option').remove();
                var options = '';
                $.each(response.data, function (index, value) {
                    $('.broker')
                            .append($("<option></option>")
                                    .attr("value", index)
                                    .text(value));
                });
            },
            error: function (e) {
                $('.total').val(0);
            }
        });
    }
    function stockInfo() {
        var getCompanyPrice = $('.potfolio_data').attr("data-company-price");
        var $el = $(".company");
        var companies = [];
        $el.find('option:selected').each(function () {
            companies.push($(this).val());
        });

        $.ajax({
            type: "GET",
            url: getCompanyPrice,
            data: {
                companies_id: companies
            },
            success: function (response) {
                companyPrice = response.price;
            },
            error: function (e) {
                $('.total').val(0);
            }
        });
    }

    function Profit() {
        var getMarketInfoUrl = $('.potfolio_data').attr("data-market-info");
        var getCurrencyListUrl = $('.potfolio_data').attr("data-currency-list");
        var getCurrencyRateUrl = $('.potfolio_data').attr("data-currency-rate");

        $('.select_market').change(function () {
            var market_id = $(this).val();
        });

        $('.account_currency').change(function () {
            var currency = $(".account_currency option:selected").text();
            $.ajax({
                type: "GET",
                url: getCurrencyListUrl,
                async: false,
                data: {
                    currency: currency
                },
                success: function (response) {
                    $('.currency_pair').children('option').remove();
                    var options = '';
                    for (var i = 0; i < response.list.length; i++) {
                        $('.currency_pair')
                                .append($("<option></option>")
                                        .attr("value", response.list[i])
                                        .text(response.list[i]));
                    }
                    var trade_price = response.trade_price;
                    $('.current_price').val(response.current_price);
                    $('.trade_price').val(trade_price);
                    calculateProfit();

                },
                error: function (e) {
                    $('.profit').val(0);
                }
            });

        });
        $('.number_of_units,.trade_price').on("change paste keyup", function () {
            calculateProfit();

        });
        $("body").delegate('.stepper-btn-up,.stepper-btn-dwn', 'click', function () {
            calculateProfit();

        });

        $("body").delegate('.currency_pair', 'change', function () {
            var currency = $(".currency_pair option:selected").text();
            $.ajax({
                type: "GET",
                url: getCurrencyRateUrl,
                async: false,
                data: {
                    currency: currency
                },
                success: function (response) {
                    var trade_price = response.trade_price;
                    $('.current_price').val(response.current_price);
                    $('.trade_price').val(trade_price);
                    calculateProfit();
                },
                error: function (e) {
                    $('.profit').val(0);
                }
            });

        });

        function calculateProfit() {
            var number_of_units = $('.number_of_units').val();
            var trade_price = $('.trade_price').val();
            $('.profit').val(trade_price * number_of_units);

        }
    }

    function Company() {
        var market = $(".select_market option:selected").val();
        var getCompany = $('.potfolio_data').attr("data-company");

        var time = setTimeout(function () {
            if (selec2itme) {
                select2Ajax(market, '.js-company-data-ajax', getCompany, 'company');
//                select2Ajax(market, '.js-broker-data-ajax', getCompany, 'broker');
                clearTimeout(time);
            }
        }, 1000);


        $('.select_market').change(function () {
            var market = $(this).val();
            select2Ajax(market, '.js-company-data-ajax', getCompany, 'company');
//            select2Ajax(market, '.js-broker-data-ajax', getCompany, 'broker');

        });

    }
    function Limit() {
        $(".limit-price").keyup(function () {
            getBrokerInfo();
            limit = 1;
            limitPrice = $(this).val();
        });
        $('.order-type').change(function () {
            var type = $(this).val();
            if (type == 2) {
                $('.enter-price').attr("disabled", false).show();
                $('.limit-price').attr("disabled", false);
            } else {
                limit = 0;
                $('.enter-price').hide();
                $('.limit-price').attr("disabled", true);
            }
        });
        $('.select_market').change(function () {
            var market = $(this).val();
            if (market == 'JMD') {
                $(".action option[value='3']").hide();
            } else {
                $(".action option[value='3']").show();
            }

        });
        var market = $(".select_market option:selected").val();
        if (market == 'JMD') {
            $(".action option[value='3']").hide();
        } else {
            $(".action option[value='3']").show();
        }
        var type = $(".order-type option:selected").val();
        ;
        if (type == 2) {
            $('.enter-price').attr("disabled", false).show();
            $('.limit-price').attr("disabled", false);
        } else {
            $('.enter-price').hide();
            $('.limit-price').attr("disabled", true);
        }
        var limit_price_load = $('.limit-price').val();
        if (limit_price_load.length) {
            limit = 1;
            limitPrice = limit_price_load;
        }

    }
    /**
     * params compony and brocker
     * @param market
     * @param className
     */
    function select2Ajax(market, className, url, placeholder) {
        $(className).select2({
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        market: market,
                        search: params.term, // search term
                        page: params.page
                    };
                },

                processResults: function (data, params) {
                    params.page = params.page || 1;
                    if (placeholder === 'broker') {
                        test = data.allBrokers;
                    } else {
                        test = data.allCompony;
                    }
                    return {
                        results: convertToJson(test),
                        pagination: {
                            more: (params.page * 30) < data.length
                        }
                    };
                },
                cache: true
            },

            placeholder: 'Search for a ' + placeholder,
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.name;
            }

            var markup = repo.name;
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }

        function convertToJson(data) {
            $(className).find("option").remove();
            var array = [];
            $.each(data, function (index, value) {
                array.push({
                    id: index,
                    name: value
                });
            });

            return array;

        }
    }


});
