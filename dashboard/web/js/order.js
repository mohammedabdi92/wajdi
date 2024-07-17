

$(document).on('change', '[id$=-count]', function (item) {
    var box_id =  getBoxId($(item.currentTarget).attr('id')) ;
    calculateSupTotals($('.item')[box_id]);
    checkDiscount();
    calculateTotal();

});


$(document).on('change', '[id$=discount]', function (item) {
    checkDiscount();
    calculateTotal();

});
$(document).on('change', '[id$=-amount]', function (item) {
    var box_id =  getBoxId($(item.currentTarget).attr('id')) ;
    calculateSupTotals($('.item')[box_id],true);
    checkDiscount();
    calculateTotal();

});
function checkDiscount(){
    var totalDiscount =  $('[id$="order-total_discount"]').val();
    var discounts = $('[id$=-discount]');
    if(totalDiscount)
    {
        discounts.each(function (index, element) {
            $(element).val('');
            $(element).attr('readonly', true);
        });
        $('[id$="order-total_price_discount_product"]').val('');
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
        $('[id$="order-total_price_discount_product"]').val(supdis);
        $('[id$="order-total_discount"]').val('').attr('readonly', true);
    }else {
        $('[id$="order-total_discount"]').attr('readonly', false);
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
    var total_cost = 0;
    $('[id$=total_product_amount]').each(function (index, element) {
        var elementCost = parseFloat(element.value);
        if (elementCost) {
            elementCost = Math.round((elementCost + Number.EPSILON) * 100) / 100
            total = total + elementCost;
        }
    });
    $('[id$=-orignal_cost]').each(function (index, element) {
        var elementCost = parseFloat(element.value);
        if (elementCost) {
            elementCost = Math.round((elementCost + Number.EPSILON) * 100) / 100
            total_cost = total_cost + elementCost * $("#orderproduct-"+index+"-count").val() ;
        }
    });

    $('[id$=order-total_amount_without_discount]').val(parseFloat(total.toFixed(3)));

    var ordertotaldiscount = $("[id$=order-total_discount]").val();
    if(!ordertotaldiscount)
    {
        ordertotaldiscount = $("[id$=order-total_price_discount_product]").val();
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

    $('[id$=order-total_amount]').val(parseFloat(total.toFixed(3)));
    var profit = total - total_cost;
    if(profit < 0)
    {
        $('#order_status').html('انتبه الفاتورة خسرانة');
    }else{
        $('#order_status').html('');
    }
    $('#titleElement_all').attr('title',profit);
    $('#titleElement_all').attr('data-original-title',profit);
    $("[id$=-earn_the_bill]").val(profit);


    var paid = $('[id$=paid]').val();
    if(paid)
    {
        total =  parseFloat(paid)-total;
        $('[id$=remaining]').val(parseFloat(total.toFixed(3)));
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

    $('[id$="order-total_count"]').val(supdis.toFixed(3));

}
   

function calculateSupTotals(item,isAmountChanged = false) {
    var mainBox = $(item);
    var product_id_item = $("select[id$=product_id]");
    var product_id = mainBox.find(product_id_item).val();
    var store_id =  $('[id$="order-store_id"]').val();
    var product_cost_el = $("[id$=-amount]");
    var product_orignal_cost_el = $("[id$=-orignal_cost]");
    var price_selected  =  mainBox.find($("input[type='radio'][name$='[price_number]']:checked"));

    if(product_id )
    {
        $.ajax({
            url: '/product/get-detials?id=' + product_id+'&store_id='+store_id,
            type: 'GET',
            async: false,
            success: function  (result)  {
                if (result) {
                    result =  JSON.parse(result);
                    mainBox.find(product_orignal_cost_el).val(result['price']);
                    if(price_selected && !isAmountChanged )
                    {
                        mainBox.find(product_cost_el).val(result['price_'+price_selected.val()]);
                    }
                    mainBox.find($(".inventory_count")).html(result['inventory_count']);
                }
                var product_count_el = $("[id$=-count]");
                var product_total_cost_final_el = $("[id$=-total_product_amount]");

                var product_cost = + mainBox.find(product_cost_el).val();
                var product_count = + mainBox.find(product_count_el).val();
                var product_total_cost_final = 0;
                if(product_cost && product_count)
                {
                    product_total_cost_final = product_cost * product_count;
                }



                mainBox.find(product_total_cost_final_el).val(product_total_cost_final.toFixed(3));
            }
        });
    }else {
        mainBox.find($(".inventory_count")).html(0);
        mainBox.find(product_cost_el).val(0);
    }




}

function productChange(This) {
    let thisid = $(This).attr('id');
    var box_id =  getBoxId(thisid) ;
    let isarorder = thisid.includes("arorderproduct");
   let orderproduct = isarorder?"arorderproduct":"orderproduct"
   let order = isarorder?"ar-order":"order"

    var box_product_title = $("#select2-"+orderproduct+"-"+box_id+"-product_id-container").attr("title");
    $('#'+orderproduct+'-'+box_id+'-title').val(box_product_title);
    $.post("/"+order+"/price-list?id=" + $(This).val() + "&key=" + $(This).attr('id'), function (data) {
        var id = $(This).attr('id');
        var amount = id.replace('product_id', 'amount');
        $("#" + amount).val('');
        id = id.replace('product_id', 'price_number');
        $("#" + id).html(data);
    });
    $.post("/product/get-detials?id=" + $(This).val() , function (data) {
        if (data) {
            result =  JSON.parse(data);
            $('#orderproduct-'+box_id+'-orignal_cost').val(result.price);
            $('#titleElement_'+box_id).attr('title', result.price);
            $('#titleElement_'+box_id).attr('data-original-title', result.price); 
           
            $('#orderproduct-'+box_id+'-count_type_name').val(result['count_type_title']);
        }
    });

}
function getBoxId(id){
    var product_changed_id_array = id.split('-');
    return  product_changed_id_array[1];
}

$(document).on('change', 'input[type=radio][name$="[price_number]"]', function (item) {


    var node_id = $(item.currentTarget.parentNode.parentNode).attr('id');
    if(node_id == undefined)
    {
        node_id = $(item.currentTarget.parentNode.parentNode.parentNode).attr('id');
    }
    var box_id =  getBoxId(node_id) ;
    calculateSupTotals($('.item')[box_id]);

    calculateTotal();
});
$(document).on('change', '[id$="order-store_id"]', function (item) {
    $.pjax.reload({container:"#new_country"});

});
$(document).on('pjax:end', function(e) {
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
            calculateSupTotals(element,true);
        });
        checkDiscount();
        calculateTotal();
    });
});
$(document).on('click',".add-item", function (item) {
    var num =$(".item.panel.panel-default").length;
    $('[id$="order-product_count"]').val(num);
});
$(document).on('click',".remove-item", function (item) {
    var num = $(".item.panel.panel-default").length;
    $('[id$="order-product_count"]').val(num);
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
        calculateSupTotals(element,true);
    });
    checkDiscount();
    calculateTotal();
});

$(document).on('select2:open',"select[id$=-product_id]", function (item) {
    $(".select2-search__field")[0].focus()
});

function initializeTooltips() {
    // Initialize all tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Enable tooltip on click for mobile devices
    if (/Mobi|Android/i.test(navigator.userAgent)) {
        $('[data-toggle="tooltip"]').on('click', function() {
            $(this).tooltip('show');
        });
    }
}

$(document).on('pjax:success', function() {
    initializeTooltips();
});