

$(document).on('change', '[id$=price]', function (item) {
    var price  =  item.target.value;
    $('#product-price_1').val(parseFloat(price)+parseFloat(price_profit_rate_1*price));
    $('#product-price_2').val(parseFloat(price)+parseFloat(price_profit_rate_2*price));
    $('#product-price_3').val(parseFloat(price)+(price_profit_rate_3*price));
    $('#product-price_4').val(parseFloat(price)+(price_profit_rate_4*price));
});

