<div class="checkout-right">
    <h3><?php esc_html_e( 'Betalning', 'checkout-designer' ); ?></h3>
    <div class="payment-methods-section">
        <h6 class="method"><?php esc_html_e( 'Metod', 'checkout-designer' ); ?></h6>

        <?php
        if ( function_exists( 'woocommerce_checkout_payment' ) ) {
            woocommerce_checkout_payment();
        }
        ?>
