getdetails();

$(document).on('change', '[id$=order_id]', function (item) {
   
    getdetails();
});

$(document).on('change', '[id$=-inventory_order_id]', function (item) {
    var inventory_order_id = $('#damaged-inventory_order_id').val();
    var product_id = $('#product_id').val();
    
    $.get("/inventory-order-product/get-final-cost", {
        inventory_order_id: inventory_order_id,
        product_id: product_id
    })
    .done(function(data) {

           $("#damaged-supplyer_price").val(data.product_cost_final*$('#damaged-count').val());
           $("#damaged-supplyer_price").change();
           $.get("/inventory-order/get-details?order_id="+inventory_order_id, function(data){
            // Convert to milliseconds
            const date = new Date(data.created_at * 1000);

            // Format the date
            const formattedDate = date.toLocaleString(); 
            
            var data = "<span><b dir='ltr'>معلومات الطلب</b></span> <b>اسم المورد:</b> "+data.supplier.name+" <br> <b>رقم المورد:</b> "+data.supplier.phone_number+"  <br> <b>تاريخ انشاء الطلب:</b> "+formattedDate+" <br> <b>البائع :</b>"+data.created_by+"<br>";
            $("#order_inventory_details").html(data);
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        
        console.log('Error:', textStatus, errorThrown);
        // Logic to handle the failure scenario
        // For example, show an error message to the user
        alert('لا توجد هذه المادة في فاتورة المشتريات المدخلة');
    })
    
});
function getdetails(){
    var order_id = $('#order_id').val();
    if(order_id)
    {
       
        $.get("/order/get-details?order_id="+order_id, function(data){
            // Convert to milliseconds
            const date = new Date(data.created_at * 1000);

            // Format the date
            const formattedDate = date.toLocaleString(); 
            
            var data = "<span><b dir='ltr'>معلومات الطلب<br></b></span> <b>اسم المحل:</b> "+data.store_name+" <br>  <b>اسم العميل:</b> "+data.customer.name+" <br> <b>رقم العميل:</b> "+data.customer.phone_number+"  <br> <b>تاريخ انشاء الطلب:</b> "+formattedDate+" <br> <b>البائع :</b>"+data.created_by+"<br>";
            $("#order_details").html(data);
        });
    }else{
        $("#order_details").html('');
    }
}

$(document).on('change', '[id$=product_id]', function (item) {
    $('#damaged-count').val('');
    $('#damaged-cost_value').val('');
    $('#damaged-total_amount').val('');
    $('#damaged-amount').val('');
});

$(document).on('change', '[id$=damaged-count]', function (item) {
    var order_id = $('#order_id').val();
    var product_id = $('#product_id').val();
    var count = $('#damaged-count').val();
    var cost_value = $('#damaged-cost_value').val();
    if(count && product_id && order_id)
    {
         $.post("/order/get-product-price?order_id="+order_id+"&product_id="+product_id+"&count="+count, function(data){
            $('#damaged-amount').val(data);
            $('#damaged-total_amount').val(data-cost_value);
        });
    }
});
$(document).on('change', '[id$=cost_value]', function (item) {
    var amount = $('#damaged-amount').val();
    var cost_value = $('#damaged-cost_value').val();
    $('#damaged-total_amount').val(amount-cost_value);
    
});
$(document).on('change', '[id$=supplyer_price]', function (item) {
    setSupplyerCal();
    
});
$(document).on('change', '[id$=supplyer_pay_amount]', function (item) {
    setSupplyerCal();
    
});

function setSupplyerCal(){
    var supplyer_price = $('#damaged-supplyer_price').val();
    var supplyer_pay_amount = $('#damaged-supplyer_pay_amount').val();

    $('#damaged-supplyer_total_amount').val(supplyer_price-supplyer_pay_amount);

}
