jQuery(document).ready(function($) {
    $('#custom-form').on('submit', function(e) {
        e.preventDefault();
            $form.find(':input').change(function() {
            // Trigger form submission when any form element changes
            $form.submit();
        });

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: custom_script_vars.ajax_url,
            data: {
                action: 'add_to_cart_with_custom_data',
                nonce: custom_script_vars.security,
                data: formData
            },
            success: function(response) {
                // Handle the response (e.g., update cart contents)
            }
        });
    });
});
