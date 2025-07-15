<?php
/**
 * All settings related functions
 */
namespace Codexpert\CheckoutDesigner\App;
use Codexpert\CheckoutDesigner\Helper;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Settings as Settings_API;

/**
 * @package Plugin
 * @subpackage Settings
 * @author Codexpert <hi@codexpert.io>
 */
class Settings extends Base {

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
	
	public function init_menu() {
		$gateway_options = [];

		$gateways = WC()->payment_gateways->payment_gateways();

		foreach ( $gateways as $gateway_id => $gateway ) {
			 $gateway_options[ $gateway_id ] = $gateway->method_title;
		}
		
		$settings = [
			'id'            => $this->slug,
			'label'         => $this->name,
			'title'         => "{$this->name} v{$this->version}",
			'header'        => $this->name,
			// 'parent'     => 'woocommerce',
			// 'priority'   => 10,
			// 'capability' => 'manage_options',
			// 'icon'       => 'dashicons-wordpress',
			// 'position'   => 25,
			// 'topnav'	=> true,
			'sections'      => [
				'checkout-designer_basic'	=> [
					'id'        => 'checkout-designer_basic',
					'label'     => __( 'Basic Settings', 'checkout-designer' ),
					'icon'      => 'dashicons-admin-tools',
					// 'color'		=> '#4c3f93',
					'sticky'	=> false,
					'fields'    => [
						// 'primary_color' => [
						// 	'id'      => 'primary_color',
						// 	'label'     => __( 'Primary Color', 'checkout-designer' ),
						// 	'type'      => 'color',
						// 	'desc'      => __( 'Pick a primary color.', 'checkout-designer' ),
						// ],
						// 'secondary_color' => [
						// 	'id'      => 'secondary_color',
						// 	'label'     => __( 'Secondary Color', 'checkout-designer' ),
						// 	'type'      => 'color',
						// 	'desc'      => __( 'Pick a secondary color.', 'checkout-designer' ),
						// ],
						'crypto_gateway' => [
							'id'      => 'crypto_gateway',
							'label'     => __( 'Crypto Gateway', 'checkout-designer' ),
							'type'      => 'select',
							'desc'      => __( 'Select a crypto gateway.', 'checkout-designer' ),
							'options'   => $gateway_options,
							'disabled'  => false, // true|false
							'multiple'  => false, // true|false
						],
						'crypto_icon' => [
							'id'      => 'crypto_icon',
							'label'     => __( 'Crypto Icon', 'checkout-designer' ),
							'type'      => 'file',
							'upload_button'     => __( 'Upload File', 'checkout-designer' ),
							'select_button'     => __( 'Select File', 'checkout-designer' ),
							'desc'      => __( 'Upload crypto icon.', 'checkout-designer' ),
							// 'class'     => '',
							'disabled'  => false, // true|false
						],
						'checkout_heading' => [
							'id'        => 'checkout_heading',
							'label'     => __( 'checkout Heading text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is checkout Heading text field.', 'checkout-designer' ),
						 	'default'   => 'Varukorg'
						],
						'addon_heading' => [
							'id'        => 'addon_heading',
							'label'     => __( 'addon Heading text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is addon Heading text field.', 'checkout-designer' ),
						 	'default'   => 'Valfritt Extra konton'
						 ],
						'extra_accounts_text' => [
							'id'      => 'extra_accounts_text',
							'label'   => __( 'Extra accounts info text', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Text shown above addon options.', 'checkout-designer' ),
							'default' => 'Du kan lägga till hur många extra konton du vill.',
						],
						'add_button_text' => [
							'id'      => 'add_button_text',
							'label'   => __( 'Add button text', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Text for the “Add” button.', 'checkout-designer' ),
							'default' => 'Lägg till',
						],
						'payment_heading' => [
							'id'      => 'payment_heading',
							'label'   => __( 'Payment section heading', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Heading for the payment section.', 'checkout-designer' ),
							'default' => 'Betalning',
						],
						'table_heading' => [
							'id'      => 'table_heading',
							'label'   => __( 'table section heading', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Heading for the table section.', 'checkout-designer' ),
							'default' => 'Fakturauppgifter',
						],
						'method_label' => [
							'id'      => 'method_label',
							'label'   => __( 'Payment method label', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Label for payment method selection.', 'checkout-designer' ),
							'default' => 'Metod',
						],
						'misc_fee_title' => [
							'id'      => 'misc_fee_title',
							'label'   => __( 'Misc Fee Title', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Title for the misc fees section.', 'checkout-designer' ),
							'default' => 'Total avgifter',
						],
						'misc_fee_desc' => [
							'id'      => 'misc_fee_desc',
							'label'   => __( 'Misc Fee Description', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Description for misc fees.', 'checkout-designer' ),
							'default' => 'Avgift beroende på börs:',
						],
						'misc_fee_amount' => [
							'id'      => 'misc_fee_amount',
							'label'   => __( 'Misc Fee Amount', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Amount range for misc fees.', 'checkout-designer' ),
							'default' => '100-400 SEK',
						],
						'crypto_fee_title' => [
							'id'      => 'crypto_fee_title',
							'label'   => __( 'Crypto Fee Title', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Title for crypto fees.', 'checkout-designer' ),
							'default' => 'Kortavgift',
						],
						'crypto_fee_amount' => [
							'id'      => 'crypto_fee_amount',
							'label'   => __( 'Crypto Fee Amount', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Fee amount for crypto payments.', 'checkout-designer' ),
							'default' => '10%',
						],
						'crypto_message_success' => [
							'id'      => 'crypto_message_success',
							'label'   => __( 'Crypto Success Message', 'checkout-designer' ),
							'type'    => 'textarea',
							'desc'    => __( 'Success message shown when paying with crypto.', 'checkout-designer' ),
							'default' => 'När du betalar med Krypto så skickar du valfri valuta från valfri plånbok eller från någon utav de kryptobörserna vi har guider för.',
						],
						'crypto_message_warning' => [
							'id'      => 'crypto_message_warning',
							'label'   => __( 'Crypto Warning Message', 'checkout-designer' ),
							'type'    => 'textarea',
							'desc'    => __( 'Warning message shown for crypto payments.', 'checkout-designer' ),
							'default' => 'OBS! Du ansvarar för avgifterna som plånboken/börsen du skickar ifrån tar. Skickar du ett för lågt belopp så går din beställning inte igenom!',
						],
						'crypto_button_text' => [
							'id'      => 'crypto_button_text',
							'label'   => __( 'Crypto Button Text', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Button text for crypto payments.', 'checkout-designer' ),
							'default' => 'Betala med krypto',
						],
						'card_button_text' => [
							'id'      => 'card_button_text',
							'label'   => __( 'Card Button Text', 'checkout-designer' ),
							'type'    => 'text',
							'desc'    => __( 'Button text for card payments.', 'checkout-designer' ),
							'default' => 'Betala med kort',
						],
					]
				],
				
			],
		];

		new Settings_API( $settings );
	}
}