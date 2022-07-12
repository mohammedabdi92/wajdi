console.log('sadsadfasdljkfhkjsadhfkjsahdkjahskd');

$(document).on('change', '[id$=product_cost]', function (item) {

    calculateSupTotals(item);
    calculateTotals();
});
$(document).on('change', '[id$=product_total_cost]', function (item) {

    calculateTotTotals(item);
    calculateTotals();
});
$(document).on('change', '[id$=count]', function (item) {
    calculateSupTotals(item);
    calculateTotTotals(item);
    calculateTotals();
});
function calculateTotals() {
    var total = 0;
    $('[id$=product_total_cost]').each(function (index, element) {
        var elementCost = parseFloat(element.value);
        if (elementCost) {
            elementCost = Math.round((elementCost + Number.EPSILON) * 100) / 100
            total = total + elementCost;
        }
    });
    $('[id$=inventoryorder-total_cost]').val(total);
}
function calculateSupTotals(item) {
    var mainBox = $($(item.target).parents( ".item" )[0]);
    var product_total_cost = $( "[id$=product_total_cost]" );
    var productCostItem = $( "[id$=product_cost]" );
    var productCost  = mainBox.find( productCostItem).val();
    var countItem = $( "[id$=count]" );
    var count  = mainBox.find( countItem).val();
    if(count && productCost){
        mainBox.find( product_total_cost).val(parseFloat(count)*parseFloat(productCost));
    }
}
function calculateTotTotals(item) {
    var mainBox = $($(item.target).parents( ".item" )[0]);
    var product_total_cost = $( "[id$=product_total_cost]" );
    var productCostItem = $( "[id$=product_cost]" );
    var productCost  = mainBox.find( product_total_cost).val();
    var countItem = $( "[id$=count]" );
    var count  = mainBox.find( countItem).val();
    if(count && productCost ){
        mainBox.find( productCostItem).val(parseFloat(productCost)/parseFloat(count));
    }
}