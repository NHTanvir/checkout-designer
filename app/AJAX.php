<?php
/**
 * All AJAX related functions
 */
namespace Codexpert\CheckoutDesigner\App;
use Codexpert\Plugin\Base;

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

		$selected_payment_method = sanitize_text_field($_POST['payment_method']);
		$cyrpto_check               = get_option( "{$selected_payment_method}_crypto_check" );
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = $cart_item['data'];
			$product_name = $_product->get_name();
			
		}

		if ($cyrpto_check == 'yes') {

			echo '<table class="totals-table">';
			echo '<tbody>';
			echo '<tr><td>Pris | SEK</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
			echo '</tbody>';
			echo '</table>';
		} else{

			echo '<table class="totals-table">';
			echo '<tbody>';
			echo '<tr><td>Pris | SEK</td><td>' . do_shortcode('[total-price-sek]') . '</td></tr>';
			echo '<tr><td>Kortavgift - 10%</td><td>' . do_shortcode('[total-fee-sek]') . '</td></tr>'; 
			echo '<tr><td>Totalt</td><td>' . do_shortcode('[total-price-eur]') . '</td></tr>';
			echo '</tbody>';
			echo '</table>';

		}
		wp_die();
	}

	public function update_table_on_payment_method_change() {
		$selected_payment_method    = sanitize_text_field($_POST['payment_method']);
		$addon_product_id           = get_option('addon_product');
		$addon_product              = wc_get_product( $addon_product_id );

		echo '<table class="product-table">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>Produkt</th>';
			echo '<th>Quantity</th>';
			echo '<th>Pris i SEK</th>';
			echo "<th></th>";
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product           = $cart_item['data'];
				$product_id       	= $cart_item['product_id']; 
				$product_product  	= wc_get_product($product_id); 
				$price              = $product_product->get_price();
				$product_name       = $_product->get_name();

				echo '<tr>';
				echo '<td>' . $product_name . '</td>';
				echo '<td>';
					echo '<div class="quantity">';
						echo '<input type="number" class="qty-input" name="cart[' . $cart_item_key . '][qty]" value="' . $cart_item['quantity'] . '" min="1">';
					echo '</div>';
				echo '</td>';
				echo '<td>' . wc_price( $price ) . '</td>';
				echo '<td>';
					echo "<button type='button' class='remove-cart' data-cart-item-key='{$cart_item_key}'>";
						echo '<img src="https://iptvutanbox.com/wp-content/uploads/2024/08/Group-63.svg">';
					echo '</button>';
				echo '</td>';
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
			wp_die();
	}

	public function woocommerce_update_cart_item_qty() {
		$cart_item_key = sanitize_text_field($_POST['cart_item_key']);
		$quantity = intval($_POST['quantity']);

		if ($cart_item_key && $quantity) {
			WC()->cart->set_quantity($cart_item_key, $quantity);
			WC()->cart->calculate_totals(); 
		}

		wp_die();
	}
	
}