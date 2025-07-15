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
		if ( is_checkout() && !is_wc_endpoint_url() && WC()->cart->is_empty() ) {
			wp_safe_redirect( wc_get_page_permalink('shop') );
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

	public function cd_map_custom_to_billing() {

		if ( ! empty( $_POST['cd_name'] ) ) {
			$parts = explode( ' ', sanitize_text_field( wp_unslash( $_POST['cd_name'] ) ), 2 );
			$_POST['billing_first_name'] = $parts[0];
			if ( ! empty( $parts[1] ) ) {
				$_POST['billing_last_name'] = $parts[1];
			}
		}

		if ( ! empty( $_POST['cd_email'] ) ) {
			$_POST['billing_email'] = sanitize_email( wp_unslash( $_POST['cd_email'] ) );
		}

		if ( isset( $_POST['cd_phone'] ) ) {
			$_POST['billing_phone'] = sanitize_text_field( wp_unslash( $_POST['cd_phone'] ) );
		}
	}

	public function remove_terms_error( $data, $errors ) {
		if ( $errors->get_error_code('terms') ) {
			$errors->remove('terms');
		}
	}

	public function change_woocommerce_order_button_text( $button_text ) {
		$chosen_payment_method 	= WC()->session->get( 'chosen_payment_method' );
		$crypto_gateway        	= Helper::get_option( 'checkout-designer_basic', 'crypto_gateway' );
		$crypto_button_text 	= Helper::get_option( 'checkout-designer_basic', 'crypto_button_text', 'Betala med krypto' );
		$card_button_text   	= Helper::get_option( 'checkout-designer_basic', 'card_button_text', 'Betala med kort' );

		do_action( 'wpml_register_single_string', 'checkout-designer', 'crypto_button_text', $crypto_button_text );
		do_action( 'wpml_register_single_string', 'checkout-designer', 'card_button_text', $card_button_text );

		if ( function_exists( 'wpml_translate_single_string' ) ) {
			$crypto_button_text = wpml_translate_single_string( $crypto_button_text, 'checkout-designer', 'crypto_button_text' );
			$card_button_text   = wpml_translate_single_string( $card_button_text, 'checkout-designer', 'card_button_text' );
		}

		if ( $crypto_gateway === $chosen_payment_method ) {
			$button_text = $crypto_button_text;
		} else {
			$button_text = $card_button_text;
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

	public function cd_save_custom_fields_to_order_meta( $order, $data ) {

		if ( isset( $_POST['cd_name'] ) ) {
			$order->update_meta_data( '_cd_name', sanitize_text_field( $_POST['cd_name'] ) );
		}

		if ( isset( $_POST['cd_phone'] ) ) {
			$order->update_meta_data( '_cd_phone', sanitize_text_field( $_POST['cd_phone'] ) );
		}

		if ( isset( $_POST['cd_email'] ) ) {
			$order->update_meta_data( '_cd_email', sanitize_email( $_POST['cd_email'] ) );
		}

		if ( isset( $_POST['cd_mac'] ) ) {
			$order->update_meta_data( '_cd_mac', sanitize_text_field( $_POST['cd_mac'] ) );
		}

		if ( isset( $_POST['cd_adult'] ) ) {
			$order->update_meta_data( '_cd_adult', sanitize_text_field( $_POST['cd_adult'] ) );
		}
	}

	public function save_custom_data_to_order_meta( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['addon_option'] ) ) {
			$item->add_meta_data( 'Addon Option', $values['addon_option'], true );
		}

		if ( isset( $values['mac_address'] ) ) {
			$item->add_meta_data( 'MAC Address', $values['mac_address'], true );
		}
	}

	public function cd_display_custom_fields_in_admin( $order ) {
		echo '<p><strong>' . __( 'MAC‑address', 'checkout-designer' ) . ':</strong> ' . esc_html( $order->get_meta( '_cd_mac' ) ) . '</p>';
		echo '<p><strong>' . __( 'Adult Content', 'checkout-designer' ) . ':</strong> ' . esc_html( $order->get_meta( '_cd_adult' ) ) . '</p>';
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