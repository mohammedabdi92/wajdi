$(document).on('change', '[id=order_id]', function (item) {
    // $.pjax.reload({container:"#new_country"});
    
});

$(document).on('change', '[id$=count]', function (item) {
    var box_id =  getBoxId($(item.currentTarget).attr('id')) ;
    var mainBox =  $($('.item')[box_id]);
    var product_id = mainBox.find('[id^=product_id]').val();
    var count = $(this).val();
     setAmount(product_id,count,mainBox);
    
});
 function setAmount(product_id,count,mainBox){
    try {
    if(product_id){
        var order_id = $('#order_id').val();
        if(count)
        {
             $.post("/order/get-product-price?order_id="+order_id+"&product_id="+product_id+"&count="+count, function(data){
                mainBox.find($("[id$=amount]")).val(data);
                setTotals();
            });
        }
    }
    } catch(err) {
        console.log(err);
    }

}

function setTotals(){
    var totalAmount=0;
    var totalCount=0;

$("[id$='-count']").each(function() {
    totalCount += parseInt(this.value);
});
$("[id$='-amount']").each(function() {
    totalAmount += parseFloat(this.value);
});
$('#returnsgroup-total_count').val(totalCount);
$('#returnsgroup-total_amount').val(totalAmount);

}
$(".dynamicform_wrapper").on("afterInsert", function(e, wrapper) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("المادة : " + (index + 1))
    });
    
    $(wrapper).find("[id^='product_id']").removeAttr("disabled");
    $(wrapper).find("[id^='product_id']").html("");
    $("[id^='product_id']:first option").each(function(i,item) {           
            $(wrapper).find("[id^='product_id']").each(function(x,product)  {
            $(item).clone().appendTo(product);
            });
    });
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("المادة : " + (index + 1))
    });
});
function getBoxId(id){
    var product_changed_id_array = id.split('-');
    return  product_changed_id_array[1];
}