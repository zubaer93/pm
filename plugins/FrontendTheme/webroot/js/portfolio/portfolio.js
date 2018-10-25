$(document).ready(function () {
    companyPrice = 0;
    percent = 0;
    fee = 0;
    exchange_fee = 0;
    trade_fee = 0;
    Limit();
    Company();
    Profit();
    InvestmentGlance();

    function InvestmentCalculate() {
        var count = $('.quantity_to_buy').val();

        if (percent) {
            fee = (parseFloat(companyPrice) * parseFloat(fee)) / 100;
        }

        var total_fee = parseFloat(exchange_fee) + parseFloat(trade_fee) + parseFloat(fee);
        var price = (parseFloat(companyPrice) * count) + parseFloat(total_fee);
        $('.total').val(price);
        $('.company-price').val(companyPrice);
        $('.total-fee').val(total_fee);

    }

    function getBrokerInfo() {
        var broker = $(".broker option:selected").val();
        var market = $(".select_market option:selected").val();
        var getBrokerFee = $('.potfolio_data').attr("data-broker-fee");

        $.ajax({
            type: "GET",
            url: getBrokerFee,
            data: {
                broker: broker,
                market: market
            },
            success: function (response) {

                percent = response.data.percent;
                fee = response.data.fee;
                trade_fee = response.data.trade_fee;
                exchange_fee = response.data.exchange_fee;
                InvestmentCalculate();
            },
            error: function (e) {
                $('.total').val(0);
            }
        });
    }

    function InvestmentGlance() {

        var getCompanyWithMarketUrl = $('.potfolio_data').attr("data-company-info-market");

        var getCompanyPrice = $('.potfolio_data').attr("data-company-price");

        $('.select_market').change(function () {

            getBrokerInfo();
        });

        $('.broker').change(function () {

            getBrokerInfo();

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
            });

            if (companies.length) {
                InvestmentCalculate();
            } else {
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
            $('.glance').show();

            $.ajax({
                type: "GET",
                url: getMarketInfoUrl,
                data: {
                    market_id: market_id
                },
                success: function (response) {

                },
                error: function (e) {
                }
            });


        });

        $('.account_currency').change(function () {
            var currency = $(".account_currency option:selected").text();
            $.ajax({
                type: "GET",
                url: getCurrencyListUrl,
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
        var getCompany = $('.potfolio_data').attr("data-company");

        $('.select_market').change(function () {
            var market = $(this).val();


            // $.ajax({
            //     type: "GET",
            //     url: getCompany,
            //     data: {
            //         market: market
            //     },
            //     success: function (response) {
            //         $(".company").select2({
            //             data:response.allCompony
            //         })
            //     },
            //     error: function (e) {
            //
            //     }
            // });
        });
    }
    function Limit(){

        $('.order-type').change(function () {
            var type = $(this).val();
            if(type == 2){
                $('.enter-price').show();
            }else{
                $('.enter-price').hide();
            }
        });
        $('.select_market').change(function () {
            var market = $(this).val();
            if(market == 'JMD'){
                $(".action option[value='3']").hide();
            }else{
                $(".action option[value='3']").show();
            }

        });
        var market=$( ".select_market option:selected" ).val();
        if(market == 'JMD'){
            $(".action option[value='3']").hide();
        }else{
            $(".action option[value='3']").show();
        }
    }

});

