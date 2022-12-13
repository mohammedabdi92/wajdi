

$(document).on('change', '[id$=count]', function (item) {
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
    });
    checkDiscount();
    calculateTotal();

});
function checkDiscount(){
    var totalDiscount =  $('#order-total_discount').val();
    var discounts = $('[id$=-discount]');
    if(totalDiscount)
    {
        discounts.each(function (index, element) {
            $(element).val('');
            $(element).attr('readonly', true);
        });
    }else {
        discounts.each(function (index, element) {
            $(element).attr('readonly', false);
        });
    }

    var supdis = 0;
    discounts.each(function (index, element) {
        var svalue =$(element).val();
        if(svalue)
        {
            supdis += parseFloat(svalue);
        }
    });
    if(supdis){
        $('#order-total_price_discount_product').val(supdis);
        $('#order-total_discount').val('').attr('readonly', true);
    }else {
        $('#order-total_discount').attr('readonly', false);
    }
}
$(document).on('change', '[id$=debt]', function (item) {
    calculateTotal();
});
$(document).on('change', '[id$=repayment]', function (item) {
    calculateTotal();
});
$(document).on('change', '[id$=paid]', function (item) {
    calculateTotal();
});
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
    var debt = $('[id$=debt]').val();
    if(debt)
    {
        total =  total-parseFloat(debt);
    }
    var repayment = $('[id$=repayment]').val();
    if(repayment)
    {
        total =  total+parseFloat(repayment);
    }

    $('[id=order-total_amount]').val(total);
    var paid = $('[id$=paid]').val();
    if(paid)
    {
        total =  parseFloat(paid)-total;
        $('[id$=remaining]').val(total);
    }else {
        $('[id$=remaining]').val(0);
    }

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
    var product_id_item = $("select[id$=product_id]");
    var product_id = mainBox.find(product_id_item).val();
    var store_id =  $("#order-store_id").val();
    var product_cost_el = $("[id$=-amount]");
    var price_selected  =  mainBox.find($("input[type='radio'][name$='[price_number]']:checked"));

    if(product_id && store_id)
    {
        $.ajax({
            url: '/product/get-detials?id=' + product_id+'&store_id='+store_id,
            type: 'GET',
            async: false,
            success: function  (result)  {
                if (result) {
                    result =  JSON.parse(result);
                    if(price_selected)
                    {
                        mainBox.find(product_cost_el).val(result['price_'+price_selected.val()]);
                    }
                    mainBox.find($(".inventory_count")).html(result['inventory_count']);
                }
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
        });
    }else {
        mainBox.find($(".inventory_count")).html(0);
        mainBox.find(product_cost_el).val(0);
    }




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


    $('.item').each(function (index, element) {
        calculateSupTotals(element);
    });
    calculateTotal();
});
$(document).on('click',".add-item", function (item) {
    var num =parseInt( $('#order-product_count').val()) + 1;
    $('#order-product_count').val(num);
});
$(document).on('click',".remove-item", function (item) {
    var num = parseInt( $('#order-product_count').val()) - 1;
    $('#order-product_count').val(num);
});

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("المادة : " + (index + 1))
    });
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {

    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("المادة : " + (index + 1))
    });
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
    });
    checkDiscount();
    calculateTotal();
});

