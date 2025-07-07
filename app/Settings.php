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
			$gateway_options[ $gateway_id ] = $gateway->get_title();
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
						// 'sample_text' => [
						// 	'id'        => 'sample_text',
						// 	'label'     => __( 'Text Field', 'checkout-designer' ),
						// 	'type'      => 'text',
						// 	'desc'      => __( 'This is a text field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'default'   => 'Hello World!',
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_number' => [
						// 	'id'      => 'sample_number',
						// 	'label'     => __( 'Number Field', 'checkout-designer' ),
						// 	'type'      => 'number',
						// 	'desc'      => __( 'This is a number field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'default'   => 10,
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_email' => [
						// 	'id'      => 'sample_email',
						// 	'label'     => __( 'Email Field', 'checkout-designer' ),
						// 	'type'      => 'email',
						// 	'desc'      => __( 'This is an email field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'default'   => 'john@doe.com',
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_url' => [
						// 	'id'      => 'sample_url',
						// 	'label'     => __( 'URL Field', 'checkout-designer' ),
						// 	'type'      => 'url',
						// 	'desc'      => __( 'This is a url field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'default'   => 'https://johndoe.com',
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_password' => [
						// 	'id'      => 'sample_password',
						// 	'label'     => __( 'Password Field', 'checkout-designer' ),
						// 	'type'      => 'password',
						// 	'desc'      => __( 'This is a password field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// 	'default'   => 'uj34h'
						// ],
						// 'sample_textarea' => [
						// 	'id'      => 'sample_textarea',
						// 	'label'     => __( 'Textarea Field', 'checkout-designer' ),
						// 	'type'      => 'textarea',
						// 	'desc'      => __( 'This is a textarea field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'columns'   => 24,
						// 	'rows'      => 5,
						// 	'default'   => 'lorem ipsum dolor sit amet',
						// 	'readonly'  => false, // true|false
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_radio' => [
						// 	'id'      => 'sample_radio',
						// 	'label'     => __( 'Radio Field', 'checkout-designer' ),
						// 	'type'      => 'radio',
						// 	'desc'      => __( 'This is a radio field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'options'   => [
						// 		'item_1'  => 'Item One',
						// 		'item_2'  => 'Item Two',
						// 		'item_3'  => 'Item Three',
						// 	],
						// 	'default'   => 'item_2',
						// 	'disabled'  => false, // true|false
						// ],
						// 'sample_multiselect' => [
						// 	'id'      => 'sample_multiselect',
						// 	'label'     => __( 'Multi-select Field', 'checkout-designer' ),
						// 	'type'      => 'select',
						// 	'desc'      => __( 'This is a multiselect field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'options'   => [
						// 		'option_1'  => 'Option One',
						// 		'option_2'  => 'Option Two',
						// 		'option_3'  => 'Option Three',
						// 	],
						// 	'default'   => [ 'option_2', 'option_3' ],
						// 	'disabled'  => false, // true|false
						// 	'multiple'  => true, // true|false
						// ],
						// 'sample_checkbox' => [
						// 	'id'      => 'sample_checkbox',
						// 	'label'     => __( 'Checkbox Field', 'checkout-designer' ),
						// 	'type'      => 'checkbox',
						// 	'desc'      => __( 'This is a checkbox field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'default'   => 'on'
						// ],
						// 'sample_multicheck' => [
						// 	'id'      => 'sample_multicheck',
						// 	'label'     => __( 'Multi-check Field', 'checkout-designer' ),
						// 	'type'      => 'checkbox',
						// 	'desc'      => __( 'This is a multi-check field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'options'   => [
						// 		'option_1'  => 'Option One',
						// 		'option_2'  => 'Option Two',
						// 		'option_3'  => 'Option Three',
						// 	],
						// 	'default'   => [ 'option_2' ],
						// 	'disabled'  => false, // true|false
						// 	'multiple'  => true, // true|false
						// ],
						// 'sample_switch' => [
						// 	'id'      => 'sample_switch',
						// 	'label'     => __( 'Switch Field', 'checkout-designer' ),
						// 	'type'      => 'switch',
						// 	'desc'      => __( 'This is a switch field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'default'   => 'on'
						// ],
						// 'sample_multiswitch' => [
						// 	'id'      => 'sample_multiswitch',
						// 	'label'     => __( 'Multi-switch Field', 'checkout-designer' ),
						// 	'type'      => 'switch',
						// 	'desc'      => __( 'This is a multi-switch field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'options'   => [
						// 		'option_1'  => 'Option One',
						// 		'option_2'  => 'Option Two',
						// 		'option_3'  => 'Option Three',
						// 	],
						// 	'default'   => [ 'option_2' ],
						// 	'disabled'  => false, // true|false
						// 	'multiple'  => true, // true|false
						// ],
						// 'sample_range' => [
						// 	'id'      => 'sample_range',
						// 	'label'     => __( 'Range Field', 'checkout-designer' ),
						// 	'type'      => 'range',
						// 	'desc'      => __( 'This is a range field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'min'		=> 0,
						// 	'max'		=> 16,
						// 	'step'		=> 2,
						// 	'default'   => 4,
						// ],
						// 'sample_date' => [
						// 	'id'      => 'sample_date',
						// 	'label'     => __( 'Date Field', 'checkout-designer' ),
						// 	'type'      => 'date',
						// 	'desc'      => __( 'This is a date field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'default'   => '1971-12-16',
						// ],
						// 'sample_time' => [
						// 	'id'      => 'sample_time',
						// 	'label'     => __( 'Time Field', 'checkout-designer' ),
						// 	'type'      => 'time',
						// 	'desc'      => __( 'This is a time field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'default'   => '15:45',
						// ],
						// 'sample_color' => [
						// 	'id'      => 'sample_color',
						// 	'label'     => __( 'Color Field', 'checkout-designer' ),
						// 	'type'      => 'color',
						// 	'desc'      => __( 'This is a color field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	// 'default'   => '#f0f'
						// ],
						// 'sample_wysiwyg' => [
						// 	'id'      => 'sample_wysiwyg',
						// 	'label'     => __( 'WYSIWYG Field', 'checkout-designer' ),
						// 	'type'      => 'wysiwyg',
						// 	'desc'      => __( 'This is a wysiwyg field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'width'     => '100%',
						// 	'rows'      => 5,
						// 	'teeny'     => true,
						// 	'text_mode'     => false, // true|false
						// 	'media_buttons' => false, // true|false
						// 	'default'       => 'Hello World'
						// ],
						// 'sample_file' => [
						// 	'id'      => 'sample_file',
						// 	'label'     => __( 'File Field' ),
						// 	'type'      => 'file',
						// 	'upload_button'     => __( 'Choose File', 'checkout-designer' ),
						// 	'select_button'     => __( 'Select File', 'checkout-designer' ),
						// 	'desc'      => __( 'This is a file field.', 'checkout-designer' ),
						// 	// 'class'     => '',
						// 	'disabled'  => false, // true|false
						// 	'default'   => 'http://example.com/sample/file.txt'
						// ],
					]
				],
				// 'checkout-designer_advanced'	=> [
				// 	'id'        => 'checkout-designer_advanced',
				// 	'label'     => __( 'Advanced Settings', 'checkout-designer' ),
				// 	'icon'      => 'dashicons-admin-generic',
				// 	// 'color'		=> '#d30c5c',
				// 	'sticky'	=> false,
				// 	'fields'    => [
				// 		'sample_select3' => [
				// 			'id'      => 'sample_select3',
				// 			'label'     => __( 'Select with Chosen', 'checkout-designer' ),
				// 			'type'      => 'select',
				// 			'desc'      => __( 'jQuery Chosen plugin enabled. <a href="https://harvesthq.github.io/chosen/" target="_blank">[See more]</a>', 'checkout-designer' ),
				// 			// 'class'     => '',
				// 			'options'   => Helper::get_posts( [ 'post_type' => 'page' ], false, true ),
				// 			'default'   => 2,
				// 			'disabled'  => false, // true|false
				// 			'multiple'  => false, // true|false
				// 			'chosen'    => true
				// 		],
				// 		'sample_multiselect3' => [
				// 			'id'      => 'sample_multiselect3',
				// 			'label'     => __( 'Multi-select with Chosen', 'checkout-designer' ),
				// 			'type'      => 'select',
				// 			'desc'      => __( 'jQuery Chosen plugin enabled. <a href="https://harvesthq.github.io/chosen/" target="_blank">[See more]</a>', 'checkout-designer' ),
				// 			// 'class'     => '',
				// 			'options'   => [
				// 				'option_1'  => 'Option One',
				// 				'option_2'  => 'Option Two',
				// 				'option_3'  => 'Option Three',
				// 			],
				// 			'default'   => [ 'option_2', 'option_3' ],
				// 			'disabled'  => false, // true|false
				// 			'multiple'  => true, // true|false
				// 			'chosen'    => true
				// 		],
				// 		'sample_select2' => [
				// 			'id'      => 'sample_select2',
				// 			'label'     => __( 'Select with Select2', 'checkout-designer' ),
				// 			'type'      => 'select',
				// 			'desc'      => __( 'jQuery Select2 plugin enabled. <a href="https://select2.org/" target="_blank">[See more]</a>', 'checkout-designer' ),
				// 			// 'class'     => '',
				// 			'options'   => [
				// 				'option_1'  => 'Option One',
				// 				'option_2'  => 'Option Two',
				// 				'option_3'  => 'Option Three',
				// 			],
				// 			'default'   => 'option_2',
				// 			'disabled'  => false, // true|false
				// 			'multiple'  => false, // true|false
				// 			'select2'   => true
				// 		],
				// 		'sample_multiselect2' => [
				// 			'id'      => 'sample_multiselect2',
				// 			'label'     => __( 'Multi-select with Select2', 'checkout-designer' ),
				// 			'type'      => 'select',
				// 			'desc'      => __( 'jQuery Select2 plugin enabled. <a href="https://select2.org/" target="_blank">[See more]</a>', 'checkout-designer' ),
				// 			// 'class'     => '',
				// 			'options'   => [
				// 				'option_1'  => 'Option One',
				// 				'option_2'  => 'Option Two',
				// 				'option_3'  => 'Option Three',
				// 			],
				// 			'default'   => [ 'option_2', 'option_3' ],
				// 			'disabled'  => false, // true|false
				// 			'multiple'  => true, // true|false
				// 			'select2'   => true
				// 		],
				// 		'sample_group' => [
				// 			'id'      => 'sample_group',
				// 			'label'     => __( 'Field Group' ),
				// 			'type'      => 'group',
				// 			'desc'      => __( 'A group of fields.', 'checkout-designer' ),
				// 			'items'     => [
				// 				'sample_group_select1' => [
				// 					'id'      => 'sample_group_select1',
				// 					'label'     => __( 'First Item', 'checkout-designer' ),
				// 					'type'      => 'select',
				// 					'options'   => [
				// 						'option_1'  => 'Option One',
				// 						'option_2'  => 'Option Two',
				// 						'option_3'  => 'Option Three',
				// 					],
				// 					'default'   => 'option_2',
				// 				],
				// 				'sample_group_select2' => [
				// 					'id'      => 'sample_group_select2',
				// 					'label'     => __( 'Second Item', 'checkout-designer' ),
				// 					'type'      => 'select',
				// 					'options'   => [
				// 						'option_1'  => 'Option One',
				// 						'option_2'  => 'Option Two',
				// 						'option_3'  => 'Option Three',
				// 					],
				// 					'default'   => 'option_1',
				// 				],
				// 				'sample_group_select3' => [
				// 					'id'      => 'sample_group_select3',
				// 					'label'     => __( 'Third Item', 'checkout-designer' ),
				// 					'type'      => 'select',
				// 					'options'   => [
				// 						'option_1'  => 'Option One',
				// 						'option_2'  => 'Option Two',
				// 						'option_3'  => 'Option Three',
				// 					],
				// 					'default'   => 'option_3',
				// 				],
				// 			],
				// 		],
				// 		'sample_conditional' => [
				// 			'id'      => 'sample_conditional',
				// 			'label'     => __( 'Conditional Field', 'checkout-designer' ),
				// 			'type'      => 'select',
				// 			'options'   => [
				// 				'option_1'  => 'Option One',
				// 				'option_2'  => 'Option Two',
				// 				'option_3'  => 'Option Three',
				// 			],
				// 			'desc'      => __( 'Shows up if the third option in the  \'Field Group\' above is set as \'Option Two\'', 'checkout-designer' ),
				// 			'default'   => 'option_2',
				// 			'condition'	=> [
				// 				'key'		=> 'sample_group_select3',
				// 				'value'		=> 'option_2',
				// 				'compare'	=> '==',
				// 			]
				// 		],
				// 		'sample_repeater'	=> [
				// 			'id'		=> 'sample_repeater',
				// 			'label'		=> __( 'Sample Repeater' ),
				// 			'type'		=> 'repeater',
				// 			'items'		=> [
				// 				'text_repeat' => [
				// 					'id'		=> 'text_repeat',
				// 					'label'		=> __( 'Repeat Text Field', 'checkout-designer' ),
				// 					'type'		=> 'text',
				// 					'placeholder'=> __( 'Repeat Text', 'checkout-designer' ),
				// 					'desc'		=> __( 'This field will be repeated.', 'checkout-designer' ),
				// 				],
				// 				'number_repeat' => [
				// 					'id'		=> 'number_repeat',
				// 					'label'		=> __( 'Repeat Number Field', 'checkout-designer' ),
				// 					'type'		=> 'number',
				// 					'placeholder'=> __( 'Repeat Number', 'checkout-designer' ),
				// 					'desc'		=> __( 'This field will be repeated.', 'checkout-designer' ),
				// 				],
				// 			]
				// 		],
				// 		'sample_tabs' => [
				// 			'id'      => 'sample_tabs',
				// 			'label'     => __( 'Sample Tabs' ),
				// 			'type'      => 'tabs',
				// 			'items'     => [
				// 				'sample_tab1' => [
				// 					'id'      => 'sample_tab1',
				// 					'label'     => __( 'First Tab', 'checkout-designer' ),
				// 					'fields'    => [
				// 						'sample_tab1_email' => [
				// 							'id'      => 'sample_tab1_email',
				// 							'label'     => __( 'Tab Email Field', 'checkout-designer' ),
				// 							'type'      => 'email',
				// 							'desc'      => __( 'This is an email field.', 'checkout-designer' ),
				// 							// 'class'     => '',
				// 							'default'   => 'john@doe.com',
				// 							'readonly'  => false, // true|false
				// 							'disabled'  => false, // true|false
				// 						],
				// 						'sample_tab1_url' => [
				// 							'id'      => 'sample_tab1_url',
				// 							'label'     => __( 'Tab URL Field', 'checkout-designer' ),
				// 							'type'      => 'url',
				// 							'desc'      => __( 'This is a url field.', 'checkout-designer' ),
				// 							// 'class'     => '',
				// 							'default'   => 'https://johndoe.com',
				// 							'readonly'  => false, // true|false
				// 							'disabled'  => false, // true|false
				// 						],
				// 					],
				// 				],
				// 				'sample_tab2' => [
				// 					'id'      => 'sample_tab2',
				// 					'label'     => __( 'Second Tab', 'checkout-designer' ),
				// 					'fields'    => [
				// 						'sample_tab2_text' => [
				// 							'id'        => 'sample_tab2_text',
				// 							'label'     => __( 'Tab Text Field', 'checkout-designer' ),
				// 							'type'      => 'text',
				// 							'desc'      => __( 'This is a text field.', 'checkout-designer' ),
				// 							// 'class'     => '',
				// 							'default'   => 'Hello World!',
				// 							'readonly'  => false, // true|false
				// 							'disabled'  => false, // true|false
				// 						],
				// 						'sample_tab2_number' => [
				// 							'id'      => 'sample_tab2_number',
				// 							'label'     => __( 'Tab Number Field', 'checkout-designer' ),
				// 							'type'      => 'number',
				// 							'desc'      => __( 'This is a number field.', 'checkout-designer' ),
				// 							// 'class'     => '',
				// 							'default'   => 10,
				// 							'readonly'  => false, // true|false
				// 							'disabled'  => false, // true|false
				// 						],
				// 					],
				// 				],
				// 			],
				// 		],
				// 	]
				// ],
				// 'checkout-designer_tools'	=> [
				// 	'id'        => 'checkout-designer_tools',
				// 	'label'     => __( 'Tools', 'checkout-designer' ),
				// 	'icon'      => 'dashicons-hammer',
				// 	'sticky'	=> false,
				// 	'fields'    => [
				// 		'enable_debug' => [
				// 			'id'      	=> 'enable_debug',
				// 			'label'     => __( 'Enable Debug', 'checkout-designer' ),
				// 			'type'      => 'switch',
				// 			'desc'      => __( 'Enable this if you face any CSS or JS related issues.', 'checkout-designer' ),
				// 			'disabled'  => false,
				// 		],
				// 		'report' => [
				// 			'id'      => 'report',
				// 			'label'     => __( 'Report', 'checkout-designer' ),
				// 			'type'      => 'textarea',
				// 			'desc'     	=> '<button id="checkout-designer_report-copy" class="button button-primary"><span class="dashicons dashicons-admin-page"></span></button>',
				// 			'columns'   => 24,
				// 			'rows'      => 10,
				// 			'default'   => json_encode( $site_config, JSON_PRETTY_PRINT ),
				// 			'readonly'  => true,
				// 		],
				// 	]
				// ],
				// 'checkout-designer_table' => [
				// 	'id'        => 'checkout-designer_table',
				// 	'label'     => __( 'Table', 'checkout-designer' ),
				// 	'icon'      => 'dashicons-editor-table',
				// 	// 'color'		=> '#28c9ee',
				// 	'hide_form'	=> true,
				// 	'template'  => Checkout_Designer_DIR . '/views/settings/table.php',
				// ],
			],
		];

		new Settings_API( $settings );
	}
}