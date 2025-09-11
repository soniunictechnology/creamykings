jQuery(document).ready(function () {
    // Handle form submission
    jQuery('#contactpage').on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        var formData = jQuery(this).serialize();
        
        // Show loading state
        jQuery('#submit').prop('disabled', true).html('Sending...<i class="fa-solid fa-spinner fa-spin"></i>');
        
        // Remove any existing messages
        jQuery('.form-message').remove();
        
        // Submit form via AJAX
        jQuery.ajax({
            url: 'sendmail.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    jQuery('#form-messages').html('<div class="form-message alert alert-success">' + response.message + '</div>');
                    
                    // Reset form
                    jQuery('#contactpage')[0].reset();
                    
                    // Scroll to message
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#form-messages').offset().top - 100
                    }, 500);
                } else {
                    // Show error message
                    jQuery('#form-messages').html('<div class="form-message alert alert-danger">' + response.message + '</div>');
                    
                    // Scroll to message
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#form-messages').offset().top - 100
                    }, 500);
                }
            },
            error: function(xhr, status, error) {
                // Show generic error message
                jQuery('#form-messages').html('<div class="form-message alert alert-danger">Sorry, there was an error processing your request. Please try again.</div>');
                
                // Scroll to message
                jQuery('html, body').animate({
                    scrollTop: jQuery('#form-messages').offset().top - 100
                }, 500);
            },
            complete: function() {
                // Reset button state
                jQuery('#submit').prop('disabled', false).html('Submit Now<i class="fa-solid fa-arrow-right"></i>');
            }
        });
    });
    
    // Remove message when user starts typing
    jQuery('#contactpage input, #contactpage textarea').on('input', function() {
        jQuery('.form-message').fadeOut(300, function() {
            jQuery(this).remove();
        });
    });
    
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        jQuery('.form-message').fadeOut(300, function() {
            jQuery(this).remove();
        });
    }, 5000);
});
