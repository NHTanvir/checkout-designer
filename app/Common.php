<?php
/**
 * All common functions to load in both admin and front
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
 * @subpackage Common
 * @author Codexpert <hi@codexpert.io>
 */
class Common extends Base {

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

	public function set_default_payment_method() {
	
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		if ( empty( $available_gateways ) ) {
			return;
		}
	
		$gateway_order = get_option( 'woocommerce_gateway_order', [] );
		if ( empty( $gateway_order ) ) {
			return;
		}
	
		asort( $gateway_order );
	
		foreach ( $gateway_order as $gateway_id => $priority ) {
			if ( isset( $available_gateways[ $gateway_id ] ) ) {
				WC()->session->set( 'chosen_payment_method', $gateway_id );
				break;
			}
		}
	}
	
	public function custom_checkout_columns_start() {
		echo Helper::get_template( 'left', 'views/checkout' );
	}

	public function custom_checkout_columns_end() {
		echo Helper::get_template( 'right', 'views/checkout' );
	}

	public function custom_payment_message() {
		echo Helper::get_template( 'message', 'views/checkout' );
	}

	public function redirect_cart_to_checkout() {
		if ( is_cart() ) {
			wp_redirect( wc_get_checkout_url() );
			exit;
		}
	}

	public function make_all_checkout_fields_optional ($fields ) {
		foreach ( $fields as $fieldset_key => $fieldset ) {
			foreach ( $fieldset as $field_key => $field ) {
				$fields[$fieldset_key][$field_key]['required'] = false;
			}
		}
		return $fields;
	}

	public function remove_terms_error( $data, $errors ) {
		if ( $errors->get_error_code('terms') ) {
			$errors->remove('terms');
		}
	}

	public function change_woocommerce_order_button_text( $button_text ) {

		$chosen_payment_method 	= WC()->session->get('chosen_payment_method'); 
		$cyrpto_gateway       	= Helper::get_option( "checkout-designer_basic", 'crypto_gateway' );

		if ( $chosen_payment_method == $cyrpto_gateway ) {
			$button_text = 'Betala med krypto';
		} else {
			$button_text = 'Betala med kort';
		}

		return $button_text;
	}

	public function add_custom_data_to_cart_item( $cart_item_data, $product_id ) {
		if ( isset( $_POST['addon_option'] ) ) {
			$cart_item_data['addon_option'] = sanitize_text_field( $_POST['addon_option'] );
		}
		if ( isset( $_POST['mac_address'] ) ) {
			$cart_item_data['mac_address'] = sanitize_text_field( $_POST['mac_address'] );
		}
		return $cart_item_data;
	}

	public function save_custom_data_to_order_meta( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['addon_option'] ) ) {
			$item->add_meta_data( 'Addon Option', $values['addon_option'], true );
		}

		if ( isset( $values['mac_address'] ) ) {
			$item->add_meta_data( 'MAC Address', $values['mac_address'], true );
		}
	}

	public function display_custom_order_item_meta_in_admin( $item_id, $item, $order ) {
		$addon_option 	= $item->get_meta('addon_option');
		$mac_address 	= $item->get_meta('mac_address');

		if ( $addon_option ) {
			echo '<p><strong>Addon Option:</strong> ' . esc_html( $addon_option ) . '</p>';
		}

		if ( $mac_address ) {
			echo '<p><strong>MAC Address:</strong> ' . esc_html( $mac_address ) . '</p>';
		}
	}
}

