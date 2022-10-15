

$(document).on('change', '[id$=price]', function (item) {
    debugger;
    var price  =  item.target.value;
    $('#product-price_1').val(parseFloat(parseFloat(price)+(parseFloat(price_profit_rate_1)*parseFloat(price))).toFixed(3));
    $('#product-price_2').val(parseFloat(parseFloat(price)+(parseFloat(price_profit_rate_2)*parseFloat(price))).toFixed(3));
    $('#product-price_3').val(parseFloat(parseFloat(price)+(parseFloat(price_profit_rate_3)*parseFloat(price))).toFixed(3));
    $('#product-price_4').val(parseFloat(parseFloat(price)+(parseFloat(price_profit_rate_4)*parseFloat(price))).toFixed(3));
});

$(document).on('change', '[id$=price_pf_vat]', function (item) {
   getTotalProductPrice();
});
$(document).on('change', '#product-vat', function (item) {
    getTotalProductPrice();
});
$(document).on('change', '[id$=price_discount_percent]', function (item) {
    getTotalProductPrice();
});
$(document).on('change', '[id$=price_discount_amount]', function (item) {
    getTotalProductPrice();
});

function getTotalProductPrice(){

    debugger;
    var total =$('#product-price_pf_vat').val();
    if(total){


        var price_discount_percent =$('#product-price_discount_percent').val();
        if(price_discount_percent){
            total = total * (1-(price_discount_percent/100));
        }
        var price_discount_amount =$('#product-price_discount_amount').val();
        if(price_discount_amount){
            total = total -price_discount_amount;
        }
        var vat =$('#product-vat').val();
        if(vat){
            total = total * (1+(vat/100));
        }


    }

    total = parseFloat(total).toFixed(3);
    $('#product-price').val(total).trigger("change")
}