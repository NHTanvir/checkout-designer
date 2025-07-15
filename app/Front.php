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

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", Checkout_Designer ), '', time(), 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", Checkout_Designer ), [ 'jquery' ], time(), true );

		$crypto_button_text = Helper::get_option( 'checkout-designer_basic', 'crypto_button_text', 'Betala med krypto' );
		$card_button_text   = Helper::get_option( 'checkout-designer_basic', 'card_button_text', 'Betala med kort' );

		do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_button_text', $crypto_button_text );
		do_action( 'wpml_register_single_string', 'checkout-designer', 'card_button_text', $card_button_text );

		$crypto_button_text_translated = apply_filters( 'wpml_translate_single_string', $crypto_button_text, 'checkout-designer', 'crypto_button_text' );
		$card_button_text_translated   = apply_filters( 'wpml_translate_single_string', $card_button_text, 'checkout-designer', 'card_button_text' );
		
		$localized = [
			'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'				=> wp_create_nonce(),
			'crypto_button_text' 	=> $crypto_button_text_translated,
			'card_button_text'   	=> $card_button_text_translated
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
				'<img src="%1$s" data-payment="crypto" alt="%2$s" class="bit-coin-logo" style="margin: 0 10px !important;">
				<div class="payment-text">%3$s<p class="payment-dis">%4$s</p></div>
				<p class="payment-discription">
					<img src="%5$s" alt="%6$s">%7$s
				</p>',
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Icon-awesome-btc.png' ),
				esc_attr__( 'Bitcoin', 'checkout-designer' ),
				$title,
				$description,
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-15.png' ),
				esc_attr__( 'Clock icon', 'checkout-designer' ),
				esc_html__( '10–60 min', 'checkout-designer' )
			);
		} else {
			$icon = sprintf(
				'<img src="%1$s" data-payment="card" alt="%2$s" class="card-logo">
				<div class="payment-text">%3$s<p class="payment-dis">%4$s</p></div>
				<p class="payment-discription">
					<img src="%5$s" alt="%6$s">%7$s
				</p>',
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Mastercard.png' ),
				esc_attr__( 'Kortbetalning (+10% avgift)', 'checkout-designer' ),
				$title,
				$description,
				esc_url( 'https://iptvutanbox.com/wp-content/uploads/2024/09/Vector-14.png' ),
				esc_attr__( 'Clock icon', 'checkout-designer' ),
				esc_html__( 'Direkt', 'checkout-designer' )
			);
		}

		return $icon;
	}

}