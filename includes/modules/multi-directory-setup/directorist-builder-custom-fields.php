<?php
/**
 * Builder custom fields.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$custom_field_meta_key_field = apply_filters( 'directorist_custom_field_meta_key_field_args', [
	'type'  => 'hidden',
	'label' => __( 'Key', 'directorist' ),
	'value' => 'custom-text',
	'rules' => [
		'unique'   => true,
		'required' => true,
	]
]);

return array(
	'text' => [
		'label'   => __( 'Text', 'directorist' ),
		'icon'    => 'uil uil-text',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-text',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Text',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
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
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'textarea' => [
		'label'   => __( 'Textarea', 'directorist' ),
		'icon'    => 'uil uil-text-fields',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'textarea',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-textarea',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Textarea',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'rows' => [
				'type'  => 'number',
				'label' => __( 'Rows', 'directorist' ),
				'value' => 8,
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'number' => [
		'label'   => __( 'Number', 'directorist' ),
		'icon'    => 'uil uil-0-plus',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'number',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-number',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Number',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
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
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'min_value' => [
				'type'  => 'number',
				'label' => __( 'Min Value', 'directorist' ),
				'value' => 0,
			],
			'max_value' => [
				'type'  => 'number',
				'label' => __( 'Max Value', 'directorist' ),
				'value' => 100,
			],
			'prepend' => [
				'type'        => 'text',
				'label'       => __( 'Prepend', 'directorist' ),
				'description' => __( 'Appears before The Input', 'directorist' ),
				'value'       => "",
			],
			'append' => [
				'type'        => 'text',
				'label'       => __( 'Append', 'directorist' ),
				'description' => __( 'Appears after The Input', 'directorist' ),
				'value'       => "",
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'url' => [
		'label'   => __( 'URL', 'directorist' ),
		'icon'    => 'uil uil-link-add',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'text',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-url',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'URL',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'placeholder' => [
				'type'  => 'text',
				'label' => __( 'Placeholder', 'directorist' ),
				'value' => '',
			],
			'target' => [
				'type'  => 'toggle',
				'label' => __( 'Open in new tab', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'date' => [
		'label'   => __( 'Date', 'directorist' ),
		'icon'    => 'uil uil-calender',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'date',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-date',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Date',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
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
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'time' => [
		'label'   => __( 'Time', 'directorist' ),
		'icon'    => 'uil uil-clock',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'time',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-time',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Time',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
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
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'color_picker' => [
		'label'   => __( 'Color Picker', 'directorist' ),
		'icon'    => 'uil uil-palette',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'color',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-color-picker',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Color',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'select' => [
		'label'   => __( 'Select', 'directorist' ),
		'icon'    => 'uil uil-file-check',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'select',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-select',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Select',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'options' => [
				'type'                 => 'multi-fields',
				'label'                => __('Options', 'directorist'),
				'add-new-button-label' => __( 'Add Option', 'directorist' ),
				'options'              => [
					'option_value' => [
						'type'  => 'text',
						'label' => __( 'Value', 'directorist' ),
						'value' => '',
					],
					'option_label' => [
						'type'  => 'text',
						'label' => __( 'Label', 'directorist' ),
						'value' => '',
					],
				]
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'checkbox' => [
		'label'   => __( 'Checkbox', 'directorist' ),
		'icon'    => 'uil uil-check-square',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'checkbox',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-checkbox',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Checkbox',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'options' => [
				'type'                 => 'multi-fields',
				'label'                => __('Options', 'directorist'),
				'add-new-button-label' => __( 'Add Option', 'directorist' ),
				'options'              => [
					'option_value' => [
						'type'  => 'text',
						'label' => __( 'Value', 'directorist' ),
						'value' => '',
					],
					'option_label' => [
						'type'  => 'text',
						'label' => __( 'Label', 'directorist' ),
						'value' => '',
					],
				]
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'radio' => [
		'label'   => __( 'Radio', 'directorist' ),
		'icon'    => 'uil uil-circle',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'radio',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-radio',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'Radio',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'options' => [
				'type'                 => 'multi-fields',
				'label'                => __('Options', 'directorist'),
				'add-new-button-label' => __( 'Add Option', 'directorist' ),
				'options'              => [
					'option_value' => [
						'type'  => 'text',
						'label' => __( 'Value', 'directorist' ),
						'value' => '',
					],
					'option_label' => [
						'type'  => 'text',
						'label' => __( 'Label', 'directorist' ),
						'value' => '',
					],
				]
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
			'assign_to' => [
				'type'  => 'toggle',
				'label' => __('Assign to Category', 'directorist'),
				'value' => false,
			],
			'category'  => $this->get_category_select_field([
				'show_if' => [
					'where'      => "self.assign_to",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			]),
		]
	],

	'file' => [
		'label'   => __( 'File Upload', 'directorist' ),
		'icon'    => 'uil uil-file-upload-alt',
		'options' => [
			'type' => [
				'type'  => 'hidden',
				'value' => 'file',
			],
			'field_key' => array_merge( $custom_field_meta_key_field, [
				'value' => 'custom-file',
			]),
			'label' => [
				'type'  => 'text',
				'label' => __( 'Label', 'directorist' ),
				'value' => 'File Upload',
			],
			'description' => [
				'type'  => 'text',
				'label' => __( 'Description', 'directorist' ),
				'value' => '',
			],
			'file_type' => [
				'type'        => 'select',
				'label'       => __( 'Select a file type', 'directorist' ),
				'description' => __( 'By selecting a file type you are going to allow your users to upload only that or those type(s) of file.', 'directorist' ),
				'value'       => 'image',
				'options'     => self::get_file_upload_field_options(),
			],
			'file_size' => [
				'type'        => 'text',
				'label'       => __( 'File Size', 'directorist' ),
				'description' => __('Set maximum file size to upload', 'directorist'),
				'value'       => '2mb',
			],
			'required' => [
				'type'  => 'toggle',
				'label' => __( 'Required', 'directorist' ),
				'value' => false,
			],
			'only_for_admin' => [
				'type'  => 'toggle',
				'label' => __( 'Administrative Only', 'directorist' ),
				'value' => false,
			],
		]
	],
);
