<?php
/**
 * Plugin Name: Checkout Designer
 * Description: Checkout designer
 * Plugin URI: https://goodtechies.com
 * Author: Goodtechies
 * Author URI: https://goodtechies.com
 * Version: 0.9
 * Text Domain: checkout-designer
 * Domain Path: /languages
 */

namespace Codexpert\CheckoutDesigner;
use Codexpert\Plugin\Notice;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Codexpert <hi@codexpert.io>
 */
final class Plugin {
	
	/**
	 * Plugin instance
	 * 
	 * @access private
	 * 
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * The constructor method
	 * 
	 * @access private
	 * 
	 * @since 0.9
	 */
	private function __construct() {
		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Runs actual hooks
		 */
		$this->hook();
	}

	/**
	 * Includes files
	 * 
	 * @access private
	 * 
	 * @uses composer
	 * @uses psr-4
	 */
	private function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 * 
	 * @access private
	 * 
	 * @uses get_plugin_data
	 * @uses plugin_basename
	 */
	private function define() {

		/**
		 * Define some constants
		 * 
		 * @since 0.9
		 */
		define( 'Checkout_Designer', __FILE__ );
		define( 'Checkout_Designer_DIR', dirname( Checkout_Designer ) );
		define( 'Checkout_Designer_ASSET', plugins_url( 'assets', Checkout_Designer ) );
		define( 'Checkout_Designer_DEBUG', apply_filters( 'checkout-designer_debug', true ) );

		/**
		 * The plugin data
		 * 
		 * @since 0.9
		 * @var $plugin
		 */
		$this->plugin					= get_plugin_data( Checkout_Designer );
		$this->plugin['basename']		= plugin_basename( Checkout_Designer );
		$this->plugin['file']			= Checkout_Designer;
		$this->plugin['server']			= apply_filters( 'checkout-designer_server', 'https://codexpert.io/dashboard' );
		$this->plugin['min_php']		= '5.6';
		$this->plugin['min_wp']			= '4.0';
		$this->plugin['icon']			= Checkout_Designer_ASSET . '/img/icon.png';
		$this->plugin['depends']		= [ 'woocommerce/woocommerce.php' => 'WooCommerce' ];
		
	}

	/**
	 * Hooks
	 * 
	 * @access private
	 * 
	 * Executes main plugin features
	 *
	 * To add an action, use $instance->action()
	 * To apply a filter, use $instance->filter()
	 * To register a shortcode, use $instance->register()
	 * To add a hook for logged in users, use $instance->priv()
	 * To add a hook for non-logged in users, use $instance->nopriv()
	 * 
	 * @return void
	 */
	private function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 */
			$admin = new App\Admin( $this->plugin );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );

			/**
			 * Settings related hooks
			 */
			$settings = new App\Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu', 9999999999 );

		else : // ! is_admin() ?

			/**
			 * Front facing hooks
			 */
			$front = new App\Front( $this->plugin );
			$front->action( 'wp_head', 'head' );
			$front->action( 'wp_footer', 'modal' );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );
			$front->filter( 'body_class', 'add_payment_method_class' );
			$front->filter( 'woocommerce_gateway_icon', 'payment_gateway_icon', 30, 2 );

			/**
			 * Shortcode related hooks
			 */
			$shortcode = new App\Shortcode( $this->plugin );
			$shortcode->register( 'my_shortcode', 'my_shortcode' );

		endif;
		
		/**
		 * Common hooks
		 *
		 * Executes on both the admin area and front area
		 */
		$common = new App\Common( $this->plugin );
		$common->action( 'woocommerce_before_checkout_form', 'set_default_payment_method', 10 );
		$common->action( 'woocommerce_checkout_before_order_review', 'custom_checkout_columns_start' );
		$common->action( 'woocommerce_checkout_after_order_review', 'custom_checkout_columns_end' );
		$common->action( 'woocommerce_review_order_before_submit', 'custom_payment_message' );
		$common->action( 'template_redirect', 'redirect_cart_to_checkout' );
		$common->filter( 'woocommerce_checkout_fields', 'make_all_checkout_fields_optional' );
		$common->action( 'woocommerce_checkout_process', 'cd_map_custom_to_billing' );
		$common->action( 'woocommerce_after_checkout_validation', 'remove_terms_error', 10, 2 );
		$common->filter( 'woocommerce_order_button_text', 'change_woocommerce_order_button_text', 9999999999 );
		$common->filter( 'woocommerce_add_cart_item_data', 'add_custom_data_to_cart_item', 10, 2 );
		$common->action( 'woocommerce_checkout_create_order', 'cd_save_custom_fields_to_order_meta', 20, 2 );
		$common->action( 'woocommerce_checkout_create_order_line_item', 'save_custom_data_to_order_meta', 10, 4 );
		$common->action( 'woocommerce_admin_order_data_after_billing_address', 'cd_display_custom_fields_in_admin', 10, 1 );
		$common->action( 'woocommerce_before_order_itemmeta', 'display_custom_order_item_meta_in_admin', 10, 3 );


		/**
		 * AJAX related hooks
		 */
		$ajax = new App\AJAX( $this->plugin );
		$ajax->all( 'update_cart_totals_on_payment_method_change', 'update_cart_totals_on_payment_method_change' );
		$ajax->all( 'update_table_on_payment_method_change', 'update_table_on_payment_method_change' );
		$ajax->all( 'woocommerce_update_cart_item_qty', 'woocommerce_update_cart_item_qty' );
		$ajax->all( 'add_addon_to_cart', 'add_addon_to_cart' );

		//remove duplicate payment div
		add_action('wp', function() {
			remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
		});
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @access public
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @access public
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 * 
	 * @access public
	 * 
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();