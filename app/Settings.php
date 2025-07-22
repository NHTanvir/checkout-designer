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
		$gateway_options 	= [];
		$gateways 			= WC()->payment_gateways->payment_gateways();

		foreach ( $gateways as $gateway_id => $gateway ) {
			 $gateway_options[ $gateway_id ] = $gateway->method_title;
		}
		
		$settings = [
			'id'            => $this->slug,
			'label'         => $this->name,
			'title'         => $this->name,
			'header'        => $this->name,
			'sections'      => [
				'checkout_designer_user_info'	=> [
					'id'        => 'checkout_designer_user_info',
					'label'     => __( 'User info texts', 'checkout-designer' ),
					'icon'      => 'dashicons-admin-tools',
					'sticky'	=> false,
					'fields'    => [
						'name' => [
							'id'        => 'name',
							'label'     => __( 'Name text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Name text field.', 'checkout-designer' ),
						 	'default'   => 'Name'
						],
						'phone' => [
							'id'        => 'phone',
							'label'     => __( 'Phone text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Phone text field.', 'checkout-designer' ),
						 	'default'   => 'Phone(optional)'
						],
						'email' => [
							'id'        => 'email',
							'label'     => __( 'Email text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Email text field.', 'checkout-designer' ),
						 	'default'   => 'Email'
						],
						'mac' => [
							'id'        => 'mac',
							'label'     => __( 'MAC text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is MAC text field.', 'checkout-designer' ),
						 	'default'   => 'MAC‑address(optional)'
						],
						'mac_desc' => [
							'id'        => 'mac_desc',
							'label'     => __( 'MAC desc text', 'checkout-designer' ),
							'type'      => 'textarea',
							'desc'      => __( 'This is MAC desc text field.', 'checkout-designer' ),
						 	'default'   => 'Only use this field if you have a Formuler, TVIP, MAG or Smart STP app. Only accepts MAC that start with 10:27, 00:1A or 00:1E.'
						],
						'adult' => [
							'id'        => 'adult',
							'label'     => __( 'Adult text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Adult text field.', 'checkout-designer' ),
						 	'default'   => 'Adult content'
						],
						'choose' => [
							'id'        => 'choose',
							'label'     => __( 'Choose text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Choose text field.', 'checkout-designer' ),
						 	'default'   => '--Please choose--'
						],
						'yes' => [
							'id'        => 'yes',
							'label'     => __( 'Adult Yes text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Adult Yes text field.', 'checkout-designer' ),
						 	'default'   => 'Yes'
						],
						'no' => [
							'id'        => 'no',
							'label'     => __( 'Adult No text', 'checkout-designer' ),
							'type'      => 'text',
							'desc'      => __( 'This is Adult No text field.', 'checkout-designer' ),
						 	'default'   => 'No'
						]
					]
				],
				'checkout_designer_table'	=> [
					'id'        => 'checkout_designer_table',
					'label'     => __( 'Others texts', 'checkout-designer' ),
					'icon'      => 'dashicons-admin-tools',
					'sticky'	=> false,
					'fields'    => [
						'crypto_gateway' => [
							'id'      => 'crypto_gateway',
							'label'     => __( 'Crypto Gateway', 'checkout-designer' ),
							'type'      => 'select',
							'desc'      => __( 'Select a crypto gateway.', 'checkout-designer' ),
							'options'   => $gateway_options,
							'disabled'  => false,
							'multiple'  => false,
						],
						'crypto_icon' => [
							'id'      => 'crypto_icon',
							'label'     => __( 'Crypto Icon', 'checkout-designer' ),
							'type'      => 'file',
							'upload_button'     => __( 'Upload File', 'checkout-designer' ),
							'select_button'     => __( 'Select File', 'checkout-designer' ),
							'desc'      => __( 'Upload crypto icon.', 'checkout-designer' ),
							// 'class'     => '',
							'disabled'  => false,
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