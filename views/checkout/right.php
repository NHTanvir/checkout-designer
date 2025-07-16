<?php
use Codexpert\CheckoutDesigner\Helper;
?>
<div class="checkout-right">
    <h3>
        <?php
    
        $payment_heading = Helper::get_option( 'checkout-designer_basic', 'payment_heading', 'Betalning' );
        do_action( 'wpml_register_single_string', 'checkout-designer', 'payment_heading', $payment_heading );
        echo esc_html( apply_filters( 'wpml_translate_single_string', $payment_heading, 'checkout-designer', 'payment_heading' ) );
        ?>
    </h3>
    <div class="payment-methods-section">
        <h6 class="method">
            <?php
            $method_label = Helper::get_option( 'checkout-designer_basic', 'method_label', 'Metod' );
            do_action( 'wpml_register_single_string', 'checkout-designer', 'method_label', $method_label );
            echo esc_html( apply_filters( 'wpml_translate_single_string', $method_label, 'checkout-designer', 'method_label' ) );
            ?>
        </h6>

        <?php
        if ( function_exists( 'woocommerce_checkout_payment' ) ) {
            woocommerce_checkout_payment();
        }
        ?>
