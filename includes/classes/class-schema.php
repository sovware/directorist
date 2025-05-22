<?php
/**
 * Schema data class.
 *
 * @since 8.4.0
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Schema {

	protected static $schema_type_fields = [];

	public static function init() {
		add_filter( 'atbdp_listing_type_settings_field_list', [ static::class, 'register_fields' ] );
		add_filter( 'atbdp_advanced_submenu', [ static::class,'register_section' ] );

		if ( get_directorist_option( 'enable_schema_markup' ) ) {
			add_action( 'wp_footer', [ static::class, 'print_schema' ] );
		}
	}

	public static function register_section( $sections ) {
		if ( isset( $sections['miscellaneous'] ) ) {
			$temp_section = $sections['miscellaneous'];
			unset( $sections['miscellaneous'] );
		}

		$field_keys = [
			'enable_schema_markup',
			'apply_schema_markup',
			'directory_schema_type_global',
		];

		$_field_keys = array_keys( static::get_schema_type_fields_for_directories() );
		if ( $_field_keys ) {
			$field_keys = array_merge( $field_keys, $_field_keys );
		}

		$sections['schema_markup'] = [
			'label'    => esc_html__('Schema Markup', 'directorist'),
			'icon'     => '<i class="fas fa-database"></i>',
			'sections' => apply_filters('directorist_schema_controls', [
				'schema_type' => [
					'fields' => $field_keys,
				],
			] ),
		];

		if ( isset( $temp_section ) ) {
			$sections['miscellaneous'] = $temp_section;
		}

		return $sections;
	}

	public static function register_fields( $fields = [] ) {
		$fields['enable_schema_markup'] = [
			'type'  => 'toggle',
			'label' => __( 'Enable Schema Markup', 'directorist' ),
			'value' => true,
		];

		$fields['apply_schema_markup'] = [
			'schema' => __( 'Apply Schema To', 'directorist' ),
			'multi_directory_status' => directorist_is_multi_directory_enabled(),
			'type'    => 'tab',
			'value'   => 'all-directory',
			'options' => [
				[
					'label' => __('All Directories', 'directorist'),
					'description' => __('Use the same schema for all directories or select this if Multi-Directory is disabled.', 'directorist'),
					'value' => 'all-directory',
				],
				[
					'label' => __('Per Directory', 'directorist'),
					'description' => __('Set different schemas for each directory. Choose this for directory-specific schema types.', 'directorist'),
					'value' => 'per-directory',
				],
			],
			'show-if' => [
				[
					'where' => "enable_schema_markup",
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
			],
		];

		$fields['directory_schema_type_global'] = [
			'label' => __('Schema Type', 'directorist'),
			'icon' => '<i class="fas fa-database"></i>',
			'type'  => 'select',
			'value' => 'searched_value',
			'options' => static::get_schema_types_as_dropdown_options(),
			'show-if' => [
				[
					'where' => 'enable_schema_markup',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => true
						],
					],
				],
				[
					'where' => 'apply_schema_markup',
					'conditions' => [
						[
							'key'     => 'value',
							'compare' => '=',
							'value'   => 'all-directory',
						],
					],
				],
			],
		];

		$_fields = static::get_schema_type_fields_for_directories();


		if ( $_fields ) {
			$fields = array_merge( $fields, $_fields );
		}

		return $fields;
	}

	public static function print_schema() {
		$schema = static::get_schema();
		if ( ! $schema ) {
			return;
		}

		$schema = wp_json_encode( $schema );
		echo '<script type="application/ld+json">' . $schema . '</script>';
	}

	/**
	 * Get schema data for the current listing
	 *
	 * @since 8.4.0
	 * @return array|void Schema data array or void if conditions not met
	 */
	public static function get_schema() {
		if ( ! is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		$post_id      = get_the_ID();
		$directory_id = static::get_listing_directory_id();

		if ( ! directorist_is_directory( $directory_id ) ) {
			return array();
		}

		$data = directorist_get_directory_meta( $directory_id, 'single_listings_contents' );
		if ( empty( $data['fields'] ) ) {
			return array();
		}

		$fields = $data['fields'];
		$schema = static::get_base_schema( $post_id );

		static::maybe_add_description( $schema, $fields, $post_id );
		static::maybe_add_geo_data( $schema, $fields, $post_id );
		static::maybe_add_address( $schema, $fields, $post_id );
		static::maybe_add_website( $schema, $fields, $post_id );
		static::maybe_add_email( $schema, $fields, $post_id );
		static::maybe_add_phone_numbers( $schema, $fields, $post_id );
		static::maybe_add_ratings( $schema, $post_id );
		static::maybe_add_social_links( $schema, $fields, $post_id );

		return $schema;
	}

	/**
	 * Get base schema structure
	 *
	 * @param int $post_id Post ID
	 * @return array Base schema array
	 */
	private static function get_base_schema( $post_id ) {
		return array(
			'@context' => 'https://schema.org',
			'@type'    => static::get_schema_type( $post_id ),
			'name'     => get_the_title( $post_id ),
			'url'      => get_the_permalink( $post_id ),
		);
	}

	/**
	 * Add description to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_description( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['description'] ) ) {
			return;
		}

		$excerpt = get_post_meta( $post_id, '_excerpt', true );
		$schema['description'] = empty( $excerpt ) ? get_the_excerpt( $post_id ) : esc_textarea( $excerpt );
	}

	/**
	 * Add geo coordinates to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_geo_data( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['map'] ) ) {
			return;
		}

		$schema['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => esc_html( get_post_meta( $post_id, '_manual_lat', true ) ),
			'longitude' => esc_html( get_post_meta( $post_id, '_manual_lng', true ) ),
		);
	}

	/**
	 * Add address to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_address( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['address'] ) && ! isset( $fields['zip'] ) ) {
			return;
		}

		$schema['address'] = array(
			'@type'         => 'PostalAddress',
			'streetAddress' => esc_html( get_post_meta( $post_id, '_address', true ) ),
			'postalCode'    => esc_html( get_post_meta( $post_id, '_zip', true ) ),
		);
	}

	/**
	 * Add website URL to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_website( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['website'] ) ) {
			return;
		}

		$schema['url'] = esc_url( get_post_meta( $post_id, '_website', true ) );
	}

	/**
	 * Add email to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_email( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['email'] ) ) {
			return;
		}

		$schema['email'] = sanitize_email( get_post_meta( $post_id, '_email', true ) );
	}

	/**
	 * Add phone numbers to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_phone_numbers( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['phone'] ) && ! isset( $fields['phone2'] ) ) {
			return;
		}

		$phone1 = get_post_meta( $post_id, '_phone', true );
		$phone2 = get_post_meta( $post_id, '_phone2', true );

		if ( $phone1 || $phone2 ) {
			$schema['contactPoint'] = array();
		}

		if ( $phone1 ) {
			$schema['contactPoint'][] = array(
				'@type'       => 'ContactPoint',
				'telephone'   => static::format_phone( $phone1 ),
				'contactType' => 'customer service',
			);
		}

		if ( $phone2 ) {
			$schema['contactPoint'][] = array(
				'@type'       => 'ContactPoint',
				'telephone'   => static::format_phone( $phone2 ),
				'contactType' => 'customer service',
			);
		}
	}

	/**
	 * Add ratings to schema if available
	 *
	 * @param array $schema Schema array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_ratings( &$schema, $post_id ) {
		if ( ! directorist_is_review_enabled() ) {
			return;
		}

		$review_count = directorist_get_listing_review_count( $post_id );
		if ( $review_count <= 0 ) {
			return;
		}

		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => directorist_get_listing_rating( $post_id ),
			'reviewCount' => $review_count,
		);
	}

	/**
	 * Add social links to schema if available
	 *
	 * @param array $schema Schema array
	 * @param array $fields Fields array
	 * @param int   $post_id Post ID
	 */
	private static function maybe_add_social_links( &$schema, $fields, $post_id ) {
		if ( ! isset( $fields['social_info'] ) ) {
			return;
		}

		$links = get_post_meta( $post_id, '_social', true );
		if ( empty( $links ) ) {
			return;
		}

		$schema['sameAs'] = array();

		foreach ( $links as $link ) {
			if ( empty( $link['url'] ) ) {
				continue;
			}

			$schema['sameAs'][] = esc_url( $link['url'] );
		}
	}

	protected static function get_listing_directory_id() {
		return (int) get_post_meta( get_the_ID(),'_directory_type', true );
	}

	protected static function get_country_code() {
		if ( class_exists( 'WooCommerce' ) ) {
			$country = get_option( 'woocommerce_default_country' );
			$country = explode( ':', $country )[0];
		} else {
			$locale  = get_locale();
			$country = substr( $locale, -2 );
		}

		$country_codes = [
			'AF' => '+93',  'AL' => '+355', 'DZ' => '+213', 'AS' => '+1',   'AD' => '+376',
			'AO' => '+244', 'AI' => '+1',   'AG' => '+1',   'AR' => '+54',  'AM' => '+374',
			'AW' => '+297', 'AU' => '+61',  'AT' => '+43',  'AZ' => '+994', 'BS' => '+1',
			'BH' => '+973', 'BD' => '+880', 'BB' => '+1',   'BY' => '+375', 'BE' => '+32',
			'BZ' => '+501', 'BJ' => '+229', 'BM' => '+1',   'BT' => '+975', 'BO' => '+591',
			'BA' => '+387', 'BW' => '+267', 'BR' => '+55',  'IO' => '+246', 'BN' => '+673',
			'BG' => '+359', 'BF' => '+226', 'BI' => '+257', 'KH' => '+855', 'CM' => '+237',
			'CA' => '+1',   'CV' => '+238', 'KY' => '+1',   'CF' => '+236', 'TD' => '+235',
			'CL' => '+56',  'CN' => '+86',  'CO' => '+57',  'KM' => '+269', 'CG' => '+242',
			'CD' => '+243', 'CR' => '+506', 'HR' => '+385', 'CU' => '+53',  'CY' => '+357',
			'CZ' => '+420', 'DK' => '+45',  'DJ' => '+253', 'DM' => '+1',   'DO' => '+1',
			'EC' => '+593', 'EG' => '+20',  'SV' => '+503', 'GQ' => '+240', 'ER' => '+291',
			'EE' => '+372', 'ET' => '+251', 'FJ' => '+679', 'FI' => '+358', 'FR' => '+33',
			'GA' => '+241', 'GM' => '+220', 'GE' => '+995', 'DE' => '+49',  'GH' => '+233',
			'GR' => '+30',  'GD' => '+1',   'GT' => '+502', 'GN' => '+224', 'GW' => '+245',
			'GY' => '+592', 'HT' => '+509', 'HN' => '+504', 'HK' => '+852', 'HU' => '+36',
			'IS' => '+354', 'IN' => '+91',  'ID' => '+62',  'IR' => '+98',  'IQ' => '+964',
			'IE' => '+353', 'IL' => '+972', 'IT' => '+39',  'JM' => '+1',   'JP' => '+81',
			'JO' => '+962', 'KZ' => '+7',   'KE' => '+254', 'KI' => '+686', 'KP' => '+850',
			'KR' => '+82',  'KW' => '+965', 'KG' => '+996', 'LA' => '+856', 'LV' => '+371',
			'LB' => '+961', 'LS' => '+266', 'LR' => '+231', 'LY' => '+218', 'LI' => '+423',
			'LT' => '+370', 'LU' => '+352', 'MO' => '+853', 'MK' => '+389', 'MG' => '+261',
			'MW' => '+265', 'MY' => '+60',  'MV' => '+960', 'ML' => '+223', 'MT' => '+356',
			'MH' => '+692', 'MR' => '+222', 'MU' => '+230', 'MX' => '+52',  'FM' => '+691',
			'MD' => '+373', 'MC' => '+377', 'MN' => '+976', 'ME' => '+382', 'MA' => '+212',
			'MZ' => '+258', 'MM' => '+95',  'NA' => '+264', 'NR' => '+674', 'NP' => '+977',
			'NL' => '+31',  'NZ' => '+64',  'NI' => '+505', 'NE' => '+227', 'NG' => '+234',
			'NO' => '+47',  'OM' => '+968', 'PK' => '+92',  'PW' => '+680', 'PA' => '+507',
			'PG' => '+675', 'PY' => '+595', 'PE' => '+51',  'PH' => '+63',  'PL' => '+48',
			'PT' => '+351', 'QA' => '+974', 'RO' => '+40',  'RU' => '+7',   'RW' => '+250',
			'WS' => '+685', 'SM' => '+378', 'ST' => '+239', 'SA' => '+966', 'SN' => '+221',
			'RS' => '+381', 'SC' => '+248', 'SL' => '+232', 'SG' => '+65',  'SK' => '+421',
			'SI' => '+386', 'SB' => '+677', 'SO' => '+252', 'ZA' => '+27',  'ES' => '+34',
			'LK' => '+94',  'SD' => '+249', 'SR' => '+597', 'SE' => '+46',  'CH' => '+41',
			'SY' => '+963', 'TW' => '+886', 'TJ' => '+992', 'TZ' => '+255', 'TH' => '+66',
			'TL' => '+670', 'TG' => '+228', 'TO' => '+676', 'TT' => '+1',   'TN' => '+216',
			'TR' => '+90',  'TM' => '+993', 'UG' => '+256', 'UA' => '+380', 'AE' => '+971',
			'GB' => '+44',  'US' => '+1',   'UY' => '+598', 'UZ' => '+998', 'VU' => '+678',
			'VE' => '+58',  'VN' => '+84',  'YE' => '+967', 'ZM' => '+260', 'ZW' => '+263'
		];

		return $country_codes[$country] ?? '+1';
	}

	protected static function format_phone( $phone ) {
		$phone = preg_replace( '/[^\d+]/', '', $phone );

		if ( strpos( $phone, '00' ) === 0 ) {
			$phone = '+' . substr( $phone, 2 );
		}

		if ( $phone[0] !== '+' ) {
			$phone = static::get_country_code() . $phone;
		}

		return $phone;
	}

	protected static function get_schema_type_fields_for_directories() {
		if ( ! directorist_is_multi_directory_enabled() || static::$schema_type_fields ) {
			return static::$schema_type_fields;
		}

		$field_base = [
			'label'   => '',
			'icon'    => '',
			'type'    => 'select',
			'options' => static::get_schema_types_as_dropdown_options(),
			'show-if' => [
				[
					'where'      => 'enable_schema_markup',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				],
				[
					'where'      => 'apply_schema_markup',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => 'per-directory'],
					],
				],
				[
					'where'      => 'enable_multi_directory',
					'conditions' => [
						['key' => 'value', 'compare' => '=', 'value' => true],
					],
				]
			],
		];

		$directories = directorist_get_directories_for_template();

		foreach ( $directories as $directory_id => $directory ) {
			$field_base['label']  = $directory['name'];
			$field_base['icon']   = '<i class="'. esc_attr( $directory['data']['icon'] ) . '"></i>';
			$field_key            = 'directory_schema_type_'. $directory_id;
			static::$schema_type_fields[ $field_key ] = $field_base;
		}

		unset( $field_base );

		return static::$schema_type_fields;
	}

	public static function get_schema_types() {
		$schema_types = array(
			'BusinessEvent'       => esc_html__( 'BusinessEvent', 'directorist' ),
			'ApartmentComplex'    => esc_html__( 'ApartmentComplex', 'directorist' ),
			'Event'               => esc_html__( 'Event', 'directorist' ),
			'Festival'            => esc_html__( 'Festival', 'directorist' ),
			'JobPosting'          => esc_html__( 'JobPosting', 'directorist' ),
			'LocalBusiness'       => esc_html__( 'LocalBusiness', 'directorist' ),
			'MarketingAgency'     => esc_html__( 'MarketingAgency', 'directorist' ),
			'Organization'        => esc_html__( 'Organization', 'directorist' ),
			'ProfessionalService' => esc_html__( 'ProfessionalService', 'directorist' ),
			'RealEstateAgent'     => esc_html__( 'RealEstateAgent', 'directorist' ),
			'Service'             => esc_html__( 'Service', 'directorist' ),
		);

		return apply_filters( 'directorist_schema_types', $schema_types );
	}

	public static function get_schema_types_as_dropdown_options() {
		$schema_types = static::get_schema_types();

		$output = array();
		foreach ( $schema_types as $key => $value ) {
			$output[] = array(
				'value' => $key,
				'label' => $value,
			);
		}

		return $output;
	}

	public static function get_schema_type( $post_id ) {
		$schema_applied_to = get_directorist_option( 'apply_schema_markup', 'all-directory' );

		if ( $schema_applied_to === 'per-directory' && directorist_is_multi_directory_enabled() ) {
			$directory_id = directorist_get_listings_directory_type( $post_id );
			$schema_type  = get_directorist_option( 'directory_schema_type_'. $directory_id, 'LocalBusiness' );
		} else {
			$schema_type = get_directorist_option( 'directory_schema_type_global', 'LocalBusiness' );
		}

		if ( empty( $schema_type ) || ! is_string( $schema_type ) ) {
			return 'LocalBusiness';
		}

		return array_key_exists( $schema_type, static::get_schema_types() ) ? $schema_type : 'LocalBusiness';
	}
}

Schema::init();
