<?php
/**
 * All admin facing functions
 */
namespace Codexpert\CheckoutDesigner\App;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Metabox;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Codexpert <hi@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'checkout-designer', false, Checkout_Designer_DIR . '/languages/' );
	}

	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'Checkout_Designer_DEBUG' ) && Checkout_Designer_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", Checkout_Designer ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", Checkout_Designer ), [ 'jquery' ], $this->version, true );
	}

	public function modal() {
		echo '
		<div id="checkout-designer-modal" style="display: none">
			<img id="checkout-designer-modal-loader" src="' . esc_attr( Checkout_Designer_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}
}