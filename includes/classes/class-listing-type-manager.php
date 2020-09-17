<?php

if ( ! class_exists( 'ATBDP_Listing_Type_Manager' ) ) {
    class ATBDP_Listing_Type_Manager {
        public $settings = [];
        public $fields = [];
        public $form_fields = [];

        // run
        public function run() {
            $this->prepare_settings();
            add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'init', [ $this, 'register_terms' ] );
            add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
            add_action( 'admin_post_delete_listing_type', [ $this, 'handle_delete_listing_type_request' ] );

            add_action( 'wp_ajax_save_post_type_data', [ $this, 'save_post_type_data' ] );
        }
        

        // save_post_type_data
        public function save_post_type_data() {
            if ( empty( $_REQUEST['name'] ) ) {
                wp_send_json( [
                    'status' => false,
                    'status_log' => [
                        'name_is_missing' => 'Name is missing'
                    ],
                ], 200 );
            } 
            

            $term_id = 0;
            $mode    = 'create';
            $listing_type_name = $_REQUEST['name'];

            if ( ! empty( $_REQUEST['listing_type_id'] ) ) {
                $mode = 'edit';
                $term_id = absint( $_REQUEST['listing_type_id'] );
                wp_update_term( $term_id, 'atbdp_listing_types', ['name' => $listing_type_name] );
            } else {
                $term = wp_insert_term( $listing_type_name, 'atbdp_listing_types' );

                if ( is_wp_error( $term ) ) {
                    if ( ! empty( $term->errors['term_exists'] )  ) {
                        $term_id = $term->error_data['term_exists'];
                    }
                } else {
                    $term_id = $term['term_id'];
                }
                
            }

            if ( empty( $term_id ) ) {
                wp_send_json( [
                    'status' => false,
                    'status_log' => [
                        'invalid_id' => [
                            'type' => 'error',
                            'message' => 'Error found, please try again',
                        ] 
                    ],
                ], 200 );
            }

            $created_message = ( 'create' == $mode ) ? 'creared' : 'updated';

            if ( empty( $_REQUEST['field_list'] ) ) {
                wp_send_json( [
                    'status' => true,
                    'post_id' => $term_id,
                    'status_log' => [
                        'post_created' => [
                            'type' => 'success',
                            'message' => 'The Post type has been '. $created_message .' successfully',
                        ],
                        'field_list_not_found' => [
                            'type' => 'error',
                            'message' => 'Field list not found',
                        ] ,
                    ],
                ], 200 );
            }

            foreach ( $_REQUEST['field_list'] as $field ) {
                if ( ! empty( $_REQUEST[ $field ] ) && 'name' !== $field ) {
                    update_term_meta( $term_id, $field, $this->get_sanitized_field_value( $field, $_REQUEST[ $field ] ) );
                }
            }

            wp_send_json( [
                'status' => true,
                'post_id' => $term_id,
                'status_log' => [
                    'post_created' => [
                        'type' => 'success',
                        'message' => 'The post type has been '. $created_message .' successfully'
                    ]
                ],
            ], 200 );
        }

        // get_field_value 
        public function get_sanitized_field_value( $key, $value ) {

            if ( ! isset( $this->fields[ $key ] )  ) {
                return '';
            }

            if ( 'text' == $this->fields[ $key ]['type'] ) {
                return sanitize_text_field( $value );
            }

            if ( 'icon' == $this->fields[ $key ]['type'] ) {
                return sanitize_text_field( $value );
            }

            if ( 'select' == $this->fields[ $key ]['type'] ) {
                return sanitize_text_field( $value );
            }

            if ( 'select' == $this->fields[ $key ]['type'] ) {
                return sanitize_text_field( $value );
            }

            if ( 'toggle' == $this->fields[ $key ]['type'] ) {
                return sanitize_text_field( $value );
            }

            return '';
        }


        // prepare_settings
        public function prepare_settings() {

            $this->fields = [
                'name' => [
                    'label' => 'Name *',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => true,
                    ],
                ],

                'icon' => [
                    'label' => 'Icon',
                    'type'  => 'icon',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'singular_name' => [
                    'label' => 'Singular name (e.g. Business)',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],
                
                'plural_name' => [
                    'label' => 'Plural name (e.g. Businesses)',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'permalink' => [
                    'label' => 'Permalink',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'package_list' => [
                    'label' => __( 'Select Packages', 'directorist' ),
                    'type'  => 'select',
                    'multiple' => true,
                    'value' => '',
                    'options' => [
                        [
                            'label' => 'Plan A',
                            'value' => 12565,
                        ],
                        [
                            'label' => 'Plan B',
                            'value' => 62552,
                        ],
                    ],
                ],

                'default_expiration' => [
                    'label' => __( 'Default expiration in days', 'directorist' ),
                    'type'  => 'number',
                    'value' => '',
                    'placeholder' => '365',
                    'rules' => [
                        'required' => true,
                    ],
                ],

                'new_listing_status' => [
                    'label' => __( 'New Listing Default Status', 'directorist' ),
                    'type'  => 'select',
                    'value' => '',
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

                'edit_listing_status' => [
                    'label' => __( 'Edited Listing Default Status', 'directorist' ),
                    'type'  => 'select',
                    'value' => '',
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

                'global_listing_type' => [
                    'label' => __( 'Global Listing Type', 'directorist' ),
                    'type'  => 'toggle',
                    'value' => '',
                ],

                'submission_form_fields' => [
                    'type'  => 'array',
                    'value' => [
                        'active_fields' => [
                            'title' => [
                                'label' => 'Title',
                                'icon' => 'fa fa-text-height',
                                'lock' => true,
                                'options' => [
                                    'type' => [
                                        'type'  => 'hidden',
                                        'value' => 'text',
                                    ],
                                    'field_key' => [
                                        'type'  => 'hidden',
                                        'value' => 'title',
                                    ],
                                    'label' => [
                                        'type'  => 'text',
                                        'label' => 'Label',
                                        'value' => 'Title',
                                    ],
                                    'placeholder' => [
                                        'type'  => 'text',
                                        'label' => 'Placeholder',
                                        'value' => '',
                                    ],
                                    'tag_with_plan' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Tag with plan',
                                        'value' => false,
                                    ],
                                    'plan' => [
                                        'type'  => 'option_group',
                                        'label'  => 'Chose a plan',
                                        'show_if' => [
                                            [
                                                'key'     => 'tag_with_plan',
                                                'compare' => '=',
                                                'value'   => true,
                                            ]
                                        ],
                                        'option_groups' => [
                                            [
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'options' => [],
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                            ]
                                            
                                        ]
                                    ],
                                ],
                            ],

                            'description' => [
                                'label' => 'Description',
                                'icon' => 'fa fa-align-left',
                                'options' => [
                                    'type' => [
                                        'type'  => 'hidden',
                                        'value' => 'text',
                                    ],
                                    'field_key' => [
                                        'type'  => 'hidden',
                                        'value' => 'description',
                                    ],
                                    'label' => [
                                        'type'  => 'text',
                                        'label' => 'Label',
                                        'value' => 'Description',
                                    ],
                                    'placeholder' => [
                                        'type'  => 'text',
                                        'label' => 'Placeholder',
                                        'value' => '',
                                    ],
                                    'tag_with_plan' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Tag with plan',
                                        'value' => false,
                                    ],
                                    'plan' => [
                                        'type'  => 'option_group',
                                        'label'  => 'Chose a plan',
                                        'show_if' => [
                                            [
                                                'key'     => 'tag_with_plan',
                                                'compare' => '=',
                                                'value'   => true,
                                            ]
                                        ],
                                        'option_groups' => [
                                            [
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'options' => [],
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                                'min' => [
                                                    'type'  => 'number',
                                                    'label'  => 'Min',
                                                    'value' => '',
                                                ],
                                                'max' => [
                                                    'type'  => 'number',
                                                    'label'  => 'Min',
                                                    'value' => '',
                                                ],
                                            ]
                                            
                                        ]
                                    ],
                                ]
                            ],
                        ],

                        'groups' => [
                            [ 
                                'label'  => 'General',
                                'lock'   => true,
                                'fields' => [ 'title', 'description' ],
                            ],
                        ],
                    ],
                ],
            ];

            $this->form_fields = [
                'preset' => [
                    'title' => [
                        'label' => 'Title',
                        'icon' => 'fa fa-text-height',
                        'lock' => true,
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'title',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Title',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => true,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                ]
                            ],
                        ],
                    ],
                    
                    'description' => [
                        'label' => 'Description',
                        'icon' => 'fa fa-align-left',
                        'options' => [
                            'type' => [
                                'type'  => 'radio',
                                'value' => 'wp_editor',
                                'options' => [
                                    'textarea' => [
                                        'label' => __( 'Textarea', 'directorist' ),
                                        'value' => 'textarea',
                                    ],
                                    'wp_editor' => [
                                        'label' => __( 'WP Editor', 'directorist' ),
                                        'value' => 'wp_editor',
                                    ],
                                ]
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'description',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Description',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'min' => [
                                            'type'  => 'number',
                                            'label'  => 'Min',
                                            'value' => '',
                                        ],
                                        'max' => [
                                            'type'  => 'number',
                                            'label'  => 'Min',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                ]
                            ],
                        ]
                    ],

                    'tagline' => [
                        'label' => 'Tagline',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'tagline',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Tagline',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'pricing' => [
                        'label' => 'Pricing',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'radio',
                                'label' => __( 'Select a method', 'directorist' ),
                                'value'  => 'unit',
                                'options' => [
                                    'unit' => [
                                        'label' => __( 'Price', 'directorist' ),
                                        'value' => 'text',
                                    ],
                                    'range' => [
                                        'label' => __( 'Price Range', 'directorist' ),
                                        'description' => __( '(Optional - Uncheck to hide pricing for this listing)', 'directorist' ),
                                        'value' => 'radio',
                                    ],
                                ]
                            ],
                            'sub_options' => [
                                'unit' => [
                                    'type' => [
                                        'type'  => 'hidden',
                                        'value' => 'text',
                                    ],
                                    'field_key' => [
                                        'type'  => 'hidden',
                                        'value' => 'price',
                                    ],
                                    'label' => [
                                        'type'  => 'text',
                                        'label' => 'Label',
                                        'value' => 'Price',
                                    ],
                                    'placeholder' => [
                                        'type'  => 'text',
                                        'label' => 'Placeholder',
                                        'value' => '',
                                    ],
                                    'required' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Required',
                                        'value' => false,
                                    ],
                                    'only_for_admin' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Only For Admin Use',
                                        'value' => false,
                                    ],
                                    'tag_with_plan' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Tag with plan',
                                        'value' => false,
                                    ],
                                    'plan' => [
                                        'type'  => 'option_group',
                                        'label'  => 'Chose a plan',
                                        'show_if' => [
                                            [
                                                'key'     => 'tag_with_plan',
                                                'compare' => '=',
                                                'value'   => true,
                                            ]
                                        ],
                                        'option_groups' => [
                                            [
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'options' => [],
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                            ]
                                            
                                            ],
                                    ],
                                ],
                                'range' => [
                                    'type' => [
                                        'type'  => 'hidden',
                                        'value' => 'radio',
                                    ],
                                    'field_key' => [
                                        'type'  => 'hidden',
                                        'value' => 'price_range',
                                    ],
                                    'label' => [
                                        'type'  => 'text',
                                        'label' => 'Label',
                                        'value' => 'Price Range',
                                    ],
                                    'placeholder' => [
                                        'type'  => 'text',
                                        'label' => 'Placeholder',
                                        'value' => '',
                                    ],
                                    'required' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Required',
                                        'value' => false,
                                    ],
                                    'only_for_admin' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Only For Admin Use',
                                        'value' => false,
                                    ],
                                    'tag_with_plan' => [
                                        'type'  => 'toggle',
                                        'label'  => 'Tag with plan',
                                        'value' => false,
                                    ],
                                    'plan' => [
                                        'type'  => 'option_group',
                                        'label'  => 'Chose a plan',
                                        'show_if' => [
                                            [
                                                'key'     => 'tag_with_plan',
                                                'compare' => '=',
                                                'value'   => true,
                                            ]
                                        ],
                                        'option_groups' => [
                                            [
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'options' => [],
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                                'plan' => [
                                                    'type'  => 'select',
                                                    'label'  => 'Plan',
                                                    'value' => '',
                                                ],
                                            ]
                                            
                                            ],
                                    ],
                                ],
                            ]
                        ],
                    ],

                    'view_count' => [
                        'label' => 'View Count',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'number',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'view_count',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'View Count',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => true,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'excerpt' => [
                        'label' => 'Excerpt',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'textarea',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'excerpt',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Excerpt',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'location' => [
                        'label' => 'Location',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'radio',
                                'value' => 'multiple',
                                'options' => [
                                    'single' => [
                                        'label' => __( 'Single Selection', 'directorist' ),
                                        'value' => 'single',
                                    ],
                                    'multiple' => [
                                        'label' => __( 'Multi Selection', 'directorist' ),
                                        'value' => 'multiple',
                                    ]
                                ]
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'location',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Location',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'tag' => [
                        'label' => 'Tag',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'radio',
                                'value' => 'multiple',
                                'options' => [
                                    'single' => [
                                        'label' => __( 'Single Selection', 'directorist' ),
                                        'value' => 'single',
                                    ],
                                    'multiple' => [
                                        'label' => __( 'Multi Selection', 'directorist' ),
                                        'value' => 'multiple',
                                    ]
                                ]
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'tag',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Tag',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'allow_new' => [
                                'type'  => 'toggle',
                                'label'  => 'Allow New',
                                'value' => true,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'category' => [
                        'label' => 'Category',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'radio',
                                'value' => 'multiple',
                                'options' => [
                                    'single' => [
                                        'label' => __( 'Single Selection', 'directorist' ),
                                        'value' => 'single',
                                    ],
                                    'multiple' => [
                                        'label' => __( 'Multi Selection', 'directorist' ),
                                        'value' => 'multiple',
                                    ]
                                ]
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'category',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Category',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'address' => [
                        'label' => 'Address',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'address',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Address',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'map' => [
                        'label' => 'Map',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'map',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'map',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Map',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'zip' => [
                        'label' => 'Zip/Post Code',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'zip',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Zip/Post Code',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'phone' => [
                        'label' => 'Phone',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'tel',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'phone',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Phone',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'phone2' => [
                        'label' => 'Phone 2',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'tel',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'phone2',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Phone 2',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'fax' => [
                        'label' => 'Fax',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'number',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'fax',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Fax',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'email' => [
                        'label' => 'Email',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'email',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'email',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Email',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'website' => [
                        'label' => 'Website',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'website',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Website',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'social_info' => [
                        'label' => 'Social Info',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'add_new',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'social_info',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Social Info',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'image_upload' => [
                        'label' => 'Images',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'media',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'image_upload',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Select Files',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'max_image_limit' => [
                                'type'  => 'number',
                                'label' => 'Max Image Limit',
                                'value' => '',
                            ],
                            'max_per_image_limit' => [
                                'type'  => 'number',
                                'label' => 'Max Upload Size Per Image in MB',
                                'value' => '',
                            ],
                            'max_total_image_limit' => [
                                'type'  => 'number',
                                'label' => 'Total Upload Size in MB',
                                'value' => '',
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'video' => [
                        'label' => 'Video',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'video',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Video',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ],
                    ],

                    'terms_conditions' => [
                        'label' => 'Terms & Conditions',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'checkbox',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'terms_conditions',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'I agree with all',
                            ],
                            'linking_text' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Terms & Conditions',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => true,
                            ],
                        ],
                    ],

                    'privacy_policy' => [
                        'label' => 'Privacy & Policy',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'checkbox',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'privacy_policy',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'I agree to the',
                            ],
                            'linking_text' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Privacy & Policy',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => true,
                            ],
                        ],
                    ],

                    'submit_button' => [
                        'label' => 'Submit Button',
                        'icon' => 'fa fa-text-height',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'button',
                            ],
                            'field_key' => [
                                'type'  => 'hidden',
                                'value' => 'submit_button',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Save & Preview',
                            ],
                            'preview' => [
                                'type'  => 'toggle',
                                'label'  => 'Enable Preview Mode',
                                'value' => true,
                            ]
                         
                        ],
                    ],

                ],

                'custom' => [
                    'text' => [
                        'label' => 'Text',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'textarea' => [
                        'label' => 'Textarea',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'textarea',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'rows' => [
                                'type'  => 'number',
                                'label' => 'Rows',
                                'value' => 8,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'number' => [
                        'label' => 'Number',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'number',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'url' => [
                        'label' => 'URL',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'date' => [
                        'label' => 'Date',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'date',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'time' => [
                        'label' => 'Time',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'time',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'color_picker' => [
                        'label' => 'Color',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'color_picker',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'select' => [
                        'label' => 'Select',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'select',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'options' => [
                                'type' => 'textarea',
                                'label' => __( 'Options', 'directorist' ),
                                'description' => __( 'Each on a new line, for example,
                                Male: Male
                                Female: Female
                                Other: Other', 'directorist' ),
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'checkbox' => [
                        'label' => 'Checkbox',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'checkbox',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'options' => [
                                'type' => 'textarea',
                                'label' => __( 'Options', 'directorist' ),
                                'description' => __( 'Each on a new line, for example,
                                Male: Male
                                Female: Female
                                Other: Other', 'directorist' ),
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'radio' => [
                        'label' => 'Radio',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'radio',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'options' => [
                                'type' => 'textarea',
                                'label' => __( 'Options', 'directorist' ),
                                'description' => __( 'Each on a new line, for example,
                                Male: Male
                                Female: Female
                                Other: Other', 'directorist' ),
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],

                    'file' => [
                        'label' => 'File Upload',
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'file',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => 'Custom Field',
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => 'custom-field',
                            ],
                            'file_types' => [
                                'type'  => 'radio',
                                'label' => 'File Type',
                                'value' => '',
                                'options' => [
                                    [
                                        'label' => __( 'All Types' ),
                                        'value' => 'all',
                                    ],
                                ],
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => '',
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' => false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __( 'Assign to', 'directorist' ),
                                'value' => 'form',
                                'options' => [
                                    'form'  => [
                                        'label' => __( 'Form', 'directorist' ),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __( 'Category', 'directorist' ),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __( 'Select Categories', 'directorist' ),
                                            'options' => [
                                                [
                                                    'label' => 'Category A',
                                                    'value' => 'category_a'
                                                ],
                                                [
                                                    'label' => 'Category B',
                                                    'value' => 'category_b'
                                                ],
                                            ]
                                        ],
                                    ], 
                                ],
                            ],
                            'tag_with_plan' => [
                                'type'  => 'toggle',
                                'label'  => 'Tag with plan',
                                'value' => false,
                            ],
                            'plan' => [
                                'type'  => 'option_group',
                                'label'  => 'Chose a plan',
                                'show_if' => [
                                    [
                                        'key'     => 'tag_with_plan',
                                        'compare' => '=',
                                        'value'   => true,
                                    ]
                                ],
                                'option_groups' => [
                                    [
                                        'plan' => [
                                            'type'  => 'select',
                                            'options' => [],
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                        'plan' => [
                                            'type'  => 'select',
                                            'label'  => 'Plan',
                                            'value' => '',
                                        ],
                                    ]
                                    
                                    ],
                            ],
                        ]
                        
                    ],


                ],
            ];

            $this->settings = apply_filters( 'atbdp_listing_type_settings', [
                'general' => [
                    'label' => 'General',
                    'icon' => '',
                    'submenu' => apply_filters('atbdp_listing_type_general_submenu', [
                        'general' => [
                            'label' => __( 'General', 'directorist' ),
                            'sections' => [
                                'labels' => [
                                    'title'       => __( 'Labels', 'directorist' ),
                                    'description' => '',
                                    'fields'      => [
                                        'name',
                                        'icon',
                                        'singular_name',
                                        'plural_name' ,
                                        'permalink',
                                    ],
                                ],
                            ],
                        ],
                        'packages' => [
                            'label' => 'Packages',
                            'sections' => [
                                'labels' => [
                                    'title'       => 'Paid listing packages',
                                    'description' => 'Set what packages the user can choose from when submitting a listing of this type.',
                                    'fields'      => [
                                        'package_list'
                                    ],
                                ],
                            ],
                        ],
                        'other' => [
                            'label' => __( 'Other', 'directorist' ),
                            'sections' => [
                                'labels' => [
                                    'title'       => __( 'Common Settings', 'directorist' ),
                                    'description' => '',
                                    'fields'      => [
                                        'default_expiration',
                                        'new_listing_status',
                                        'edit_listing_status',
                                        'global_listing_type',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],

                'submission_form' => [
                    'label' => 'General',
                    'icon' => '',
                    'sections' => [
                        'fields' => [
                            'title'       => __( 'Select or create fields for this listing type', 'directorist' ),
                            'description' => 'need help?',
                            'form_fields' => 'submission_form_fields',
                        ],
                    ],

                ]
            ]);
        }

        // add_menu_pages
        public function add_menu_pages() {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Listing Types',
                'Listing Types',
                'manage_options',
                'atbdp-listing-types',
                [ $this, 'menu_page_callback__listing_types' ],
                5
            );
        }

        // menu_page_callback__listing_types
        public function menu_page_callback__listing_types() {
            $post_types_list_table = new Listing_Types_List_Table( $this );
            
            $action = $post_types_list_table->current_action();
            $post_types_list_table->prepare_items();

            $listing_type_id = 0;

            if ( ! empty( $action ) && ( 'edit' === $action ) && ! empty( $_REQUEST['listing_type_id'] )  ) {
                $listing_type_id = absint( $_REQUEST['listing_type_id'] );

                $term = get_term( $listing_type_id, 'atbdp_listing_types' );
                $all_term_meta = get_term_meta( $listing_type_id );

                if ( $term ) {
                    $this->fields[ 'name' ]['value'] = $term->name;
                }

                foreach ( $all_term_meta as $meta_key => $meta_value ) {
                    if ( isset( $this->fields[ $meta_key ] ) ) {
                        $this->fields[ $meta_key ]['value'] = $meta_value[0];
                    }
                }
            }

            $data = [
                'post_types_list_table' => $post_types_list_table,
                'settings'              => json_encode( $this->settings ),
                'fields'                => json_encode( $this->fields ),
                'form_fields'           => json_encode( $this->form_fields ),
                'id'                    => $listing_type_id,
                'add_new_link'          => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-listing-types&action=add_new' ),
            ];

            if ( ! empty( $action ) && ( 'edit' === $action || 'add_new' === $action )  ) {
                $this->enqueue_scripts();
                atbdp_load_admin_template( 'post-types-manager/edit-listing-type', $data );

                return;
            }

            atbdp_load_admin_template( 'post-types-manager/all-listing-types', $data );
        }

        // handle_delete_listing_type_request
        public function handle_delete_listing_type_request() {

            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete_listing_type' ) ) {
                wp_die( 'Are you cheating? | _wpnonce' );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( 'Are you cheating? | manage_options' );
            }

            $term_id = isset( $_REQUEST['listing_type_id'] ) ? absint( $_REQUEST['listing_type_id'] ) : 0;

            $this->delete_listing_type( $term_id );


            wp_redirect( admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-listing-types' ) );
            exit;
        }

        // delete_listing_type
        public function delete_listing_type( $term_id = 0 ) {
            if ( wp_delete_term( $term_id , 'atbdp_listing_types' ) ) {
                atbdp_add_flush_alert([
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'message' => 'Successfully Deleted the listing type',
                ]);
            } else {
                atbdp_add_flush_alert([ 
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'type'    => 'error',
                    'message' => 'Failed to delete the listing type'
                ]);
            }
        }

        // register_terms
        public function register_terms() {
            register_taxonomy( 'atbdp_listing_types', [ ATBDP_POST_TYPE ], [
                'hierarchical' => false,
                'labels' => [
                    'name' => _x( 'Listing Type', 'taxonomy general name', 'directorist' ),
                    'singular_name' => _x('Listing Type', 'taxonomy singular name', 'directorist'),
                    'search_items' => __('Search Listing Type', 'directorist'),
                    'menu_name' => __('Listing Type', 'directorist'),
                ],
                'show_ui' => false,
             ]);
        }


       
        // enqueue_scripts
        public function enqueue_scripts() {
            wp_enqueue_style( 'atbdp-font-awesome' );
            wp_enqueue_style( 'atbdp_admin_css' );

            wp_localize_script( 'atbdp_admin_app', 'ajax_data', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
            wp_enqueue_script( 'atbdp_admin_app' );
            
            
        }

        // register_scripts
        public function register_scripts() {
            wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION );
        }

    }
}