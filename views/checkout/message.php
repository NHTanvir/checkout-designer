<?php
use Codexpert\CheckoutDesigner\Helper;

$selected_payment_method = WC()->session->get('chosen_payment_method');
$crypto_gateway          = Helper::get_option('checkout-designer_basic', 'crypto_gateway', 'crypto_gateway_slug');
$misc_fee_title          = Helper::get_option('checkout-designer_basic', 'misc_fee_title', 'Total avgifter');
$misc_fee_desc           = Helper::get_option('checkout-designer_basic', 'misc_fee_desc', 'Avgift beroende på börs:');
$misc_fee_amount         = Helper::get_option('checkout-designer_basic', 'misc_fee_amount', '100-400 SEK');
$crypto_fee_title        = Helper::get_option('checkout-designer_basic', 'crypto_fee_title', 'Kortavgift');
$crypto_fee_amount       = Helper::get_option('checkout-designer_basic', 'crypto_fee_amount', '10%');
$crypto_message_success  = Helper::get_option('checkout-designer_basic', 'crypto_message_success', 'När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.');
$crypto_message_warning  = Helper::get_option('checkout-designer_basic', 'crypto_message_warning', 'OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!');


do_action( 'wpml_register_single_string', 'checkout-designer', 'misc_fee_title', $misc_fee_title );
$misc_fee_title = apply_filters( 'wpml_translate_single_string', $misc_fee_title, 'checkout-designer', 'misc_fee_title' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'misc_fee_desc', $misc_fee_desc );
$misc_fee_desc = apply_filters( 'wpml_translate_single_string', $misc_fee_desc, 'checkout-designer', 'misc_fee_desc' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'misc_fee_amount', $misc_fee_amount );
$misc_fee_amount = apply_filters( 'wpml_translate_single_string', $misc_fee_amount, 'checkout-designer', 'misc_fee_amount' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_fee_title', $crypto_fee_title );
$crypto_fee_title = apply_filters( 'wpml_translate_single_string', $crypto_fee_title, 'checkout-designer', 'crypto_fee_title' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_fee_amount', $crypto_fee_amount );
$crypto_fee_amount = apply_filters( 'wpml_translate_single_string', $crypto_fee_amount, 'checkout-designer', 'crypto_fee_amount' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_message_success', $crypto_message_success );
$crypto_message_success = apply_filters( 'wpml_translate_single_string', $crypto_message_success, 'checkout-designer', 'crypto_message_success' );

do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_message_warning', $crypto_message_warning );
$crypto_message_warning = apply_filters( 'wpml_translate_single_string', $crypto_message_warning, 'checkout-designer', 'crypto_message_warning' );

if ( $crypto_gateway == $selected_payment_method ) {
    $misc_style = 'display:none';
    $crypto_style = 'display:block';
} else {
    $misc_style = 'display:block';
    $crypto_style = 'display:none';
}
?>

<div class="card-payments-info" style="<?php echo esc_attr( $misc_style ); ?>">
    <h6 class="method"><?php echo esc_html( $misc_fee_title ); ?></h6>
    <div class="fee-table">
        <div class="fee-title"><?php echo esc_html( $misc_fee_desc ); ?></div>
        <div class="fee-price"><?php echo esc_html( $misc_fee_amount ); ?></div>
    </div>
    <p class="card-payments-message">
        <p class="success-box">
            <img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png' ); ?>" alt="">
            <span><?php echo wp_kses_post( $crypto_message_success ); ?></span>
        </p>
        <p class="warning-box">
            <img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning-icon.png' ); ?>" alt="">
            <span><?php echo wp_kses_post( $crypto_message_warning ); ?></span>
        </p>
    </p>
</div>

<div class="crypto-payments-info" style="<?php echo esc_attr( $crypto_style ); ?>">
    <h6 class="method"><?php echo esc_html( $crypto_fee_title ); ?></h6>
    <div class="fee-table">
        <div class="fee-title"><?php echo esc_html( $crypto_fee_title ); ?></div>
        <div class="fee-price"><?php echo esc_html( $crypto_fee_amount ); ?></div>
    </div>
    <p class="crypto-payments-message">
        <p class="success-box">
            <img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning.png' ); ?>" alt="">
            <span><?php echo wp_kses_post( $crypto_message_success ); ?></span>
        </p>
        <p class="warning-box">
            <img src="<?php echo esc_url( 'https://iptvking.co/wp-content/plugins/checkout-designer/assets/img/warning-icon.png' ); ?>" alt="">
            <span><?php echo wp_kses_post( $crypto_message_warning ); ?></span>
        </p>
    </p>
</div>
