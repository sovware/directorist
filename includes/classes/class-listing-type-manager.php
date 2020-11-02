<?php

if (!class_exists('ATBDP_Listing_Type_Manager')) {
    class ATBDP_Listing_Type_Manager
    {
        public $fields = [];
        public $layouts = [];
        public $config = [];
        public $default_form = [];
        public $old_custom_fields = [];

        // run
        public function run()
        {
            add_filter( 'cptm_fields_before_update', [$this, 'cptm_fields_before_update'], 20, 1 );

            add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
            add_action( 'init', [$this, 'register_terms'] );
            add_action( 'admin_menu', [$this, 'add_menu_pages'] );
            add_action( 'admin_post_delete_listing_type', [$this, 'handle_delete_listing_type_request'] );

            add_action( 'wp_ajax_save_post_type_data', [ $this, 'save_post_type_data' ] );
            add_action( 'wp_ajax_save_imported_post_type_data', [ $this, 'save_imported_post_type_data' ] );
            add_action( 'template_redirect', [ $this, 'directory_json_download_link' ] );
        }

        public function save_imported_post_type_data() {
            $term_id        = ( ! empty( $_POST[ 'term_id' ] ) ) ? ( int ) $_POST[ 'term_id' ] : 0;
            $directory_name = ( ! empty( $_POST[ 'directory-name' ] ) ) ? $_POST[ 'directory-name' ] : '';
            $json_file      = ( ! empty( $_FILES[ 'directory-import-file' ] ) ) ? $_FILES[ 'directory-import-file' ] : '';

            // Validation
            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            // Validate file
            if ( empty( $json_file ) ) {
                $response['status']['status_log']['file_is_missing'] = [
                    'type' => 'error',
                    'message' => 'File is missing',
                ];

                $response['status']['error_count']++;
            }

            // Validate file data
            $file_contents = file_get_contents( $json_file['tmp_name'] );
            if ( empty( $file_contents ) ) {
                $response['status']['status_log']['invalid_data'] = [
                    'type' => 'error',
                    'message' => 'The data is invalid',
                ];

                $response['status']['error_count']++;
            }
        
            // Send respone if has error
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                wp_send_json( $response , 200 );
            }

            $add_directory = $this->add_directory([ 
                'term_id'        => $term_id,
                'directory_name' => $directory_name,
                'fields_value'   => $file_contents,
                'is_json'        => true
            ]);

            wp_send_json( $add_directory, 200 );
        }

        // add_directory
        public function add_directory( array $args = [] ) {
            $default = [ 
                'term_id'        => 0,
                'directory_name' => '',
                'fields_value'   => [],
                'is_json'        => false
            ];
            $args = array_merge( $default, $args );

            $has_term_id = false;
            if ( ! empty( $args['term_id'] ) ) {
                $has_term_id = true;
            }

            if ( $has_term_id && ! is_numeric( $args['term_id'] ) ) {
                $has_term_id = false;
            }

            if ( $has_term_id && $args['term_id'] < 1 ) {
                $has_term_id = false;
            }
            
            $create_directory = [ 'term_id' => 0 ];

            if ( ! $has_term_id ) {
                $create_directory = $this->create_directory([ 
                    'directory_name' => $args['directory_name']
                ]);

                if ( ! $create_directory['status']['success'] ) {
                    return $create_directory;
                }
            }
            
            $update_directory = $this->update_directory([
                'term_id'        => ( ! $has_term_id ) ? $create_directory['term_id'] : $args['term_id'],
                'directory_name' => $args['directory_name'],
                'fields_value'   => $args['fields_value'],
                'is_json'        => $args['is_json'],
            ]);

            return $update_directory;
        }

        // create_directory
        public function create_directory( array $args = [] ) {
            $default = [ 'directory_name' => '' ];
            $args    = array_merge( $default, $args );

            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            // Validate name
            if ( empty( $args['directory_name'] ) ) {
                $response['status']['status_log']['name_is_missing'] = [
                    'type'    => 'error',
                    'message' => 'Name is missing',
                ];

                $response['status']['error_count']++;
            }

            // Validate term name
            if ( ! empty( $args['directory_name'] ) && term_exists( $args['directory_name'], 'atbdp_listing_types' ) ) {
                $response['status']['status_log']['term_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            // Return status
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }

            // Create the directory
            $term = wp_insert_term( $args['directory_name'], 'atbdp_listing_types');
            
            if ( is_wp_error( $term ) ) {
                $response['status']['status_log']['term_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }

            $response['term_id'] = $term['term_id'];
            $response['status']['status_log']['term_created'] = [
                'type'    => 'success',
                'message' => 'The directory has been created successfuly',
            ];

            return $response;
        }

        // update_directory
        public function update_directory( array $args = [] ) {
            $default = [ 'directory_name' => '', 'term_id' => 0, 'fields_value' => [], 'is_json' => false ];
            $args = array_merge( $default, $args );

            // return [ 'status' => true, 'mode' => 'debug' ];

            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            $response['term_id'] = $args['term_id'];
            
            // Validation
            if ( $args['is_json'] ) {
                $args['fields_value'] = json_decode( $args['fields_value'], true );
            }

            // Validate data
            $has_invalid_data = false;

            if ( is_null( $args['fields_value'] ) ) {
                $has_invalid_data = true;
            }

            if ( 'array' !== gettype( $args['fields_value'] ) ) {
                $has_invalid_data = true;
            }

            if ( $has_invalid_data ) {
                $response['status']['status_log']['invalid_data'] = [
                    'type' => 'error',
                    'message' => 'The data is invalid',
                ];
    
                $response['status']['error_count']++;
            }

            // Validate term id
            $has_invalid_term_id = false;

            if ( empty( $args['term_id'] ) ) {
                $has_invalid_term_id = true;
            }

            if ( ! is_numeric( $args['term_id'] ) ) {
                $has_invalid_term_id = true;
            }

            $term_id = $args['term_id'];
            if ( is_numeric( $term_id ) ) {
                $args['term_id'] = ( int ) $term_id;
            }

            // Validate term id
            if ( ! term_exists( $args['term_id'], 'atbdp_listing_types' ) ) {
                $has_invalid_term_id = true;
            }

            if ( $has_invalid_term_id ) {
                $response['status']['status_log']['invalid_term_id'] = [
                    'type'    => 'error',
                    'message' => 'Invalid term ID',
                ];

                $response['status']['error_count']++;
            }

            
            $fields = apply_filters( 'cptm_fields_before_update', $args['fields_value'] );
            $directory_name = ( ! empty( $fields['name'] ) ) ? $fields['name'] : '';
            $directory_name = ( ! empty( $args['directory_name'] ) ) ? $args['directory_name'] : $directory_name;
            
            $response['fields_value']   = $fields;
            $response['directory_name'] = $args['directory_name'];

            unset( $fields['name'] );

            $term = get_term( $args['term_id'], 'atbdp_listing_types');
            $old_name = $term->name;

            if ( $old_name !== $directory_name && term_exists( $directory_name, 'atbdp_listing_types' ) ) {
                $response['status']['status_log']['name_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            // Return status
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }
            
            // Update name if exist
            if ( ! empty( $directory_name ) ) {
                wp_update_term( $args['term_id'], 'atbdp_listing_types', ['name' => $directory_name] );
            }
            
            // Update the value
            foreach ( $fields as $key => $value ) {
                $this->update_validated_term_meta( $args['term_id'], $key, $value );
            }

            $response['status']['status_log']['updated'] = [
                'type'    => 'success',
                'message' => 'The directory has been updated successfuly',
            ];

            return $response;
        }


        // cptm_fields_before_update
        public function cptm_fields_before_update( $fields ) {
            $new_fields     = $fields;
            $fields_group   = $this->config['fields_group'];

            foreach ( $fields_group as $group_name => $group_fields ) {
                $grouped_fields_value = [];

                foreach ( $group_fields as $field_index => $field_key ) {
                    if ('string' === gettype( $field_key ) && array_key_exists($field_key, $this->fields)) {
                        $grouped_fields_value[ $field_key ] = ( isset( $new_fields[ $field_key ] ) ) ? $new_fields[ $field_key ] : '';
                        unset( $new_fields[ $field_key ] );
                    }

                    if ( 'array' === gettype( $field_key ) ) {
                        $grouped_fields_value[ $field_index ] = [];

                        foreach ( $field_key as $sub_field_key ) {
                            if ( array_key_exists( $sub_field_key, $this->fields ) ) {
                                $grouped_fields_value[ $field_index ][ $sub_field_key ] = ( isset( $new_fields[ $sub_field_key ] ) ) ? $new_fields[ $sub_field_key ] : '';
                                unset( $new_fields[ $sub_field_key ] );
                            }
                        }
                    }
                }

                $new_fields[ $group_name ] = $grouped_fields_value;
            }

            return $new_fields;
        }   

        // save_post_type_data
        public function save_post_type_data()
        {
           /*  wp_send_json([
                'status' => false,
                'listings_card_grid_view' => $this->maybe_json( $_POST['listings_card_grid_view'] ),
                'status_log' => [
                    'name_is_missing' => [
                        'type' => 'error',
                        'message' => 'Debugging',
                    ],
                ],
            ], 200 ); */

            if (empty($_POST['name'])) {
                wp_send_json([
                    'status' => false,
                    'status_log' => [
                        'name_is_missing' => [
                            'type' => 'error',
                            'message' => 'Name is missing',
                        ],
                    ],
                ], 200);
            }

            $term_id = 0;
            $mode    = 'create';
            $listing_type_name = $_POST['name'];

            if (!empty($_POST['listing_type_id']) && absint($_POST['listing_type_id'])) {
                $mode = 'edit';
                $term_id = absint($_POST['listing_type_id']);
                wp_update_term($term_id, 'atbdp_listing_types', ['name' => $listing_type_name]);
            } else {
                $term = wp_insert_term($listing_type_name, 'atbdp_listing_types');

                if (is_wp_error($term)) {
                    if (!empty($term->errors['term_exists'])) {
                        wp_send_json([
                            'status' => false,
                            'status_log' => [
                                'name_exists' => [
                                    'type' => 'error',
                                    'message' => 'The name already exists',
                                ]
                            ],
                        ], 200);
                    }
                } else {
                    $mode = 'edit';
                    $term_id = $term['term_id'];
                }
            }

            if (empty($term_id)) {
                wp_send_json([
                    'status' => false,
                    'status_log' => [
                        'invalid_id' => [
                            'type' => 'error',
                            'message' => 'Error found, please try again',
                        ]
                    ],
                ], 200);
            }

            $created_message = ('create' == $mode) ? 'created' : 'updated';

            if (empty($_POST['field_list'])) {
                wp_send_json([
                    'status' => true,
                    'post_id' => $term_id,
                    'status_log' => [
                        'post_created' => [
                            'type' => 'success',
                            'message' => 'The Post type has been ' . $created_message . ' successfully',
                        ],
                        'field_list_not_found' => [
                            'type' => 'error',
                            'message' => 'Field list not found',
                        ],
                    ],
                ], 200);
            }
            $url = '';
            $field_list = $this->maybe_json($_POST['field_list']);
            foreach ($field_list as $field_key) {
                if (isset($_POST[$field_key]) && 'name' !==  $field_key) {
                    $this->update_validated_term_meta($term_id, $field_key, $_POST[$field_key]);
                }
            }
            $url = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=edit&listing_type_id=' . $term_id);
            wp_send_json([
                'status' => true,
                'post_id' => $term_id,
                'redirect_url' => $url,
                'status_log' => [
                    'post_created' => [
                        'type' => 'success',
                        'message' => 'The post type has been ' . $created_message . ' successfully'
                    ]
                ],
            ], 200);
        }

        // update_validated_term_meta
        public function update_validated_term_meta($term_id, $field_key, $value)
        {
            if (!isset($this->fields[$field_key]) && !array_key_exists($field_key, $this->config['fields_group'])) {
                return;
            }

            if ('toggle' === $this->fields[$field_key]['type']) {
                $value = ('true' === $value || true === $value || '1' === $value || 1 === $value) ? true : 0;
            }

            $value = $this->maybe_json($value);
            update_term_meta($term_id, $field_key, $value);
        }

        // maybe_json
        public function maybe_json($string)
        {
            $string_alt = $string;

            if (preg_match('/\\\\+/', $string_alt)) {
                $string_alt = preg_replace('/\\\\+/', '', $string_alt);
                $string_alt = json_decode($string_alt, true);
                $string     = (!is_null($string_alt)) ? $string_alt : $string;
            }

            $test_json = json_decode($string, true);
            if (!is_null($test_json)) {
                $string = $test_json;
            }

            return $string;
        }

        // maybe_serialize
        public function maybe_serialize($value = '')
        {
            return maybe_serialize($this->maybe_json($value));
        }

        public function get_old_custom_fields($fields_of = 'form')
        {
            $fields = [];
            $old_fields = atbdp_get_custom_field_ids('', true);
            foreach ($old_fields as $old_field) {
                $field_type = get_post_meta($old_field, 'type', true);
                $description = get_post_meta($old_field, 'instructions', true);
                $required = get_post_meta($old_field, 'required', true);
                $admin_use = get_post_meta($old_field, 'admin_use', true);
                $associate = get_post_meta($old_field, 'associate', true);
                $category_pass = get_post_meta($old_field, 'category_pass', true);
                $choices = get_post_meta($old_field, 'choices', true);
                $rows = get_post_meta($old_field, 'rows', true);
                $target = get_post_meta($old_field, 'target', true);
                $file_type = get_post_meta($old_field, 'file_type', true);
                $file_size = get_post_meta($old_field, 'file_size', true);
                if (('text' === $field_type) || ('number' === $field_type) || ('date' === $field_type) || ('color' === $field_type) || ('time' === $field_type)) {
                    $fields[$field_type] = [
                        'label' => get_the_title($old_field),
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => get_the_title($old_field),
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => $old_field,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => $description,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => $required == 1 ? true : false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' =>  $admin_use == 1 ? true : false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __('Assign to', 'directorist'),
                                'value' => $associate,
                                'options' => [
                                    'form'  => [
                                        'label' => __('Form', 'directorist'),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __('Category', 'directorist'),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __('Select Categories', 'directorist'),
                                            'value' => $category_pass,
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
                        ]
                    ];
                }
                if (('radio' === $field_type) || ('checkbox' === $field_type) || ('select' === $field_type)) {
                    $fields[$field_type] = [
                        'label' => get_the_title($old_field),
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'select',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => get_the_title($old_field),
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => $old_field,
                            ],
                            'options' => [
                                'type' => 'textarea',
                                'label' => __('Options', 'directorist'),
                                'value' => $choices,
                                'description' => __('Each on a new line, for example,
                                Male: Male
                                Female: Female
                                Other: Other', 'directorist'),
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => $description,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => $required == 1 ? true : false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' =>  $admin_use == 1 ? true : false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __('Assign to', 'directorist'),
                                'value' => $associate,
                                'options' => [
                                    'form'  => [
                                        'label' => __('Form', 'directorist'),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __('Category', 'directorist'),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __('Select Categories', 'directorist'),
                                            'value' => $category_pass,
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
                        ]
                    ];
                }
                if (('textarea' === $field_type)) {
                    $fields[$field_type] = [
                        'label' => get_the_title($old_field),
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => get_the_title($old_field),
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => $old_field,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => $description,
                            ],
                            'rows' => [
                                'type'  => 'number',
                                'label' => $rows,
                                'value' => 8,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => $required == 1 ? true : false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' =>  $admin_use == 1 ? true : false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __('Assign to', 'directorist'),
                                'value' => $associate,
                                'options' => [
                                    'form'  => [
                                        'label' => __('Form', 'directorist'),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __('Category', 'directorist'),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __('Select Categories', 'directorist'),
                                            'value' => $category_pass,
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
                        ]
                    ];
                }
                if (('url' === $field_type)) {
                    $fields[$field_type] = [
                        'label' => get_the_title($old_field),
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => get_the_title($old_field),
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => $old_field,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => $description,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => $required == 1 ? true : false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' =>  $admin_use == 1 ? true : false,
                            ],
                            'target' => [
                                'type'  => 'toggle',
                                'label' => 'Open in new tab',
                                'value' => $target == '_blank' ? true : false,
                            ],
                            'assign_to' => [
                                'type' => 'radio',
                                'label' => __('Assign to', 'directorist'),
                                'value' => $associate,
                                'options' => [
                                    'form'  => [
                                        'label' => __('Form', 'directorist'),
                                        'value' => 'form',
                                    ],
                                    'category'  => [
                                        'label' => __('Category', 'directorist'),
                                        'value' => 'category',
                                        'sub_options' => [
                                            'type' => 'select',
                                            'label' => __('Select Categories', 'directorist'),
                                            'value' => $category_pass,
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
                        ]
                    ];
                }
                if (('file' === $field_type)) {
                    $fields[$field_type] = [
                        'label' => get_the_title($old_field),
                        'icon' => 'fa fa-text-width',
                        'options' => [
                            'type' => [
                                'type'  => 'hidden',
                                'value' => 'text',
                            ],
                            'label' => [
                                'type'  => 'text',
                                'label' => 'Label',
                                'value' => get_the_title($old_field),
                            ],
                            'field_key' => [
                                'type'  => 'text',
                                'label' => 'Key',
                                'value' => $old_field,
                            ],
                            'placeholder' => [
                                'type'  => 'text',
                                'label' => 'Placeholder',
                                'value' => '',
                            ],
                            'description' => [
                                'type'  => 'text',
                                'label' => 'Description',
                                'value' => $description,
                            ],
                            'required' => [
                                'type'  => 'toggle',
                                'label'  => 'Required',
                                'value' => $required == 1 ? true : false,
                            ],
                            'only_for_admin' => [
                                'type'  => 'toggle',
                                'label'  => 'Only For Admin Use',
                                'value' =>  $admin_use == 1 ? true : false,
                            ],
                            'file_types' => [
                                'type'  => 'radio',
                                'label' => 'File Type',
                                'value' => $file_type,
                                'options' => [
                                    'all' => [
                                        'label' => __('All Types', 'directorist'),
                                        'value' => 'all',
                                    ],
                                    'image_format' => [
                                        [
                                            'label' => __('jpg', 'directorist'),
                                            'value' => 'jpg',
                                        ],
                                        [
                                            'label' => __('jpeg', 'directorist'),
                                            'value' => 'jpeg',
                                        ],
                                        [
                                            'label' => __('gif', 'directorist'),
                                            'value' => 'gif',
                                        ],
                                        [
                                            'label' => __('png', 'directorist'),
                                            'value' => 'png',
                                        ],
                                        [
                                            'label' => __('bmp', 'directorist'),
                                            'value' => 'bmp',
                                        ],
                                        [
                                            'label' => __('ico', 'directorist'),
                                            'value' => 'ico',
                                        ],
                                    ],
                                    'video_format' => [
                                        [
                                            'label' => __('asf', 'directorist'),
                                            'value' => 'asf',
                                        ],
                                        [
                                            'label' => __('flv', 'directorist'),
                                            'value' => 'flv',
                                        ],
                                        [
                                            'label' => __('avi', 'directorist'),
                                            'value' => 'avi',
                                        ],
                                        [
                                            'label' => __('mkv', 'directorist'),
                                            'value' => 'mkv',
                                        ],
                                        [
                                            'label' => __('mp4', 'directorist'),
                                            'value' => 'mp4',
                                        ],
                                        [
                                            'label' => __('mpeg', 'directorist'),
                                            'value' => 'mpeg',
                                        ],
                                        [
                                            'label' => __('mpg', 'directorist'),
                                            'value' => 'mpg',
                                        ],
                                        [
                                            'label' => __('wmv', 'directorist'),
                                            'value' => 'wmv',
                                        ],
                                        [
                                            'label' => __('3gp', 'directorist'),
                                            'value' => '3gp',
                                        ],
                                    ],
                                    'audio_format' => [
                                        [
                                            'label' => __('ogg', 'directorist'),
                                            'value' => 'ogg',
                                        ],
                                        [
                                            'label' => __('mp3', 'directorist'),
                                            'value' => 'mp3',
                                        ],
                                        [
                                            'label' => __('wav', 'directorist'),
                                            'value' => 'wav',
                                        ],
                                        [
                                            'label' => __('wma', 'directorist'),
                                            'value' => 'wma',
                                        ],
                                    ],
                                    'text_format' => [
                                        [
                                            'label' => __('css', 'directorist'),
                                            'value' => 'css',
                                        ],
                                        [
                                            'label' => __('csv', 'directorist'),
                                            'value' => 'csv',
                                        ],
                                        [
                                            'label' => __('htm', 'directorist'),
                                            'value' => 'htm',
                                        ],
                                        [
                                            'label' => __('html', 'directorist'),
                                            'value' => 'html',
                                        ],
                                        [
                                            'label' => __('txt', 'directorist'),
                                            'value' => 'txt',
                                        ],
                                        [
                                            'label' => __('rtx', 'directorist'),
                                            'value' => 'rtx',
                                        ],
                                        [
                                            'label' => __('vtt', 'directorist'),
                                            'value' => 'vtt',
                                        ],
                                    ],
                                    'application_format' => [
                                        [
                                            'label' => __('doc', 'directorist'),
                                            'value' => 'doc',
                                        ],
                                        [
                                            'label' => __('docx', 'directorist'),
                                            'value' => 'docx',
                                        ],
                                        [
                                            'label' => __('odt', 'directorist'),
                                            'value' => 'odt',
                                        ],
                                        [
                                            'label' => __('pdf', 'directorist'),
                                            'value' => 'pdf',
                                        ],
                                        [
                                            'label' => __('pot', 'directorist'),
                                            'value' => 'pot',
                                        ],
                                        [
                                            'label' => __('ppt', 'directorist'),
                                            'value' => 'ppt',
                                        ],
                                        [
                                            'label' => __('pptx', 'directorist'),
                                            'value' => 'pptx',
                                        ],
                                        [
                                            'label' => __('rar', 'directorist'),
                                            'value' => 'rar',
                                        ],
                                        [
                                            'label' => __('rtf', 'directorist'),
                                            'value' => 'rtf',
                                        ],
                                        [
                                            'label' => __('swf', 'directorist'),
                                            'value' => 'swf',
                                        ],
                                        [
                                            'label' => __('xls', 'directorist'),
                                            'value' => 'xls',
                                        ],
                                        [
                                            'label' => __('xlsx', 'directorist'),
                                            'value' => 'xlsx',
                                        ],
                                        [
                                            'label' => __('gpx', 'directorist'),
                                            'value' => 'gpx',
                                        ],
                                    ],

                                ],
                            ],
                            'file_size' => [
                                'type'  => 'text',
                                'label' => 'File Size',
                                'description' => __('Set maximum file size to upload', 'directorist'),
                                'value' => $file_size,
                            ],
                        ]
                    ];
                }
            }
            return $fields;
        }

        // prepare_settings
        public function prepare_settings()
        {
            $this->default_form = apply_filters('atbdp_default_listing_form_sections', [
                'general_information' => [
                    'label' => __('General Information', 'directorist'),
                    'fields' => apply_filters('atbdp_general_info_section_fields', ['title', 'description', 'pricing', $this->get_old_custom_fields(), 'location', 'tag', 'category', $this->get_old_custom_fields('category')]),
                ],

                'contact_information' => [
                    'label' => __('Contact Information', 'directorist'),
                    'fields' => apply_filters('atbdp_contact_info_section_fields', ['zip', 'phone', 'phone2', 'fax', 'email', 'website', 'social_info']),
                ],

                'map' => [
                    'label' => __('Map', 'directorist'),
                    'fields' => apply_filters('atbdp_map_section_fields', ['address', 'map']),
                ],

                'media' => [
                    'label' => __('Images & Video', 'directorist'),
                    'fields' => apply_filters('atbdp_media_section_fields', ['image_upload', 'video']),
                ],

                'submit_area' => [
                    'fields' => apply_filters('atbdp_submit_area_fields', ['terms_conditions', 'privacy_policy', 'submit_button']),
                ],

            ]);


            $form_field_widgets = [
                'preset' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'widgets' => apply_filters('atbdp_form_preset_widgets', [
                        'title' => [
                            'label' => 'Title',
                            'icon' => 'fa fa-text-height',
                            'lock' => true,
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'listing_title',
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
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],

                            ],
                        ],

                        'description' => [
                            'label' => 'Description',
                            'icon' => 'fa fa-align-left',
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'select',
                                    'label' => 'Type',
                                    'value' => 'wp_editor',
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
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value' => 'listing_content',
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
                                    'name'  => 'required',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],

                            ]
                        ],

                        'tagline' => [
                            'label' => 'Tagline',
                            'icon' => 'uil uil-text-fields',
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tagline',
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

                            ],
                        ],

                        'pricing' => [
                            'label' => 'Pricing',
                            'icon' => 'uil uil-bill',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value'  => 'Pricing',
                                ],
                                'pricing_type' => [
                                    'type'  => 'select',
                                    'label'  => 'Select Pricing Type',
                                    'value' => 'both',
                                    'options' => [
                                        ['value' => '', 'label' => 'Select...'],
                                        ['value' => 'price_unit', 'label' => 'Price Unit'],
                                        ['value' => 'price_range', 'label' => 'Price Range'],
                                        ['value' => 'both', 'label' => 'Both'],
                                    ],
                                ],
                                'pricing_type' => [
                                    'type'  => 'select',
                                    'label'  => 'Select Pricing Type',
                                    'value' => 'both',
                                    'options' => [
                                        ['value' => '', 'label' => 'Select...'],
                                        ['value' => 'price_unit', 'label' => 'Price Unit'],
                                        ['value' => 'price_range', 'label' => 'Price Range'],
                                        ['value' => 'both', 'label' => 'Both'],
                                    ],
                                ],
                                'price_range_label' => [
                                    'type'  => 'text',
                                    'show_if' => [
                                        [
                                            'compare' => 'or',
                                            'conditions' => [
                                                ['key' => 'pricing_type', 'value' => 'both'],
                                                ['key' => 'pricing_type', 'value' => 'price_range'],
                                            ]
                                        ],

                                    ],
                                    'label'  => 'Price range label',
                                    'value' => 'Price range',
                                    'options' => [
                                        ['value' => 'number', 'label' => 'Number',],
                                        ['value' => 'text', 'label' => 'text',],
                                    ],
                                ],
                                'price_range_options' => [
                                    'type'  => 'select',
                                    'show_if'  => [
                                        [
                                            'compare' => 'or',
                                            'conditions' => [
                                                ['key' => 'pricing_type', 'value' => 'both'],
                                                ['key' => 'pricing_type', 'value' => 'price_range'],
                                            ]
                                        ],

                                    ],
                                    'label'  => 'Price Type Label',
                                    'value' => 'cheap',
                                    'options' => [
                                        ['value' => 'cheap', 'label' => 'Cheap',],
                                        ['value' => 'moderate', 'label' => 'Moderate',],
                                        ['value' => 'expensive', 'label' => 'Expensive',],
                                        ['value' => 'high', 'label' => 'Ultra High',],
                                    ],
                                ],
                                'price_unit_field_type' => [
                                    'type'  => 'select',
                                    'label'  => 'Price Unit field type',
                                    'show_if'  => [
                                        [
                                            'compare' => 'or',
                                            'conditions' => [
                                                ['key' => 'pricing_type', 'value' => 'both'],
                                                ['key' => 'pricing_type', 'value' => 'price_unit'],
                                            ]
                                        ],

                                    ],
                                    'value' => 'number',
                                    'options' => [
                                        ['value' => 'number', 'label' => 'Number',],
                                        ['value' => 'text', 'label' => 'Text',],
                                    ],
                                ],
                                'price_unit_field_label' => [
                                    'type'  => 'text',
                                    'label'  => 'Price Unit field label',
                                    'show_if'  => [
                                        [
                                            'compare' => 'or',
                                            'conditions' => [
                                                ['key' => 'pricing_type', 'value' => 'both'],
                                                ['key' => 'pricing_type', 'value' => 'price_unit'],
                                            ]
                                        ],
                                    ],
                                    'value' => 'Price [USD]',
                                    'options' => [
                                        ['value' => 'number', 'label' => 'Number',],
                                        ['value' => 'text', 'label' => 'text',],
                                    ],
                                ],
                            ]
                        ],

                        'view_count' => [
                            'label' => 'View Count',
                            'icon' => 'uil uil-eye',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value' => 'atbdp_post_views_count',
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


                            ],
                        ],

                        'excerpt' => [
                            'label' => 'Excerpt',
                            'icon' => 'uil uil-subject',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'textarea',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
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


                            ],
                        ],

                        'location' => [
                            'label' => 'Location',
                            'icon' => 'uil uil-map-marker',
                            'options' => [
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
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
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tax_input[at_biz_dir-location][]',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Location',
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


                            ],
                        ],

                        'tag' => [
                            'label' => 'Tag',
                            'icon' => 'uil uil-tag-alt',
                            'options' => [
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
                                    'options' => [
                                        [
                                            'label' => __('Single Selection', 'directorist'),
                                            'value' => 'single',
                                        ],
                                        [
                                            'label' => __('Multi Selection', 'directorist'),
                                            'value' => 'multiple',
                                        ],
                                    ]
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tax_input[at_biz_dir-tags][]',
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


                            ],
                        ],

                        'category' => [
                            'label' => 'Category',
                            'icon' => 'uil uil-folder-open',
                            'options' => [
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
                                    'options' => [
                                        [
                                            'label' => __('Single Selection', 'directorist'),
                                            'value' => 'single',
                                        ],
                                        [
                                            'label' => __('Multi Selection', 'directorist'),
                                            'value' => 'multiple',
                                        ],
                                    ]
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'admin_category_select[]',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Category',
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


                            ],
                        ],

                        'address' => [
                            'label' => 'Address',
                            'icon' => 'uil uil-postcard',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'address',
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


                            ],
                        ],

                        'map' => [
                            'label' => 'Map',
                            'icon' => 'uil uil-map',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'map',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'map',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Address Label',
                                    'value' => 'Address',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Address Placeholder',
                                    'value' => 'Listing address eg. New York, USA',
                                ],
                                'lat_long' => [
                                    'type'  => 'text',
                                    'label' => 'Enter Coordinates Label',
                                    'value' => 'Or Enter Coordinates (latitude and longitude) Manually',
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
                            ],
                        ],

                        'zip' => [
                            'label' => 'Zip/Post Code',
                            'icon' => 'uil uil-map-pin',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'zip',
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


                            ],
                        ],

                        'phone' => [
                            'label' => 'Phone',
                            'icon' => 'uil uil-phone',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'tel',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'phone',
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


                            ],
                        ],

                        'phone2' => [
                            'label' => 'Phone 2',
                            'icon' => 'uil uil-phone',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'tel',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'phone2',
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


                            ],
                        ],

                        'fax' => [
                            'label' => 'Fax',
                            'icon' => 'uil uil-print',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'fax',
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


                            ],
                        ],

                        'email' => [
                            'label' => 'Email',
                            'icon' => 'uil uil-envelope',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'email',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'email',
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


                            ],
                        ],

                        'website' => [
                            'label' => 'Website',
                            'icon' => 'uil uil-globe',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'website',
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


                            ],
                        ],

                        'social_info' => [
                            'label' => 'Social Info',
                            'icon' => 'uil uil-user-arrows',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'add_new',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'social',
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


                            ],
                        ],

                        'image_upload' => [
                            'label' => 'Images',
                            'icon' => 'uil uil-image',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'media',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'listing_img',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Images',
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


                            ],
                        ],

                        'video' => [
                            'label' => 'Video',
                            'icon' => 'uil uil-video',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'videourl',
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


                            ],
                        ],

                        'terms_conditions' => [
                            'label' => 'Terms & Conditions',
                            'icon' => 'uil uil-file-exclamation-alt',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'checkbox',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 't_c_check',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'I agree with all',
                                ],
                                'linking_text' => [
                                    'type'  => 'text',
                                    'label' => 'Linking Text',
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
                            'icon' => 'uil uil-file-exclamation-alt',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'checkbox',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'privacy_policy',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'I agree to the',
                                ],
                                'linking_text' => [
                                    'type'  => 'text',
                                    'label' => 'Linking Text',
                                    'value' => 'Privacy & Policy',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ],
                        ],
                    ]),
                ],

                'custom' => [
                    'title' => 'Custom Fields',
                    'description' => 'Click on a field type you want to create',
                    'allow_multiple' => true,
                    'widgets' => apply_filters('atbdp_form_custom_widgets', [
                        'text' => [
                            'label' => 'Text',
                            'icon' => 'uil uil-text',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Text',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'textarea' => [
                            'label' => 'Textarea',
                            'icon' => 'uil uil-text-fields',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'textarea',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Textarea',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'number' => [
                            'label' => 'Number',
                            'icon' => 'uil uil-0-plus',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Number',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'url' => [
                            'label' => 'URL',
                            'icon' => 'uil uil-link-add',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'URL',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'target' => [
                                    'type'  => 'text',
                                    'label' => 'Open in new tab',
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
                                ],


                            ]

                        ],

                        'date' => [
                            'label' => 'Date',
                            'icon' => 'uil uil-calender',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'date',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Date',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'time' => [
                            'label' => 'Time',
                            'icon' => 'uil uil-clock',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'time',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Time',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'color_picker' => [
                            'label' => 'Color',
                            'icon' => 'uil uil-palette',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'color_picker',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Color',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
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
                                ],


                            ]

                        ],

                        'select' => [
                            'label' => 'Select',
                            'icon' => 'uil uil-file-check',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'select',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Select',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
                                ],
                                'options' => [
                                    'type' => 'textarea',
                                    'label' => __('Options', 'directorist'),
                                    'description' => __('Each on a new line, for example,
                                    Male: Male
                                    Female: Female
                                    Other: Other', 'directorist'),
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
                                ],


                            ]

                        ],

                        'checkbox' => [
                            'label' => 'Checkbox',
                            'icon' => 'uil uil-check-square',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'checkbox',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Checkbox',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
                                ],
                                'options' => [
                                    'type' => 'textarea',
                                    'label' => __('Options', 'directorist'),
                                    'description' => __('Each on a new line, for example,
                                    Male: Male
                                    Female: Female
                                    Other: Other', 'directorist'),
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
                                ],


                            ]

                        ],

                        'radio' => [
                            'label' => 'Radio',
                            'icon' => 'uil uil-circle',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'radio',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Radio',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
                                ],
                                'options' => [
                                    'type' => 'custom-options',
                                    'label' => __('Options', 'directorist'),
                                    'description' => __('Each on a new line, for example,
                                        Male: Male
                                        Female: Female
                                        Other: Other', 'directorist'),
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
                                ],


                            ]

                        ],

                        'file' => [
                            'label' => 'File Upload',
                            'icon' => 'uil uil-file-upload-alt',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'file',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'File Upload',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom',
                                ],
                                'file_types' => [
                                    'type'  => 'select',
                                    'label' => 'Chose a file type',
                                    'value' => '',
                                    'options' => [
                                        [
                                            'label' => __('All Types', 'directorist'),
                                            'value' => 'all',
                                        ],
                                        [
                                            'group' => __('Image Format', 'directorist'),
                                            'options' => [
                                                [
                                                    'label' => __('jpg', 'directorist'),
                                                    'value' => 'jpg',
                                                ],
                                                [
                                                    'label' => __('jpeg', 'directorist'),
                                                    'value' => 'jpeg',
                                                ],
                                                [
                                                    'label' => __('gif', 'directorist'),
                                                    'value' => 'gif',
                                                ],
                                                [
                                                    'label' => __('png', 'directorist'),
                                                    'value' => 'png',
                                                ],
                                                [
                                                    'label' => __('bmp', 'directorist'),
                                                    'value' => 'bmp',
                                                ],
                                                [
                                                    'label' => __('ico', 'directorist'),
                                                    'value' => 'ico',
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => '',
                                            'options' => [
                                                [
                                                    'label' => __('asf', 'directorist'),
                                                    'value' => 'asf',
                                                ],
                                                [
                                                    'label' => __('flv', 'directorist'),
                                                    'value' => 'flv',
                                                ],
                                                [
                                                    'label' => __('avi', 'directorist'),
                                                    'value' => 'avi',
                                                ],
                                                [
                                                    'label' => __('mkv', 'directorist'),
                                                    'value' => 'mkv',
                                                ],
                                                [
                                                    'label' => __('mp4', 'directorist'),
                                                    'value' => 'mp4',
                                                ],
                                                [
                                                    'label' => __('mpeg', 'directorist'),
                                                    'value' => 'mpeg',
                                                ],
                                                [
                                                    'label' => __('mpg', 'directorist'),
                                                    'value' => 'mpg',
                                                ],
                                                [
                                                    'label' => __('wmv', 'directorist'),
                                                    'value' => 'wmv',
                                                ],
                                                [
                                                    'label' => __('3gp', 'directorist'),
                                                    'value' => '3gp',
                                                ],
                                            ],
                                        ],

                                        [
                                            'group' => '',
                                            'options' => [
                                                [
                                                    'label' => __('ogg', 'directorist'),
                                                    'value' => 'ogg',
                                                ],
                                                [
                                                    'label' => __('mp3', 'directorist'),
                                                    'value' => 'mp3',
                                                ],
                                                [
                                                    'label' => __('wav', 'directorist'),
                                                    'value' => 'wav',
                                                ],
                                                [
                                                    'label' => __('wma', 'directorist'),
                                                    'value' => 'wma',
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => 'Text Format',
                                            'options' => [
                                                [
                                                    'label' => __('css', 'directorist'),
                                                    'value' => 'css',
                                                ],
                                                [
                                                    'label' => __('csv', 'directorist'),
                                                    'value' => 'csv',
                                                ],
                                                [
                                                    'label' => __('htm', 'directorist'),
                                                    'value' => 'htm',
                                                ],
                                                [
                                                    'label' => __('html', 'directorist'),
                                                    'value' => 'html',
                                                ],
                                                [
                                                    'label' => __('txt', 'directorist'),
                                                    'value' => 'txt',
                                                ],
                                                [
                                                    'label' => __('rtx', 'directorist'),
                                                    'value' => 'rtx',
                                                ],
                                                [
                                                    'label' => __('vtt', 'directorist'),
                                                    'value' => 'vtt',
                                                ],
                                            ],

                                        ],
                                        [
                                            'group' => 'Application Format',
                                            'options' => [
                                                [
                                                    'label' => __('doc', 'directorist'),
                                                    'value' => 'doc',
                                                ],
                                                [
                                                    'label' => __('docx', 'directorist'),
                                                    'value' => 'docx',
                                                ],
                                                [
                                                    'label' => __('odt', 'directorist'),
                                                    'value' => 'odt',
                                                ],
                                                [
                                                    'label' => __('pdf', 'directorist'),
                                                    'value' => 'pdf',
                                                ],
                                                [
                                                    'label' => __('pot', 'directorist'),
                                                    'value' => 'pot',
                                                ],
                                                [
                                                    'label' => __('ppt', 'directorist'),
                                                    'value' => 'ppt',
                                                ],
                                                [
                                                    'label' => __('pptx', 'directorist'),
                                                    'value' => 'pptx',
                                                ],
                                                [
                                                    'label' => __('rar', 'directorist'),
                                                    'value' => 'rar',
                                                ],
                                                [
                                                    'label' => __('rtf', 'directorist'),
                                                    'value' => 'rtf',
                                                ],
                                                [
                                                    'label' => __('swf', 'directorist'),
                                                    'value' => 'swf',
                                                ],
                                                [
                                                    'label' => __('xls', 'directorist'),
                                                    'value' => 'xls',
                                                ],
                                                [
                                                    'label' => __('xlsx', 'directorist'),
                                                    'value' => 'xlsx',
                                                ],
                                                [
                                                    'label' => __('gpx', 'directorist'),
                                                    'value' => 'gpx',
                                                ],
                                            ],
                                        ]

                                    ],
                                ],
                                'file_size' => [
                                    'type'  => 'text',
                                    'label' => 'File Size',
                                    'description' => __('Set maximum file size to upload', 'directorist'),
                                    'value' => '2mb',
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


                            ]

                        ],
                    ])

                ],
            ];

            $single_listings_contents_widgets = [
                'available_widgets' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'template' => 'submission_form_fields',
                    'widgets' => [
                        'title'            => [ 'options' => [] ],
                        'description'      => [ 'options' => [] ],
                        'tagline'          => [ 'options' => [] ],
                        'pricing'          => [ 'options' => [] ],
                        'view_count'       => [ 'options' => [] ],
                        'excerpt'          => [ 'options' => [] ],
                        'location'         => [ 'options' => [] ],
                        'tag'              => [ 'options' => [] ],
                        'category'         => [ 'options' => [] ],
                        'address'          => [ 'options' => [] ],
                        'map'              => [ 'options' => [] ],
                        'zip'              => [ 'options' => [] ],
                        'phone'            => [ 'options' => [] ],
                        'phone2'           => [ 'options' => [] ],
                        'fax'              => [ 'options' => [] ],
                        'email'            => [ 'options' => [] ],
                        'website'          => [ 'options' => [] ],
                        'social_info'      => [ 'options' => [] ],
                        // 'image_upload'     => [ 'options' => [] ],
                        'video'            => [ 'options' => [] ],
                        // 'terms_conditions' => [ 'options' => [] ],
                        // 'privacy_policy'   => [ 'options' => [] ],
                        'text'             => [ 'options' => [] ],
                        'textarea'         => [ 'options' => [] ],
                        'number'           => [ 'options' => [] ],
                        'url'              => [ 'options' => [] ],
                        'date'             => [ 'options' => [] ],
                        'time'             => [ 'options' => [] ],
                        'color_picker'     => [ 'options' => [] ],
                        'select'           => [ 'options' => [] ],
                        'checkbox'         => [ 'options' => [] ],
                        'radio'            => [ 'options' => [] ],
                        'file'             => [ 'options' => [] ],
                    ],
                ],
            ];

            $search_form_widgets = [
                'available_widgets' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'template' => 'submission_form_fields',
                    'widgets' => [
                        'title' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ],
                        ],

                        'description' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'tagline' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'pricing' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'view_count' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'excerpt' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'location' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'tag' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'category' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'address' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'map' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'zip' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'phone' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'phone2' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'fax' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'email' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],

                        'website' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],
                            ]
                        ],
                       
                        'text' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'textarea' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'number' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'url' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'date' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'time' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'color_picker' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'select' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'checkbox' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'radio' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],
                        
                    ],
                ],
            ];

            $listing_card_widget = [
                'listing_title' => [
                    'type' => "title",
                    'label' => "Listing Title",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_listing_title",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'title'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listing Title Settings",
                        'fields' => [
                            'label' => [
                                'type' => "text",
                                'label' => "Label",
                                'value' => "text",
                            ],
                        ],
                    ],
                ],

                'listings_location' => [
                    'type' => "list-item",
                    'label' => "Listings Location",
                    'icon' => 'uil uil-location-point',
                    'hook' => "atbdp_listings_location",
                    'options' => [
                        'title' => "Listings Location Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-location-point",
                            ],
                        ],
                    ],
                ],

                'listings_tag' => [
                    'type' => "list-item",
                    'label' => "Listings Tag",
                    'icon' => 'la la-tags',
                    'hook' => "atbdp_listings_location",
                    'options' => [
                        'title' => "Listings Tag Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-tags",
                            ],
                        ],
                    ],
                ],

                '_website' => [
                    'type' => "list-item",
                    'label' => "Listings Website",
                    'icon' => 'la la-globe',
                    'hook' => "atbdp_listings_website",
                    'options' => [
                        'title' => "Listings Website Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-globe",
                            ],
                        ],
                    ],
                ],

                '_zip' => [
                    'type' => "list-item",
                    'label' => "Listings Zip",
                    'icon' => 'la la-at',
                    'hook' => "atbdp_listings_zip",
                    'options' => [
                        'title' => "Listings Zip Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-at",
                            ],
                        ],
                    ],
                ],

                '_email' => [
                    'type' => "list-item",
                    'label' => "Listings Email",
                    'icon' => 'la la-envelope',
                    'hook' => "atbdp_listings_email",
                    'options' => [
                        'title' => "Listings Email Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-envelope",
                            ],
                        ],
                    ],
                ],

                '_fax' => [
                    'type' => "list-item",
                    'label' => "Listings Fax",
                    'icon' => 'la la-fax',
                    'hook' => "atbdp_listings_fax",
                    'options' => [
                        'title' => "Listings Fax Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-fax",
                            ],
                        ],
                    ],
                ],

                '_phone' => [
                    'type' => "list-item",
                    'label' => "Listings Phone",
                    'icon' => 'la la-phone',
                    'hook' => "atbdp_listings_phone",
                    'options' => [
                        'title' => "Listings Phone Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-phone",
                            ],
                        ],
                    ],
                ],

                '_phone2' => [
                    'type' => "list-item",
                    'label' => "Listings Phone 2",
                    'icon' => 'la la-phone',
                    'hook' => "atbdp_listings_phone2",
                    'options' => [
                        'title' => "Listings Phone 2 Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-phone",
                            ],
                        ],
                    ],
                ],

                '_address' => [
                    'type' => "list-item",
                    'label' => "Listings Address",
                    'icon' => 'la la-map-marker',
                    'hook' => "atbdp_listings_map_address",
                    'options' => [
                        'title' => "Listings Address Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-map-marker",
                            ],
                        ],
                    ],
                ],

                'featured_badge' => [
                    'type' => "badge",
                    'label' => "Featured",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_featured_badge",
                    'options' => [
                        'title' => "Featured Badge Settings",
                        'fields' => [
                            'label' => [
                                'type' => "text",
                                'label' => "Label",
                                'value' => "Fetured",
                            ],
                        ],
                    ],
                ],

                'rating' => [
                    'type' => "rating",
                    'label' => "Rating",
                    'hook' => "atbdp_listings_rating",
                    'icon' => 'uil uil-text-fields',
                ],

                'new_badge' => [
                    'type' => "badge",
                    'label' => "New",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_new_badge",
                    'options' => [
                        'title' => "New Badge Settings",
                        'fields' => [
                            'label' => [
                                'type' => "text",
                                'label' => "Label",
                                'value' => "New",
                            ],
                            'new_badge_duration' => [
                                'type' => "number",
                                'label' => "New Badge Duration in Days",
                                'value' => "3",
                            ],
                        ],
                    ],
                ],

                'popular_badge' => [
                    'type' => "badge",
                    'label' => "Popular",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_popular_badge",
                    'options' => [
                        'title' => "Popular Badge Settings",
                        'fields' => [
                            'label' => [
                                'type' => "text",
                                'label' => "Label",
                                'value' => "Popular",
                            ],
                            'listing_popular_by' => [
                                'type' => "select",
                                'label' => "Popular Based on",
                                'value' => "view_count",
                                'options' => [
                                    ['value' => 'view_count', 'label' => 'View Count'],
                                    ['value' => 'average_rating', 'label' => 'Average Rating'],
                                ],
                            ],

                            'views_for_popular' => [
                                'type' => "number",
                                'label' => "Threshold in Views Count",
                                'value' => "5",
                                'show_if' => [
                                    [
                                        'condition' => [
                                            ['key' => 'listing_popular_by', 'value' => 'view_count'],
                                            ['key' => 'listing_popular_by', 'value' => 'both'],
                                        ]
                                    ]
                                ]
                            ],
                            'count_loggedin_user' => [
                                'type' => "toggle",
                                'label' => "Count Logged-in User View",
                                'value' => "",
                            ],

                        ],
                    ],
                ],

                'favorite_badge' => [
                    'type' => "badge",
                    'label' => "Favorite",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_favorite_badge",
                    'options' => [
                        'title' => "Favorite Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "fa fa-heart",
                            ],
                        ],
                    ],
                ],

                'view_count' => [
                    'type' => "view-count",
                    'label' => "View Count",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_view_count",
                    'options' => [
                        'title' => "View Count Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "fa fa-heart",
                            ],
                        ],
                    ],
                ],

                'category' => [
                    'type' => "category",
                    'label' => "Category",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_category",
                    'options' => [
                        'title' => "Category Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "fa fa-folder",
                            ],
                        ],
                    ],
                ],

                'user_avatar' => [
                    'type' => "avatar",
                    'label' => "User Avatar",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_user_avatar",
                    'options' => [
                        'title' => "User Avatar Settings",
                        'fields' => [
                            'align' => [
                                'type' => "radio",
                                'label' => "Align",
                                'value' => "center",
                                'options' => [
                                    [ 'id' => 'atbdp_user_avatar_align_right', 'label' => 'Right', 'value' => 'right' ],
                                    [ 'id' => 'atbdp_user_avatar_align_center', 'label' => 'Center', 'value' => 'center' ],
                                    [ 'id' => 'atbdp_user_avatar_align_left', 'label' => 'Left', 'value' => 'left' ],
                                ],
                            ],
                        ],
                    ],
                ],
                
                // Custom Fields
                'text' => [
                    'type' => "list-item",
                    'label' => "Text",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_text",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'text'],
                        ],
                    ],
                    'options' => [
                        'title' => "Text Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'textarea' => [
                    'type' => "list-item",
                    'label' => "Textarea",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_textarea",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'textarea'],
                        ],
                    ],
                    'options' => [
                        'title' => "Textarea Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],
                
                'number' => [
                    'type' => "list-item",
                    'label' => "Number",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_number",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'number'],
                        ],
                    ],
                    'options' => [
                        'title' => "Number Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'url' => [
                    'type' => "list-item",
                    'label' => "URL",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_url",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'url'],
                        ],
                    ],
                    'options' => [
                        'title' => "URL Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'date' => [
                    'type' => "list-item",
                    'label' => "Date",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_date",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'date'],
                        ],
                    ],
                    'options' => [
                        'title' => "Date Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'time' => [
                    'type' => "list-item",
                    'label' => "Time",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_time",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'time'],
                        ],
                    ],
                    'options' => [
                        'title' => "Time Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'color_picker' => [
                    'type' => "list-item",
                    'label' => "Color Picker",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_color",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'color'],
                        ],
                    ],
                    'options' => [
                        'title' => "Color Picker Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'select' => [
                    'type' => "list-item",
                    'label' => "Select",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_select",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'select'],
                        ],
                    ],
                    'options' => [
                        'title' => "Select Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'checkbox' => [
                    'type' => "list-item",
                    'label' => "Checkbox",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_checkbox",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'checkbox'],
                        ],
                    ],
                    'options' => [
                        'title' => "Checkbox Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'radio' => [
                    'type' => "list-item",
                    'label' => "Radio",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_radio",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'radio'],
                        ],
                    ],
                    'options' => [
                        'title' => "Radio Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'file' => [
                    'type' => "list-item",
                    'label' => "File",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_file",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'file'],
                        ],
                    ],
                    'options' => [
                        'title' => "File Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],
            ];


            $listing_card_list_view_widget = $listing_card_widget;

            if ( ! empty( $listing_card_list_view_widget[ 'user_avatar' ] ) && ! empty( $listing_card_list_view_widget[ 'user_avatar' ]['options'] ) ) {
                unset( $listing_card_list_view_widget[ 'user_avatar' ]['options'] );
            }

            $this->fields = apply_filters('atbdp_listing_type_settings_field_list', [
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

                'preview_image' => [
                    'label'       => __('Select', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => ATBDP_PUBLIC_ASSETS . 'images/grid.jpg',
                    'value'       => '',
                ],

                'import_export' => [
                    'type'  => 'export',
                ],

                'default_expiration' => [
                    'label' => __('Default expiration in days', 'directorist'),
                    'type'  => 'number',
                    'value' => '',
                    'placeholder' => '365',
                    'rules' => [
                        'required' => true,
                    ],
                ],

                'new_listing_status' => [
                    'label' => __('New Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => '',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

                'edit_listing_status' => [
                    'label' => __('Edited Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => '',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

                'global_listing_type' => [
                    'label' => __('Global Listing Type', 'directorist'),
                    'type'  => 'toggle',
                    'value' => '',
                ],
                'submit_button_label' => [
                    'label' => __('Submit Button Label', 'directorist'),
                    'type'  => 'text',
                    'value' => __('Save & Preview', 'directorist'),
                ],
                'preview_mode' => [
                    'label' => __('Enable Preview', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                
                'submission_form_fields' => apply_filters( 'atbdp_listing_type_form_fields', [
                    'type'    => 'form-builder',
                    'widgets' => $form_field_widgets,
                    'group-options' => [
                        'label' => [
                            'type'  => 'text',
                            'label' => 'Group Name',
                            'value' => '',
                        ],
                    ],

                    'value' => [
                        'fields' => [
                            'title' => [
                                'widget_group' => 'preset',
                                'widget_name' => 'title',
                                'type'        => 'text',
                                'field_key'   => 'listing_title',
                                'required'    => true,
                                'label'       => 'Title',
                                'placeholder' => '',
                            ],
                        ],
                        'groups' => [
                            [
                                'label' => 'General Group',
                                'lock' => true,
                                'fields' => ['title'],
                                'plans' => []
                            ],
                        ]
                    ],

                ] ),

                
                'single_listings_contents' => [
                    'type'     => 'form-builder',
                    'widgets'  => $single_listings_contents_widgets,
                    'group-options' => [
                        'icon' => [
                            'type'  => 'icon',
                            'label'  => 'Block/Section Icon',
                            'value' => '',
                        ],
                        'label' => [
                            'type'  => 'text',
                            'label' => 'Label',
                            'value' => '',
                        ],
                        'custom_block_id' => [
                            'type'  => 'text',
                            'label'  => 'Custom block ID',
                            'value' => '',
                        ],
                        'custom_block_classes' => [
                            'type'  => 'text',
                            'label'  => 'Custom block Clases',
                            'value' => '',
                        ],

                    ],
                    'value' => [],
                ],
                

                'enable_similar_listings' => [
                    'type'  => 'toggle',
                    'label' => 'Enable similar listings',
                    'value' => true,
                ],

                'similar_listings_logics' => [
                    'type'    => 'radio',
                    'name'    => 'similar_listings_logics',
                    'label' => 'Similar listings logics',
                    'options' => [
                        ['id' => 'match_category_nd_location', 'label' => 'Must match category and location', 'value' => 'match_category_nd_location'],
                        ['id' => 'match_category_or_location', 'label' => 'Must match category or location', 'value' => 'match_category_or_location'],
                    ],
                    'value'   => '',
                ],

                'similar_listings_number_of_listings_to_show' => [
                    'type'  => 'range',
                    'min'   => 0,
                    'max'   => 10,
                    'label' => 'Number of listings to show',
                    'value' => 0,
                ],

                'similar_listings_auto_hide' => [
                    'type'  => 'toggle',
                    'label' => 'Auto hide',
                    'value' => false,
                ],

                'search_form_fields' => [
                    'type'     => 'form-builder',
                    'widgets'  => $search_form_widgets,
                    'allow_add_new_section' => false,
                    'value' => [
                        'groups' => [
                            [
                                'label' => 'Basic',
                                'fields' => [],
                            ],
                            [
                                'label' => 'Advanced',
                                'fields' => [],
                            ],
                        ]
                    ],
                ],

                'single_listing_header' => [
                    'type' => 'card-builder',
                    'template' => 'listing-header',
                    'value' => '',
                    'card-options' => [
                        'general' => [
                            'back' => [
                              'type' => "badge",
                              'label' => "Back",
                              'options' => [
                                  'title' => "Back Button Settings",
                                  'fields' => [
                                      'label' => [
                                          'type' => "toggle",
                                          'label' => "Enable",
                                          'value' => true,
                                      ],
                                  ],
                              ],
                            ],
                            'section_title' => [
                              'type' => "title",
                              'label' => "Section Title",
                              'options' => [
                                  'title' => "Section Title Options",
                                  'fields' => [
                                      'label' => [
                                          'type' => "text",
                                          'label' => "Label",
                                          'value' => "Section Title",
                                      ],
                                  ],
                              ],
                            ],
                        ],
                        'content_settings' => [
                            'listing_title' => [
                              'type' => "title",
                              'label' => "Listing Title",
                              'options' => [
                                  'title' => "Listing Title Settings",
                                  'fields' => [
                                      'enable_title' => [
                                          'type' => "toggle",
                                          'label' => "Show Title",
                                          'value' => true,
                                      ],
                                      'enable_tagline' => [
                                          'type' => "toggle",
                                          'label' => "Show Tagline",
                                          'value' => true,
                                      ],
                                  ],
                              ],
                            ],
                            'listing_description' => [
                              'type' => "title",
                              'label' => "Description",
                              'options' => [
                                  'title' => "Description Settings",
                                  'fields' => [
                                    'enable' => [
                                        'type' => "toggle",
                                        'label' => "Show Description",
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
                        'bookmark' => [
                            'type' => "button",
                            'label' => "Bookmark",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_title",
                            'options' => [
                                'title' => "Bookmark Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'fa fa-home',
                                    ],
                                ],
                            ],
                        ],
                        'share' => [
                            'type' => "badge",
                            'label' => "Share",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_title",
                            'options' => [
                                'title' => "Share Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'fa fa-home',
                                    ],
                                ],
                            ],
                        ],
                        'report' => [
                            'type' => "badge",
                            'label' => "Report",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_title",
                            'options' => [
                                'title' => "Report Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'fa fa-home',
                                    ],
                                ],
                            ],
                        ],
                        'compare' => [
                            'type' => "badge",
                            'label' => "Compare",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_title",
                            'options' => [
                                'title' => "Compare Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'fa fa-home',
                                    ],
                                ],
                            ],
                        ],
                        
                        'listing_slider' => [
                            'type' => "thumbnail",
                            'label' => "Listings Slider",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_slider",
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'image_upload'],
                                ],
                            ],
                            'options' => [
                                'title' => "Listings Slider Settings",
                                'fields' => [
                                    'footer_thumbail' => [
                                        'type' => "toggle",
                                        'label' => "Enable Footer Thumbail",
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        'price' => [
                            'type' => "badge",
                            'label' => "Listings Price",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_price",
                        ],
                        'badges' => [
                            'type' => "badge",
                            'label' => "Badges",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_badges",
                            'options' => [
                                'title' => "Badge Settings",
                                'fields' => [
                                    'new_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display New Badge",
                                        'value' => true,
                                    ],
                                    'popular_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display Popular Badge",
                                        'value' => true,
                                    ],
                                    'popular_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display Popular Badge",
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        
                        'reviews' => [
                            'type' => "reviews",
                            'label' => "Listings Reviews",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_reviews",
                        ],
                        'ratings_count' => [
                            'type' => "ratings-count",
                            'label' => "Listings Ratings",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listings_ratings_count",
                        ],
                        'category' => [
                            'type' => "badge",
                            'label' => "Listings Category",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listing_category",
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                                ],
                            ],
                        ],
                        'location' => [
                            'type' => "badge",
                            'label' => "Listings Location",
                            'icon' => 'uil uil-text-fields',
                            'hook' => "atbdp_single_listing_location",
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                                ],
                            ],
                        ],
                    ],
 
                    'layout' => [
                        'listings_header' => [
                            'quick_actions' => [
                                'label' => 'Top Right',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'bookmark', 'share', 'report', 'compare' ],
                            ],
                            'thumbnail' => [
                                'label' => 'Thumbnail',
                                'maxWidget' => 1,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'listing_slider' ],
                            ],
                            'quick_info' => [
                                'label' => 'Quick info',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'badges', 'price', 'reviews', 'ratings_count', 'category', 'location' ],
                            ],
                        ],
                    ],
                ],

                'listings_card_grid_view' => [
                    'type' => 'card-builder',
                    'template' => 'grid-view',
                    'value' => '',
                    'widgets' => $listing_card_widget,

                    'layout' => [
                        'thumbnail' => [
                            'top_right' => [
                                'label' => 'Top Right',
                                'maxWidget' => 3,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'top_left' => [
                                'maxWidget' => 3,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom_right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom_left' => [
                                'maxWidget' => 3,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'avatar' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["user_avatar"],
                            ],
                        ],

                        'body' => [
                            'top' => [
                                'maxWidget' => 0,
                                'acceptedWidgets' => [
                                    "listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge"
                                ],
                            ],
                            'bottom' => [
                                'maxWidget' => 0,
                                'widgetGroups' => [
                                    ['label' => 'Preset', 'widgets' => ['listing_title']],
                                    ['label' => 'Custom', 'widgets' => ['listing_title']],
                                ],
                                'acceptedWidgets' => [
                                    "listings_location", "_phone", "_phone2", "_website", "_zip", "_fax", "_address", "_email", "listings_tag", 
                                    'text', 'textarea', 'number', 'url', 'date', 'time', 'color_picker', 'select', 'checkbox', 'radio', 'file'
                                ],
                            ],
                        ],

                        'footer' => [
                            'right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],

                            'left' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],
                        ],
                    ],
                ],

                'listings_card_list_view' => [
                    'type' => 'card-builder',
                    'template' => 'list-view',
                    'value' => '',
                    'widgets' => $listing_card_list_view_widget,
                    'layout' => [
                        'thumbnail' => [
                            'top_right' => [
                                'label' => 'Top Right',
                                'maxWidget' => 3,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                        ],

                        'body' => [
                            'top' => [
                                'label' => 'Body Top',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'right' => [
                                'label' => 'Body Right',
                                'maxWidget' => 2,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom' => [
                                'label' => 'Body Bottom',
                                'maxWidget' => 4,
                                'widgetGroups' => [
                                    ['label' => 'Preset', 'widgets' => ['listing_title']],
                                    ['label' => 'Custom', 'widgets' => ['listing_title']],
                                ],
                                'acceptedWidgets' => [
                                    "listings_location", "_phone", "_phone2", "_website", "_zip", "_fax", "_address", "_email", "listings_tag", 
                                    'text', 'textarea', 'number', 'url', 'date', 'time', 'color_picker', 'select', 'checkbox', 'radio', 'file'
                                ],
                            ],
                        ],

                        'footer' => [
                            'right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["user_avatar", "category", "favorite_badge", "view_count"],
                            ],

                            'left' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],
                        ],
                    ],
                ],

                'listings_card_height' => [
                    'type' => 'number',
                    'label' => 'Height',
                    'value' => '250',
                    'unit' => 'px',
                    'units' => [
                        ['label' => 'px', 'value' => 'px'],
                        ['label' => '%', 'value' => '%'],
                    ],
                ],

                'listings_card_width' => [
                    'type' => 'number',
                    'label' => 'Width',
                    'value' => '100',
                    'unit' => '%',
                    'units' => [
                        ['label' => 'px', 'value' => 'px'],
                        ['label' => '%', 'value' => '%'],
                    ],
                ],

            ]);

            $pricing_plan = '<a style="color: red" href="https://directorist.com/product/directorist-pricing-plans" target="_blank">Pricing Plans</a>';
            $wc_pricing_plan = '<a style="color: red" href="https://directorist.com/product/directorist-woocommerce-pricing-plans" target="_blank">WooCommerce Pricing Plans</a>';
            $plan_promo = sprintf(__('Monetize your website by selling listing plans using %s or %s extensions.', 'directorist'), $pricing_plan, $wc_pricing_plan);

            $this->layouts = apply_filters('atbdp_listing_type_settings_layout', [
                'general' => [
                    'label' => 'General',
                    'icon' => '<i class="uil uil-estate"></i>',
                    'submenu' => apply_filters('atbdp_listing_type_general_submenu', [
                        'general' => [
                            'label' => __('General', 'directorist'),
                            'sections' => [
                                'labels' => [
                                    'title'       => __('Labels', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'name', 'icon', 'singular_name', 'plural_name', 'permalink',
                                    ],
                                ],
                            ],
                        ],
                        'preview_image' => [
                            'label' => __('Preview Image', 'directorist'),
                            'sections' => [
                                'labels' => [
                                    'title'       => __('Default Preview Image', 'directorist'),
                                    'description' => __('This image will be used when listing preview image is not present. Leave empty to hide the preview image completely.', 'directorist'),
                                    'fields'      => [
                                        'preview_image',
                                    ],
                                ],
                            ],
                        ],
                        'packages' => [
                            'label' => 'Packages',
                            'sections' => [
                                'labels' => [
                                    'title'       => 'Paid listing packages',
                                    'description' => $plan_promo,
                                ],
                            ],
                        ],
                        'other' => [
                            'label' => __('Other', 'directorist'),
                            'sections' => [
                                'listing_status' => [
                                    'title' => __('Default Status', 'directorist'),
                                    'description' => __('Need help?', 'directorist'),
                                    'fields'      => [
                                        'new_listing_status',
                                        'edit_listing_status',
                                    ],
                                ],

                                'expiration' => [
                                    'title'       => __('Expiration', 'directorist'),
                                    'description' => __('Default time to expire a listing.', 'directorist'),
                                    'fields'      => [
                                        'default_expiration',
                                    ],
                                ],

                                'export_import' => [
                                    'title'       => __('Export & Import Config File', 'directorist'),
                                    'description' => __('Bulk import and export all the form, layout and settings', 'directorist'),
                                    'fields'      => [
                                        'import_export',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],

                'submission_form' => [
                    'label' => 'Submission Form',
                    'icon' => '<span class="uil uil-file-edit-alt"></span>',
                    'container' => 'wide',
                    'sections' => [
                        'form_fields' => [
                            'title' => __('Select or create fields for this listing type', 'directorist'),
                            'description' => 'need help?',
                            'fields' => [
                                'submission_form_fields'
                            ],
                        ],
                        'general_settings' => [
                            'title' => __('General Settings', 'directorist'),
                            'description' => 'need help?',
                            'fields' => [
                                'preview_mode',
                                'submit_button_label',
                            ],
                        ],
                    ],

                ],

                'single_page_layout' => [
                    'label' => 'Single Page Layout',
                    'icon' => '<span class="uil uil-credit-card"></span>',
                    'submenu' => [
                        'listing_header' => [
                            'label' => 'Listing Header',
                            'container' => 'wide',
                            'sections' => [
                                'listing_header' => [
                                    'title' => 'Listing Header',
                                    'description' => 'need help?',
                                    'fields' => [
                                        'single_listing_header'
                                    ],
                                ]
                            ]
                        ],
                        'contents' => [
                            'label' => 'Contents',
                            'container' => 'wide',
                            'sections' => [
                                'contents' => [
                                    'title' => 'Contents',
                                    'description' => 'need help?',
                                    'fields' => [
                                        'single_listings_contents'
                                    ],
                                ]
                            ]
                        ],
                        'similar_listings' => [
                            'label' => 'Similar Listings',
                            'sections' => [
                                'similar_listings' => [
                                    'title' => 'Similar Listings Settings',
                                    'description' => 'need help?',
                                    'fields' => [
                                        'enable_similar_listings',
                                        'similar_listings_logics',
                                        'similar_listings_number_of_listings_to_show',
                                        'similar_listings_auto_hide',
                                    ],
                                ]
                            ]
                        ],
                    ]
                ],
                'listings_card_layout' => [
                    'label' => 'Listings Card Layout',
                    'icon' => '<span class="uil uil-list-ul"></span>',
                    'submenu' => [
                        'grid_view' => [
                            'label' => 'Listings Card Grid Layout',
                            'container' => 'wide',
                            'sections' => [
                                'listings_card' => [
                                    'title' => __('Create and customize the listing card for grid view', 'directorist'),
                                    'description' => 'need help? Read the documentation or open a ticket in our helpdesk.',
                                    'fields' => [
                                        'listings_card_grid_view'
                                    ],
                                ],
                            ],
                        ],
                        'list_view' => [
                            'label' => 'Listings Card List Layout',
                            'container' => 'full-width',
                            'sections' => [
                                'listings_card' => [
                                    'title' => __('Create and customize the listing card for listing view', 'directorist'),
                                    'description' => 'need help?',
                                    'fields' => [
                                        'listings_card_list_view'
                                    ],
                                ],
                            ],
                        ],
                        'options' => [
                            'label' => 'Listings Card Options',
                            'sections' => [
                                'listings_card_options' => [
                                    'title' => __('Customize the options', 'directorist'),
                                    'description' => 'need help?',
                                    'fields' => [
                                        'listings_card_height',
                                        'listings_card_width'
                                    ],
                                ],
                            ],
                        ],
                    ],

                ],
                'search_forms' => [
                    'label' => 'Search Forms',
                    'icon' => '<span class="uil uil-search"></span>',
                    'container' => 'wide',
                    'sections' => [
                        'form_fields' => [
                            'title' => __('Customize the search form for this listing type', 'directorist'),
                            'description' => 'need help?',
                            'fields' => [
                                'search_form_fields'
                            ],
                        ],
                    ],
                ],
            ]);

            $this->config = [
                'fields_group' => [
                    'general_config' => [
                        'icon',
                        'singular_name',
                        'plural_name',
                        'permalink',
                        'preview_image',
                        'archive_general' => [
                            'listings_card_height',
                            'listings_card_width',
                        ]
                    ]
                ]
            ];
        }

        // add_menu_pages
        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Directory Types',
                'Directory Types',
                'manage_options',
                'atbdp-directory-types',
                [$this, 'menu_page_callback__directory_types'],
                5
            );
        }

        // menu_page_callback__directory_types
        public function menu_page_callback__directory_types()
        {
            $post_types_list_table = new Listing_Types_List_Table($this);

            $action = $post_types_list_table->current_action();
            $post_types_list_table->prepare_items();

            $listing_type_id = 0;

            if (!empty($action) && ('edit' === $action) && !empty($_REQUEST['listing_type_id'])) {
                $listing_type_id = $_REQUEST['listing_type_id'];
                $this->update_fields_with_old_data();
            }

            $data = [
                'post_types_list_table' => $post_types_list_table,
                'add_new_link'          => admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=add_new'),
            ];

            $cptm_data = [
                'fields'  => $this->fields,
                'layouts' => $this->layouts,
                'config'  => $this->config,
                'id'      => $listing_type_id,
            ];

            $this->enqueue_scripts();

            if (!empty($action) && ('edit' === $action || 'add_new' === $action)) {
                wp_localize_script('atbdp_admin_app', 'cptm_data', $cptm_data);
                
                atbdp_load_admin_template('post-types-manager/edit-listing-type', $data);

                return;
            }

            atbdp_load_admin_template('post-types-manager/all-listing-types', $data);
        }

        // update_fields_with_old_data
        public function update_fields_with_old_data()
        {
            $listing_type_id = absint($_REQUEST['listing_type_id']);
            $term = get_term($listing_type_id, 'atbdp_listing_types');

            if (!$term) {
                return;
            }

            $this->fields['name']['value'] = $term->name;

            $all_term_meta = get_term_meta($term->term_id);
            if ('array' !== getType($all_term_meta)) {
                return;
            }

            foreach ($all_term_meta as $meta_key => $meta_value) {
                if (isset($this->fields[$meta_key])) {
                    $value = maybe_unserialize(maybe_unserialize($meta_value[0]));
                    $this->fields[$meta_key]['value'] = $value;
                }
            }

            foreach ($this->config['fields_group'] as $group_key => $group_fields) {
                if (array_key_exists($group_key, $all_term_meta)) {
                    $group_value = maybe_unserialize(maybe_unserialize($all_term_meta[$group_key][0]));

                    foreach ($group_fields as $field_index => $field_key) {
                        if ('string' === gettype($field_key) && array_key_exists($field_key, $this->fields)) {
                            $this->fields[$field_key]['value'] = $group_value[$field_key];
                        }

                        if ('array' === gettype($field_key)) {
                            foreach ($field_key as $sub_field_key) {
                                if (array_key_exists($sub_field_key, $this->fields)) {
                                    $this->fields[$sub_field_key]['value'] = $group_value[$field_index][$sub_field_key];
                                }
                            }
                        }
                    }
                }
            }

            // $test = get_term_meta( $listing_type_id, 'submission_form_fields' );
            // $test = get_term_meta( $listing_type_id, 'listings_card_grid_view' );
            // var_dump( $this->fields[ 'listings_card_grid_view' ]['value']['body']['bottom'] );
            // var_dump( json_decode( $test ) );
        }

        // handle_delete_listing_type_request
        public function handle_delete_listing_type_request()
        {

            if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'delete_listing_type')) {
                wp_die('Are you cheating? | _wpnonce');
            }

            if (!current_user_can('manage_options')) {
                wp_die('Are you cheating? | manage_options');
            }

            $term_id = isset($_REQUEST['listing_type_id']) ? absint($_REQUEST['listing_type_id']) : 0;

            $this->delete_listing_type($term_id);


            wp_redirect(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types'));
            exit;
        }

        // delete_listing_type
        public function delete_listing_type($term_id = 0)
        {
            if (wp_delete_term($term_id, 'atbdp_listing_types')) {
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
        public function register_terms()
        {
            register_taxonomy('atbdp_listing_types', [ATBDP_POST_TYPE], [
                'hierarchical' => false,
                'labels' => [
                    'name' => _x('Listing Type', 'taxonomy general name', 'directorist'),
                    'singular_name' => _x('Listing Type', 'taxonomy singular name', 'directorist'),
                    'search_items' => __('Search Listing Type', 'directorist'),
                    'menu_name' => __('Listing Type', 'directorist'),
                ],
                'show_ui' => false,
            ]);

            $this->prepare_settings();
            // $this->get_old_custom_fields();
        }

        public function directory_json_download_link() {
            $directory_id = ( ! empty( $_REQUEST['id'] ) && is_numeric( $_REQUEST['id'] ) ) ? ( int ) $_REQUEST['id'] : 0;
            $is_valid_term = get_term_by( 'id', $directory_id, 'atbdp_listing_types' );

            
            // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            // var_dump( $_SERVER['REQUEST_URI'] );
            // var_dump( $_SERVER['REQUEST_URI'] );

            if ( $is_valid_term && preg_match( "/^(/download\/directory\?id=).+$/", $_SERVER['REQUEST_URI'] ) ) {

                header("Content-type: application/x-msdownload",true,200);
                header("Content-Disposition: attachment; filename=directory.json");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo json_encode( $this->fields );
                exit();
            }
        }

        public function directory_csv_download_link() {
            if ( $_SERVER['REQUEST_URI'] == '/download/directory' ) {
        
                $csv = []; $row = [];
                foreach ( $this->fields as $field => $value ) {
                    $data_type = gettype( $value );
                    $row[ $field ] = ( 'array' === $data_type ) ? json_encode( $value ) : $value; 
                }

                $csv[] = $row;


                $this->outputCSV( 'Directory.csv', $csv );
                exit();
            }
        }

        // outputCSV
        public function outputCSV( $fileName, $assocDataArray ) {
            ob_clean();
            header("Content-type: application/x-msdownload",true,200);
            header("Content-Disposition: attachment; filename=$fileName");
            header("Pragma: no-cache");
            header("Expires: 0");

            if( isset( $assocDataArray['0'] ) ){
                $fp = fopen('php://output', 'w');
                fputcsv($fp, array_keys($assocDataArray['0']));
                foreach($assocDataArray AS $values) {
                    $row = [];

                    foreach ( $values as $key => $value ) {
                        $row[] = $value;
                    }
                    fputcsv($fp, $row);

                }
                fclose($fp);
            }
            ob_flush();
        }

        // enqueue_scripts
        public function enqueue_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_style('atbdp-unicons');
            wp_enqueue_style('atbdp-font-awesome');
            wp_enqueue_style('atbdp_admin_css');

            wp_localize_script('atbdp_admin_app', 'ajax_data', ['ajax_url' => admin_url('admin-ajax.php')]);
            wp_enqueue_script('atbdp_admin_app');
        }

        // register_scripts
        public function register_scripts()
        {
            wp_register_style('atbdp-unicons', '//unicons.iconscout.com/release/v3.0.3/css/line.css', false);
            wp_register_style('atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false);

            wp_register_style( 'atbdp_admin_css', ATBDP_PUBLIC_ASSETS . 'css/admin_app.css', [], '1.0' );
            wp_register_script( 'atbdp_admin_app', ATBDP_PUBLIC_ASSETS . 'js/admin_app.js', ['jquery'], false, true );
        }
    }
}
