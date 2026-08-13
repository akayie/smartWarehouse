$(document).ready(function() {
    // When a dress radio button is clicked
    $('.dress-radio').on('change', function() {
        let price = $(this).data('price');
        let size = $(this).data('size');

        // Confirm Selection with user
        if(confirm("Do you want to select this dress for " + new Intl.NumberFormat().format(price) + " MMK?")) {
            // Update Prices
            $('#display_price').val(new Intl.NumberFormat().format(price) + " MMK");
            $('#hidden_price').val(price);
            $('#hidden_size').val(size);
        } else {
            // Reset if user clicks Cancel
            $(this).prop('checked', false);
            $('#display_price').val('');
            $('#hidden_price').val('');
        }
    });

    // Reset button logic
    $('button[type="reset"]').on('click', function() {
        $('#display_price').val('');
        $('#hidden_price').val('');
    });
});
