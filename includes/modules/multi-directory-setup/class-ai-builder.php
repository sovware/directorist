<?php
/**
 * Directorist
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Error;
use Directorist\Multi_Directory\Multi_Directory_Manager as DirectoryManager;

if ( ! is_admin() ) {
	return;
}

class AI_Builder {

	const API_URL = 'https://app.directorist.com/wp-json/waxai/v1/';

	/**
	 * Preset fields map
	 */
	protected static $preset_fields = [
		'title'       => 'title',
		'description' => 'description',
		'tagline'     => 'tagline',
		'pricing'     => 'pricing',
		'excerpt'     => 'excerpt',
		'location'    => 'location',
		'tag'         => 'tag',
		'category'    => 'category',
		'map'         => 'map',
		'address'     => 'address',
		'postcode'    => 'zip',
		'phone'       => 'phone',
		'phone2'      => 'phone2',
		'fax'         => 'fax',
		'email'       => 'email',
		'website'     => 'website',
		'socialinfo'  => 'social_info',
		'images'      => 'image_upload',
		'video'       => 'video',
	];

	public static function init() {
		add_action( 'wp_ajax_directorist_ai_directory_form', [ __CLASS__, 'form_handler' ] );
        add_action( 'wp_ajax_directorist_ai_directory_creation', [ __CLASS__, 'create_directory' ] );
	}

	public static function form_handler() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'You are not allowed to access this resource', 'directorist' ),
				],
			], 200);
		}

		$installed['success'] = true;

		ob_start();

		atbdp_load_admin_template('post-types-manager/ai/step-one', []);

		$form = ob_get_clean();

		$installed['html'] = $form;
		wp_send_json( $installed );
	}

	protected static function prepare_keywords( $keywords ) {
		$keywords = array_map( static function( $keyword ) {
			return '"' . trim( $keyword ) . '"';
		}, explode( ',', $keywords ) );

		return implode( ',', $keywords );
	}

	// handle step one
	public static function create_directory() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'You are not allowed to access this resource', 'directorist' ),
				],
			], 200);
		}

		$prompt     = ! empty( $_POST['prompt'] ) ? sanitize_textarea_field( $_POST['prompt'] ) : '';
		$keywords   = ! empty( $_POST['keywords'] ) ? static::prepare_keywords( $_POST['keywords'] ) : '';
		$pinned     = ! empty( $_POST['pinned'] ) ? $_POST['pinned'] : '';
		$step       = ! empty( $_POST['step'] ) ? absint( $_POST['step'] ) : '';
		$name       = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$fields     = ! empty( $_POST['fields'] ) ? $_POST['fields'] : [];

		if ( 1 === $step ) {
			$html = static::ai_create_keywords( $prompt );

			if ( is_wp_error( $html ) ) {
				wp_send_json([
					'status' => [
						'success' => false,
						'message' => $html->get_error_message(),
					],
				], 200);
			}
		}

		if ( 2 === $step ) {
			$response = static::ai_create_fields( $keywords, $pinned );

			wp_send_json( [
				'success' => true,
				'html'    => $response['html'],
				'data'    => $response['data'],
				'fields'  => $response['fields'],
			] );
		}

		if ( 3 === $step ) {
			$data = static::build_directory( $name, $fields );

			$id = ! empty( $data['id'] ) ? $data['id'] : '';

			wp_send_json( [
				'data'    => $data,
				'success' => true,
				'url'     => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . $id . '&action=edit' ),
			] );
		}

		$installed['success'] = true;
		$installed['html'] = $html;

		wp_send_json( $installed );
	}

	public static function merge_new_fields($existing_config, $new_fields) {
		$new_fields_array = json_decode(stripslashes($new_fields), true);

		if (is_null($new_fields_array)) {
			// throw new Exception('Failed to decode new fields JSON: ' . json_last_error_msg());
		}

		// Reformat new fields to match the old format and ensure unique field keys for same type fields
		$type_counts = [];
		$formatted_fields = [];
		foreach ($new_fields_array as $key => $field) {
			$type = strtolower($field['type']);
			if (!isset($type_counts[$type])) {
				$type_counts[$type] = 0;
			} else {
				$type_counts[$type]++;
			}
			$suffix = $type_counts[$type] > 0 ? '-' . $type_counts[$type] : '';
			$field_key = "custom-{$type}{$suffix}";

			// Handle specific structures for checkbox, radio, and select fields
			if (in_array($type, ['checkbox', 'radio', 'select']) && isset($field['options']) && is_array($field['options'])) {
				$field['options'] = array_map(function ($option) {
					if (is_array($option)) {
						return [
							'option_value' => $option['option_value'] ?? $option['value'],
							'option_label' => $option['option_label'] ?? $option['label']
						];
					}
					return [
						'option_value' => $option,
						'option_label' => $option
					];
				}, $field['options']);
			}

			$formatted_fields[$field_key] = array_merge($field, [
				'widget_group' => 'custom',
				'widget_name' => $type,
				'field_key' => $field_key,
				'widget_key' => $key,
			]);
		}

		// Group the fields based on 'group_name'
		$groups = [];
		foreach ($formatted_fields as $field_key => $field) {
			$group_name = $field['group_name'];
			if (!isset($groups[$group_name])) {
				$groups[$group_name] = [
					"type" => "general_group",
					"label" => $group_name,
					"fields" => [],
					"defaultGroupLabel" => "Section",
					"disableTrashIfGroupHasWidgets" => [
						[
							"widget_name" => "title",
							"widget_group" => "preset"
						]
					],
					"icon" => "las la-pen-nib",
				];
			}
			$groups[$group_name]['fields'][] = $field_key;
		}

		// Keep old title and description fields
		$title_description_fields = array_intersect_key(
			$existing_config['submission_form_fields']['fields'] ?? [],
			array_flip(['title', 'description'])
		);

		// Replace the old fields with new fields, keeping title and description
		$existing_config['submission_form_fields']['fields'] = array_merge(
			$title_description_fields,
			$formatted_fields
		);

		$existing_config['submission_form_fields']['groups'] = array_values($groups);

		// Update the single listing layout to use the new fields
		$single_listing_fields = array_merge(
			$existing_config['single_listings_contents']['fields'] ?? [],
			array_map(function ($field) {
				return [
					'icon' => $field['icon'] ?? '',
					'widget_group' => $field['widget_group'],
					'widget_name' => $field['widget_name'],
					'original_widget_key' => $field['field_key'],
					'widget_key' => $field['field_key'],
				];
			}, $formatted_fields)
		);

		$existing_config['single_listings_contents']['fields'] = $single_listing_fields;
		$existing_config['single_listings_contents']['groups'] = array_values($groups);

		return $existing_config;
	}

	public static function merge_new_fields_v2( $structure, $new_fields ) {

		$new_fields_array = json_decode(stripslashes($new_fields), true);

		if (is_null($new_fields_array)) {
			return [];
		}
		array_walk($new_fields_array, function (&$field, $key) {
			// Generate the field_key dynamically by type and prefix "custom-"
			$type = strtolower($field['type']);
			$field_key = "custom-{$type}";

			$field = array_merge($field, [
				'widget_group' => 'custom',
				'widget_name' => $type,
				'field_key' => $field_key,
				'widget_key' => $key,
			]);
		});

		// Keep old title and description fields
		$title_description_fields = array_intersect_key(
			$structure['submission_form_fields']['fields'] ?? [],
			array_flip(['title', 'description'])
		);

		// Replace the old fields with new fields, keeping title and description
		$structure['submission_form_fields']['fields'] = array_merge(
			$title_description_fields,
			$new_fields_array
		);

		// Replace old groups with a new group containing the new fields and keeping title and description
		$structure['submission_form_fields']['groups'] = [
			[
				"type" => "general_group",
				"label" => "General Information",
				"fields" => array_merge(['title', 'description'], array_keys($new_fields_array)),
				"defaultGroupLabel" => "Section",
				"disableTrashIfGroupHasWidgets" => [
					[
						"widget_name" => "title",
						"widget_group" => "preset"
					]
				],
				"icon" => "las la-pen-nib",
			]
		];

		return $structure;
	}

	public static function build_directory( $name, $fields ) {
		$directory_config_file = DIRECTORIST_ASSETS_DIR . 'sample-data/directory/directory.json';
		$directory_config      = json_decode( file_get_contents( $directory_config_file ), 1 );

		$fields        = json_decode( wp_unslash( $fields, 1 ), 1 );
		$form_fields   = static::prepare_form_fields( $fields );
		$single_fields = static::prepare_single_fields( $form_fields );

		$directory_config['submission_form_fields'] = $form_fields;
		$directory_config['single_listing_header']  = $single_fields['header'];
		unset( $single_fields['header'] );
		$directory_config['single_listings_contents'] = $single_fields;

		DirectoryManager::load_builder_data();

		$directory = DirectoryManager::add_directory( [
			'directory_name' => $name,
			'fields_value'   => $directory_config,
			'is_json'        => false
		] );

		if ( $directory['status']['success'] ) {
			$term_id = $directory['term_id'];
		} else {
			$term_id = $directory['status']['term_id'];
		}

		// update_term_meta( $term_id, 'single_listing_header', $single_fields['header'] );
		// unset( $single_fields['header'] );
		// update_term_meta( $term_id, 'single_listings_contents', $single_fields );

		return [
			'structure'      => $directory_config,
			'new_fields'     => $form_fields['fields'],
			'updated_config' => $directory_config,
			'id'             => $term_id,
		];
	}

	public static function ai_create_fields( $keywords, $pinned = null ) {
		$response = static::request_fields( [
			'keywords' => $keywords,
			'pinned' => $pinned,
		] );

		if ( is_wp_error( $response ) ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => $response->get_error_message(),
				],
			], 200);
		}

		if ( empty( $response['response']['fields'] ) || ! is_array( $response['response']['fields'] ) ) {
			return [
				'fields' => [],
				'html'   => '',
				'data'   => $response,
			];
		}

		ob_start();

		if ( ! empty( $response['response']['fields'] ) ) {
			static::render_fields( $response['response']['fields'] );
		}

		$html = ob_get_clean();

		return [
			'fields' => $response['response']['fields'],
			'html'   => $html,
			'data'   => $response,
		];
	}

	public static function ai_create_keywords( $prompt ) {
		$response = static::request_keywords( ['prompt' => $prompt] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		ob_start();

		if ( ! empty( $response['response']['keywords'] ) ) {
			foreach ( $response['response']['keywords'] as $keyword ) { ?>
				<li class="free-enabled"><?php echo ucwords( $keyword ); ?></li>
			<?php }
		}

		return ob_get_clean();
	}

	protected static function prepare_form_fields( $fields ) {
		$form_fields_file = DIRECTORIST_ASSETS_DIR . 'sample-data/listing-form-fields.json';
		$form_fields      = json_decode( file_get_contents( $form_fields_file ), 1 );

		$prepared_fields      = [];
		$prepared_groups      = [];
		$counter              = [];
		$should_include_group = false;

		foreach ( $fields as $field ) {
			if ( empty( $field['type'] ) ) {
				continue;
			}

			// Handle preset fields
			if ( isset( static::$preset_fields[ $field['type'] ] ) ) {
				$field_name = static::$preset_fields[ $field['type'] ];

				if ( isset( $form_fields[ $field_name ] ) ) {
					$_field                         = $form_fields[ $field_name ];
					$_field['label']                = $field['label'];
					$prepared_fields[ $field_name ] = $_field;

					$should_include_group = true;
				}

			// Handle custom fields
			} elseif ( isset( $form_fields[ $field['type'] ] ) ) {
				$_field          = $form_fields[ $field['type'] ];
				$_field['label'] = $field['label'];

				if ( in_array( $field['type'], [ 'select', 'radio', 'checkbox' ], true ) &&
					isset( $field['options'] ) &&
					is_array( $field['options'] ) ) {
					$_field['options'] = array_map( static function( $option ) {
						return [
							'option_value' => $option,
							'option_label' => $option
						];
					}, $field['options'] );
				}

				// "text_2": {
				// 	"type": "text",
				// 	"field_key": "custom-text-2",
				// 	"widget_key": "text_2"
				// },

				if ( isset( $counter[ $field['type'] ] ) ) {
					$counter[ $field['type'] ] += 1;
					$field_name                 = $field['type'] . '_' . $counter[ $field['type'] ];
					$_field['field_key']        = 'custom-' . $field['type'] . '-' . $counter[ $field['type'] ];
				} else {
					$counter[ $field['type'] ] = 1;
					$field_name                = $field['type'];
					$_field['field_key']       = 'custom-' . $field['type'];
				}

				$_field['widget_key']           = $field_name;
				$prepared_fields[ $field_name ] = $_field;
				$should_include_group           = true;
			}

			// Setup groups
			if ( $should_include_group ) {
				if ( isset( $prepared_groups[ $field['group'] ] ) ) {
					$prepared_groups[ $field['group'] ]['fields'][] = $field_name;
				} else {
					$prepared_groups[ $field['group'] ] = [
						'label'  => $field['group'],
						'fields' => [ $field_name ],
					];
				}
			}

			$should_include_group = false;
		}

		return [
			'groups' => array_values( $prepared_groups ),
			'fields' => $prepared_fields,
		];
	}

	protected static function prepare_single_fields( $form_fields ) {
		$fields           = [];
		$ignorable_fields = [
			'title'        => false,
			'tagline'      => false,
			'image_upload' => false,
			'location'     => false,
			'category'     => false,
			'pricing'      => false,
		];

		// Prepare fields
		foreach ( $form_fields['fields'] as $field_key => $field ) {
			if ( isset( $ignorable_fields[ $field_key ] ) ) {
				$ignorable_fields[ $field_key ] = true;

				continue;
			}

			$fields[ $field_key ] = [
				'icon'                => 'las la-tag',
				'widget_group'        => 'preset_widgets',
				'widget_name'         => $field['widget_name'],
				'original_widget_key' => $field_key,
				'widget_key'          => $field_key
			];

			if ( $field_key === 'address' ) {
				$fields[ $field_key ]['address_link_with_map'] = false;
			}

			if ( $field_key === 'website' ) {
				$fields[ $field_key ]['use_nofollow'] = true;
			}
		}

		// Prepare groups
		$groups               = [];
		$ignorable_field_keys = array_keys( $ignorable_fields );
		$section_id           = 0;

		foreach ( $form_fields['groups'] as $group ) {
			$group_fields = array_diff( $group['fields'], $ignorable_field_keys );

			if ( ! $group_fields ) {
				continue;
			}

			$groups[] = [
				'type'       => 'general_group',
				'label'      => $group['label'],
				'fields'     => array_values( $group_fields ),
				'section_id' => ++$section_id,
			];
		}

		$groups[] = [
			'type'          => 'section',
			'label'         => 'Author Info',
			'section_id'    => ++$section_id,
			'icon'          => 'las la-user',
			'display_email' => true,
			'widget_group'  => 'other_widgets',
			'widget_name'   => 'author_info',
		];

		$groups[] = [
			'type'   => 'section',
			'label'  => 'Contact Listings Owner Form',
			'fields' => [
				'contact_name',
				'contact_email',
				'contact_message',
			],
			'section_id'       => ++$section_id,
			'icon'             => 'las la-phone',
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
			'widget_group' => 'other_widgets',
			'widget_name'  => 'contact_listings_owner',
		];

		// Prepare header
		$header = static::prepare_single_header_fields( $ignorable_fields );

		return [
			'header' => $header,
			'groups' => $groups,
			'fields' => $fields
		];
	}

	protected static function prepare_single_header_fields( $header_fields ) {
		$fields = [
			'quick-widgets-placeholder' => [
				'type'           => 'placeholder_group',
				'placeholderKey' => 'quick-widgets-placeholder',
				'placeholders'   => [
					[
						'type'           => 'placeholder_group',
						'placeholderKey' => 'quick-info-placeholder',
						'selectedWidgets' => [
							[
								'type'        => 'button',
								'label'       => 'Back',
								'widget_name' => 'back',
								'widget_key'  => 'back',
							],
						],
					],
					[
						'type'           => 'placeholder_group',
						'placeholderKey' => 'quick-action-placeholder',
						'selectedWidgets' => [
							[
								'type'        => 'button',
								'label'       => 'Bookmark',
								'widget_name' => 'bookmark',
								'widget_key'  => 'bookmark',
							],
							[
								'type'        => 'badge',
								'label'       => 'Share',
								'widget_name' => 'share',
								'widget_key'  => 'share',
								'icon'        => 'las la-share',
							],
							[
								'type'        => 'badge',
								'label'       => 'Report',
								'widget_name' => 'report',
								'widget_key'  => 'report',
								'icon'        => 'las la-flag',
							],
						],
					],
				],
			],
			'slider-placeholder' => [
				'type'           => 'placeholder_item',
				'placeholderKey' => 'slider-placeholder',
				'selectedWidgets' => [
					[
						'type'           => 'thumbnail',
						'label'          => 'Listing Image/Slider',
						'widget_name'    => 'slider',
						'widget_key'     => 'slider',
						'footer_thumbnail' => true,
					],
				],
			],
			'listing-title-placeholder' => [
				'type'           => 'placeholder_item',
				'placeholderKey' => 'listing-title-placeholder',
				'selectedWidgets' => [
					[
						'type'          => 'title',
						'label'         => 'Listing Title',
						'widget_name'   => 'title',
						'widget_key'    => 'title',
						'enable_tagline' => true,
					],
				],
			],
			'more-widgets-placeholder' => [
				'type'           => 'placeholder_item',
				'placeholderKey' => 'more-widgets-placeholder',
				'selectedWidgets' => [
					[
						'type'        => 'badge',
						'label'       => 'Pricing',
						'widget_name' => 'price',
						'widget_key'  => 'price',
					],
					[
						'type'        => 'ratings-count',
						'label'       => 'Rating',
						'widget_name' => 'ratings_count',
						'widget_key'  => 'ratings_count',
					],
					[
						'type'          => 'badge',
						'label'         => 'Badges',
						'widget_name'   => 'badges',
						'widget_key'    => 'badges',
						'new_badge'     => true,
						'popular_badge' => true,
						'featured_badge' => true,
					],
					[
						'type'        => 'badge',
						'label'       => 'Category',
						'widget_name' => 'category',
						'widget_key'  => 'category',
					],
					[
						'type'        => 'badge',
						'label'       => 'Location',
						'widget_name' => 'location',
						'widget_key'  => 'location',
					],
				],
			],
		];

		if ( ! $header_fields['image_upload'] ) {
			$fields['slider-placeholder']['selectedWidgets'] = [];
		}

		if ( ! $header_fields['title'] ) {
			$fields['listing-title-placeholder']['selectedWidgets'] = [];
		}

		if ( $header_fields['title'] && $header_fields['tagline'] ) {
			$fields['listing-title-placeholder']['selectedWidgets'][0]['enable_tagline'] = true;
		}

		foreach ( $fields['more-widgets-placeholder']['selectedWidgets'] as $index => $widget ) {
			if (
				( $widget['widget_key'] === 'price' && ! $header_fields['pricing'] ) ||
				( $widget['widget_key'] === 'location' && ! $header_fields['location'] ) ||
				( $widget['widget_key'] === 'category' && ! $header_fields['category'] )
			) {
				unset( $fields['more-widgets-placeholder']['selectedWidgets'][ $index ] );
			}
		}

		return array_values( $fields );
	}

	protected static function render_fields( $fields ) {
		$icons_map = array(
            'title'         => 'las la-text-height',
            'description'   => 'uil uil-align-left',
            'tagline'       => 'uil uil-text-fields',
            'pricing'       => 'uil uil-bill',
            'excerpt'       => 'uil uil-paragraph',
            'location'      => 'uil uil-map-marker',
            'tag'           => 'las la-tag',
            'category'      => 'uil uil-folder-open',
            'map'           => 'uil uil-map',
            'address'       => 'uil uil-map-pin',
            'zip'           => 'uil uil-map-pin',
            'phone'         => 'uil uil-phone',
            'phone2'        => 'uil uil-phone',
            'fax'           => 'uil uil-print',
            'email'         => 'uil uil-envelope',
            'website'       => 'uil uil-globe',
            'social_info'   => 'uil uil-users-alt',
            'image_upload'  => 'uil uil-image',
            'video'         => 'uil uil-video',
            'terms_privacy' => 'uil uil-text-fields',
            'text'          => 'uil uil-text',
            'textarea'      => 'uil uil-align-left',
            'number'        => 'uil uil-0-plus',
            'url'           => 'uil uil-link-add',
            'date'          => 'uil uil-calender',
            'time'          => 'uil uil-clock',
            'color_picker'  => 'uil uil-palette',
            'select'        => 'uil uil-list-ui-alt',
            'checkbox'      => 'uil uil-check-square',
            'radio'         => 'uil uil-dot-circle',
            'file_upload'   => 'uil uil-file-upload'
        );

		foreach ( $fields as $field ) {
			$label   = $field['label'] ?? '';
			$options = empty( $field['options'] ) ? [] : $field['options'];
			$icon    = $icons_map[ ( static::$preset_fields[ $field['type'] ] ?? $field['type'] ) ] ?? 'uil uil-paragraph';
			?>
			<div class="directorist-ai-generate-box__item">
				<div class="directorist-ai-generate-dropdown" aria-expanded="false">
					<div class="directorist-ai-generate-dropdown__header <?php echo ! empty( $options ) ? 'has-options' : ''; ?>">
						<div class="directorist-ai-generate-dropdown__header-title">
							<div class="directorist-ai-generate-dropdown__pin-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M11.0616 1.29452C11.4288 1.05364 11.8763 0.967454 12.3068 1.05472C12.6318 1.12059 12.8801 1.29569 13.0651 1.44993C13.2419 1.59735 13.4399 1.79537 13.653 2.00849L17.9857 6.34116C18.1988 6.55424 18.3968 6.75223 18.5442 6.92908C18.6985 7.11412 18.8736 7.36242 18.9395 7.6874C19.0267 8.11785 18.9405 8.56535 18.6997 8.93261C18.5178 9.20988 18.263 9.3754 18.0511 9.48992C17.8485 9.59937 17.5911 9.70966 17.3141 9.82836L15.2051 10.7322C15.1578 10.7525 15.1347 10.7624 15.118 10.77C15.1176 10.7702 15.1173 10.7704 15.1169 10.7705C15.1166 10.7708 15.1163 10.7711 15.116 10.7714C15.1028 10.7841 15.0849 10.8018 15.0485 10.8382L13.7478 12.1389C13.6909 12.1959 13.6629 12.224 13.6432 12.2451C13.6427 12.2456 13.6423 12.2461 13.6418 12.2466C13.6417 12.2472 13.6415 12.2479 13.6414 12.2486C13.6347 12.2767 13.6268 12.3155 13.611 12.3944L12.9932 15.4835C12.92 15.8499 12.8541 16.1794 12.7773 16.438C12.7004 16.6969 12.5739 17.0312 12.289 17.2841C11.9244 17.6075 11.4366 17.7552 10.9539 17.6883C10.5765 17.636 10.2858 17.4279 10.0783 17.2552C9.87091 17.0826 9.63334 16.845 9.36915 16.5808L6.98049 14.1922L2.85569 18.317C2.53026 18.6424 2.00262 18.6424 1.67718 18.317C1.35175 17.9915 1.35175 17.4639 1.67718 17.1384L5.80198 13.0136L3.41338 10.625C3.14915 10.3608 2.91154 10.1233 2.73896 9.91588C2.56626 9.70833 2.3582 9.41765 2.30588 9.04027C2.23896 8.55756 2.38666 8.06974 2.7101 7.70522C2.96297 7.42025 3.29732 7.29379 3.55615 7.2169C3.81479 7.14007 4.14427 7.0742 4.51068 7.00094L7.59973 6.38313C7.67866 6.36735 7.71751 6.35946 7.7456 6.35282C7.74629 6.35266 7.74696 6.3525 7.7476 6.35234C7.74808 6.3519 7.74858 6.35143 7.7491 6.35095C7.7702 6.33126 7.79832 6.3033 7.85523 6.24639L9.15597 4.94565C9.19239 4.90923 9.21013 4.89143 9.22278 4.87819C9.22308 4.87787 9.22336 4.87757 9.22364 4.87729C9.22381 4.87692 9.22398 4.87654 9.22416 4.87615C9.23175 4.85949 9.24169 4.83641 9.26199 4.78906L10.1658 2.68009C10.2845 2.40306 10.3948 2.14567 10.5043 1.94311C10.6188 1.73117 10.7843 1.47638 11.0616 1.29452ZM10.5222 15.3768C10.82 15.6746 11.003 15.8565 11.1444 15.9741C11.1535 15.9817 11.162 15.9886 11.1699 15.995C11.173 15.9853 11.1762 15.9748 11.1796 15.9634C11.232 15.7871 11.2834 15.5343 11.366 15.1213L11.9767 12.0676C11.9787 12.0577 11.9808 12.0475 11.9828 12.037C12.0055 11.9222 12.0342 11.7776 12.0892 11.6374C12.1369 11.5156 12.1989 11.3999 12.2737 11.2926C12.3599 11.169 12.4643 11.065 12.5472 10.9825C12.5547 10.9749 12.5621 10.9676 12.5693 10.9604L13.87 9.6597C13.8746 9.65514 13.8793 9.65044 13.884 9.64564C13.9371 9.59249 14.0038 9.52556 14.08 9.46504C14.1462 9.41243 14.2164 9.36493 14.2899 9.32297C14.3743 9.2747 14.4613 9.23757 14.5303 9.20809C14.5366 9.20543 14.5427 9.20282 14.5486 9.20028L16.6272 8.30944C16.9451 8.17321 17.1311 8.09262 17.2588 8.02362C17.2656 8.01993 17.272 8.01642 17.2779 8.0131C17.2736 8.00783 17.269 8.00221 17.264 7.99624C17.1711 7.88475 17.0283 7.74085 16.7838 7.49631L12.4979 3.21037C12.2533 2.96583 12.1094 2.82309 11.9979 2.73014C11.992 2.72517 11.9864 2.72057 11.9811 2.71631C11.9778 2.72222 11.9743 2.72858 11.9706 2.73541C11.9016 2.86312 11.821 3.04909 11.6847 3.36696L10.7939 5.44559C10.7914 5.45153 10.7887 5.45762 10.7861 5.46387C10.7566 5.53289 10.7195 5.61984 10.6712 5.70432C10.6292 5.77777 10.5817 5.84793 10.5291 5.91417C10.4686 5.99036 10.4017 6.05713 10.3485 6.11013C10.3437 6.11492 10.339 6.1196 10.3345 6.12417L9.03374 7.4249C9.02658 7.43206 9.01924 7.43943 9.01172 7.44698C8.92915 7.52989 8.82513 7.63432 8.70159 7.72048C8.59429 7.79531 8.47855 7.85725 8.35677 7.90502C8.21655 7.96002 8.07196 7.98864 7.95717 8.01136C7.94673 8.01343 7.93652 8.01544 7.92659 8.01743L4.87287 8.62817C4.45985 8.71078 4.20704 8.7622 4.03076 8.81456C4.0194 8.81794 4.00891 8.82117 3.99923 8.82425C4.00557 8.83219 4.01251 8.84071 4.02009 8.84981C4.13771 8.99116 4.31954 9.17418 4.61738 9.47202L10.5222 15.3768Z" fill="currentColor"></path>
								</svg>
							</div>
							<div class="directorist-ai-generate-dropdown__title">
								<div class="directorist-ai-generate-dropdown__title-icon">
									<i class="<?php echo esc_attr( $icon ); ?>"></i>
								</div>
								<div class="directorist-ai-generate-dropdown__title-main">
									<h6><?php echo esc_html( $label ); ?></h6>
								</div>
							</div>
						</div>
						<?php if ( ! empty( $options ) ) : ?>
							<div class="directorist-ai-generate-dropdown__header-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M4.41058 6.91058C4.73602 6.58514 5.26366 6.58514 5.58909 6.91058L9.99984 11.3213L14.4106 6.91058C14.736 6.58514 15.2637 6.58514 15.5891 6.91058C15.9145 7.23602 15.9145 7.76366 15.5891 8.08909L10.5891 13.0891C10.2637 13.4145 9.73602 13.4145 9.41058 13.0891L4.41058 8.08909C4.08514 7.76366 4.08514 7.23602 4.41058 6.91058Z" fill="#4D5761"></path>
								</svg>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $options ) ) : ?>
						<div class="directorist-ai-generate-dropdown__content" aria-expanded="false">
							<div class="directorist-ai-keyword-field">
								<div class="directorist-ai-keyword-field__items">
									<div class="directorist-ai-keyword-field__item">
										<div class="directorist-ai-keyword-field__list">
											<?php foreach ( $options as $option ) : ?>
												<div class="directorist-ai-keyword-field__list-item --px-12 --h-32"><?php echo esc_html( $option ); ?></div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}

	protected static function request_keywords( $params ) {
		return static::request( 'keywords', $params );
	}

	protected static function request_fields( $params ) {
		return static::request( 'fields', $params );
	}

	protected static function request( $endpoint = 'keywords', $params = array() ) {
		$headers = array(
			'user-agent'    => 'Directorist\\' . ATBDP_VERSION,
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json'
		);

		$config = array(
			'method'      => 'POST',
			'timeout'     => 30,
			'redirection' => 5,
			'httpversion' => '1.0',
			'headers'     => $headers,
			'body'        => json_encode( $params ),
		);

		$response = wp_remote_post( static::API_URL . $endpoint, $config );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response = wp_remote_retrieve_body( $response );
		if ( empty( $response ) ) {
			return new WP_Error( 'empty_data', 'Empty response', 400 );
		}

		// Decode the JSON string into a PHP array.
        $response = json_decode( $response, true );

        if ( JSON_ERROR_NONE !== json_last_error() ) {
			return new WP_Error( 'invalid_data', 'Malformed JSON response', 400 );
        }

		return $response;
	}
}

AI_Builder::init();
