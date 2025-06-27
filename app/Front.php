<?php
/**
 * All public facing functions
 */
namespace Codexpert\CheckoutDesigner\App;
use Codexpert\Plugin\Base;
use Codexpert\CheckoutDesigner\Helper;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Codexpert <hi@codexpert.io>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	public function head() {}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'Checkout_Designer_DEBUG' ) && Checkout_Designer_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", Checkout_Designer ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", Checkout_Designer ), [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce(),
		];
		wp_localize_script( $this->slug, 'Checkout_Designer', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function modal() {
		echo '
		<div id="checkout-designer-modal" style="display: none">
			<img id="checkout-designer-modal-loader" src="' . esc_attr( Checkout_Designer_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}

	public function add_payment_method_class( $classes ) {
		$selected_payment_method 		= WC()->session->get('chosen_payment_method');
		$cyrpto_gateway               	= Helper::get_option( "checkout-designer_basic", 'crypto_gateway' );
		if ( $selected_payment_method == $cyrpto_gateway ) {
			$classes[] = 'payment-method-crypto';
		}
		else{
			$classes[] = 'payment-method-' . esc_attr( $selected_payment_method );
		}
		return $classes;
	}

	public function payment_gateway_icon( $icon, $gateway_id ) {
		$crypto_gateway = Helper::get_option( 'checkout-designer_basic', 'crypto_gateway' );
		$settings       = get_option( "woocommerce_{$gateway_id}_settings", [] );
		$title          = isset( $settings['title'] ) ? esc_html( $settings['title'] ) : '';
		$description    = isset( $settings['description'] ) ? esc_html( $settings['description'] ) : '';

		if ( $gateway_id === $crypto_gateway ) {
			$icon = sprintf(
				'<img src="%1$s" data-payment="crypto" alt="Bitcoin" class="bit-coin-logo" style="margin: 0 10px !important;">
				<div class="payment-text">%2$s<p class="payment-dis">%3$s</p></div>
				<p class="payment-discription">
					<img src="%4$s" alt="Clock icon">10–60 min
				</p>',
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Icon-awesome-btc.png' ),
				$title,
				$description,
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-15.png' )
			);
		} else {
			$icon = sprintf(
				'<img src="%1$s" data-payment="card" alt="Kortbetalning (+10%% avgift)" class="card-logo">
				<div class="payment-text">%2$s<p class="payment-dis">%3$s</p></div>
				<p class="payment-discription">
					<img src="%4$s" alt="Clock icon">Direkt
				</p>',
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Mastercard.png' ),
				$title,
				$description,
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-14.png' )
			);
		}

		return $icon;
	}

}