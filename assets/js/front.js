jQuery(document).ready(function ($) {


    let cd_modal = (show = true) => {
        if (show) {
            jQuery('#checkout-designer-modal').show();
        } else {
            jQuery('#checkout-designer-modal').hide();
        }
    };
    
    $("form.checkout").on(
        "change",
        'input[name="payment_method"]',
        function () {
            update_totals_based_on_payment_method();
        }
    );

    function updatePaymentMethodClass() {
        $('input[name="payment_method"]:checked')
            .closest("li")
            .addClass("payment-active");

        $(".wc_payment_methods li").each(function () {
            var paymentType = $(this).find('img[data-payment]').attr('data-payment');
    
            if (paymentType === "crypto") {
                $(this).addClass("payment-type-blockchain");
            } else if (paymentType) {
                $(this).addClass("payment-type-card");
            }
        });
    }
    
    function updateBodyClass() {
        
        var selectedMethod = $('input[name="payment_method"]:checked').closest('li').find('img[data-payment]').attr('data-payment');
    
        // Remove previous payment method body classes
        $("body").removeClass(function (index, className) {
            return (className.match(/(^|\s)payment-method-\S+/g) || []).join(" ");
        });
    
        if (selectedMethod === "crypto") {
            $("body").addClass("payment-method-crypto");
        } else {
            $("body").addClass("payment-method-card");
        }
    }
    


    updateBodyClass();
    updatePaymentMethodClass();

    // Change event to update class when payment method is changed
    $("form.woocommerce-checkout").on(
        "change",
        'input[name="payment_method"]',
        function () {
            updatePaymentMethodClass();
            updateBodyClass();
        }
    );

    $(document.body).on("updated_checkout", function () {
       updateBodyClass();
        updatePaymentMethodClass();
    });

    function update_totals_based_on_payment_method() {
        cd_modal(true);
        var selected_payment_method = $(
            'input[name="payment_method"]:checked'
        ).val();
        $(
            ".bitcoin-payments-message-below, .normal-payments-message, .crypto-payments-message"
        ).hide();

        if (selected_payment_method === "crypto") {
            $(".bitcoin-payments-message-below").show();
            $(".crypto-payments-message").show();
        } else {
            $(".normal-payments-message").show();
        }

        $.ajax({
			url: Checkout_Designer.ajaxurl,
            type: "POST",
            data: {
                action: "update_cart_totals_on_payment_method_change",
                payment_method: selected_payment_method,
            },
            success: function (response) {
                $(".checkout-left").find(".total-section").html(response);
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            },
        });

        $.ajax({
			url: Checkout_Designer.ajaxurl,
            type: "POST",
            data: {
                action: "update_table_on_payment_method_change",
                payment_method: selected_payment_method,
            },
            success: function (response) {
                $(".checkout-left").find(".table-wrapper").html(response);
                cd_modal(false);
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            },
        });
    }

    // Update cart on quantity change
    $(document).on('click', '.qty-increase, .qty-decrease', function () {
        cd_modal(true);
    
        var control = $(this).closest('.quantity-control');
        var display = control.find('.qty-display');
        var cartItemKey = control.data('cart-item-key');
        var currentQty = parseInt(display.text()) || 1;
    
        if ($(this).hasClass('qty-increase')) {
            currentQty++;
        } else if ($(this).hasClass('qty-decrease') && currentQty > 1) {
            currentQty--;
        }
    
        display.text(currentQty < 10 ? '0' + currentQty : currentQty);
    
        $.ajax({
            type: 'POST',
            url: Checkout_Designer.ajaxurl,
            data: {
                action: 'woocommerce_update_cart_item_qty',
                cart_item_key: cartItemKey,
                quantity: currentQty,
            },
            success: function (response) {
                cd_modal(false);
                update_totals_based_on_payment_method();
            },
        });
    });
    
    // Function to toggle MAC address input visibility
    function toggleMacAddressInput() {
        var selectedOption = $(".addon-option-select").val();
        var macAddressInput = $(".addon-mac-address");

        if (selectedOption === "förnyelse") {
            macAddressInput.show();
        } else {
            macAddressInput.hide().removeClass("red"); // Hide and remove red border
        }
    }

    function validateMacAddress() {
        var selectedOption = $(".addon-option-select").val();
        var macAddressInput = $(".addon-mac-address");

        if (selectedOption === "förnyelse" && !macAddressInput.val()) {
            macAddressInput.addClass("red"); // Add red border if empty
            return false;
        } else {
            macAddressInput.removeClass("red"); // Remove red border if filled
            return true;
        }
    }

    $(document).ready(function () {
        toggleMacAddressInput(); // Check initial state on page load

        // Remove "red" class when user starts typing
        $(".addon-mac-address").on("input", function () {
            if ($(this).val()) {
                $(this).removeClass("red");
            } else {
                $(this).addClass("red");
            }
        });
    });

    $(".addon-option-select").on("change", function () {
        toggleMacAddressInput();
    });
	
	$(".add-addon-to-cart").on("click", function (e) {
		if (!validateMacAddress()) {
			e.preventDefault();
			return;
		}
		cd_modal(true);

		var product_id = $('.addon-variation-select').val();
		var addon_option = $(".addon-option-select").val();
		var mac_address = $(".addon-mac-address").val();

		$.ajax({
			url: Checkout_Designer.ajaxurl,
			type: "POST",
			data: {
				action: "add_addon_to_cart",
				product_id: product_id,
				addon_option: addon_option,
				mac_address: mac_address,
			},
			success: function (response) {
				$(".addon-mac-address").val("");
				if (response.success) {
					update_totals_based_on_payment_method();
				}
			},
		});
	});
});
