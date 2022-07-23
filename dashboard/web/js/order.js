

$(document).on('change', '[id$=count]', function (item) {
    debugger;
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
    });
    checkDiscount();
    calculateTotal();

});
function checkDiscount(){
    var totalDiscount =  $('#order-total_discount').val();
    if(totalDiscount)
    {
        $('[id$=-discount]').each(function (index, element) {
            $(element).val('');
            $(element).attr('readonly', true);
        });
    }else {
        $('[id$=-discount]').each(function (index, element) {
            $(element).attr('readonly', false);
        });
    }

    var supdis = 0;
    $('[id$=-discount]').each(function (index, element) {
        var svalue =$(element).val();
        if(svalue)
        {
            supdis += parseFloat(svalue);
        }
    });
    if(supdis){
        $('#order-total_price_discount_product').val(supdis);
        $('#order-total_discount').val('');
        $('#order-total_discount').attr('readonly', true);
    }else {
        $('#order-total_discount').attr('readonly', false);
    }
}
function calculateTotal() {
    var total = 0;
    $('[id$=total_product_amount]').each(function (index, element) {
        var elementCost = parseFloat(element.value);
        if (elementCost) {
            elementCost = Math.round((elementCost + Number.EPSILON) * 100) / 100
            total = total + elementCost;
        }
    });
    $('[id=order-total_amount_without_discount]').val(total);

    var ordertotaldiscount = $("[id=order-total_discount]").val();
    if(!ordertotaldiscount)
    {
        ordertotaldiscount = $("[id=order-total_price_discount_product]").val();
    }
    if(ordertotaldiscount)
    {
        total=total-ordertotaldiscount;
    }
    $('[id=order-total_amount]').val(total);
    var supdis = 0;
    $('[id$=-count]').each(function (index, element) {
        var svalue =$(element).val();
        if(svalue)
        {
            supdis += parseFloat(svalue);
        }
    });
    $('#order-total_count').val(supdis);
}

function calculateSupTotals(item) {
    var mainBox = $(item);
    var product_cost_el = $("[id$=-amount]");
    var product_count_el = $("[id$=-count]");
    var product_total_cost_final_el = $("[id$=-total_product_amount]");

    var product_cost = mainBox.find(product_cost_el).val();
    var product_count = mainBox.find(product_count_el).val();
    var product_total_cost_final = 0;
    if(product_cost && product_count)
    {
        product_total_cost_final = product_cost * product_count;
    }
     mainBox.find(product_total_cost_final_el).val(product_total_cost_final);


}

function productChange(This) {
    $.post("/order/price-list?id=" + $(This).val() + "&key=" + $(This).attr('id'), function (data) {
        var id = $(This).attr('id');
        var amount = id.replace('product_id', 'amount');
        $("#" + amount).val('');
        id = id.replace('product_id', 'price_number');
        $("#" + id).html(data);
    });
}

$(document).on('change', 'input[type=radio][name$="[price_number]"]', function (item) {
    var itemName =  $(item.target).attr('name');
    itemName =itemName.toLowerCase();
    itemName =itemName.replaceAll('[', '-');
    itemName =itemName.replaceAll(']', '');
    var itemProduct = itemName.replace('price_number', 'product_id');
    var product_id =  $("#" + itemProduct).val();

    itemName =itemName.replace('price_number', 'amount');
   var price = products[product_id]['price_'+$(item.target).val()];
    $("#" + itemName).val(price);
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
    });
    calculateTotal();
});

