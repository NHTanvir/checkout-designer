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
		if ( ! WC()->session->get('chosen_payment_method') ) {
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
			if ( ! empty( $available_gateways ) ) {
				$first_gateway = current( $available_gateways );
				WC()->session->set( 'chosen_payment_method', $first_gateway->id );
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
}

