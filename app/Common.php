<?php
/**
 * All common functions to load in both admin and front
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
}