<?php
use Codexpert\CheckoutDesigner\Helper;

$selected_payment_method = WC()->session->get('chosen_payment_method');
$cyrpto_gateway       = Helper::get_option( "checkout-designer_basic", 'crypto_gateway' );

if ($cyrpto_gateway == $selected_payment_method) {
      $mics_style = 'display:none';
    $bit_style = 'display:block';
} else {
      $mics_style = 'display:block';
    $bit_style = 'display:none';
}
?>

<div class="card-payments-info" style="<?php echo esc_attr($mics_style); ?>">
    <h6 class="method"><?php esc_html_e('Total avgifter', 'checkout-designer'); ?></h6>
    <div class="fee-table">
        <div class="fee-title">
            <?php esc_html_e('Avgift beroende på börs:', 'checkout-designer'); ?>
        </div>
        <div class="fee-price">
            <?php esc_html_e('100-400 SEK', 'checkout-designer'); ?>
        </div>
    </div>
    <p class="card-payments-message">
        <p class="success-box">
            <img src="<?php echo esc_url('https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.'); 
            ?>
        </span>
        </p>
        <p class="warning-box">
            <img src="<?php echo esc_url('https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning-icon.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!'); 
            ?>
        </span>
        </p>
    </p>

</div>

<div class="crypto-payments-info" style="<?php echo esc_attr($bit_style); ?>">
    <h6 class="method"><?php esc_html_e('Total avgifter', 'checkout-designer'); ?></h6>
    <div class="fee-table">
        <div class="fee-title">
            <?php esc_html_e('Kortavgift', 'checkout-designer'); ?>
        </div>
        <div class="fee-price">
            10%
        </div>
    </div>
    <p class="crypto-payments-message">
       <p class="success-box">
            <img src="<?php echo esc_url('https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.'); 
            ?>
        </span>
        </p>
        <p class="warning-box">
            <img src="<?php echo esc_url('https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning-icon.png'); ?>" alt="">
        <span>
            <?php 
            echo wp_kses_post('OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!'); 
            ?>
        </span>
        </p>
    </p>

</div>
