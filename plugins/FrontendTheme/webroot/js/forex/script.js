$(document).ready(function () {

    customizeTraderInfo();

});
function customizeTraderInfo()
{
    var change = $(".ticker-header .ticker-price .pricing").attr("data-stock-change");
    if (change < 0) {
        // $('.ticker-header').addClass('negative');
        $('.change-image').addClass('negative');
        $('.change').addClass('negative');
        $('.btn-price-change').addClass('negative');
    } else {
        // $('.ticker-header').addClass('positive');
        $('.change-image').addClass('positive');
        $('.change').addClass('positive');
        $('.btn-price-change').addClass('positive');
    }
}