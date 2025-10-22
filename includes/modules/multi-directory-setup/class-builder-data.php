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
                'widgets'       => require_once __DIR__ . '/builder-preset-fields.php',
            ],

            'custom' => [
                'title'         => __( 'Custom Fields', 'directorist' ),
                'description'   => __( 'Click on a field type you want to create.', 'directorist' ),
                'allowMultiple' => true,
                'widgets'       => require_once __DIR__ . '/builder-custom-fields.php',

            ],
        ];

        $single_listings_contents_widgets = [
            'preset_widgets' => [
                'title'         => __( 'Preset Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'template'      => 'submission_form_fields',
                'widgets'       => apply_filters(
                    'atbdp_single_listing_content_widgets', [

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
                    ] 
                ),
            ],
            'other_widgets'  => [
                'title'         => __( 'Other Fields', 'directorist' ),
                'description'   => __( 'Click on a field to use it', 'directorist' ),
                'allowMultiple' => false,
                'widgets'       => apply_filters(
                    'atbdp_single_listing_other_fields_widget', [
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
                            'icon'    => 'la la-star-o',
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
                                [
                                    'widget_group'      => 'other_widgets',
                                    'widget_name'       => 'review',
                                    'widget_child_name' => 'review_consent',
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
                                'review_consent' => [
                                    'label'    => __( 'Consent', 'directorist' ),
                                    'canTrash' => false,
                                    'canMove'  => false,
                                    'options'  => [
                                        'enable_cookie_consent' => [
                                            'label'   => __( 'Show Cookies Consent', 'directorist' ),
                                            'type'    => 'toggle',
                                            'value'   => false,
                                        ],
                                        'enable_gdpr_consent' => [
                                            'label'   => __( 'Enable GDPR Consent', 'directorist' ),
                                            'type'    => 'toggle',
                                            'value'   => false,
                                        ],
                                        'consent_label' => [
                                            'label'       => __( 'Consent Label', 'directorist' ),
                                            'type'        => 'textarea',
                                            'editor'      => 'wp_editor',
                                            'editorID'    => 'wp_editor_terms_privacy_consent_label',
                                            'value'       => sprintf(
                                                __( 'I have read and agree to the <a href="%s" target="_blank">Privacy Policy</a> and <a href="%s" target="_blank">Terms of Service</a>', 'directorist' ),
                                                ATBDP_Permalink::get_privacy_policy_page_url(),
                                                ATBDP_Permalink::get_terms_and_conditions_page_url(),
                                            ),
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
                                    'label' => __( 'Section Name', 'directorist' ),
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
                                    'label' => __( 'Section Name', 'directorist' ),
                                    'value' => 'Contact Listings Owner Form',
                                ],
                                'icon'                 => [
                                    'type'  => 'icon',
                                    'label' => __( 'Section Icon', 'directorist' ),
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
                        'formgent-form'          => [
                            'type'          => 'widget',
                            'label'         => __( 'FormGent Form', 'directorist' ),
                            'icon'          => '<svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.7399 1.5H4.87331C4.11485 1.5 3.5 2.11485 3.5 2.87331V5.61994C3.5 6.3784 4.11485 6.99325 4.87331 6.99325H11.7399C12.4983 6.99325 13.1132 6.3784 13.1132 5.61994V2.87331C13.1132 2.11485 12.4983 1.5 11.7399 1.5Z" fill="#747c89"/><path d="M4.87331 21.4131C4.11799 21.4131 3.5 20.7951 3.5 20.0398V11.7999C3.5 11.0446 4.11799 10.4266 4.87331 10.4266H17.9198C18.6751 10.4266 19.2931 11.0446 19.2931 11.7999V14.5465C19.2931 15.3018 18.6751 15.9198 17.9198 15.9198H8.99325V20.0398C8.99325 20.7951 8.37526 21.4131 7.61994 21.4131H4.87331Z" fill="#747c89"/><path d="M16.203 2.87329L16.718 4.41827L18.263 4.93326L16.718 5.44825L16.203 6.99323L15.688 5.44825L14.1431 4.93326L15.688 4.41827L16.203 2.87329Z" fill="#747c89"/><path d="M18.4347 1.5L18.6476 2.14546L19.2931 2.35832L18.6476 2.57118L18.4347 3.21664L18.2219 2.57118L17.5764 2.35832L18.2219 2.14546L18.4347 1.5Z" fill="#747c89"/> </svg>',
                            'iconType'      => 'svg',
                            'allowMultiple' => true,
                            'options'       => [
                                'value' => [
                                    'type'             => 'formgent-form',
                                    'label'            => __( 'Select a form', 'directorist' ),
                                    'value'            => '--',
                                    'createFormButton' => [
                                        'label' => __( 'Create a form', 'directorist' ),
                                        'href'  => admin_url( 'admin.php?page=formgent' ),
                                    ],
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
                                    'label' => __( 'Section Name', 'directorist' ),
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
                    ] 
                ),
            ],
        ];

        $search_form_widgets = apply_filters(
            'directorist_search_form_widgets', [
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
                                    'label' => __( 'Label', 'directorist' ),
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
                            'icon'    => 'la la-star-o',
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
            ] 
        );

        $listing_card_widget = apply_filters(
            'directorist_listing_card_widgets', [
                'listing_title'     => [
                    'type'    => 'title',
                    'label'   => __( 'Listing Title', 'directorist' ),
                    'icon'    => 'las la-text-height',
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
                    'icon'    => 'las la-text-height',
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
                    'icon'    => 'las la-map-marker',
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
                    'icon'    => 'la la-file-invoice-dollar',
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
                    'icon'  => 'la la-star-o',
                ],

                'featured_badge'    => [
                    'type'  => 'badge',
                    'label' => __( 'Featured', 'directorist' ),
                    'icon'  => 'la la-star-o',
                    'hook'  => 'atbdp_featured_badge',
                ],

                'new_badge'         => [
                    'type'  => 'badge',
                    'label' => __( 'New', 'directorist' ),
                    'icon'  => 'la la-bolt',
                    'hook'  => 'atbdp_new_badge',
                ],

                'popular_badge'     => [
                    'type'  => 'badge',
                    'label' => __( 'Popular', 'directorist' ),
                    'icon'  => 'la la-fire',
                    'hook'  => 'atbdp_popular_badge',
                ],

                'favorite_badge'    => [
                    'type'  => 'icon',
                    'label' => __( 'Favorite', 'directorist' ),
                    'icon'  => 'la la-heart-o',
                    'hook'  => 'atbdp_favorite_badge',
                ],

                'view_count'        => [
                    'type'    => 'view-count',
                    'label'   => __( 'View Count', 'directorist' ),
                    'icon'    => 'las la-eye',
                    'hook'    => 'atbdp_view_count',
                    'options' => [
                        'title'  => __( 'View Count Settings', 'directorist' ),
                        'fields' => [
                            'icon' => [
                                'type'  => 'icon',
                                'label' => __( 'Icon', 'directorist' ),
                                'value' => 'las la-eye',
                            ],
                        ],
                    ],
                ],

                'category'          => [
                    'type'    => 'category',
                    'label'   => __( 'Category', 'directorist' ),
                    'icon'    => 'las la-text-height',
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
                    'icon'     => 'las la-user-circle',
                    'hook'     => 'atbdp_user_avatar',
                    'canMove'  => false,
                    'options'  => [
                        'title'  => __( 'User Avatar Settings', 'directorist' ),
                        'fields' => [
                            'align' => [
                                'type'    => 'radio',
                                'label'   => __( 'Align', 'directorist' ),
                                'value'   => 'left',
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
                    'icon'    => 'las la-dot-circle',
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
                                'value' => 'las la-dot-circle',
                            ],
                            'show_label' => [
                                'type'  => 'toggle',
                                'label' => __( 'Show Label', 'directorist' ),
                                'value' => false,
                            ],
                        ],
                    ],
                ],
            ] 
        );

        $listing_card_conditional_widget = $listing_card_widget;

        if ( ! empty( $listing_card_conditional_widget['user_avatar'] ) ) {
            $listing_card_conditional_widget['user_avatar']['canMove'] = true;

            if ( ! empty( $listing_card_conditional_widget['user_avatar']['options'] ) ) {
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
                    'acceptedWidgets' => [ 'listing_title', "rating", "pricing" ],
                    "selectedWidgets" => [],
                ],
                'bottom'  => [
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email', 'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
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
                    'label'             => __( 'Add Avatar', 'directorist' ),
                    'maxWidget'         => 1,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets' => [ 'user_avatar' ],
                ],
                'title'         => [
                    'maxWidget'       => 1,
                    'acceptedWidgets' => ['listing_title'],
                    "selectedWidgets" => [],
                ],
                'quick_actions' => [
                    'maxWidget'       => 3,
                    'acceptedWidgets' => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'quick_info'    => [
                    'acceptedWidgets' => ['rating', 'pricing'],
                ],
                'bottom'        => [
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email', 'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
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
                    'maxWidget'       => 2,
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
                    'acceptedWidgets'   => ['popular_badge', 'featured_badge', 'new_badge'],
                ],
            ],

            'body'      => [
                'top'     => [
                    'label'             => __( 'Body Top', 'directorist' ),
                    'maxWidget'         => 0,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets' => ['listing_title', 'rating', 'pricing'],
                    "selectedWidgets" => [],
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
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email', 'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
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
                    'acceptedWidgets'   => ['listing_title', 'rating', 'pricing'],
                    "selectedWidgets"   => [],
                ],
                'right'   => [
                    'label'             => __( 'Body Right', 'directorist' ),
                    'maxWidget'         => 3,
                    'maxWidgetInfoText' => 'Up to __DATA__ item{s} can be added',
                    'acceptedWidgets'   => ['favorite_badge', 'popular_badge', 'featured_badge', 'new_badge'],
                ],
                'bottom'  => [
                    'label'           => __( 'Body Bottom', 'directorist' ),
                    'maxWidget'       => 0,
                    'acceptedWidgets' => [
                        'listings_location', 'phone', 'phone2', 'website', 'zip', 'fax', 'address', 'email', 'text', 'textarea', 'number', 'url', 'date', 'time', 'color', 'select', 'checkbox', 'radio', 'file', 'posted_date',
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

        self::$fields = apply_filters(
            'atbdp_listing_type_settings_field_list', [
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

                'submission_form_fields'                      => apply_filters(
                    'atbdp_listing_type_form_fields', [
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
                                'label' => 'Section Name',
                                'value' => 'Section',
                            ],
                            'icon' => [
                                'type'  => 'icon',
                                'label'  => __( 'Section Icon', 'directorist' ),
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
                                    'icon'   => 'las la-align-left',
                                    'fields' => ['title'],
                                    'plans'  => [],
                                ],
                            ],
                        ],

                    ] 
                ),

                // Submission Settings
                'enable_sidebar' => [
                    'label' => __( 'Enable Sidebar', 'directorist' ),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'preview_mode'                                => [
                    'label' => __( 'Enable Listing Preview', 'directorist' ),
                    'description' => __( 'Enable it to see a preview of your listing before you finalize it.', 'directorist' ),
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
                            'label' => __( 'Section  Name', 'directorist' ),
                            'value' => 'Section',
                        ],
                        'icon'                 => [
                            'type'  => 'icon',
                            'label' => __( 'Section Icon', 'directorist' ),
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
                    'description' => __(
                        'Enabling this option will replace the default single listing page. After enabling you must create and assign a new page with generated shortcodes to display single listing content.', 'directorist' 
                    ),
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
                                'type'      => 'general_group',
                                'fields'    => [],
                            ],
                            [
                                'label'     => __( 'Search Filter', 'directorist' ),
                                'lock'      => true,
                                'draggable' => false,
                                'type'      => 'general_group',
                                'fields'    => [],
                            ],
                        ],
                    ],
                ],

                'single_listing_header'                       => apply_filters(
                    'directorist_listing_header_layout', [
                        'type' => 'card-builder',
                        'template' => 'listing-header',
                        'value' => '',
                        'icon' => 'la la-sign',
                        'title'       => __( 'Listing Header', 'directorist' ),
                        'video' => [
                            'type' => 'video',
                            'url' => 'https://www.youtube.com/embed/NtLXjEAPQzc',
                            'button_text' => __( 'Watch Tutorial', 'directorist' ),
                            'title' => __( 'Listing Header Tutorial', 'directorist' ),
                            'description' => __( 'Watch the video to learn how to create listing header.', 'directorist' ),
                        ],
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
                                'icon' => 'la la-arrow-left',
                                'options' => [
                                    'title' => __( "Back Settings", "directorist" ),
                                    'fields' => [
                                        'label' => [
                                            'type' => "text",
                                            'label' => __( "Label", "directorist" ),
                                            'value' => 'Back',
                                        ],
                                        'icon' => [
                                            'type' => "icon",
                                            'label' => __( "Icon", "directorist" ),
                                            'value' => 'las la-arrow-left',
                                        ],
                                    ],
                                ],
                            ],
                            'title' => [
                                'type' => "title",
                                'label' => __( "Listing Title", "directorist" ),
                                'icon' => 'la la-heading',
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
                                'label' => __( "Image/Slider", "directorist" ),
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
                                'icon' => 'la la-heart-o',
                                'options' => [
                                    'title' => __( "Bookmark Settings", "directorist" ),
                                    'fields' => [
                                        'label' => [
                                            'type' => "text",
                                            'label' => __( "Label", "directorist" ),
                                            'value' => 'Bookmark',
                                        ],
                                        'icon' => [
                                            'type' => "icon",
                                            'label' => __( "Icon", "directorist" ),
                                            'value' => 'la la-heart-o',
                                        ],
                                    ],
                                ],
                            ],
                            'share' => [
                                'type' => "badge",
                                'label' => __( "Share", "directorist" ),
                                'icon' => 'la la-share-square',
                                'options' => [
                                    'title' => __( "Share Settings", "directorist" ),
                                    'fields' => [
                                        'label' => [
                                            'type' => "text",
                                            'label' => __( "Label", "directorist" ),
                                            'value' => 'Share',
                                        ],
                                        'icon' => [
                                            'type' => "icon",
                                            'label' => __( "Icon", "directorist" ),
                                            'value' => 'la la-share-square',
                                        ],
                                    ],
                                ],
                            ],
                            'report' => [
                                'type' => "badge",
                                'label' => __( "Report", "directorist" ),
                                'icon' => 'la la-flag',
                                'options' => [
                                    'title' => __( "Report Settings", "directorist" ),
                                    'fields' => [
                                        'label' => [
                                            'type' => "text",
                                            'label' => __( "Label", "directorist" ),
                                            'value' => 'Report',
                                        ],
                                        'icon' => [
                                            'type' => "icon",
                                            'label' => __( "Icon", "directorist" ),
                                            'value' => 'la la-flag',
                                        ],
                                    ],
                                ],
                            ],
                            'price' => [
                                'type' => "badge",
                                'label' => __( "Pricing", "directorist" ),
                                'icon' => 'la la-file-invoice-dollar',
                            ],
                            'badges' => [
                                'type' => "badge",
                                'label' => __( "Badges", "directorist" ),
                                'icon' => 'la la-circle-notch',
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
                                'icon' => 'la la-star-o',
                            ],
                            'category' => [
                                'type' => "badge",
                                'label' => __( "Listings Category", "directorist" ),
                                'icon' => 'las la-folder-open
',
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
                                'icon' => 'las la-map-marked-alt',
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
                                        'label'             => __( 'Top Left', 'directorist' ),
                                        'maxWidget'         => 1,
                                        'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                        'acceptedWidgets'   => ['back'],
                                    ],
                                    [
                                        'type'              => 'placeholder_item',
                                        'placeholderKey'    => 'quick-action-placeholder',
                                        'label'             => __( 'Top Right', 'directorist' ),
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
                                'label'             => __( 'Quick Info', 'directorist' ),
                                'maxWidget'         => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets'   => [ 'location', 'category', 'ratings_count', 'badges', 'price' ],
                            ],
                            [
                                'type'            => 'placeholder_item',
                                'label'           => 'Image/Slider',
                                'placeholderKey'  => 'slider-placeholder',
                                'acceptedWidgets' => ['slider'],
                                'maxWidget'       => 1,
                            ],
                        ],
                    ] 
                ),

                'listings_card_grid_view'                     => apply_filters(
                    'directorist_listing_card_layouts', [
                        'type'           => 'card-builder',
                        'card_templates' => [
                            'grid_view_with_thumbnail'    => [
                                'label'     => '<svg width="34" height="40" viewBox="0 0 34 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2.52344" y="2.52344" width="28.9524" height="19.0476" rx="1.88235" fill="#D2D6DB"/>
                                <path d="M22.3614 16.3137H10.8648C10.7534 16.3137 10.6631 16.2234 10.6631 16.112V14.7281C10.6631 14.5769 10.6964 14.427 10.7774 14.2994C11.0513 13.8679 11.827 12.8849 13.21 12.8849C14.9817 12.8849 15.7569 14.4984 15.7569 14.4984C15.7569 14.4984 17.0165 10.9688 19.8402 10.9688C21.266 10.9688 22.2981 12.0712 22.519 12.3277C22.5485 12.362 22.5631 12.4052 22.5631 12.4504V16.112C22.5631 16.2234 22.4728 16.3137 22.3614 16.3137Z" fill="white"/>
                                <circle cx="12.075" cy="9.65796" r="1.41186" fill="white"/>
                                <rect x="8.61914" y="26.1426" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="12.4287" y="30.7148" width="9.14286" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                </svg>',
                                'template' => 'grid-view-with-thumbnail',
                                'widgets'  => $listing_card_widget,
                                'layout'   => $listing_card_grid_view_with_thumbnail_layout,
                            ],
                            'grid_view_without_thumbnail' => [
                                'label'     => '<svg width="34" height="40" viewBox="0 0 34 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="8.61914" y="18.1426" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="8.61914" y="15" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="12.4287" y="22.7148" width="9.14286" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                </svg>',
                                'template' => 'grid-view-without-thumbnail',
                                'widgets'  => $listing_card_conditional_widget,
                                'layout'   => $listing_card_grid_view_without_thumbnail_layout,
                            ],
                        ],
                    ] 
                ),

                'listings_card_list_view'                     => apply_filters(
                    'directorist_listing_list_layouts', [
                        'type'           => 'card-builder',
                        'card_templates' => [
                            'list_view_with_thumbnail'    => [
                            // 'label'    => __( 'With Preview Image', 'directorist' ),
                                'label'     => '<svg width="34" height="40" viewBox="0 0 34 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2.52344" y="2.52344" width="28.9524" height="19.0476" rx="1.88235" fill="#D2D6DB"/>
                                <path d="M22.3614 16.3137H10.8648C10.7534 16.3137 10.6631 16.2234 10.6631 16.112V14.7281C10.6631 14.5769 10.6964 14.427 10.7774 14.2994C11.0513 13.8679 11.827 12.8849 13.21 12.8849C14.9817 12.8849 15.7569 14.4984 15.7569 14.4984C15.7569 14.4984 17.0165 10.9688 19.8402 10.9688C21.266 10.9688 22.2981 12.0712 22.519 12.3277C22.5485 12.362 22.5631 12.4052 22.5631 12.4504V16.112C22.5631 16.2234 22.4728 16.3137 22.3614 16.3137Z" fill="white"/>
                                <circle cx="12.075" cy="9.65796" r="1.41186" fill="white"/>
                                <rect x="8.61914" y="26.1426" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="12.4287" y="30.7148" width="9.14286" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                </svg>',
                                'template' => 'list-view-with-thumbnail',
                                'widgets'  => $listing_card_conditional_widget,
                                'layout'   => $listing_card_list_view_with_thumbnail_layout,
                            ],
                            'list_view_without_thumbnail' => [
                            // 'label'    => __( 'Without Preview Image', 'directorist' ),
                                'label'     => '<svg width="34" height="40" viewBox="0 0 34 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="8.61914" y="18.1426" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="8.61914" y="15" width="16.7619" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                <rect x="12.4287" y="22.7148" width="9.14286" height="1.52381" rx="0.761905" fill="#D2D6DB"/>
                                </svg>',
                                'template' => 'list-view-without-thumbnail',
                                'widgets'  => $listing_card_conditional_widget,
                                'layout'   => $listing_card_list_view_without_thumbnail_layout,
                            ],
                        ],
                    ] 
                ),

            ] 
        );

        self::$layouts = apply_filters(
            'directorist_builder_layouts', [
                'general'              => [
                    'label'    => 'General',
                    'icon'     => 'las la-home',
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
                    'icon'    => 'las la-file-medical',
                    'container' => 'full-width',
                    'video' => [
                        'type' => 'video',
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
                    'icon'    => 'las la-file-alt',
                    'submenu' => [
                        'listing_header'   => [
                            'label'     => __( 'Listing Header', 'directorist' ),
                            'icon'    => 'las la-sign',
                            'container' => 'full-width',
                            'learn_more' => [
                                'type' => 'image',
                                'url'  => DIRECTORIST_ASSETS . 'images/single-listing-header-preview.png',
                                'button_text' => __( 'What is it?', 'directorist' ),
                                'title' => __( 'Single Listing Header', 'directorist' ),
                                'description' => __( 'Details of Single Listing Header', 'directorist' ),
                            ],
                            'sections'  => [
                                'listing_header' => [
                                    'fields'      => [
                                        'single_listing_header',
                                    ],
                                ],
                            ],
                        ],
                        'contents'         => [
                            'label'     => __( 'Listing Contents', 'directorist' ),
                            'icon'    => 'lab la-elementor',
                            'container' => 'full-width',
                            'video' => [
                                'type' => 'video',
                                'url' => 'https://www.youtube.com/embed/82CFngofqbM',
                                'button_text' => __( 'Watch Tutorial', 'directorist' ),
                                'title' => __( 'Contents Tutorial', 'directorist' ),
                                'description' => __( 'Watch the video to learn how to create a custom contents.', 'directorist' ),
                            ],
                            'learn_more' => [
                                'type' => 'image',
                                'url'  => DIRECTORIST_ASSETS . 'images/single-listing-header-preview.png',
                                'button_text' => __( 'What is it?', 'directorist' ),
                                'title' => __( 'Single Listing Contents', 'directorist' ),
                                'description' => __( 'Details of Single Listing Contents', 'directorist' ),
                            ],
                            'sections'  => [
                                'contents' => [
                                    'title'       => __( 'Listing Contents', 'directorist' ),
                                    'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/single-listings-layout/"> ' . __( 'Need help?', 'directorist' ) . ' </a>',
                                    'fields'      => [
                                        'single_listings_contents',
                                    ],
                                ],
                            ],
                        ],
                        'similar_listings' => [
                            'label'    => __( 'Custom Single Listing Page', 'directorist' ),
                            'icon'    => 'las la-file-alt',
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
                    'icon'    => 'las la-window-maximize',
                    'submenu' => [
                        'grid_view' => [
                            'label'     => __( 'Grid View', 'directorist' ),
                            'icon_type' => 'svg',
                            'icon'      => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.83341 2.16602V8.83268H2.16675V2.16602H8.83341Z" stroke="#4D5761" stroke-width="1.5" stroke-linejoin="round"/>
                            <path d="M18.8334 2.16602V8.83268H12.1667V2.16602H18.8334Z" stroke="#4D5761" stroke-width="1.5" stroke-linejoin="round"/>
                            <path d="M18.8334 12.166V18.8327H12.1667V12.166H18.8334Z" stroke="#4D5761" stroke-width="1.5" stroke-linejoin="round"/>
                            <path d="M8.83341 12.166V18.8327H2.16675V12.166H8.83341Z" stroke="#4D5761" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>',
                            'container' => 'full-width',
                            'video' => [
                                'type' => 'video',
                                'url' => 'https://www.youtube.com/embed/SijKFqgwXVQ',
                                'button_text' => __( 'Watch Tutorial', 'directorist' ),
                                'title' => __( 'Grid Tutorial', 'directorist' ),
                                'description' => __( 'Watch the video to learn how to create listing grid.', 'directorist' ),
                            ],
                            'sections'  => [
                                'listings_card' => [
                                    'title'       => __( 'All Listing Grid View', 'directorist' ),
                                    'title_align' => 'center',
                                    'description' => '<a target="_blank" href="https://directorist.com/documentation/directorist/form-and-layout-builder/multiple-directories/"> ' . __( 'Need help?', 'directorist' ) . ' </a>' . __( 'Read the documentation or open a ticket in our helpdesk.', 'directorist' ),
                                    'fields'      => [
                                        'listings_card_grid_view',
                                    ],
                                ],
                            ],
                        ],
                        'list_view' => [
                            'label'     => __( 'List View', 'directorist' ),
                            'icon_type' => 'svg',
                            'icon'      => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 4.66699L18 4.66699" stroke="#4D5761" stroke-width="1.5"/>
                            <path d="M3 4.66699L5.5 4.66699" stroke="#4D5761" stroke-width="1.5"/>
                            <path d="M8 10.5L18 10.5" stroke="#4D5761" stroke-width="1.5"/>
                            <path d="M3 10.5L5.5 10.5" stroke="#4D5761" stroke-width="1.5"/>
                            <path d="M8 16.333L18 16.333" stroke="#4D5761" stroke-width="1.5"/>
                            <path d="M3 16.333L5.5 16.333" stroke="#4D5761" stroke-width="1.5"/>
                            </svg>',
                            'container' => 'full-width',
                            'video' => [
                                'type' => 'video',
                                'url' => 'https://www.youtube.com/embed/T9VovVonLV0',
                                'button_text' => __( 'Watch Tutorial', 'directorist' ),
                                'title' => __( 'List View Tutorial', 'directorist' ),
                                'description' => __( 'Watch the video to learn how to create listing list.', 'directorist' ),
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
                    'icon'      => 'las la-search',
                    'container' => 'full-width',
                    'video' => [
                        'type' => 'video',
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
            ] 
        );

        self::$fields = apply_filters( 'directorist/builder/fields', self::$fields );

        self::$layouts = apply_filters( 'directorist/builder/layouts', self::$layouts );

        // Conditional Fields
        // -----------------------------
        // Guest Submission
        if ( get_directorist_option( 'guest_listings', 1 ) == '1' ) {
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
            'label' => __( 'Assign to', 'directorist' ),
            'value' => 'form',
            'options' => [
                [
                    'label' => __( 'Form', 'directorist' ),
                    'value' => 'form',
                ],
                [
                    'label' => __( 'Category', 'directorist' ),
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
