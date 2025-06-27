
<div class="checkout-right">

    <h3>Betalning</h3>
    <div class="payment-methods-section">
        <h6 class="method">Metod</h6>
<?php

if (function_exists('woocommerce_checkout_payment')) {
    woocommerce_checkout_payment();
}

echo '</div>';
echo '</div>';