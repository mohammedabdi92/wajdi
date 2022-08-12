$(document).on('change', '[id=product_id]', function (item) {
    var product_id = $(this).val();
    var count = $('[id$=count]').val();
    setAmount(product_id,count);
});
$(document).on('change', '[id$=count]', function (item) {
    var product_id = $('[id=product_id]').val();
    var count = $(this).val();
    setAmount(product_id,count);
});
function setAmount(product_id,count){
    if(product_id){
        var order_id = $('#order_id').val();
        if(count)
        {
            $.post("/order/get-product-price?order_id="+order_id+"&product_id="+product_id+"&count="+count, function(data){
                $("[id$=amount]").val(data);
            });
        }
    }

}