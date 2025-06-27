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
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_order_review', 30 );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 30 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 