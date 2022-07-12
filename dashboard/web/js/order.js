

$(document).on('change', '[id$=count]', function (item) {
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
