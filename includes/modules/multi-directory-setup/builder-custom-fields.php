<?php
/**
 * Builder custom fields.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$custom_field_meta_key_field = apply_filters(
    'directorist_custom_field_meta_key_field_args', [
        'type'  => 'hidden',
        'label' => __( 'Key', 'directorist' ),
        'value' => 'custom-text',
        'rules' => [
            'unique'   => true,
            'required' => true,
        ]
    ]
);

function get_file_upload_field_options() {
    $options = [
        [
            'label' => __( 'All types', 'directorist' ),
            'value' => 'all_types',
        ],
        [
            'label' => __( 'Image types', 'directorist' ),
            'value' => 'image',
        ],
        [
            'label' => __( 'Audio types', 'directorist' ),
            'value' => 'audio',
        ],
        [
            'label' => __( 'Video types', 'directorist' ),
            'value' => 'video',
        ],
        [
            'label' => __( 'Document types', 'directorist' ),
            'value' => 'document',
        ],
    ];

    foreach ( directorist_get_supported_file_types() as $file_type ) {
        $options[] = [
            'label' => $file_type,
            'value' => $file_type,
        ];
    }

    return $options;
}

function get_category_select_field( array $args = [] ) {
    $default = [
        'type'    => 'select',
        'label'   => __( 'Select Category', 'directorist' ),
        'value'   => '',
        'options' => get_cetagory_options(),
    ];

    return array_merge( $default, $args );
}

function get_cetagory_options() {
    $terms = get_terms(
        [
            'taxonomy'   => ATBDP_CATEGORY,
            'hide_empty' => false,
        ] 
    );

    $directory_type = isset( $_GET['listing_type_id'] ) ? absint( $_GET['listing_type_id'] ) : directorist_get_default_directory();
    $options        = [];

    if ( is_wp_error( $terms ) ) {
        return $options;
    }

    if ( ! count( $terms ) ) {
        return $options;
    }

    foreach ( $terms as $term ) {
        $term_directory_types = get_term_meta( $term->term_id, '_directory_type', true );

        if ( is_array( $term_directory_types ) && in_array( $directory_type, $term_directory_types, true ) ) {
            $options[] = [
                'id'    => $term->term_id,
                'value' => $term->term_id,
                'label' => $term->name,
            ];
        }

    }

    return $options;
}

/**
 * Get conditional logic field option configuration.
 *
 * @param array $args Optional arguments to override defaults.
 * @return array Conditional logic field configuration.
 */
function get_conditional_logic_field( array $args = [] ) {
    $default = [
        'type'        => 'conditional-logic',
        'label'       => __( 'Conditional Logic', 'directorist' ),
        'description' => __( 'Show or hide this field based on other field values.', 'directorist' ),
        'value'       => [
            'enabled' => false,
            'action'  => 'show',
            'groups'  => [],
        ],
    ];

    return array_merge( $default, $args );
}

