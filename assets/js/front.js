jQuery(document).ready(function ($) {
    let cd_modal = (show = true) => {
        if (show) {
            jQuery('#checkout-designer-modal').show();
        } else {
            jQuery('#checkout-designer-modal').hide();
        }
    };

    $("form.checkout").on("change", 'input[name="payment_method"]', function () {
        update_totals_based_on_payment_method();
    });

    function updatePaymentMethodClass() {
        $('.wc_payment_methods li').removeClass('payment-active');
        $('input[name="payment_method"]:checked').closest("li").addClass("payment-active");

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
        $("body").removeClass(function (index, className) {
            return (className.match(/(^|\s)payment-method-\S+/g) || []).join(" ");
        });

        if (selectedMethod === "crypto") {
            $("body").addClass("payment-method-crypto");
        } else {
            $("body").addClass("payment-method-card");
        }
    }

    function updatePlaceOrderButtonText() {
         var selectedMethod = $('input[name="payment_method"]:checked').closest('li').find('img[data-payment]').attr('data-payment');
        const cryptoGatewaySlug = 'crypto';
        const buttonText = selectedMethod === cryptoGatewaySlug
            ? 'Betala med krypto'
            : 'Betala med kort';

        $('#place_order').text(buttonText);
    }

    function update_totals_based_on_payment_method() {
        cd_modal(true);
        var selected_payment_method = $('input[name="payment_method"]:checked').val();
        $(".bitcoin-payments-message-below, .normal-payments-message, .crypto-payments-message").hide();

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

    $(document).on('click', '.qty-increase, .qty-decrease', function () {
        cd_modal(true);
        var control = $(this).closest('.quantity-control');
        var display = control.find('.qty-display');
        var cartItemKey = control.data('cart-item-key');
        var currentQty = parseInt(display.text()) || 1;
        var newQty = currentQty;

        if ($(this).hasClass('qty-increase')) {
            newQty++;
        } else if ($(this).hasClass('qty-decrease')) {
            newQty--;
        }

        if (newQty <= 0) {
            control.closest('.cart-item-row').remove();
        } else {
            display.text(newQty < 10 ? '0' + newQty : newQty);
        }

        $.ajax({
            type: 'POST',
            url: Checkout_Designer.ajaxurl,
            data: {
                action: 'woocommerce_update_cart_item_qty',
                cart_item_key: cartItemKey,
                quantity: newQty,
            },
            success: function (response) {
                cd_modal(false);
                update_totals_based_on_payment_method();
            },
        });
    });

    function toggleMacAddressInput() {
        var selectedOption = $(".addon-option-select").val();
        var macAddressInput = $(".addon-mac-address");

        if (selectedOption === "förnyelse") {
            macAddressInput.show();
        } else {
            macAddressInput.hide().removeClass("red");
        }
    }

    function validateMacAddress() {
        var selectedOption = $(".addon-option-select").val();
        var macAddressInput = $(".addon-mac-address");

        if (selectedOption === "förnyelse" && !macAddressInput.val()) {
            macAddressInput.addClass("red");
            return false;
        } else {
            macAddressInput.removeClass("red");
            return true;
        }
    }

    toggleMacAddressInput();

    $(".addon-mac-address").on("input", function () {
        if ($(this).val()) {
            $(this).removeClass("red");
        } else {
            $(this).addClass("red");
        }
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

    // Initial call
    updateBodyClass();
    updatePaymentMethodClass();
    updatePlaceOrderButtonText();

    // Payment method change
    $("form.woocommerce-checkout").on("change", 'input[name="payment_method"]', function () {
        updatePaymentMethodClass();
        updateBodyClass();
        updatePlaceOrderButtonText();
    });

    // After AJAX updates
    $(document.body).on("updated_checkout", function () {
        updateBodyClass();
        updatePaymentMethodClass();
        updatePlaceOrderButtonText();
    });
});
