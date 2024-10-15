<?php
/**
 * Builder preset fields.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return apply_filters( 'atbdp_form_preset_widgets', array(
	'title' => [
		'label'    => __( 'Title', 'directorist' ),
		'icon'     => 'las la-text-height',
		'canTrash' => false,
		'options'  => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'listing_title',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Title',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => true,
			],
		],
	],

	'description' => [
		'label'   => __( 'Description', 'directorist' ),
		'icon'    => 'uil uil-align-left',
		'show'    => true,
		'options' => [
			'type' => [
				'type'    => 'select',
				'label'   => __( 'Type', 'directorist' ),
				'value'   => 'wp_editor',
				'options' => [
					[
						'label' => __('Textarea', 'directorist'),
						'value' => 'textarea',
					],
					[
						'label' => __('WP Editor', 'directorist'),
						'value' => 'wp_editor',
					],
				]
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'listing_content',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Description',
			],
			'placeholder' => [
				'type'    => 'text',
				'label'   => __( 'Placeholder', 'directorist' ),
				'value'   => '',
				'show_if' => [
					'where'      => "self.type",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'textarea'],
					],
				],
			],
			'required' => [
				'type'  => 'toggle',
				'name'  => 'required',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		]
	],

	'tagline' => [
		'label'   => __( 'Tagline', 'directorist' ),
		'icon'    => 'uil uil-text-fields',
		'show'    => true,
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'tagline',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Tagline',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],

		],
	],

	'pricing' => [
		'label'   => __( 'Pricing', 'directorist' ),
		'icon'    => 'uil uil-bill',
		'options' => [
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'pricing',
				'rules' => [
					'unique'   => true,
					'required' => false,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Pricing',
			],
			'pricing_type' => [
				'type'  => 'select',
				'label' => __( 'Select Pricing Type', 'directorist' ),
				'value' => 'both',
				  // 'show-default-option' => true,
				'options' => [
					['value' => 'both', 'label' => 'Both'],
					['value' => 'price_unit', 'label' => 'Price Unit'],
					['value' => 'price_range', 'label' => 'Price Range'],
				],
			],
			'price_range_label' => [
				'type'    => 'text',
				'show_if' => [
					'where'      => "self.pricing_type",
					'compare'    => 'or',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'both'],
						['key' => 'value', 'compare' => '=', 'value' => 'price_range'],
					],
				],
				'label' => __( 'Price Range Label', 'directorist' ),
				'value' => 'Price Range',
			],
			'price_range_placeholder' => [
				'type'    => 'text',
				'show_if' => [
					'where'      => "self.pricing_type",
					'compare'    => 'or',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'both'],
						['key' => 'value', 'compare' => '=', 'value' => 'price_range'],
					],
				],
				'label' => __( 'Price Range Placeholder', 'directorist' ),
				'value' => 'Select Price Range',
			],
			'price_unit_field_type' => [
				'type'    => 'select',
				'label'   => __( 'Price Unit Field Type', 'directorist' ),
				'show_if' => [
					'where'      => "self.pricing_type",
					'compare'    => 'or',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'both'],
						['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
					],
				],
				'value'   => 'number',
				'options' => [
					['value' => 'number', 'label' => 'Number',],
					['value' => 'text', 'label' => 'Text',],
				],
			],
			'price_unit_field_label' => [
				'type'    => 'text',
				'label'   => __( 'Price Unit Field label', 'directorist' ),
				'show_if' => [
					'where'      => "self.pricing_type",
					'compare'    => 'or',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'both'],
						['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
					],
				],
				'value' => 'Price [USD]',
			],
			'price_unit_field_placeholder' => [
				'type'    => 'text',
				'label'   => __( 'Price Unit Field Placeholder', 'directorist' ),
				'show_if' => [
					'where'      => "self.pricing_type",
					'compare'    => 'or',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'both'],
						['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
					],
				],
				'value' => 'Price of this listing. Eg. 100',
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
			'modules' => [
				'type'  => 'hidden',
				'value' => [
					'price_unit' => [
						'label'     => __( 'Price Unit', 'directorist' ),
						'type'      => 'text',
						'field_key' => 'price_unit',
					],
					'price_range' => [
						'label'     => __( 'Price Range', 'directorist' ),
						'type'      => 'text',
						'field_key' => 'price_range',
					],
				],
			],
		]
	],

	'excerpt' => [
		'label'   => __( 'Excerpt', 'directorist' ),
		'icon'    => 'uil uil-paragraph',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'textarea',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'excerpt',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Excerpt',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'location' => [
		'label'   => 'Location',
		'icon'    => 'uil uil-map-marker',
		'options' => [
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'tax_input[at_biz_dir-location][]',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Location',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'type' => [
				'type'    => 'radio',
				'value'   => 'multiple',
				'label'   => __( 'Selection Type', 'directorist' ),
				'options' => [
					[
						'label' => __('Single Selection', 'directorist'),
						'value' => 'single',
					],
					[
						'label' => __('Multi Selection', 'directorist'),
						'value' => 'multiple',
					]
				]
			],
			'create_new_loc' => [
				'type'  => 'toggle',
				'label' => __( 'Allow New', 'directorist' ),
				'value' => false,
			],
			'max_location_creation' => [
				'type'        => 'number',
				'label'       => __( 'Maximum Number', 'directorist' ),
				'placeholder' => 'Here 0 means unlimited',
				'value'       => '0',
				'show_if'     => [
					'where'      => "self.type",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'multiple'],
					],
				],
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'tag' => [
		'label'   => __( 'Tag', 'directorist' ),
		'icon'    => 'las la-tag',
		'options' => [
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'tax_input[at_biz_dir-tags][]',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Tag',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => 'Tag',
			],
			'type' => [
				'type'    => 'radio',
				'value'   => 'multiple',
				'label'   => __( 'Selection Type', 'directorist' ),
				'options' => [
					[
						'label' => __('Single Selection', 'directorist'),
						'value' => 'single',
					],
					[
						'label' => __('Multi Selection', 'directorist'),
						'value' => 'multiple',
					]
				]
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'allow_new' => [
				'type'  => 'toggle',
				'label' => __( 'Allow New', 'directorist' ),
				'value' => true,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'category' => [
		'label'   => __( 'Category', 'directorist' ),
		'icon'    => 'uil uil-folder-open',
		'options' => [
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'admin_category_select[]',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Category',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'type' => [
				'type'    => 'radio',
				'value'   => 'multiple',
				'label'   => __( 'Selection Type', 'directorist' ),
				'options' => [
					[
						'label' => __('Single Selection', 'directorist'),
						'value' => 'single',
					],
					[
						'label' => __('Multi Selection', 'directorist'),
						'value' => 'multiple',
					]
				]
			],
			'create_new_cat' => [
				'type'  => 'toggle',
				'label' => __( 'Allow New', 'directorist' ),
				'value' => false,
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'map' => [
		'label'   => __( 'Map', 'directorist' ),
		'icon'    => 'uil uil-map',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'map',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'map',
			],
			'label' => [
				'type'  => 'hidden',
				'value' => __( 'Map', 'directorist' ),
			],
			'lat_long' => [
				'type'  => 'text',
				'label' => __( 'Enter Coordinates Label', 'directorist' ),
				'value' => __( 'Or Enter Coordinates (latitude and longitude) Manually', 'directorist' ),
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'address' => [
		'label'   => __( 'Address', 'directorist' ),
		'icon'    => 'uil uil-map-pin',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'address',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Address',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => __( 'Listing address eg. New York, USA', 'directorist' ),
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'zip' => [
		'label'   => __( 'Zip or Post Code', 'directorist' ),
		'icon'    => 'uil uil-map-pin',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'zip',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Zip/Post Code',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'phone' => [
		'label'   => 'Phone',
		'icon'    => 'uil uil-phone',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'tel',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'phone',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Phone',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
			'whatsapp' => [
				'type'  => 'toggle',
				'label' => __( 'Link with WhatsApp', 'directorist' ),
				'value' => false,
			],
		],
	],

	'phone2' => [
		'label'   => 'Phone 2',
		'icon'    => 'uil uil-phone',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'tel',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'phone2',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Phone 2',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
			'whatsapp' => [
				'type'  => 'toggle',
				'label' => __( 'Link with WhatsApp', 'directorist' ),
				'value' => false,
			],
		],
	],

	'fax' => [
		'label'   => 'Fax',
		'icon'    => 'uil uil-print',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'number',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'fax',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Fax',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'email' => [
		'label'   => 'Email',
		'icon'    => 'uil uil-envelope',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'email',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'email',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Email',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist'),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'website' => [
		'label'   => 'Website',
		'icon'    => 'uil uil-globe',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'website',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Website',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'social_info' => [
		'label'   => 'Social Info',
		'icon'    => 'uil uil-users-alt',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'add_new',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'social',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Social Info',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'image_upload' => [
		'label'   => __( 'Images', 'directirst' ),
		'icon'    => 'uil uil-image',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'media',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'listing_img',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'hidden',
				'value' => __( 'Images', 'directorist' ),
			],
			'select_files_label' => [
				'type'  => 'text',
				'label' => __( 'Select Files Label', 'directorist' ),
				'value' => 'Select Files',
			],
			'max_image_limit' => [
				'type'  => 'number',
				'label' => __( 'Max Image Limit', 'directorist' ),
				'value' => 5,
			],
			'max_per_image_limit' => [
				'type'        => 'number',
				'label'       => __( 'Max Upload Size Per Image in MB', 'directorist' ),
				'description' => __( 'Here 0 means unlimited.', 'directorist' ),
				'value'       => 0,
			],
			'max_total_image_limit' => [
				'type'  => 'number',
				'label' => __( 'Total Upload Size in MB', 'directorist' ),
				'value' => 2,
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			]
		],
	],

	'video' => [
		'label'   => 'Video',
		'icon'    => 'uil uil-video',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'videourl',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Video',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => 'Only YouTube & Vimeo URLs.',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Admin Only', 'directorist' ),
				'value' => false,
			],
		],
	],

	'terms_privacy' => [
		'label'   => __( 'Terms & Privacy', 'directorist' ),
		'icon'    => 'uil uil-text-fields',
		'show'    => true,
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => [
				'type'  => 'hidden',
				'value' => 'privacy_policy',
				'rules' => [
					'unique'   => true,
					'required' => true,
				]
			],
			'text' => [
				'label'       => __( 'Text', 'directorist' ),
				'type'        => 'textarea',
                'editor'      => 'wp_editor',
                'editorID'    => 'wp_editor_terms_privacy',
                'value'       => sprintf(
					__( 'I agree to the <a href="%s" target="_blank">Privacy Policy</a> and <a href="%s" target="_blank">Terms of Service</a>', 'directorist' ),
					ATBDP_Permalink::get_privacy_policy_page_url(),
					ATBDP_Permalink::get_terms_and_conditions_page_url(),
				),
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
		],
	],
));
