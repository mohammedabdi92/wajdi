$(document).on('change', '[id$=-maintenance_cost]', function (item) {
   
    calculate();
});
$(document).on('change', '[id$=-amount_paid]', function (item) {
   
    calculate();
});
function calculate(){
    var maintenance_cost = $('#maintenance-maintenance_cost').val();
    var amount_paid = $('#maintenance-amount_paid').val();
    $('#maintenance-cost_difference').val(amount_paid - maintenance_cost); 
}