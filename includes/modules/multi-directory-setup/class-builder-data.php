<?php

namespace Directorist\Multi_Directory;

class Builder_Data {
    protected static $fields           = [];
    protected static $layouts          = [];
    protected static $config           = [];
    protected static $options          = [];

    public function __construct() {
        self::prepare_data();
    }

    protected static function prepare_data() {
        $form_field_widgets = [
            'preset' => [
                'title'         => __( 'Preset Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'widgets'       => apply_filters( 'atbdp_form_preset_widgets', require_once __DIR__ .  '/builder-preset-fields.php' ),
            ],

            'custom' => [
                'title'         => __( 'Custom Fields', 'directorist' ),
                'description'   => __( 'Click on a field type you want to create.', 'directorist' ),
                'allowMultiple' => true,
                'widgets'       => apply_filters( 'atbdp_form_custom_widgets', require_once __DIR__ .  '/builder-custom-fields.php' ),

            ],
        ];

        $single_listings_contents_widgets = [
            'preset_widgets' => [
                'title'         => __( 'Preset Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'template'      => 'submission_form_fields',
                'widgets'       => apply_filters( 'atbdp_single_listing_content_widgets', [
                    'tag'          => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-tag',
                            ],
                        ],
                    ],
                    'address'      => [
                        'options' => [
                            'icon'                  => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-map',
                            ],
                            'address_link_with_map' => [
                                'type'  => 'toggle',
                                'label' => __( 'Address Linked with Map', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],
                    'map'          => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-map',
                            ],
                        ],
                    ],
                    'zip'          => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-street-view',
                            ],
                        ],
                    ],
                    'phone'        => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-phone',
                            ],
                        ],
                    ],
                    'phone2'       => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-phone',
                            ],
                        ],
                    ],
                    'fax'          => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-fax',
                            ],
                        ],
                    ],
                    'email'        => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-envelope',
                            ],
                        ],
                    ],
                    'website'      => [
                        'options' => [
                            'icon'         => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-globe',
                            ],
                            'use_nofollow' => [
                                'type'  => 'toggle',
                                'label' => __( 'Use rel="nofollow" in Website Link', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],
                    'social_info'  => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-share-alt',
                            ],
                        ],
                    ],
                    'video'        => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-video',
                            ],
                        ],
                    ],
                    'text'         => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-text-height',
                            ],
                        ],
                    ],
                    'textarea'     => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-align-center',
                            ],
                        ],
                    ],
                    'number'       => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-list-ol',
                            ],
                        ],
                    ],
                    'url'          => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-link',
                            ],
                        ],
                    ],
                    'date'         => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-calendar',
                            ],
                        ],
                    ],
                    'time'         => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-clock',
                            ],
                        ],
                    ],
                    'color_picker' => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-palette',
                            ],
                        ],
                    ],
                    'select'       => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-clipboard-check',
                            ],
                        ],
                    ],
                    'checkbox'     => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-check-square',
                            ],
                        ],
                    ],
                    'radio'        => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-circle',
                            ],
                        ],
                    ],
                    'file'         => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-file-alt',
                            ],
                        ],
                    ],
                ] ),
            ],
            'other_widgets'  => [
                'title'         => __( 'Other Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'widgets'       => apply_filters( 'atbdp_single_listing_other_fields_widget', [
                    'custom_content'         => [
                        'type'          => 'widget',
                        'label'         => __( 'Custom Content', 'directorist' ),
                        'icon'          => 'las la-align-right',
                        'allowMultiple' => true,
                        'options'       => [
                            'label'   => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => '',
                            ],
                            'icon'    => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => '',
                            ],
                            'content' => [
                                'type'        => 'textarea',
                                'label'       => __( 'Content', 'directorist' ),
                                'value'       => '',
                                'description' => __( 'You can use any text or shortcode', 'directorist' ),
                            ],
                        ],
                    ],
                    'review'                 => [
                        'type'    => 'section',
                        'label'   => __( 'Review', 'directorist' ),
                        'icon'    => 'las la-star',
                        'options' => [
                            'custom_block_id'      => [
                                'type'  => 'text',
                                'label' => __( 'Custom block ID', 'directorist' ),
                                'value' => '',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                            ],
                        ],
                    ],
                    'author_info'            => [
                        'type'    => 'section',
                        'label'   => __( 'Author Info', 'directorist' ),
                        'icon'    => 'las la-user',
                        'options' => [
                            'label'                => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Author Info',
                            ],
                            'custom_block_id'      => [
                                'type'  => 'text',
                                'label' => __( 'Custom block ID', 'directorist' ),
                                'value' => '',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                            ],
                        ],
                    ],
                    'contact_listings_owner' => [
                        'type'    => 'section',
                        'label'   => __( 'Contact Listings Owner Form', 'directorist' ),
                        'icon'    => 'las la-phone',
                        'options' => [
                            'label'                => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Contact Listings Owner Form',
                            ],
                            'icon'                 => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-phone',
                            ],
                            'custom_block_id'      => [
                                'type'  => 'text',
                                'label' => __( 'Custom block ID', 'directorist' ),
                                'value' => '',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                            ],
                        ],
                    ],
                    'related_listings'       => [
                        'type'    => 'section',
                        'label'   => __( 'Related Listings', 'directorist' ),
                        'icon'    => 'las la-copy',
                        'options' => [
                            'label'                => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Related Listings',
                            ],
                            'custom_block_id'      => [
                                'type'  => 'text',
                                'label' => __( 'Custom block ID', 'directorist' ),
                                'value' => '',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                            ],
                        ],
                    ],
                ] ),
            ],
        ];

        $search_form_widgets = apply_filters( 'directorist_search_form_widgets', [
            'available_widgets' => [
                'title'         => __( 'Preset Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'template'      => 'submission_form_fields',
                'widgets'       => [
                    'title'        => [
                        'label'   => __( 'Search Bar', 'directorist' ),
                        'options' => [
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'What are you looking for?',
                            ],
                        ],
                    ],

                    'category'     => [
                        'options' => [
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => '',
                                'sync'  => false,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Category',
                            ],
                        ],
                    ],

                    'location'     => [
                        'options' => [
                            'required'        => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                            'label'           => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => '',
                                'sync'  => false,
                            ],
                            'placeholder'     => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Location',
                            ],
                            'location_source' => [
                                'type'    => 'select',
                                'label'   => __( 'Location Source', 'directorist' ),
                                'options' => [
                                    [
                                        'label' => __( 'Display from Listing Location', 'directorist' ),
                                        'value' => 'from_listing_location',
                                    ],
                                    [
                                        'label' => __( 'Display from Map API', 'directorist' ),
                                        'value' => 'from_map_api',
                                    ],
                                ],
                                'value'   => 'from_map_api',
                            ],
                        ],
                    ],

                    'tag'          => [
                        'options' => [
                            'label'              => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                        ],
                    ],

                    'pricing'      => [
                        'options' => [
                            'price_range_min_placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Price Range Min Placeholder', 'directorist' ),
                                'value' => 'Min',
                            ],
                            'price_range_max_placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Price Range Max Placeholder', 'directorist' ),
                                'value' => 'Max',
                            ],
                        ],
                    ],

                    'zip'          => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Zip',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'phone'        => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Phone',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'phone2'       => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Phone 2',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'email'        => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Email',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'fax'          => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Fax',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Fax',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'website'      => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Website',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],

                    'text'         => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Text',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],
                    'number'       => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Number',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'url'          => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'URL',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'date'         => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Date',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'time'         => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Time',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'color_picker' => [
                        'options' => [
                            'label'    => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'select'       => [
                        'options' => [
                            'label'       => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'Select',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'checkbox'     => [
                        'options' => [
                            'label'    => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                    'radio'        => [
                        'options' => [
                            'label'    => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Tag',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
                            ],
                        ],

                    ],

                ],
            ],
            'other_widgets'     => [
                'title'         => __( 'Other Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'widgets'       => [
                    'review'        => [
                        'label'   => 'Review',
                        'icon'    => 'las la-star',
                        'options' => [
                            'label' => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Review',
                            ],
                        ],
                    ],
                    'radius_search' => [
                        'label'   => __( 'Radius Search', 'directorist' ),
                        'icon'    => 'las la-map',
                        'options' => [
                            'label'                   => [
                                'type'  => 'text',
                                'label' => __( 'Label', 'directorist' ),
                                'value' => 'Radius Search',
                            ],
                            'default_radius_distance' => [
                                'type'  => 'range',
                                'label' => __( 'Default Radius Distance', 'directorist' ),
                                'min'   => 0,
                                'max'   => apply_filters( 'directorist_search_default_radius_distance', 750 ),
                                'value' => 0,
                            ],
                            'radius_search_unit'      => [
                                'type'    => 'select',
                                'label'   => __( 'Radius Search Unit', 'directorist' ),
                                'value'   => 'miles',
                                'options' => [
                                    ['value' => 'miles', 'label' => 'Miles'],
                                    ['value' => 'kilometers', 'label' => 'Kilometers'],
                                ],
                            ],
                            'radius_search_based_on'  => [
                                'type'    => 'radio',
                                'label'   => __( 'Radius Search Based on', 'directorist' ),
                                'value'   => 'address',
                                'options' => [
                                    ['value' => 'address', 'label' => 'Address'],
                                    ['value' => 'zip', 'label' => 'Zip Code'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ] );

        $listing_card_widget = apply_filters( 'directorist_listing_card_widgets', [
            'listing_title'     => [
                'type'    => 'title',
                'label'   => __( 'Listing Title', 'directorist' ),
                'icon'    => 'uil uil-text-fields',
                'hook'    => 'atbdp_listing_title',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'title'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listing Title Settings', 'directorist' ),
                    'fields' => [
                        'show_tagline' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Tagline', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'excerpt'           => [
                'type'    => 'excerpt',
                'label'   => __( 'Excerpt', 'directorist' ),
                'icon'    => 'uil uil-text-fields',
                'hook'    => 'atbdp_listing_excerpt',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Excerpt Settings', 'directorist' ),
                    'fields' => [
                        'words_limit'        => [
                            'type'  => 'range',
                            'label' => __( 'Words Limit', 'directorist' ),
                            'min'   => 5,
                            'max'   => 200,
                            'value' => 20,
                        ],
                        'show_readmore'      => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Readmore', 'directorist' ),
                            'value' => true,
                        ],
                        'show_readmore_text' => [
                            'type'  => 'text',
                            'label' => __( 'Read More Text', 'directorist' ),
                            'value' => 'Read More',
                        ],
                    ],
                ],
            ],

            'listings_location' => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Location', 'directorist' ),
                'icon'    => 'uil uil-location-point',
                'hook'    => 'atbdp_listings_location',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Location Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-map-marker',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'posted_date'       => [
                'type'    => 'list-item',
                'label'   => __( 'Posted Date', 'directorist' ),
                'icon'    => 'las la-clock',
                'hook'    => 'atbdp_listings_posted_date',
                'options' => [
                    'title'  => __( 'Posted Date', 'directorist' ),
                    'fields' => [
                        'icon'      => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-clock',
                        ],
                        'date_type' => [
                            'type'    => 'radio',
                            'label'   => __( 'Date Type', 'directorist' ),
                            'options' => [
                                ['id' => 'atbdp_days_ago', 'label' => 'Days Ago', 'value' => 'days_ago'],
                                ['id' => 'atbdp_posted_date', 'label' => 'Posted Date', 'value' => 'post_date'],
                            ],
                            'value'   => 'post_date',
                        ],
                    ],
                ],
            ],

            'website'           => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Website', 'directorist' ),
                'icon'    => 'las la-globe',
                'hook'    => 'atbdp_listings_website',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'website'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Website Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-globe',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'zip'               => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Zip', 'directorist' ),
                'icon'    => 'las la-at',
                'hook'    => 'atbdp_listings_zip',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'zip'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Zip Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-at',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'email'             => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Email', 'directorist' ),
                'icon'    => 'las la-envelope',
                'hook'    => 'atbdp_listings_email',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'email'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Email Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-envelope',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'fax'               => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Fax', 'directorist' ),
                'icon'    => 'las la-fax',
                'hook'    => 'atbdp_listings_fax',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'fax'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Fax Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-fax',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'phone'             => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Phone', 'directorist' ),
                'icon'    => 'las la-phone',
                'hook'    => 'atbdp_listings_phone',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'phone'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Phone Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-phone',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'phone2'            => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Phone 2', 'directorist' ),
                'icon'    => 'las la-phone',
                'hook'    => 'atbdp_listings_phone2',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'phone2'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Phone 2 Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-phone',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'address'           => [
                'type'    => 'list-item',
                'label'   => __( 'Listings Address', 'directorist' ),
                'icon'    => 'las la-map-marker',
                'hook'    => 'atbdp_listings_map_address',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'address'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Listings Address Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-map-marker',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'pricing'           => [
                'type'    => 'price',
                'label'   => __( 'Listings Price', 'directorist' ),
                'icon'    => 'uil uil-text-fields',
                'hook'    => 'atbdp_single_listings_price',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'pricing'],
                    ],
                ],
            ],

            'rating'            => [
                'type'  => 'rating',
                'label' => __( 'Rating', 'directorist' ),
                'hook'  => 'atbdp_listings_rating',
                'icon'  => 'uil uil-text-fields',
            ],

            'featured_badge'    => [
                'type'  => 'badge',
                'label' => __( 'Featured', 'directorist' ),
                'icon'  => 'uil uil-text-fields',
                'hook'  => 'atbdp_featured_badge',
            ],

            'new_badge'         => [
                'type'  => 'badge',
                'label' => __( 'New', 'directorist' ),
                'icon'  => 'uil uil-text-fields',
                'hook'  => 'atbdp_new_badge',
            ],

            'popular_badge'     => [
                'type'  => 'badge',
                'label' => __( 'Popular', 'directorist' ),
                'icon'  => 'uil uil-text-fields',
                'hook'  => 'atbdp_popular_badge',
            ],

            'favorite_badge'    => [
                'type'  => 'icon',
                'label' => __( 'Favorite', 'directorist' ),
                'icon'  => 'uil uil-text-fields',
                'hook'  => 'atbdp_favorite_badge',
            ],

            'view_count'        => [
                'type'    => 'view-count',
                'label'   => __( 'View Count', 'directorist' ),
                'icon'    => 'uil uil-text-fields',
                'hook'    => 'atbdp_view_count',
                'options' => [
                    'title'  => __( 'View Count Settings', 'directorist' ),
                    'fields' => [
                        'icon' => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-heart',
                        ],
                    ],
                ],
            ],

            'category'          => [
                'type'    => 'category',
                'label'   => __( 'Category', 'directorist' ),
                'icon'    => 'uil uil-text-fields',
                'hook'    => 'atbdp_category',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Category Settings', 'directorist' ),
                    'fields' => [
                        'icon' => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-folder',
                        ],
                    ],
                ],
            ],

            'user_avatar'       => [
                'type'     => 'avatar',
                'label'    => __( 'User Avatar', 'directorist' ),
                'icon'     => 'uil uil-text-fields',
                'hook'     => 'atbdp_user_avatar',
                'can_move' => false,
                'options'  => [
                    'title'  => __( 'User Avatar Settings', 'directorist' ),
                    'fields' => [
                        'align' => [
                            'type'    => 'radio',
                            'label'   => __( 'Align', 'directorist' ),
                            'value'   => 'center',
                            'options' => [
                                ['id' => 'atbdp_user_avatar_align_right', 'label' => __( 'Right', 'directorist' ), 'value' => 'right'],
                                ['id' => 'atbdp_user_avatar_align_center', 'label' => __( 'Center', 'directorist' ), 'value' => 'center'],
                                ['id' => 'atbdp_user_avatar_align_left', 'label' => __( 'Left', 'directorist' ), 'value' => 'left'],
                            ],
                        ],
                    ],
                ],
            ],

            // Custom Fields
            'text'              => [
                'type'    => 'list-item',
                'label'   => __( 'Text', 'directorist' ),
                'icon'    => 'las la-comment',
                'hook'    => 'atbdp_custom_text',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'text'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Text Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-comment',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'number'            => [
                'type'    => 'list-item',
                'label'   => __( 'Number', 'directorist' ),
                'icon'    => 'las la-file-word',
                'hook'    => 'atbdp_custom_number',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'number'],
                    ],
                ],
                'options' => [
                    'title'  => 'Number Settings',
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-file-word',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'url'               => [
                'type'    => 'list-item',
                'label'   => __( 'URL', 'directorist' ),
                'icon'    => 'las la-link',
                'hook'    => 'atbdp_custom_url',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'url'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'URL Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-link',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'date'              => [
                'type'    => 'list-item',
                'label'   => __( 'Date', 'directorist' ),
                'icon'    => 'las la-calendar-check',
                'hook'    => 'atbdp_custom_date',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'date'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Date Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-calendar-check',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'time'              => [
                'type'    => 'list-item',
                'label'   => __( 'Time', 'directorist' ),
                'icon'    => 'las la-clock',
                'hook'    => 'atbdp_custom_time',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'time'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Time Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-clock',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'color_picker'      => [
                'type'    => 'list-item',
                'label'   => __( 'Color Picker', 'directorist' ),
                'icon'    => 'las la-palette',
                'hook'    => 'atbdp_custom_color',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'color'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Color Picker Settings', 'directorist' ),
                    'fields' => [
                        'icon' => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-palette',
                        ],
                    ],
                ],
            ],

            'select'            => [
                'type'    => 'list-item',
                'label'   => __( 'Select', 'directorist' ),
                'icon'    => 'las la-check-circle',
                'hook'    => 'atbdp_custom_select',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'select'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Select Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-check-circle',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'checkbox'          => [
                'type'    => 'list-item',
                'label'   => __( 'Checkbox', 'directorist' ),
                'icon'    => 'las la-check-square',
                'hook'    => 'atbdp_custom_checkbox',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'checkbox'],
                    ],
                ],
                'options' => [
                    'title'  => 'Checkbox Settings',
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-check-square',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],

            'radio'             => [
                'type'    => 'list-item',
                'label'   => __( 'Radio', 'directorist' ),
                'icon'    => 'las la-circle',
                'hook'    => 'atbdp_custom_radio',
                'show_if' => [
                    'where'      => 'submission_form_fields.value.fields',
                    'conditions' => [
                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'radio'],
                    ],
                ],
                'options' => [
                    'title'  => __( 'Radio Settings', 'directorist' ),
                    'fields' => [
                        'icon'       => [
                            'type'  => 'icon',
                            'label' => __( 'Icon', 'directorist' ),
                            'value' => 'las la-circle',
                        ],
                        'show_label' => [
                            'type'  => 'toggle',
                            'label' => __( 'Show Label', 'directorist' ),
                            'value' => false,
                        ],
                    ],
                ],
            ],
        ] );

        $listing_card_conditional_widget = $listing_card_widget;

        if (  ! empty( $listing_card_conditional_widget['user_avatar'] ) ) {
            $listing_card_conditional_widget['user_avatar']['can_move'] = true;

            if (  ! empty( $listing_card_conditional_widget['user_avatar']['options'] ) ) {
                unset( $listing_card_conditional_widget['user_avatar']['options'] );
            }

        }

        // Card Layouts
        $listing_card_grid_view_with_thumbnail_layout = [
            'thumbnail' => [
                'top_right'    => [
                    'label'             => __( 'Top Right', 'directorist' ),
                    'maxWidget'         => 3,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'top_left'     => [
                    'maxWidget'       => 3,
                    'acceptedWidgets' => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'bottom_right' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'bottom_left'  => [
                    'maxWidget'       => 3,
                    'acceptedWidgets' => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'avatar'       => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['user_avatar'],
                ],
            ],

            'body'      => [
                'top'     => [
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listing_title', 'favorite_badge', 'popular_badge', 'featured_badge', 'new_badge', 'rating', 'pricing',
                    ],
                ],
                'bottom'  => [
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email',
                        'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
                    ],
                ],
                'excerpt' => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['excerpt'],
                    'show_if'         => [
                        'where'      => 'submission_form_fields.value.fields',
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                        ],
                    ],
                ],
            ],

            'footer'    => [
                'right' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],

                'left'  => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],
            ],
        ];

        $listing_card_grid_view_without_thumbnail_layout = [
            'body'   => [
                'avatar'        => [
                    'label'             => __( 'Avatar', 'directorist' ),
                    'maxWidget'         => 1,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['user_avatar'],
                ],
                'title'         => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['listing_title'],
                ],
                'quick_actions' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['favorite_badge'],
                ],
                'quick_info'    => [
                    'acceptedWidgets' => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge', 'rating', 'pricing'],
                ],
                'bottom'        => [
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email',
                        'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
                    ],
                ],
                'excerpt'       => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['excerpt'],
                    'show_if'         => [
                        'where'      => 'submission_form_fields.value.fields',
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                        ],
                    ],
                ],
            ],

            'footer' => [
                'right' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],

                'left'  => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],
            ],
        ];

        $listing_card_list_view_with_thumbnail_layout = [
            'thumbnail' => [
                'top_right' => [
                    'label'             => __( 'Top Right', 'directorist' ),
                    'maxWidget'         => 3,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
            ],

            'body'      => [
                'top'     => [
                    'label'             => __( 'Body Top', 'directorist' ),
                    'maxWidget'         => 0,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['listing_title', 'favorite_badge', 'popular_badge', 'featured_badge', 'new_badge', 'rating', 'pricing'],
                ],
                'right'   => [
                    'label'             => __( 'Body Right', 'directorist' ),
                    'maxWidget'         => 2,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'bottom'  => [
                    'label'           => __( 'Body Bottom', 'directorist' ),
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email',
                        'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
                    ],
                ],
                'excerpt' => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['excerpt'],
                    'show_if'         => [
                        'where'      => 'submission_form_fields.value.fields',
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                        ],
                    ],
                ],
            ],

            'footer'    => [
                'right' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['user_avatar', 'category', 'favorite_badge', 'view_count'],
                ],

                'left'  => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],
            ],
        ];

        $listing_card_list_view_without_thumbnail_layout = [
            'body'   => [
                'top'     => [
                    'label'             => __( 'Body Top', 'directorist' ),
                    'maxWidget'         => 0,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['listing_title', 'favorite_badge', 'popular_badge', 'featured_badge', 'new_badge', 'rating', 'pricing'],
                ],
                'right'   => [
                    'label'             => __( 'Body Right', 'directorist' ),
                    'maxWidget'         => 2,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'bottom'  => [
                    'label'           => __( 'Body Bottom', 'directorist' ),
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email',
                        'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
                    ],
                ],
                'excerpt' => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['excerpt'],
                    'show_if'         => [
                        'where'      => 'submission_form_fields.value.fields',
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                        ],
                    ],
                ],
            ],

            'footer' => [
                'right' => [
                    'maxWidget'       => 2,
                    'acceptedWidgets' => ['user_avatar', 'category', 'favorite_badge', 'view_count'],
                ],

                'left'  => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['category', 'favorite_badge', 'view_count'],
                ],
            ],
        ];

        self::$fields = apply_filters( 'atbdp_listing_type_settings_field_list', [
            'icon' => [
                'label'       => '',
                'type'        => 'icon',
                'value'       => '',
                'placeholder' => __( 'las la-home', 'directorist' ),
                'rules'       => [
                    'required' => false,
                ],
            ],
            'preview_image'                               => [
                'button-label' => __( 'Select', 'directorist' ),
                'type'         => 'wp-media-picker',
                'default-img'  => '',
                'value'        => '',
            ],

            'import_export'                               => [
                'button-label'     => __( 'Export', 'directorist' ),
                'export-file-name' => 'directory',
                'type'             => 'export',
            ],

            'default_expiration'                          => [
                'label'       => __( 'Default expiration in days', 'directorist' ),
                'type'        => 'number',
                'value'       => 30,
                'placeholder' => '365',
                'rules'       => [
                    'required' => true,
                ],
            ],

            'new_listing_status'                          => [
                'label'   => __( 'New Listing Default Status', 'directorist' ),
                'type'    => 'select',
                'value'   => 'pending',
                'options' => [
                    [
                        'label' => __( 'Pending', 'directorist' ),
                        'value' => 'pending',
                    ],
                    [
                        'label' => __( 'Publish', 'directorist' ),
                        'value' => 'publish',
                    ],
                ],
            ],

            'edit_listing_status'                         => [
                'label'   => __( 'Edited Listing Default Status', 'directorist' ),
                'type'    => 'select',
                'value'   => 'pending',
                'options' => [
                    [
                        'label' => __( 'Pending', 'directorist' ),
                        'value' => 'pending',
                    ],
                    [
                        'label' => __( 'Publish', 'directorist' ),
                        'value' => 'publish',
                    ],
                ],
            ],

            'global_listing_type'                         => [
                'label' => __( 'Global Listing Type', 'directorist' ),
                'type'  => 'toggle',
                'value' => '',
            ],

            'submission_form_fields'                      => apply_filters( 'atbdp_listing_type_form_fields', [
                'type'            => 'form-builder',
                'widgets'         => $form_field_widgets,
                'generalSettings' => [
                    'minGroup'                       => 1,
                    'addNewGroupButtonLabel'         => __( 'Add Section', 'directorist' ),
                    'restricted_fields_warning_text' => __( 'You can not add in this section', 'directorist' ),
                ],
                'groupSettings'   => [
                    'defaultGroupLabel'             => 'Section',
                    'disableTrashIfGroupHasWidgets' => [
                        ['widget_name' => 'title', 'widget_group' => 'preset'],
                    ],
                ],
                'groupFields'     => [
                    'label' => [
                        'type'  => 'text',
                        'label' => 'Group Name',
                        'value' => 'Section',
                    ],
                ],
                'value'           => [
                    'fields' => [
                        'title' => [
                            'widget_group' => 'preset',
                            'widget_name'  => 'title',
                            'type'         => 'text',
                            'field_key'    => 'listing_title',
                            'required'     => true,
                            'label'        => 'Title',
                            'placeholder'  => '',
                        ],
                    ],
                    'groups' => [
                        [
                            'label'  => 'General Section',
                            'lock'   => true,
                            'fields' => ['title'],
                            'plans'  => [],
                        ],
                    ],
                ],

            ] ),

            // Submission Settings
            'preview_mode'                                => [
                'label' => __( 'Enable Listing Preview', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],

            // Submit Button
            'submit_button_label'                         => [
                'label' => __( 'Submit Button Label', 'directorist' ),
                'type'  => 'text',
                'value' => __( 'Save & Preview', 'directorist' ),
            ],

            // TERMS AND CONDITIONS
            'listing_terms_condition'                     => [
                'label' => __( 'Enable', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],
            'require_terms_conditions'                    => [
                'label' => __( 'Required', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],
            'terms_label'                                 => [
                'label'       => __( 'Label', 'directorist' ),
                'type'        => 'text',
                'description' => __( 'Place the linking text between two <code>%</code> mark. Ex: %link% ', 'directorist' ),
                'value'       => 'I agree with all %terms & conditions%',
            ],

            // PRIVACY AND POLICY
            'listing_privacy'                             => [
                'label' => __( 'Enable', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],
            'require_privacy'                             => [
                'label' => __( 'Required', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],
            'privacy_label'                               => [
                'label'       => __( 'Label', 'directorist' ),
                'type'        => 'text',
                'description' => __( 'Place the linking text between two <code>%</code> mark. Ex: %link% ', 'directorist' ),
                'value'       => 'I agree to the %Privacy & Policy%',
            ],

            'single_listings_contents'                    => [
                'type'            => 'form-builder',
                'widgets'         => $single_listings_contents_widgets,
                'generalSettings' => [
                    'addNewGroupButtonLabel' => __( 'Add Section', 'directorist' ),
                ],
                'groupFields'     => [
                    'section_id'           => [
                        'type'    => 'text',
                        'disable' => true,
                        'label'   => 'Section ID',
                        'value'   => '',
                    ],
                    'icon'                 => [
                        'type'  => 'icon',
                        'label' => __( 'Block/Section Icon', 'directorist' ),
                        'value' => '',
                    ],
                    'label'                => [
                        'type'  => 'text',
                        'label' => __( 'Label', 'directorist' ),
                        'value' => 'Section',
                    ],
                    'custom_block_id'      => [
                        'type'  => 'text',
                        'label' => __( 'Custom block ID', 'directorist' ),
                        'value' => '',
                    ],
                    'custom_block_classes' => [
                        'type'  => 'text',
                        'label' => __( 'Custom block Classes', 'directorist' ),
                        'value' => '',
                    ],
                    'shortcode'            => [
                        'type'        => 'shortcode-list',
                        'label'       => __( 'Shortcode', 'directorist' ),
                        'description' => __( 'Click the wizerd button to generate the shortcode.', 'directorist' ),
                        'buttonLabel' => '<i class="fas fa-magic"></i>',
                        'shortcodes'  => [
                            [
                                'shortcode' => '[directorist_single_listing_section label="@@shortcode_label@@" key="@@shortcode_key@@"]',
                                'mapAtts'   => [
                                    [
                                        'map'   => 'self.section_id',
                                        'where' => [
                                            'key'   => 'value',
                                            'mapTo' => '@@shortcode_key@@',
                                        ],
                                    ],
                                    [
                                        'map'   => 'self.label',
                                        'where' => [
                                            'key'   => 'value',
                                            'mapTo' => '@@shortcode_label@@',
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        'show_if'     => [
                            'where'      => 'enable_single_listing_page',
                            'conditions' => [
                                ['key' => 'value', 'compare' => '=', 'value' => true],
                            ],
                        ],

                    ],
                ],
                'value'           => [],
            ],
            'enable_single_listing_page'                  => [
                'type'      => 'toggle',
                'label'     => __( 'Custom Single Listing Page', 'directorist' ),
                'labelType' => 'h3',
                'value'     => false,
            ],
            'single_listing_page'                         => [
                'label'             => __( 'Single listing page', 'directorist' ),
                'type'              => 'select',
                'value'             => '',
                'showDefaultOption' => true,
                'options'           => directorist_get_all_page_list(),
                'show_if'           => [
                    'where'      => 'enable_single_listing_page',
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ],

            'single_listings_shortcodes'                  => [
                'type'        => 'shortcode-list',
                'buttonLabel' => '<i class="fas fa-magic"></i>',
                'label'       => __( 'Generate shortcodes', 'directorist' ),
                'description' => __( 'Generate single listing shortcodes', 'directorist' ),
                'shortcodes'  => [
                    '[directorist_single_listings_header]',
                    [
                        'shortcode' => '[directorist_single_listing_section label="@@shortcode_label@@" key="@@shortcode_key@@"]',
                        'mapAtts'   => [
                            [
                                'mapAll' => 'single_listings_contents.value.groups',
                                'where'  => [
                                    [
                                        'key'   => 'section_id',
                                        'mapTo' => '@@shortcode_key@@',
                                    ],
                                    [
                                        'key'   => 'label',
                                        'mapTo' => '@@shortcode_label@@',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                'show_if'     => [
                    'where'      => 'enable_single_listing_page',
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ],

            'similar_listings_logics'                     => [
                'type'    => 'radio',
                'name'    => 'similar_listings_logics',
                'label'   => __( 'Similar listings logics', 'directorist' ),
                'options' => [
                    ['id' => 'match_category_nd_location', 'label' => __( 'Must match category and tag', 'directorist' ), 'value' => 'AND'],
                    ['id' => 'match_category_or_location', 'label' => __( 'Must match category or tag', 'directorist' ), 'value' => 'OR'],
                ],
                'value'   => 'OR',
            ],
            'listing_from_same_author'                    => [
                'type'  => 'toggle',
                'label' => __( 'Listing from same author', 'directorist' ),
                'value' => false,
            ],
            'similar_listings_number_of_listings_to_show' => [
                'type'  => 'range',
                'min'   => 0,
                'max'   => 20,
                'label' => __( 'Number of listings to show', 'directorist' ),
                'value' => 0,
            ],
            'similar_listings_number_of_columns'          => [
                'type'  => 'range',
                'min'   => 1,
                'max'   => 10,
                'label' => __( 'Number of columns', 'directorist' ),
                'value' => 3,
            ],

            'search_form_fields'                          => [
                'type'            => 'form-builder',
                'generalSettings' => [
                    'allowAddNewGroup' => false,
                ],
                'groupSettings'   => [
                    'defaultGroupLabel' => 'Section',
                    'canTrash'          => false,
                    'draggable'         => false,
                ],
                'widgets'         => $search_form_widgets,
                'value'           => [
                    'groups' => [
                        [
                            'label'     => __( 'Basic', 'directorist' ),
                            'lock'      => true,
                            'draggable' => false,
                            'fields'    => [],
                        ],
                        [
                            'label'     => __( 'Advanced', 'directorist' ),
                            'lock'      => true,
                            'draggable' => false,
                            'fields'    => [],
                        ],
                    ],
                ],
            ],

            'single_listing_header' => apply_filters( 'directorist_listing_header_layout', [
                'type'           => 'card-builder',
                'template'       => 'listing-header',
                'value'          => '',
                'card-options'   => [
                    'general'          => [
                        'back'          => [
                            'type'    => 'badge',
                            'label'   => __( 'Back', 'directorist' ),
                            'options' => [
                                'title'  => __( 'Back Button Settings', 'directorist' ),
                                'fields' => [
                                    'label' => [
                                        'type'  => 'toggle',
                                        'label' => __( 'Enable', 'directorist' ),
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        'section_title' => [
                            'type'    => 'title',
                            'label'   => __( 'Section Title', 'directorist' ),
                            'options' => [
                                'title'  => __( 'Section Title Options', 'directorist' ),
                                'fields' => [
                                    'use_listing_title' => [
                                        'type'  => 'toggle',
                                        'label' => __( 'Use Listing Title', 'directorist' ),
                                        'value' => false,
                                    ],
                                    'label'             => [
                                        'type'    => 'text',
                                        'label'   => __( 'Label', 'directorist' ),
                                        'value'   => 'Section Title',
                                        'show_if' => [
                                            'where'      => 'single_listing_header.value.options.general.section_title',
                                            'conditions' => [
                                                ['key' => 'use_listing_title', 'compare' => '=', 'value' => false],
                                            ],
                                        ],
                                    ],
                                    'icon'              => [
                                        'type'  => 'icon',
                                        'label' => __( 'Icon', 'directorist' ),
                                        'value' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'content_settings' => [
                        'listing_title'       => [
                            'type'    => 'title',
                            'label'   => __( 'Listing Title', 'directorist' ),
                            'options' => [
                                'title'  => __( 'Listing Title Settings', 'directorist' ),
                                'fields' => [
                                    'enable_title'   => [
                                        'type'  => 'toggle',
                                        'label' => __( 'Show Title', 'directorist' ),
                                        'value' => true,
                                    ],
                                    'enable_tagline' => [
                                        'type'  => 'toggle',
                                        'label' => __( 'Show Tagline', 'directorist' ),
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        'listing_description' => [
                            'type'    => 'title',
                            'label'   => __( 'Description', 'directorist' ),
                            'options' => [
                                'title'  => __( 'Description Settings', 'directorist' ),
                                'fields' => [
                                    'enable' => [
                                        'type'  => 'toggle',
                                        'label' => __( 'Show Description', 'directorist' ),
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'options_layout' => [
                    'header'        => ['back', 'section_title'],
                    'contents_area' => ['title_and_tagline', 'description'],
                ],
                'widgets'        => [
                    'bookmark'       => [
                        'type'  => 'button',
                        'label' => __( 'Bookmark', 'directorist' ),
                        'icon'  => 'las la-bookmark',
                    ],
                    'share'          => [
                        'type'    => 'badge',
                        'label'   => __( 'Share', 'directorist' ),
                        'icon'    => 'las la-share',
                        'options' => [
                            'title'  => __( 'Share Settings', 'directorist' ),
                            'fields' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => __( 'Icon', 'directorist' ),
                                    'value' => 'las la-share',
                                ],
                            ],
                        ],
                    ],
                    'report'         => [
                        'type'    => 'badge',
                        'label'   => __( 'Report', 'directorist' ),
                        'icon'    => 'las la-flag',
                        'options' => [
                            'title'  => __( 'Report Settings', 'directorist' ),
                            'fields' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => __( 'Icon', 'directorist' ),
                                    'value' => 'las la-flag',
                                ],
                            ],
                        ],
                    ],

                    'listing_slider' => [
                        'type'     => 'thumbnail',
                        'label'    => __( 'Listings Slider', 'directorist' ),
                        'icon'     => 'uil uil-text-fields',
                        'can_move' => false,
                        'options'  => [
                            'title'  => __( 'Listings Slider Settings', 'directorist' ),
                            'fields' => [
                                'footer_thumbail' => [
                                    'type'  => 'toggle',
                                    'label' => __( 'Enable Footer Thumbnail', 'directorist' ),
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],
                    'price'          => [
                        'type'  => 'badge',
                        'label' => __( 'Listings Price', 'directorist' ),
                        'icon'  => 'uil uil-text-fields',
                    ],
                    'badges'         => [
                        'type'    => 'badge',
                        'label'   => __( 'Badges', 'directorist' ),
                        'icon'    => 'uil uil-text-fields',
                        'options' => [
                            'title'  => __( 'Badge Settings', 'directorist' ),
                            'fields' => [
                                'new_badge'      => [
                                    'type'  => 'toggle',
                                    'label' => __( 'Display New Badge', 'directorist' ),
                                    'value' => true,
                                ],
                                'popular_badge'  => [
                                    'type'  => 'toggle',
                                    'label' => __( 'Display Popular Badge', 'directorist' ),
                                    'value' => true,
                                ],
                                'featured_badge' => [
                                    'type'  => 'toggle',
                                    'label' => __( 'Display Featured Badge', 'directorist' ),
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],

                    'reviews'        => [
                        'type'  => 'reviews',
                        'label' => __( 'Listings Reviews', 'directorist' ),
                        'icon'  => 'uil uil-text-fields',
                    ],
                    'ratings_count'  => [
                        'type'  => 'ratings-count',
                        'label' => __( 'Listings Ratings', 'directorist' ),
                        'icon'  => 'uil uil-text-fields',
                    ],
                    'category'       => [
                        'type'    => 'badge',
                        'label'   => __( 'Listings Category', 'directorist' ),
                        'icon'    => 'uil uil-text-fields',
                        'show_if' => [
                            'where'      => 'submission_form_fields.value.fields',
                            'conditions' => [
                                ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                            ],
                        ],
                    ],
                    'location'       => [
                        'type'    => 'badge',
                        'label'   => __( 'Listings Location', 'directorist' ),
                        'icon'    => 'uil uil-text-fields',
                        'show_if' => [
                            'where'      => 'submission_form_fields.value.fields',
                            'conditions' => [
                                ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                            ],
                        ],
                    ],
                ],

                'layout'         => [
                    'listings_header' => [
                        'quick_actions' => [
                            'label'             => __( 'Top Right', 'directorist' ),
                            'maxWidget'         => 0,
                            'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                            'acceptedWidgets'   => ['bookmark', 'share', 'report'],
                        ],
                        'thumbnail'     => [
                            'label'             => __( 'Thumbnail', 'directorist' ),
                            'maxWidget'         => 1,
                            'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                            'acceptedWidgets'   => ['listing_slider'],
                        ],
                        'quick_info'    => [
                            'label'             => __( 'Quick info', 'directorist' ),
                            'maxWidget'         => 0,
                            'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                            'acceptedWidgets'   => ['badges', 'price', 'reviews', 'ratings_count', 'category', 'location'],
                        ],
                    ],
                ],
            ] ),

            'listings_card_grid_view'                     => apply_filters( 'directorist_listing_card_layouts', [
                'type'           => 'card-builder',
                'card_templates' => [
                    'grid_view_with_thumbnail'    => [
                        'label'    => __( 'With Preview Image', 'directorist' ),
                        'template' => 'grid-view-with-thumbnail',
                        'widgets'  => $listing_card_widget,
                        'layout'   => $listing_card_grid_view_with_thumbnail_layout,
                    ],
                    'grid_view_without_thumbnail' => [
                        'label'    => __( 'Without Preview Image', 'directorist' ),
                        'template' => 'grid-view-without-thumbnail',
                        'widgets'  => $listing_card_conditional_widget,
                        'layout'   => $listing_card_grid_view_without_thumbnail_layout,
                    ],
                ],
            ] ),

            'listings_card_list_view'                     => apply_filters( 'directorist_listing_list_layouts', [
                'type'           => 'card-builder',
                'card_templates' => [
                    'list_view_with_thumbnail'    => [
                        'label'    => __( 'With Preview Image', 'directorist' ),
                        'template' => 'list-view-with-thumbnail',
                        'widgets'  => $listing_card_conditional_widget,
                        'layout'   => $listing_card_list_view_with_thumbnail_layout,
                    ],
                    'list_view_without_thumbnail' => [
                        'label'    => __( 'Without Preview Image', 'directorist' ),
                        'template' => 'list-view-without-thumbnail',
                        'widgets'  => $listing_card_conditional_widget,
                        'layout'   => $listing_card_list_view_without_thumbnail_layout,
                    ],
                ],
            ] ),

        ] );

        self::$layouts = apply_filters( 'directorist_builder_layouts', [
            'general'              => [
                'label'    => 'General',
                'icon'     => '<span class="uil uil-estate"></span>',
                'sections' => [
                    'labels'          => [
                        'title'  => __( 'Directory icon', 'directorist' ),
                        'fields' => ['icon'],
                    ],

                    'listing_status'  => [
                        'title'  => __( 'Default listing status', 'directorist' ),
                        'fields' => [
                            'new_listing_status',
                            'edit_listing_status',
                        ],
                    ],

                    'expiration'      => [
                        'title'       => __( 'Expiration', 'directorist' ),
                        'description' => __( 'Default time to expire a listing.', 'directorist' ),
                        'fields'      => [
                            'default_expiration',
                        ],
                    ],

                    'default_preview' => [
                        'title'       => __( 'Default Preview', 'directorist' ),
                        'description' => __( 'This image will be used when listing preview image is not present. Leave empty to hide the preview image completely.', 'directorist' ),
                        'fields'      => [
                            'preview_image',
                        ],
                    ],

                    'export_import'   => [
                        'title'       => __( 'Export The Config File', 'directorist' ),
                        'description' => __( 'Export all the form, layout and settings', 'directorist' ),
                        'fields'      => [
                            'import_export',
                        ],
                    ],
                ],
            ],

            'submission_form'      => [
                'label'   => __( 'Add Listing Form', 'directorist' ),
                'icon'    => '<span class="uil uil-file-edit-alt"></span>',
                'submenu' => [
                    'form_fields' => [
                        'label'     => __( 'Form Fields', 'directorist' ),
                        'container' => 'wide',
                        'sections'  => [
                            'form_fields' => [
                                'title'       => __( 'Select or create fields for the add listing form', 'directorist' ),
                                'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/form-and-layout-builder/">' . __( 'Need help?', 'directorist' ) . ' </a>',
                                'fields'      => [
                                    'submission_form_fields',
                                ],
                            ],
                        ],
                    ],
                    'settings'    => [
                        'label'    => __( 'Settings', 'directorist' ),
                        'sections' => apply_filters( 'atbdp_submission_form_settings', [
                            'terms_and_conditions' => [
                                'title'     => __( 'Terms and Conditions', 'directorist' ),
                                'container' => 'short-width',
                                'fields'    => [
                                    'listing_terms_condition',
                                    'require_terms_conditions',
                                    'terms_label',
                                ],
                            ],
                            'privacy_and_policy'   => [
                                'title'     => __( 'Privacy and Policy', 'directorist' ),
                                'container' => 'short-width',
                                'fields'    => [
                                    'listing_privacy',
                                    'require_privacy',
                                    'privacy_label',
                                ],
                            ],
                            'submittion_settings'  => [
                                'title'     => __( 'Submission Settings', 'directorist' ),
                                'container' => 'short-width',
                                'fields'    => [
                                    'preview_mode',
                                    'submit_button_label',
                                ],
                            ],
                        ] ),
                    ],
                ],
            ],

            'single_page_layout'   => [
                'label'   => __( 'Single Page Layout', 'directorist' ),
                'icon'    => '<span class="uil uil-credit-card"></span>',
                'submenu' => [
                    'listing_header'   => [
                        'label'     => __( 'Listing Header', 'directorist' ),
                        'container' => 'wide',
                        'sections'  => [
                            'listing_header' => [
                                'title'       => __( 'Listing Header', 'directorist' ),
                                'title_align' => 'center',
                                'fields'      => [
                                    'single_listing_header',
                                ],
                            ],
                        ],
                    ],
                    'contents'         => [
                        'label'     => __( 'Contents', 'directorist' ),
                        'container' => 'wide',
                        'sections'  => [
                            'contents' => [
                                'title'       => __( 'Contents', 'directorist' ),
                                'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/single-listings-layout/"> ' . __( 'Need help?', 'directorist' ) . ' </a>',
                                'fields'      => [
                                    'single_listings_contents',
                                ],
                            ],
                        ],
                    ],
                    'similar_listings' => [
                        'label'    => __( 'Other Settings', 'directorist' ),
                        'sections' => [
                            'other'         => [
                                'title'  => __( 'Similar Listings', 'directorist' ),
                                'fields' => [
                                    'similar_listings_logics',
                                    'listing_from_same_author',
                                    'similar_listings_number_of_listings_to_show',
                                    'similar_listings_number_of_columns',
                                ],
                            ],
                            'page_settings' => [
                                'fields' => [
                                    'enable_single_listing_page',
                                    'single_listing_page',
                                    'single_listings_shortcodes',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'listings_card_layout' => [
                'label'   => __( 'All Listing Layout', 'directorist' ),
                'icon'    => '<span class="uil uil-list-ul"></span>',
                'submenu' => [
                    'grid_view' => [
                        'label'     => __( 'All Listing Grid Layout', 'directorist' ),
                        'container' => 'wide',
                        'sections'  => [
                            'listings_card' => [
                                'title'       => __( 'Create and customize the listing card for grid view', 'directorist' ),
                                'title_align' => 'center',
                                'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/multiple-directories/"> ' . __( 'Need help?', 'directorist' ) . ' </a>' . __( 'Read the documentation or open a ticket in our helpdesk.', 'directorist' ),
                                'fields'      => [
                                    'listings_card_grid_view',
                                ],
                            ],
                        ],
                    ],
                    'list_view' => [
                        'label'     => __( 'All Listing List Layout', 'directorist' ),
                        'container' => 'full-width',
                        'sections'  => [
                            'listings_card' => [
                                'title'       => __( 'Create and customize the listing card for listing view', 'directorist' ),
                                'title_align' => 'center',
                                'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/multiple-directories/"> ' . __( 'Need help?', 'directorist' ) . ' </a>' . __( 'Read the documentation or open a ticket in our helpdesk.', 'directorist' ),
                                'fields'      => [
                                    'listings_card_list_view',
                                ],
                            ],
                        ],
                    ],
                ],

            ],
            'search_forms'         => [
                'label'     => __( 'Search Form', 'directorist' ),
                'icon'      => '<span class="uil uil-search"></span>',
                'container' => 'wide',
                'sections'  => [
                    'form_fields' => [
                        'title'       => __( 'Customize the search form for this listing type', 'directorist' ),
                        'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/search-form-layout/"> ' . __( 'Need help?', 'directorist' ) . ' </a>',
                        'fields'      => [
                            'search_form_fields',
                        ],
                    ],
                ],
            ],
        ] );

        self::$fields = apply_filters( 'directorist/builder/fields', self::$fields );

        self::$layouts = apply_filters( 'directorist/builder/layouts', self::$layouts );

        // Conditional Fields
        // -----------------------------
        // Guest Submission
        if ( get_directorist_option( 'guest_listings', 1 ) == '1' )  {
            self::$fields['guest_email_label'] = [
                'label' => __( 'Guest Email Label', 'directorist' ),
                'type'  => 'text',
                'value' => 'Your Email',
            ];

            self::$fields['guest_email_placeholder'] = [
                'label' => __( 'Guest Email Placeholder', 'directorist' ),
                'type'  => 'text',
                'value' => 'example@email.com',
            ];

            self::$layouts['submission_form']['submenu']['settings']['sections']['guest_submission'] = [
                'title'       => __( 'Guest Listing Submission', 'directorist' ),
                'description' => __( 'Need Help?', 'directorist' ),
                'container'   => 'short-width',
                'fields'      => [
                    'guest_email_label',
                    'guest_email_placeholder',
                ],
            ];
        }

        self::$options = [
            'name' => [
                'type'        => 'text',
                'placeholder' => 'Name *',
                'value'       => '',
                'rules'       => [
                    'required' => true,
                ],
                'input_style' => [
                    'class_names' => 'cptm-form-control-light',
                ],
                'is-hidden'   => true,
            ],
        ];

        $config = [
            'submission'   => [
                'url'  => admin_url( 'admin-ajax.php' ),
                'with' => [
                    'action'            => 'save_post_type_data',
                    'directorist_nonce' => wp_create_nonce( directorist_get_nonce_key() ),
                ],
            ],
            'fields_group' => [
                'general_config' => [
                    'icon',
                    'singular_name',
                    'plural_name',
                    'permalink',
                    'preview_image',
                ],
            ],
        ];

        /**
         * Filter directory builder `config` data.
         *
         * @since 7.0.6.0
         */
        $config = apply_filters( 'directorist/builder/config', $config );

        self::$config = $config;
    }

    protected static function get_assign_to_field( array $args = [] ) {
        $default = [
            'type' => 'radio',
            'label' => __('Assign to', 'directorist'),
            'value' => 'form',
            'options' => [
                [
                    'label' => __('Form', 'directorist'),
                    'value' => 'form',
                ],
                [
                    'label' => __('Category', 'directorist'),
                    'value' => 'category',
                ],
            ],
        ];

        return array_merge( $default, $args );
    }

	protected static function get_file_upload_field_options() {
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

    public function get_fields() {
        return self::$fields;
    }

    public function get_layouts() {
        return self::$layouts;
    }

    public function get_config() {
        return self::$config;
    }

    public function get_options() {
        return self::$options;
    }

    public static function get() {
        self::prepare_data();
        return [
            'fields'  => self::$fields,
            'layouts' => self::$layouts,
            'config'  => self::$config,
            'options' => self::$options,
        ];
    }

}
