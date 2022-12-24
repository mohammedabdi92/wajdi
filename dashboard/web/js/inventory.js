console.log('sadsadfasdljkfhkjsadhfkjsahdkjahskd');
var total_without_vat = 0;
$(document).on('change', '[id$=product_cost]', function (item) {
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
        calculateTotTotals(element);
    });
    calculateTotals();

});
$(document).on('change', '[id$=product_total_cost]', function (item) {

    $('.item').each(function (index, element) {
        calculateTotTotals(element);
        calculateSupTotals(element);

    });
    calculateTotals();

});
$(document).on('change', '[id$=count]', function (item) {
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
        calculateTotTotals(element);
    });
    calculateTotals();

    var supdis = 0;
    $('[id$=-count]').each(function (index, element) {
        var svalue =$(element).val();
        if(svalue)
        {
            supdis += parseFloat(svalue);
        }
    });
    $('#inventoryorder-total_count').val(supdis);

});
$(document).on('change', '[id$=tax_percentage]', function (item) {
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
        calculateTotTotals(element);
    });
    calculateTotals();

});

$(document).on('change', '[id$=tax]', function (item) {
    var total = getTotalWithoutVat();
    var tax = parseFloat($(item.target).val());
    var tax_percentage = (tax / total) * 100;
    $('[id$=tax_percentage]').val(tax_percentage);
    $("[id$=tax_percentage]").trigger("change");

});
$(document).on('change', '[id$=discount_percentage]', function (item) {
    $('.item').each(function (index, element) {
        calculateSupTotals(element);
        calculateTotTotals(element);
        calculateTotals();
    });
});
$(document).on('change', '[id$=discount]', function (item) {
    var total = getTotalWithoutVat();
    var discount = parseFloat($(item.target).val());
    var discount_percentage = (discount / total) * 100;
    $('[id$=discount_percentage]').val(discount_percentage);
    $("[id$=discount_percentage]").trigger("change");

});
$(document).on('change', '[id$=debt]', function (item) {
    calculateTotals();
});
$(document).on('change', '[id$=repayment]', function (item) {
    calculateTotals();
});
function calculateTotals() {
    var total = getTotalWithoutVat();

    var discount_percentage = $('[id$=discount_percentage]').val();
    if (discount_percentage) {
        var discount = (total * (parseFloat(discount_percentage) / 100));
        discount = parseFloat(discount.toFixed(3));
        total =  total-discount;
        $('[id$=discount]').val(discount);
    }
    var tax_percentage = $('[id$=tax_percentage]').val();
    if (tax_percentage) {
        var tax = (total * (parseFloat(tax_percentage) / 100));
        tax = parseFloat(tax.toFixed(3));
        total = tax + total;
        $('[id$=tax]').val(tax);
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

    $('[id$=inventoryorder-total_cost]').val(total);

}

function getTotalWithoutVat() {
    var total = 0;
    $('[id$=product_total_cost]').each(function (index, element) {
        var elementCost = parseFloat(element.value);
        if (elementCost) {
            elementCost =parseFloat(elementCost.toFixed(3));
            total = parseFloat(total) + elementCost;
        }
    });
    return total
}

function calculateSupTotals(item) {
    var mainBox = $(item);
    var product_total_cost = $("[id$=product_total_cost]");
    var product_total_cost_final = $("[id$=product_total_cost_final]");
    var productCostItem = $("[id$=product_cost]");
    var productCost = mainBox.find(productCostItem).val();
    var countItem = $("[id$=count]");
    var count = mainBox.find(countItem).val();
    if (count && productCost) {
        var suptotal = parseFloat(count) * parseFloat(productCost);
        mainBox.find(product_total_cost).val(suptotal);
        var discount_percentage = $('[id$=discount_percentage]').val();
        if (discount_percentage) {
            var discount = (suptotal * (parseFloat(discount_percentage) / 100));
            discount = parseFloat(discount.toFixed(3));

            suptotal =  suptotal-discount;
        }
        var tax_percentage = $('[id$=tax_percentage]').val();
        if (tax_percentage) {
            var tax = (suptotal * (parseFloat(tax_percentage) / 100));
            tax = parseFloat(tax.toFixed(3));

            suptotal = tax + suptotal;
        }
        mainBox.find(product_total_cost_final).val(suptotal);
    }
}

function calculateTotTotals(item) {
    var mainBox = $(item);
    var product_total_cost = $("[id$=product_total_cost]");
    var productCostItem = $("[id$=product_cost]");
    var productCostItemfinal = $("[id$=product_cost_final]");
    var productCost = mainBox.find(product_total_cost).val();
    var countItem = $("[id$=count]");
    var count = mainBox.find(countItem).val();
    if (count && productCost) {
        var suptotal = parseFloat(productCost) / parseFloat(count);
        mainBox.find(productCostItem).val(suptotal);
        var discount_percentage = $('[id$=discount_percentage]').val();
        if (discount_percentage) {
            var discount = (suptotal * (parseFloat(discount_percentage) / 100));
            discount = parseFloat(discount.toFixed(3));

            suptotal =  suptotal-discount;
        }
        var tax_percentage = $('[id$=tax_percentage]').val();
        if (tax_percentage) {
            var tax = (suptotal * (parseFloat(tax_percentage) / 100));
            tax = parseFloat(tax.toFixed(3));
            suptotal = tax + suptotal;
        }
        mainBox.find(productCostItemfinal).val(suptotal);
    }

}

$(document).on('change', '[id$=product_id]', function (item) {
    $('.item').each(function (index, element) {
        getProductDetails(element);
    });

});
function getProductDetails(item) {
    var mainBox = $(item);
    var product_id_item = $("select[id$=product_id]");
    var product_id = mainBox.find(product_id_item).val();
    $.post( "/product/get-details?id="+product_id, function( data ) {
        data = jQuery.parseJSON( data );
        if(data)
        {
            mainBox.find( $('.last_price')).html(data.last_price) ;
            mainBox.find(  $('.min_price')).html(data.min_price) ;
            mainBox.find(  $('.order_product_price')).html(data.product_price);

        }
    });
}