return apply_filters(
    'atbdp_form_custom_widgets', [
        'text' => [
            'label'   => __( 'Text', 'directorist' ),
            'icon'    => 'las la-text-height',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'text',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-text',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'textarea' => [
            'label'   => __( 'Textarea', 'directorist' ),
            'icon'    => 'las la-align-left',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'textarea',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-textarea',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],
        
        'html' => [
            'label'   => __( 'Html', 'directorist' ),
            'icon'    => 'las la-code',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'wp_editor',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-html',
                    ]
                ),
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist' ),
                    'value' => 'Html',
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'number' => [
            'label'   => __( 'Number', 'directorist' ),
            'icon'    => 'las la-hashtag',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'number',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-number',
                    ]
                ),
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
                'min_value' => [
                    'type'  => 'number',
                    'label' => __( 'Min Value', 'directorist' ),
                    'value' => '',
                ],
                'max_value' => [
                    'type'  => 'number',
                    'label' => __( 'Max Value', 'directorist' ),
                    'value' => '',
                ],
                'step'       => [
                    'type'  => 'number',
                    'label' => __( 'Step', 'directorist' ),
                    'value' => 1,
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
                'only_for_admin' => [
                    'type'  => 'toggle',
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'url' => [
            'label'   => __( 'URL', 'directorist' ),
            'icon'    => 'las la-link',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'text',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-url',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'date' => [
            'label'   => __( 'Date', 'directorist' ),
            'icon'    => 'la la-calendar',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'date',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-date',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'time' => [
            'label'   => __( 'Time', 'directorist' ),
            'icon'    => 'las la-clock',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'time',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-time',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'color_picker' => [
            'label'   => __( 'Color Picker', 'directorist' ),
            'icon'    => 'las la-palette',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'color',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-color-picker',
                    ]
                ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'select' => [
            'label'   => __( 'Dropdown', 'directorist' ),
            'icon'    => 'las la-chevron-circle-down',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'select',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-select',
                    ]
                ),
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
                    'label'                => __( 'Options', 'directorist' ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'checkbox' => [
            'label'   => __( 'Checkbox', 'directorist' ),
            'icon'    => 'las la-check-square',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'checkbox',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-checkbox',
                    ]
                ),
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
                    'label'                => __( 'Options', 'directorist' ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'radio' => [
            'label'   => __( 'Radio', 'directorist' ),
            'icon'    => 'la la-dot-circle',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'radio',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-radio',
                    ]
                ),
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
                    'label'                => __( 'Options', 'directorist' ),
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
                    'label' => __( 'Admin Only', 'directorist' ),
                    'value' => false,
                ],
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'file' => [
            'label'   => __( 'File Upload', 'directorist' ),
            'icon'    => 'las la-paperclip',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'file',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-file',
                    ]
                ),
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
                    'options'     => get_file_upload_field_options(),
                ],
                'file_size' => [
                    'type'        => 'text',
                    'label'       => __( 'File Size', 'directorist' ),
                    'description' => __( 'Set maximum file size to upload', 'directorist' ),
                    'value'       => '2mb',
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
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],

        'button' => [
            'label'   => __( 'Button', 'directorist' ),
            'icon'    => 'la la-link',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'button',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field, [
                        'value' => 'custom-button',
                    ]
                ),
                'label' => [
                    'type'        => 'text',
                    'label'       => __( 'Button Text Label', 'directorist' ),
                    'value'       => __( 'Button', 'directorist' ),
                    'description' => __( 'Label for the “Button Text” input shown to the listing owner (e.g., Name, Button Text).', 'directorist' ),
                ],
                'button_text_placeholder' => [
                    'type'        => 'text',
                    'label'       => __( 'Button Text Placeholder', 'directorist' ),
                    'value'       => __( 'Visit Now', 'directorist' ),
                    'description' => __( 'Placeholder example for the Button Text input (e.g., Book Now, Visit Site).', 'directorist' ),
                ],
                'button_text_description' => [
                    'type'        => 'text',
                    'label'       => __( 'Button Text Description', 'directorist' ),
                    'value'       => '',
                    'description' => __( 'Help text displayed below the Button Text input (e.g., This text will appear as the button label on your listing).', 'directorist' ),
                ],
                'button_url_label' => [
                    'type'        => 'text',
                    'label'       => __( 'Button URL Label', 'directorist' ),
                    'value'       => __( 'Website URL', 'directorist' ),
                    'description' => __( 'Label for the “Button URL” input shown to the listing owner (e.g., Button Link, Website URL).', 'directorist' ),
                ],
                'button_url_placeholder' => [
                    'type'        => 'text',
                    'label'       => __( 'Button URL Placeholder', 'directorist' ),
                    'value'       => 'https://yourlink.com',
                    'description' => __( 'Placeholder example for the Button URL input (e.g., https://yourlink.com).', 'directorist' ),
                ],
                'button_url_description' => [
                    'type'        => 'text',
                    'label'       => __( 'Button URL Description', 'directorist' ),
                    'value'       => '',
                    'description' => __( 'Help text displayed below the Button URL input (e.g., Add the full website link).', 'directorist' ),
                ],
                'button_style' => [
                    'type'    => 'select',
                    'label'   => __( 'Button Style', 'directorist' ),
                    'value'   => 'default',
                    'options' => [
                        [
                            'value' => 'default',
                            'label' => __( 'Default', 'directorist' ),
                        ],
                        [
                            'value' => 'primary',
                            'label' => __( 'Primary', 'directorist' ),
                        ],
                        [
                            'value' => 'secondary',
                            'label' => __( 'Secondary', 'directorist' ),
                        ],
                    ],
                ],
                'open_in_new_tab' => [
                    'type'  => 'toggle',
                    'label' => __( 'Open in New Tab', 'directorist' ),
                    'value' => true,
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
                'conditional_logic' => get_conditional_logic_field(),
            ]
        ],
    ]
);
