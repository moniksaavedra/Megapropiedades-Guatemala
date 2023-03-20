(function($) {
    /* Global variables */
    "use strict";



    $('body').on('click', '.wc-listing-cart', function() {

        var id = $(this).data('product_id');
        var quantity = 1;
        var price = $(this).attr('data-price');
        var checkout = $(this).attr('data-checkout');


        var $this = $(this);
  
        $($this).prev('.spinner').show();
        // $this.hide();

        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            data: {
                action: 'listing_add_to_cart',
                product_id: id,
                price: price,
                quantity: quantity,
                security: ajax_object.ajax_nonce_listingcart,
            },
            success: function(res) {
                if (res = 'success')
                    $("body").trigger("wc_fragment_refresh")
                $this.show();
                $($this).prev('.spinner').hide();
                setTimeout(function () {
                    window.location = checkout;
                }, 500);
            }
        });
    });


})(jQuery);