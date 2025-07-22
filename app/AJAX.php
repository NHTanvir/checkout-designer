<?php
/**
 * All AJAX related functions
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
 * @subpackage AJAX
 * @author Codexpert <hi@codexpert.io>
 */
class AJAX extends Base {

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

	public function update_cart_totals_on_payment_method_change() {

		$selected_payment_method 	= sanitize_text_field( $_POST['payment_method'] );
		$crypto_gateway 			= Helper::get_option( "checkout_designer_table", 'crypto_gateway' );
		$table_heading     			= Helper::get_option( 'checkout_designer_table', 'table_heading', 'Fakturauppgifter' );

		if ( $selected_payment_method ) {
			WC()->session->set( 'chosen_payment_method', $selected_payment_method );
		}

		do_action( 'wpml_register_single_string', 'checkout-designer', 'table_heading', $table_heading );

		$table_heading = apply_filters( 'wpml_translate_single_string', $table_heading, 'checkout-designer', 'table_heading' );

		if ( $selected_payment_method == $crypto_gateway ) {
			echo '<h4>' . esc_html( $table_heading ) . '</h4>';
			echo '<table class="totals-table">';
			echo '<tbody>';
			echo '<tr><td>' . esc_html__( 'Pris | SEK', 'checkout-designer' ) . '</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
			echo '</tbody>';
			echo '</table>';
		} else {
			echo '<h4>' . esc_html( $table_heading ) . '</h4>';
			echo '<table class="totals-table">';
			echo '<tbody>';
			echo '<tr><td>' . esc_html__( 'Pris | SEK', 'checkout-designer' ) . '</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
			echo '<tr><td>' . esc_html__( 'Kortavgift - 10%', 'checkout-designer' ) . '</td><td>' . do_shortcode('[total-fee-sek]') . '</td></tr>';
			echo '<tr><td>' . esc_html__( 'Totalt', 'checkout-designer' ) . '</td><td>' . do_shortcode('[total-price-eur]') . '</td></tr>';
			echo '</tbody>';
			echo '</table>';
		}

		wp_die();
	}

	public function update_table_on_payment_method_change() {

		echo '<table class="product-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Produkt</th>';
		echo '<th>Pris i SEK</th>';
		echo '<th>Antal</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product 			= $cart_item['data'];
			$product_id 		= $cart_item['product_id'];
			$product_product 	= wc_get_product($product_id);
			$quantity 			= $cart_item['quantity'];
			$price 				= $product_product->get_price() * $quantity;
			$product_name 		= $_product->get_name();
			$quantity 			= $cart_item['quantity'];

			echo '<tr>';
				echo '<td><div class="product-title">' . esc_html( $product_name ) . '</div></td>';
				echo '<td>' . wc_price( $price ) . '</td>';
				echo '<td>';
					echo '<div class="quantity-control" data-cart-item-key="' . esc_attr( $cart_item_key ) . '">';
						echo '<button type="button" class="qty-decrease">-</button>';
						echo '<span class="qty-display">' . esc_html( str_pad( $quantity, 2, '0', STR_PAD_LEFT ) ) . '</span>';
						echo '<button type="button" class="qty-increase">+</button>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';

		wp_die();
	}


	public function woocommerce_update_cart_item_qty() {
		$cart_item_key 	= sanitize_text_field( $_POST['cart_item_key'] );
		$quantity 		= intval( $_POST['quantity'] );

		if ( $cart_item_key ) {
			if ( $quantity > 0 ) {
				WC()->cart->set_quantity( $cart_item_key, $quantity );
			} else {
				WC()->cart->remove_cart_item( $cart_item_key );
			}

			WC()->cart->calculate_totals();
		}

		wp_die();
	}


	public function add_addon_to_cart() {
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( 'Missing product ID' );
		}

		$product_id   = intval( $_POST['product_id'] );
		$addon_option = sanitize_text_field( $_POST['addon_option'] ?? '' );
		$mac_address  = sanitize_text_field( $_POST['mac_address'] ?? '' );
		$quantity     = 1;

		$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity );

		if ( $cart_item_key ) {
			WC()->cart->cart_contents[$cart_item_key]['addon_option'] 	= $addon_option;
			WC()->cart->cart_contents[$cart_item_key]['mac_address'] 	= $mac_address;

			wp_send_json_success( ['cart_item_key' => $cart_item_key] );
		} else {
			wp_send_json_error('Failed to add product to cart');
		}

		wp_die();
	}
}