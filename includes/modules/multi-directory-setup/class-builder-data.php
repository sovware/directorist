<?php

namespace Directorist\Multi_Directory;
use ATBDP_Permalink;

use function PHPSTORM_META\type;

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
                'widgets'       => require_once __DIR__ .  '/builder-preset-fields.php',
            ],

            'custom' => [
                'title'         => __( 'Custom Fields', 'directorist' ),
                'description'   => __( 'Click on a field type you want to create.', 'directorist' ),
                'allowMultiple' => true,
                'widgets'       => require_once __DIR__ .  '/builder-custom-fields.php',

            ],
        ];

        $single_listings_contents_widgets = [
            'preset_widgets' => [
                'title'         => __( 'Preset Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'template'      => 'submission_form_fields',
                'widgets'       => apply_filters( 'atbdp_single_listing_content_widgets', [

                    'image_upload' => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-tag',
                            ],
                            'footer_thumbnail' => [
                                'type'  => 'toggle',
                                'label' => __( 'Footer Thumbnail', 'directorist' ),
                                'value' => true,
                            ],
                        ]
                    ],
                    'description' => [
                        'options' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-tag',
                            ],
                        ]
                    ],

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
                                'field_type' => 'advanced',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                                'field_type' => 'advanced',
                            ],
                            'review_cookies_consent' => [
                                'label' => __( 'Show Cookies Consent', 'directorist' ),
                                'type'  => 'toggle',
                                'value' => false,
                            ],
                            'review_enable_gdpr_consent' => [
                                'label' => __( 'Enable GDPR Consent', 'directorist' ),
                                'type'  => 'toggle',
                                'value' => false,
                            ],
                            'review_gdpr_consent_label' => [
                                'label'       => __( 'Consent Label', 'directorist' ),
                                'type'        => 'textarea',
                                'editor'      => 'wp_editor',
                                'editorID'    => 'wp_editor_terms_privacy',
                                'value'       => sprintf(
                                    __( 'I have read and agree to the <a href="%s" target="_blank">Privacy Policy</a> and <a href="%s" target="_blank">Terms of Service</a>', 'directorist' ),
                                    ATBDP_Permalink::get_privacy_policy_page_url(),
                                    ATBDP_Permalink::get_terms_and_conditions_page_url(),
                                ),
                            ],
                        ],

                        'accepted_widgets' => [
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'review',
                                'widget_child_name' => 'review_comment',
                            ],
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'review',
                                'widget_child_name' => 'review_email',
                            ],
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'review',
                                'widget_child_name' => 'review_name',
                            ],
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'review',
                                'widget_child_name' => 'review_website',
                            ],
                        ],
                        'widgets' => [
                            'review_comment' => [
                                'label'    => __( 'Comment', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => '',
                                    ],
                                ]
                            ],
                            'review_email' => [
                                'label'    => __( 'Email', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'label' => [
                                        'label' => __( 'Label', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => '',
                                    ],
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => '',
                                    ],
                                ]
                            ],
                            'review_name' => [
                                'label'    => __( 'Name', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'label' => [
                                        'label' => __( 'Label', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => '',
                                    ],
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => '',
                                    ],
                                ]
                            ],
                            'review_website' => [
                                'label'    => __( 'Website', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'enable' => [
                                        'label'   => __( 'Show Website Field?', 'directorist' ),
                                        'type'    => 'toggle',
                                        'value'   => false,
                                    ],
                                    'label' => [
                                        'label'   => __( 'Label', 'directorist' ),
                                        'type'    => 'text',
                                        'value'   => '',
                                    ],
                                    'placeholder' => [
                                        'label'   => __( 'Placeholder', 'directorist' ),
                                        'type'    => 'text',
                                        'value'   => '',
                                    ],
                                ]
                            ],
                        ]

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
                            'display_email'        => [
                                'type'  => 'toggle',
                                'label' => __( 'Display Email', 'directorist' ),
                                'value' => true,
                            ],
                            'custom_block_id'      => [
                                'type'       => 'text',
                                'label'      => __( 'Custom block ID', 'directorist' ),
                                'value'      => '',
                                'field_type' => 'advanced',
                            ],
                            'custom_block_classes' => [
                                'type'       => 'text',
                                'label'      => __( 'Custom block Classes', 'directorist' ),
                                'value'      => '',
                                'field_type' => 'advanced',
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
                                'field_type' => 'advanced',
                            ],
                            'custom_block_classes' => [
                                'type'  => 'text',
                                'label' => __( 'Custom block Classes', 'directorist' ),
                                'value' => '',
                                'field_type' => 'advanced',
                            ],
                        ],
                        'accepted_widgets' => [
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'contact_listings_owner',
                                'widget_child_name' => 'contact_name',
                            ],
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'contact_listings_owner',
                                'widget_child_name' => 'contact_email',
                            ],
                            [
                                'widget_group'      => 'other_widgets',
                                'widget_name'       => 'contact_listings_owner',
                                'widget_child_name' => 'contact_message',
                            ],
                        ],
                        'widgets' => [
                            'contact_name' => [
                                'label'    => __( 'Name', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'enable' => [
                                        'label' => __( 'Enable', 'directorist' ),
                                        'type'  => 'toggle',
                                        'value' => true,
                                    ],
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => __( 'Name', 'directorist' ),
                                    ],
                                ]
                            ],
                            'contact_email' => [
                                'label'    => __( 'Email', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => __( 'Email', 'directorist' ),
                                    ],
                                ]
                            ],
                            'contact_message' => [
                                'label'    => __( 'Message', 'directorist' ),
                                'canTrash' => false,
                                'canMove'  => false,
                                'options'  => [
                                    'placeholder' => [
                                        'label' => __( 'Placeholder', 'directorist' ),
                                        'type'  => 'text',
                                        'value' => __( 'Message...', 'directorist' ),
                                    ],
                                ]
                            ],
                        ]
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
                                'type'       => 'text',
                                'label'      => __( 'Custom block ID', 'directorist' ),
                                'value'      => '',
                                'field_type' => 'advanced',
                            ],
                            'custom_block_classes' => [
                                'type'       => 'text',
                                'label'      => __( 'Custom block Classes', 'directorist' ),
                                'value'      => '',
                                'field_type' => 'advanced',
                            ],
                            'similar_listings_logics'                     => [
                                'type'    => 'radio',
                                'name'    => 'similar_listings_logics',
                                'label'   => __( 'Related listings criteria', 'directorist' ),
                                'options' => [
                                    ['id' => 'match_category_nd_location', 'label' => __( 'Match both category and tag', 'directorist' ), 'value' => 'AND'],
                                    ['id' => 'match_category_or_location', 'label' => __( 'Match either category or tag', 'directorist' ), 'value' => 'OR'],
                                ],
                                'value'   => 'OR',
                            ],
                            'listing_from_same_author'                    => [
                                'type'  => 'toggle',
                                'label' => __( 'Display listings by the same author', 'directorist' ),
                                'value' => false,
                            ],
                            'similar_listings_number_of_listings_to_show' => [
                                'type'  => 'range',
                                'min'   => 0,
                                'max'   => 20,
                                'label' => __( 'Number of listings to display', 'directorist' ),
                                'value' => 3,
                            ],
                            'similar_listings_number_of_columns'          => [
                                'type'  => 'range',
                                'min'   => 1,
                                'max'   => 4,
                                'label' => __( 'Number of columns', 'directorist' ),
                                'value' => 2,
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
                        'label'   => __( 'Search Box', 'directorist' ),
                        'options' => [
                            'label' => [
                                'type'  => 'text',
                                'label' => __( 'label', 'directorist' ),
                                'value' => 'Title',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => __( 'Placeholder', 'directorist' ),
                                'value' => 'What are you looking for?',
                            ],
                            'required'    => [
                                'type'  => 'toggle',
                                'label' => __( 'Required', 'directorist' ),
                                'value' => false,
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
                                'value' => 'Location',
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
                            'label' => [
                                'type'  => 'text',
                                'label'  => __( 'Label', 'directorist' ),
                                'value' => 'Pricing',
                            ],
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
                        'canTrash'          => false,
                        'draggable'         => false,
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
                            'type'      => [
                                'type'    => 'select',
                                'label'   => __( 'Search Type', 'directorist' ),
                                'value'   => 'number',
                                'options' => [
                                    ['value' => 'number', 'label' => 'Input'],
                                    ['value' => 'range', 'label' => 'Range'],
                                    ['value' => 'dropdown', 'label' => 'Dropdown'],
                                    ['value' => 'radio', 'label' => 'Radio'],
                                ],
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
                            'max_radius_distance' => [
                                'type'  => 'range',
                                'label' => __( 'Maximum Radius Distance', 'directorist' ),
                                'min'   => 0,
                                'max'   => 10000,
                                'value' => 1000,
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
                            'value' => '...',
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
                'label'   => __( 'Pricing', 'directorist' ),
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
                        "listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge", "rating", "pricing", "posted_date",
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
                    'acceptedWidgets' => [ "user_avatar" ],
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
                    'acceptedWidgets' => ["listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge",  "rating", "pricing", "posted_date"],
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
                'default-img'  => DIRECTORIST_ASSETS . 'images/grid.jpg',
                'value'        => '',
            ],

            'import_export'                               => [
                'button-label'     => __( 'Export', 'directorist' ),
                'export-file-name' => 'directory',
                'type'             => 'export',
            ],

            'default_expiration'                          => [
                'type'        => 'number',
                'value'       => 30,
                'placeholder' => '365',
                'rules'       => [
                    'required' => true,
                ],
            ],

            'new_listing_status'                          => [
                'label'   => __( 'New listing', 'directorist' ),
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
                'label'   => __( 'Edited listing', 'directorist' ),
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
                    'icon' => [
                        'type'  => 'icon',
                        'label'  => __( 'Block/Section Icon', 'directorist' ),
                        'value' => '',
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
            'enable_sidebar' => [
                'label' => __('Enable Sidebar', 'directorist'),
                'type'  => 'toggle',
                'value' => true,
            ],
            'preview_mode'                                => [
                'label' => __( 'Enable Listing Preview', 'directorist' ),
                'type'  => 'toggle',
                'value' => true,
            ],

            // Submit Button
            'submit_button_label_old'                         => [
                'label' => __( 'Submit Button Label', 'directorist' ),
                'type'  => 'text',
                'value' => __( 'Save & Preview', 'directorist' ),
            ],

            'submit_button_label'                         => [
                'label' => __( 'Submit Button Label', 'directorist' ),
                'type'  => 'editable-button',
                'value' => __( 'Update', 'directorist' ),
            ],

            'single_listings_contents'                    => [
                'type'            => 'form-builder',
                'widgets'         => $single_listings_contents_widgets,
                'generalSettings' => [
                    'addNewGroupButtonLabel' => __( 'Add Section', 'directorist' ),
                ],
                'groupFields'     => [
                    'label'                => [
                        'type'  => 'text',
                        'label' => __( 'Label', 'directorist' ),
                        'value' => 'Section',
                    ],
                    'icon'                 => [
                        'type'  => 'icon',
                        'label' => __( 'Block/Section Icon', 'directorist' ),
                        'value' => '',
                    ],
                    'section_id'           => [
                        'type'    => 'text',
                        'disable' => true,
                        'label'   => 'Section ID',
                        'value'   => '',
                        'field_type' => 'advanced',
                    ],
                    'custom_block_id'      => [
                        'type'  => 'text',
                        'label' => __( 'Custom block ID', 'directorist' ),
                        'value' => '',
                        'field_type' => 'advanced',
                    ],
                    'custom_block_classes' => [
                        'type'  => 'text',
                        'label' => __( 'Custom block Classes', 'directorist' ),
                        'value' => '',
                        'field_type' => 'advanced',
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
                'label'     => __( 'Enable Custom Single Listing Page', 'directorist' ),
                'description' => __( 'Enabling this option will replace the default single listing page. After enabling you must create and assign a new page with generated shortcodes to display single listing content.
', 'directorist' ),
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
                            'label'     => __( 'Search Bar', 'directorist' ),
                            'lock'      => true,
                            'draggable' => false,
                            'fields'    => [],
                        ],
                        [
                            'label'     => __( 'Search Filter', 'directorist' ),
                            'lock'      => true,
                            'draggable' => false,
                            'fields'    => [],
                        ],
                    ],
                ],
            ],

            'single_listing_header' => apply_filters( 'directorist_listing_header_layout', [
                'type' => 'card-builder',
                'template' => 'listing-header',
                'value' => '',
                'card-options' => [
                    'general' => [
                        'back' => [
                            'type' => "badge",
                            'label' => __( "Back", 'directorist' ),
                            'options' => [
                                'title' => __( "Back Button Settings", "directorist" ),
                                'fields' => [
                                    'label' => [
                                        'type' => "toggle",
                                        'label' => __( "Enable", "directorist" ),
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        'section_title' => [
                            'type' => "title",
                            'label' => __( "Section Title", "directorist" ),
                            'options' => [
                                'title' => __( "Section Title Options", "directorist" ),
                                'fields' => [
                                    'use_listing_title' => [
                                        'type' => "toggle",
                                        'label' => __( "Use Listing Title", "directorist" ),
                                        'value' => false,
                                    ],
                                    'label' => [
                                        'type' => "text",
                                        'label' => __( "Label", "directorist" ),
                                        'value' => "Section Title",
                                        'show_if' => [
                                            'where' => "single_listing_header.value.options.general.section_title",
                                            'conditions' => [
                                                ['key' => 'use_listing_title', 'compare' => '=', 'value' => false],
                                            ],
                                        ],
                                    ],
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => __( "Icon", "directorist" ),
                                        'value' => "",
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'content_settings' => [
                        'listing_title' => [
                            'type' => "title",
                            'label' => __( "Listing Title", "directorist" ),
                            'options' => [
                                'title' => __( "Listing Title Settings", "directorist" ),
                                'fields' => [
                                    'enable_title' => [
                                        'type' => "toggle",
                                        'label' => __( "Show Title", "directorist" ),
                                        'value' => true,
                                    ],
                                    'enable_tagline' => [
                                        'type' => "toggle",
                                        'label' => __( "Show Tagline", "directorist" ),
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'options_layout' => [
                    'header' => ['back', 'section_title'],
                    'contents_area' => ['title_and_tagline', 'description'],
                ],
                'widgets' => [
                    'back' => [
                        'type' => "button",
                        'label' => __( "Back", "directorist" ),
                        'icon' => 'las la-arrow-left',
                    ],
                    'title' => [
                        'type' => "title",
                        'label' => __( "Listing Title", "directorist" ),
                        'icon' => 'las la-heading',
                        'options' => [
                            'title' => __( "Listing Title Settings", "directorist" ),
                            'fields' => [
                                'enable_tagline' => [
                                    'type' => "toggle",
                                    'label' => __( "Show Tagline", "directorist" ),
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],
                    'slider' => [
                        'type' => "thumbnail",
                        'label' => __( "Listing Image/Slider", "directorist" ),
                        'icon' => 'las la-image',
                        'options' => [
                            'title' => __( "Image/Slider Settings", "directorist" ),
                            'fields' => [
                                'footer_thumbnail' => [
                                    'type' => "toggle",
                                    'label' => __( "Show Thumbnail", "directorist" ),
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],

                    'bookmark' => [
                        'type' => "button",
                        'label' => __( "Bookmark", "directorist" ),
                        'icon' => 'las la-bookmark',
                    ],
                    'share' => [
                        'type' => "badge",
                        'label' => __( "Share", "directorist" ),
                        'icon' => 'las la-share',
                        'options' => [
                            'title' => __( "Share Settings", "directorist" ),
                            'fields' => [
                                'icon' => [
                                    'type' => "icon",
                                    'label' => __( "Icon", "directorist" ),
                                    'value' => 'las la-share',
                                ],
                            ],
                        ],
                    ],
                    'report' => [
                        'type' => "badge",
                        'label' => __( "Report", "directorist" ),
                        'icon' => 'las la-flag',
                        'options' => [
                            'title' => __( "Report Settings", "directorist" ),
                            'fields' => [
                                'icon' => [
                                    'type' => "icon",
                                    'label' => __( "Icon", "directorist" ),
                                    'value' => 'las la-flag',
                                ],
                            ],
                        ],
                    ],

                    'price' => [
                        'type' => "badge",
                        'label' => __( "Pricing", "directorist" ),
                        'icon' => 'uil uil-text-fields',
                    ],
                    'badges' => [
                        'type' => "badge",
                        'label' => __( "Badges", "directorist" ),
                        'icon' => 'uil uil-text-fields',
                        'options' => [
                            'title' => __( "Badge Settings", "directorist" ),
                            'fields' => [
                                'new_badge' => [
                                    'type' => "toggle",
                                    'label' => __( "Display New Badge", "directorist" ),
                                    'value' => true,
                                ],
                                'popular_badge' => [
                                    'type' => "toggle",
                                    'label' => __( "Display Popular Badge", "directorist" ),
                                    'value' => true,
                                ],
                                'featured_badge' => [
                                    'type' => "toggle",
                                    'label' => __( "Display Featured Badge", "directorist" ),
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],
                    'ratings_count' => [
                        'type' => "ratings-count",
                        'label' => __( "Rating", "directorist" ),
                        'icon' => 'uil uil-text-fields',
                    ],
                    'category' => [
                        'type' => "badge",
                        'label' => __( "Listings Category", "directorist" ),
                        'icon' => 'uil uil-text-fields',
                        'show_if' => [
                            'where' => "submission_form_fields.value.fields",
                            'conditions' => [
                                ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                            ],
                        ],
                    ],
                    'location' => [
                        'type' => "badge",
                        'label' => __( "Listings Location", "directorist" ),
                        'icon' => 'uil uil-text-fields',
                        'show_if' => [
                            'where' => "submission_form_fields.value.fields",
                            'conditions' => [
                                ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                            ],
                        ],
                    ],
                ],

                'layout' => [
                    [
                        'type' => 'placeholder_group',
                        'placeholderKey' => 'quick-widgets-placeholder',
                        'placeholders' => [
                            [
                                'type'              => 'placeholder_item',
                                'placeholderKey'    => 'quick-info-placeholder',
                                'label'             => __( 'Quick info', 'directorist' ),
                                'maxWidget'         => 1,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets'   => ['back'],
                            ],
                            [
                                'type'              => 'placeholder_item',
                                'placeholderKey'    => 'quick-action-placeholder',
                                'label'             => __( 'Quick Action', 'directorist' ),
                                'maxWidget'         => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets'   => [ 'bookmark', 'share', 'report' ],
                            ],
                        ],
                    ],
                    [
                        'type'              => 'placeholder_item',
                        'placeholderKey'    => 'listing-title-placeholder',
                        'label'             => __( 'Listing Title', 'directorist' ),
                        'maxWidget'         => 1,
                        'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                        'acceptedWidgets'   => ['title'],
                    ],
                    [
                        'type'              => 'placeholder_item',
                        'placeholderKey'    => 'more-widgets-placeholder',
                        'label'             => __( 'More Widgets', 'directorist' ),
                        'maxWidget'         => 0,
                        'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                        'acceptedWidgets'   => [ 'location', 'category', 'ratings_count', 'badges', 'price' ],
                        'rejectedWidgets'   => ['slider'],
                    ],
                    [
                        'type'            => 'placeholder_item',
                        'label'           => 'Slider Widget',
                        'placeholderKey'  => 'slider-placeholder',
                        'selectedWidgets' => ['slider'],
                        'acceptedWidgets' => ['slider'],
                        'maxWidget'       => 1,
                        'canDelete'       => true,
                        'insertByButton'  => true,
                        'insertButton'    => [
                            'label' => 'Add Image/Slider'
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
                'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.65155 0.890914C7.87975 0.829127 8.12027 0.829127 8.34847 0.890914C8.61339 0.962641 8.83642 1.13775 9.01443 1.2775C9.03142 1.29084 9.048 1.30386 9.06418 1.31644L13.5857 4.83319C13.6031 4.84673 13.6204 4.86013 13.6375 4.87343C13.8886 5.06828 14.1098 5.23995 14.2747 5.46282C14.4193 5.6584 14.5271 5.87874 14.5927 6.11301C14.6674 6.37996 14.6671 6.65996 14.6667 6.97779C14.6667 6.99948 14.6667 7.02133 14.6667 7.04338V11.8924C14.6667 12.2438 14.6667 12.547 14.6463 12.7967C14.6248 13.0602 14.5772 13.3224 14.4487 13.5746C14.2569 13.951 13.951 14.2569 13.5747 14.4487C13.3224 14.5772 13.0603 14.6248 12.7967 14.6463C12.547 14.6667 12.2438 14.6667 11.8924 14.6667H4.10763C3.75621 14.6667 3.45304 14.6667 3.20333 14.6463C2.93976 14.6248 2.67762 14.5772 2.42536 14.4487C2.04904 14.2569 1.74308 13.951 1.55133 13.5746C1.4228 13.3224 1.37526 13.0602 1.35372 12.7967C1.33332 12.547 1.33333 12.2438 1.33334 11.8924L1.33334 7.04338C1.33334 7.02133 1.33332 6.99947 1.3333 6.97779C1.33295 6.65996 1.33265 6.37995 1.40737 6.11301C1.47295 5.87874 1.58071 5.6584 1.72537 5.46282C1.89021 5.23995 2.11142 5.06828 2.36251 4.87342C2.37964 4.86013 2.39691 4.84672 2.41431 4.83319L6.93585 1.31644C6.95202 1.30386 6.9686 1.29084 6.9856 1.2775C7.1636 1.13775 7.38664 0.962641 7.65155 0.890914ZM6.66668 13.3333H9.33335V9.06667C9.33335 8.86898 9.33283 8.76081 9.32648 8.68309C9.32622 8.67999 9.32597 8.67706 9.32571 8.6743C9.32295 8.67404 9.32002 8.67379 9.31692 8.67353C9.2392 8.66718 9.13103 8.66667 8.93334 8.66667H7.06668C6.86899 8.66667 6.76082 8.66718 6.6831 8.67353C6.68001 8.67379 6.67708 8.67404 6.67431 8.6743C6.67406 8.67706 6.6738 8.67999 6.67355 8.68309C6.6672 8.76081 6.66668 8.86898 6.66668 9.06667V13.3333ZM10.6667 13.3333L10.6667 9.04541C10.6667 8.87715 10.6667 8.71329 10.6554 8.57451C10.6429 8.42212 10.6136 8.24229 10.5214 8.06135C10.3935 7.81046 10.1896 7.60649 9.93867 7.47866C9.75772 7.38646 9.57789 7.35708 9.4255 7.34463C9.28672 7.33329 9.12286 7.33331 8.9546 7.33333H7.04542C6.87717 7.33331 6.7133 7.33329 6.57453 7.34463C6.42213 7.35708 6.24231 7.38646 6.06136 7.47866C5.81047 7.60649 5.6065 7.81046 5.47867 8.06135C5.38647 8.24229 5.35709 8.42212 5.34464 8.57451C5.3333 8.71328 5.33332 8.87714 5.33334 9.0454L5.33335 13.3333H4.13335C3.74898 13.3333 3.50079 13.3328 3.3119 13.3174C3.13079 13.3026 3.06365 13.2775 3.03068 13.2607C2.90524 13.1968 2.80326 13.0948 2.73934 12.9693C2.72255 12.9364 2.69743 12.8692 2.68263 12.6881C2.6672 12.4992 2.66668 12.251 2.66668 11.8667V7.04338C2.66668 6.62272 2.67247 6.53988 2.69135 6.47241C2.71321 6.39432 2.74913 6.32088 2.79735 6.25568C2.83901 6.19935 2.90085 6.14392 3.2329 5.88566L7.75444 2.36891C7.87904 2.272 7.94504 2.22117 7.99515 2.1877C7.99684 2.18656 7.99847 2.18549 8.00001 2.18447C8.00156 2.18549 8.00318 2.18656 8.00488 2.1877C8.05498 2.22117 8.12098 2.272 8.24559 2.36891L12.7671 5.88566C13.0992 6.14392 13.161 6.19935 13.2027 6.25568C13.2509 6.32087 13.2868 6.39432 13.3087 6.47241C13.3276 6.53988 13.3333 6.62272 13.3333 7.04338V11.8667C13.3333 12.251 13.3328 12.4992 13.3174 12.6881C13.3026 12.8692 13.2775 12.9364 13.2607 12.9693C13.1968 13.0948 13.0948 13.1968 12.9693 13.2607C12.9364 13.2775 12.8692 13.3026 12.6881 13.3174C12.4992 13.3328 12.251 13.3333 11.8667 13.3333H10.6667Z" fill="currentColor"/>
                                </svg>',
                'container' => 'short-wide',
                'sections' => [
                    'labels'          => [
                        'title'  => __( 'Directory icon', 'directorist' ),
                        'description' => __( 'Select a directory type icon to display in all listings, add listing, and search pages.', 'directorist' ),
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
                        'title'             => __( 'Default listing expiration days', 'directorist' ),
                        'description'       => __( 'Set the number of days before a listing automatically expires.', 'directorist' ),
                        'fields'            => [
                            'default_expiration',
                        ],
                    ],

                    'default_preview' => [
                        'title'       => __( 'Default listing preview image', 'directorist' ),
                        'description' => __( 'This image will appear when a listing does not have a preview image uploaded. Leave it empty to hide the preview image.', 'directorist' ),
                        'fields'      => [
                            'preview_image',
                        ],
                    ],

                    'export_import'   => [
                        'title'       => __( 'Export the config file', 'directorist' ),
                        'description' => __( 'Export all the form, layout and settings', 'directorist' ),
                        'fields'      => [
                            'import_export',
                        ],
                    ],
                ],
            ],

            'submission_form'      => [
                'label'   => __( 'Add Listing Form', 'directorist' ),
                'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83913 0.666626H10.1609C10.6975 0.666618 11.1404 0.666611 11.5012 0.696089C11.8759 0.726706 12.2204 0.792415 12.544 0.957276C13.0457 1.21294 13.4537 1.62089 13.7094 2.12265C13.8742 2.44621 13.9399 2.79068 13.9705 3.16541C14 3.5262 14 3.9691 14 4.50574V6.99996C14 7.36815 13.7015 7.66663 13.3333 7.66663C12.9651 7.66663 12.6667 7.36815 12.6667 6.99996V4.53329C12.6667 3.96224 12.6661 3.57404 12.6416 3.27399C12.6178 2.98171 12.5745 2.83224 12.5213 2.72797C12.3935 2.47709 12.1895 2.27312 11.9387 2.14528C11.8344 2.09216 11.6849 2.04887 11.3926 2.02499C11.0926 2.00048 10.7044 1.99996 10.1333 1.99996H5.86667C5.29561 1.99996 4.90742 2.00048 4.60736 2.02499C4.31508 2.04887 4.16561 2.09216 4.06135 2.14528C3.81046 2.27312 3.60649 2.47709 3.47866 2.72797C3.42553 2.83224 3.38225 2.98171 3.35837 3.27399C3.33385 3.57404 3.33333 3.96224 3.33333 4.53329V11.4666C3.33333 12.0377 3.33385 12.4259 3.35837 12.7259C3.38225 13.0182 3.42553 13.1677 3.47866 13.2719C3.60649 13.5228 3.81046 13.7268 4.06135 13.8546C4.16561 13.9078 4.31508 13.951 4.60736 13.9749C4.90742 13.9994 5.29561 14 5.86667 14H8C8.36819 14 8.66667 14.2984 8.66667 14.6666C8.66667 15.0348 8.36819 15.3333 8 15.3333H5.83912C5.30248 15.3333 4.85958 15.3333 4.49878 15.3038C4.12405 15.2732 3.77958 15.2075 3.45603 15.0426C2.95426 14.787 2.54631 14.379 2.29065 13.8773C2.12579 13.5537 2.06008 13.2092 2.02946 12.8345C1.99998 12.4737 1.99999 12.0308 2 11.4942V4.50576C1.99999 3.96911 1.99998 3.52621 2.02946 3.16541C2.06008 2.79068 2.12579 2.44621 2.29065 2.12265C2.54631 1.62089 2.95426 1.21294 3.45603 0.957276C3.77958 0.792415 4.12405 0.726706 4.49878 0.696089C4.85958 0.666611 5.30249 0.666618 5.83913 0.666626ZM4.66667 4.66663C4.66667 4.29844 4.96514 3.99996 5.33333 3.99996H10.6667C11.0349 3.99996 11.3333 4.29844 11.3333 4.66663C11.3333 5.03482 11.0349 5.33329 10.6667 5.33329H5.33333C4.96514 5.33329 4.66667 5.03482 4.66667 4.66663ZM4.66667 7.33329C4.66667 6.9651 4.96514 6.66663 5.33333 6.66663H9.33333C9.70152 6.66663 10 6.9651 10 7.33329C10 7.70148 9.70152 7.99996 9.33333 7.99996H5.33333C4.96514 7.99996 4.66667 7.70148 4.66667 7.33329ZM4.66667 9.99996C4.66667 9.63177 4.96514 9.33329 5.33333 9.33329H6.66667C7.03486 9.33329 7.33333 9.63177 7.33333 9.99996C7.33333 10.3682 7.03486 10.6666 6.66667 10.6666H5.33333C4.96514 10.6666 4.66667 10.3682 4.66667 9.99996ZM12 9.33329C12.3682 9.33329 12.6667 9.63177 12.6667 9.99996V11.3333H14C14.3682 11.3333 14.6667 11.6318 14.6667 12C14.6667 12.3681 14.3682 12.6666 14 12.6666H12.6667V14C12.6667 14.3681 12.3682 14.6666 12 14.6666C11.6318 14.6666 11.3333 14.3681 11.3333 14V12.6666H10C9.63181 12.6666 9.33333 12.3681 9.33333 12C9.33333 11.6318 9.63181 11.3333 10 11.3333H11.3333V9.99996C11.3333 9.63177 11.6318 9.33329 12 9.33329Z" fill="currentColor"/>
                    </svg>',
                'container' => 'full-width',
                'video' => [
                    'url' => 'https://www.youtube.com/embed/0rjSHUPZgoE',
                    'button_text' => __( 'Watch Tutorial', 'directorist' ),
                    'title' => __( 'Add Listing Form Tutorial', 'directorist' ),
                    'description' => __( 'Watch the video to learn how to create add listing form.', 'directorist' ),
                ],
                'sections'  => [
                    'form_fields' => [
                        'title'       => __( 'Add listing form', 'directorist' ),
                        'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/form-and-layout-builder/">' . __( 'Need help?', 'directorist' ) . ' </a>',
                        'fields'      => [
                            'submission_form_fields'
                        ],
                    ],
                    'form_options' => [
                        'fields' =>  [
                            'preview_mode',
                            'submit_button_label',
                        ]
                    ]
                ],
            ],

            'single_page_layout'   => [
                'label'   => __( 'Single Page Layout', 'directorist' ),
                    'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8276 1.33337H5.17249C4.63584 1.33337 4.19293 1.33336 3.83214 1.36284C3.45741 1.39345 3.11294 1.45916 2.78938 1.62402C2.28761 1.87969 1.87967 2.28763 1.624 2.7894C1.45914 3.11296 1.39343 3.45743 1.36282 3.83216C1.33334 4.19295 1.33335 4.63585 1.33335 5.17249V10.8276C1.33335 11.3642 1.33334 11.8071 1.36282 12.1679C1.39343 12.5427 1.45914 12.8871 1.624 13.2107C1.87967 13.7124 2.28761 14.1204 2.78938 14.3761C3.11294 14.5409 3.45741 14.6066 3.83214 14.6372C4.19293 14.6667 4.63583 14.6667 5.17247 14.6667H10.8276C11.3642 14.6667 11.8071 14.6667 12.1679 14.6372C12.5426 14.6066 12.8871 14.5409 13.2107 14.3761C13.7124 14.1204 14.1204 13.7124 14.376 13.2107C14.5409 12.8871 14.6066 12.5427 14.6372 12.1679C14.6667 11.8071 14.6667 11.3642 14.6667 10.8276V5.17251C14.6667 4.63586 14.6667 4.19295 14.6372 3.83216C14.6066 3.45743 14.5409 3.11296 14.376 2.7894C14.1204 2.28763 13.7124 1.87969 13.2107 1.62402C12.8871 1.45916 12.5426 1.39345 12.1679 1.36284C11.8071 1.33336 11.3642 1.33337 10.8276 1.33337ZM9.33335 13.3334H5.20002C4.62897 13.3334 4.24077 13.3329 3.94071 13.3083C3.64844 13.2845 3.49897 13.2412 3.3947 13.188C3.14382 13.0602 2.93984 12.8562 2.81201 12.6054C2.75889 12.5011 2.7156 12.3516 2.69172 12.0593C2.66721 11.7593 2.66669 11.3711 2.66669 10.8V5.20004C2.66669 4.62899 2.66721 4.24079 2.69172 3.94073C2.7156 3.64846 2.75889 3.49899 2.81201 3.39472C2.93984 3.14384 3.14382 2.93986 3.3947 2.81203C3.49897 2.75891 3.64844 2.71562 3.94071 2.69174C4.24077 2.66723 4.62897 2.66671 5.20002 2.66671H9.33335L9.33335 13.3334ZM10.6667 2.66671L10.6667 13.3334H10.8C11.3711 13.3334 11.7593 13.3329 12.0593 13.3083C12.3516 13.2845 12.5011 13.2412 12.6053 13.188C12.8562 13.0602 13.0602 12.8562 13.188 12.6054C13.2412 12.5011 13.2844 12.3516 13.3083 12.0593C13.3328 11.7593 13.3334 11.3711 13.3334 10.8V5.20004C13.3334 4.62899 13.3328 4.24079 13.3083 3.94073C13.2844 3.64846 13.2412 3.49899 13.188 3.39472C13.0602 3.14384 12.8562 2.93986 12.6053 2.81203C12.5011 2.75891 12.3516 2.71562 12.0593 2.69174C11.7593 2.66723 11.3711 2.66671 10.8 2.66671H10.6667ZM8.33335 4.66671C8.33335 4.29852 8.03488 4.00004 7.66669 4.00004H4.33335C3.96516 4.00004 3.66669 4.29852 3.66669 4.66671C3.66669 5.0349 3.96516 5.33337 4.33335 5.33337H7.66669C8.03488 5.33337 8.33335 5.0349 8.33335 4.66671ZM8.33335 7.33337C8.33335 6.96518 8.03488 6.66671 7.66669 6.66671H4.33335C3.96516 6.66671 3.66669 6.96518 3.66669 7.33337C3.66669 7.70156 3.96516 8.00004 4.33335 8.00004H7.66669C8.03488 8.00004 8.33335 7.70156 8.33335 7.33337ZM8.33335 10C8.33335 9.63185 8.03488 9.33337 7.66669 9.33337H4.33335C3.96516 9.33337 3.66669 9.63185 3.66669 10C3.66669 10.3682 3.96516 10.6667 4.33335 10.6667H7.66669C8.03488 10.6667 8.33335 10.3682 8.33335 10Z" fill="currentColor"/>
                    </svg>',
                'submenu' => [
                    'listing_header'   => [
                        'label'     => __( 'Listing Header', 'directorist' ),
                        'container' => 'full-width',
                        'video' => [
                            'url' => 'https://www.youtube.com/embed/NtLXjEAPQzc',
                            'button_text' => __( 'Watch Tutorial', 'directorist' ),
                            'title' => __( 'Listing Header Tutorial', 'directorist' ),
                            'description' => __( 'Watch the video to learn how to create listing header.', 'directorist' ),
                        ],
                        'learn_more' => [
                            'url' => 'https://directorist.com/features/',
                            'title' => __( 'What is it?', 'directorist' ),
                        ],
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
                        'container' => 'full-width',
                        'video' => [
                            'url' => 'https://www.youtube.com/embed/82CFngofqbM',
                            'button_text' => __( 'Watch Tutorial', 'directorist' ),
                            'title' => __( 'Contents Tutorial', 'directorist' ),
                            'description' => __( 'Watch the video to learn how to create a custom contents.', 'directorist' ),
                        ],
                        'learn_more' => [
                            'url' => 'https://directorist.com/solutions/',
                            'title' => __( 'What is it?', 'directorist' ),
                        ],
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
                        'label'    => __( 'Custom Single Listing Page', 'directorist' ),
                        'container' => 'short-wide',
                        'sections' => [
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
                'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.33331 3.99996C1.33331 3.26358 1.93027 2.66663 2.66665 2.66663C3.40303 2.66663 3.99998 3.26358 3.99998 3.99996C3.99998 4.73634 3.40303 5.33329 2.66665 5.33329C1.93027 5.33329 1.33331 4.73634 1.33331 3.99996ZM5.33331 3.99996C5.33331 3.63177 5.63179 3.33329 5.99998 3.33329H14C14.3682 3.33329 14.6666 3.63177 14.6666 3.99996C14.6666 4.36815 14.3682 4.66663 14 4.66663L5.99998 4.66663C5.63179 4.66663 5.33331 4.36815 5.33331 3.99996ZM1.33331 7.99996C1.33331 7.26358 1.93027 6.66663 2.66665 6.66663C3.40303 6.66663 3.99998 7.26358 3.99998 7.99996C3.99998 8.73634 3.40303 9.33329 2.66665 9.33329C1.93027 9.33329 1.33331 8.73634 1.33331 7.99996ZM5.33331 7.99996C5.33331 7.63177 5.63179 7.33329 5.99998 7.33329L14 7.33329C14.3682 7.33329 14.6666 7.63177 14.6666 7.99996C14.6666 8.36815 14.3682 8.66663 14 8.66663L5.99998 8.66663C5.63179 8.66663 5.33331 8.36815 5.33331 7.99996ZM1.33331 12C1.33331 11.2636 1.93027 10.6666 2.66665 10.6666C3.40303 10.6666 3.99998 11.2636 3.99998 12C3.99998 12.7363 3.40303 13.3333 2.66665 13.3333C1.93027 13.3333 1.33331 12.7363 1.33331 12ZM5.33331 12C5.33331 11.6318 5.63179 11.3333 5.99998 11.3333L14 11.3333C14.3682 11.3333 14.6666 11.6318 14.6666 12C14.6666 12.3681 14.3682 12.6666 14 12.6666L5.99998 12.6666C5.63179 12.6666 5.33331 12.3681 5.33331 12Z" fill="currentColor"/>
                </svg>',
                'submenu' => [
                    'grid_view' => [
                        'label'     => __( 'All Listing Grid Layout', 'directorist' ),
                        'container' => 'full-width',
                        'video' => [
                            'url' => 'https://www.youtube.com/embed/SijKFqgwXVQ',
                            'button_text' => __( 'Watch Tutorial', 'directorist' ),
                            'title' => __( 'All Listing Grid Tutorial', 'directorist' ),
                            'description' => __( 'Watch the video to learn how to create all listing grid.', 'directorist' ),
                        ],
                        'learn_more' => [
                            'url' => 'https://directorist.com/customers/',
                            'title' => __( 'What is it?', 'directorist' ),
                        ],
                        'sections'  => [
                            'listings_card' => [
                                'title'       => __( 'All Listing Grid Layout', 'directorist' ),
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
                        'video' => [
                            'url' => 'https://www.youtube.com/embed/T9VovVonLV0',
                            'button_text' => __( 'Watch Tutorial', 'directorist' ),
                            'title' => __( 'All Listing List Tutorial', 'directorist' ),
                            'description' => __( 'Watch the video to learn how to create all listing list.', 'directorist' ),
                        ],
                        'learn_more' => [
                            'url' => 'https://directorist.com/pricing/',
                            'title' => __( 'What is it?', 'directorist' ),
                        ],
                        'sections'  => [
                            'listings_card' => [
                                'title'       => __( 'All Listing List Layout', 'directorist' ),
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
                'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.33331 2.66671C4.75598 2.66671 2.66665 4.75605 2.66665 7.33337C2.66665 9.9107 4.75598 12 7.33331 12C8.59061 12 9.73178 11.5028 10.5709 10.6943C10.5885 10.6715 10.6077 10.6495 10.6286 10.6286C10.6495 10.6077 10.6714 10.5885 10.6942 10.571C11.5028 9.73184 12 8.59067 12 7.33337C12 4.75605 9.91064 2.66671 7.33331 2.66671ZM12.0212 11.0785C12.8423 10.0521 13.3333 8.75005 13.3333 7.33337C13.3333 4.01967 10.647 1.33337 7.33331 1.33337C4.0196 1.33337 1.33331 4.01967 1.33331 7.33337C1.33331 10.6471 4.0196 13.3334 7.33331 13.3334C8.74999 13.3334 10.052 12.8424 11.0784 12.0213L13.5286 14.4714C13.7889 14.7318 14.211 14.7318 14.4714 14.4714C14.7317 14.2111 14.7317 13.789 14.4714 13.5286L12.0212 11.0785Z" fill="currentColor"/>
                    </svg>',
                'container' => 'full-width',
                'video' => [
                    'url' => 'https://www.youtube.com/embed/bWRDFgFIvcI',
                    'button_text' => __( 'Watch Tutorial', 'directorist' ),
                    'title' => __( 'Search Form Tutorial', 'directorist' ),
                    'description' => __( 'Watch the video to learn how to create search form.', 'directorist' ),
                ],
                'sections'  => [
                    'form_fields' => [
                        'title'       => __( 'Search Form', 'directorist' ),
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
                'value' => 'Email Address',
            ];

            self::$fields['guest_email_placeholder'] = [
                'label' => __( 'Guest Email Placeholder', 'directorist' ),
                'type'  => 'text',
                'value' => 'Enter email address',
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
