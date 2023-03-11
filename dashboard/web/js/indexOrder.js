$(document).on('change', '[id$=-count]', function (item) {

    calculateSupTotals($('.item'));

});
$(document).on('change', '[id$=-amount]', function (item) {
    calculateSupTotals($('.item'));

});
$(document).on('change', 'input[type=radio][name$="[price_number]"]', function (item) {
    calculateSupTotals($('.item'));
});
function productChange(This) {
    var box_product_title = $('#select2-orderproduct-product_id-container').attr('title');
    $('#orderproduct-title').val(box_product_title);
    $.post('/order/price-list?id=' + $(This).val() + '&key=' + $(This).attr('id'), function (data) {
        var id = $(This).attr('id');
        var amount = id.replace('product_id', 'amount');
        $('#' + amount).val('');
        id = id.replace('product_id', 'price_number');
        $('#' + id).html(data);
    });
    $.post('/product/get-detials?id=' + $(This).val() , function (data) {
        if (data) {
            result =  JSON.parse(data);
            $('#orderproduct-count_type_name').val(result['count_type_title']);
        }
    });

}
function calculateSupTotals(item,isAmountChanged = false) {
    debugger;
    var mainBox = $(item);
    var product_id_item = $('select[id$=product_id]');
    var product_id = mainBox.find(product_id_item).val();
    var store_id =  $('[id$="orderproduct-store_id"]').val();
    var product_cost_el = $('[id$=-amount]');
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
                    if(price_selected && !isAmountChanged )
                    {
                        mainBox.find(product_cost_el).val(result['price_'+price_selected.val()]);
                    }
                    mainBox.find($('.inventory_count')).html(result['inventory_count']);
                }
                var product_count_el = $('[id$=-count]');
                var product_total_cost_final_el = $('[id$=-total_product_amount]');

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
        mainBox.find($('.inventory_count')).html(0);
        mainBox.find(product_cost_el).val(0);
    }
}

