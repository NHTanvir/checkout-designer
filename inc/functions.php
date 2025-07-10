<?php
use Codexpert\CheckoutDesigner\Helper;
if( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Gets the site's base URL
 * 
 * @uses get_bloginfo()
 * 
 * @return string $url the site URL
 */
if( ! function_exists( 'cd_site_url' ) ) :
function cd_site_url() {
	$url = get_bloginfo( 'url' );

	return $url;
}
endif;
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
remove_action('woocommerce_checkout_process', 'woocommerce_checkout_terms_and_conditions_validation', 9999)

if( ! function_exists( 'cd_site_url' ) ) :
function cd_site_url() {
	$url = get_bloginfo( 'url' );

	return $url;
}
endif;
//Render coupon field
function custom_coupon_form() {
    ?>
    <div class="coupon" id="coupon-section">
        <p class="form-row">
            <input type="text" name="coupon_code" class="input-text" placeholder="Rabattkod" id="coupon_code" value="">
            <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Använd</button>
        </p>
    </div>
    <?php
}