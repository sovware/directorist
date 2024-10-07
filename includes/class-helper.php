<?php
/**
 * @author wpWax
 */

namespace Directorist;

use Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

class Helper {

	use URI_Helper;
	use Markup_Helper;
	use Icon_Helper;

	public static function is_legacy_mode() {
		return false;
	}

	public static function get_directory_type_term_data( $post_id = '', string $term_key = '' ) {
		$post_id        = ( ! empty( $post_id ) ) ? $post_id : get_the_ID();
		$directory_type = get_post_meta( $post_id, '_directory_type', true );
		$directory_type = ( ! empty( $directory_type ) ) ? $directory_type : default_directory_type();

		return get_term_meta( $directory_type, $term_key, true );
	}

	/**
	 * Get first wp error message
	 *
	 * @param object $wp_error
	 * @return string $message
	 */
	public static function get_first_wp_error_message( $wp_error ) {
		if ( ! is_wp_error( $wp_error ) ) {
			return '';
		}

		$error_keys = ( is_array( $wp_error->errors ) ) ? array_keys( $wp_error->errors ) : [];
		$error_key  = ( ! empty( $error_keys ) ) ? $error_keys[0] : '';
		$message    = ( ! empty( $error_key ) && is_array( $wp_error->errors[ $error_key ] ) && ! empty( $wp_error->errors[ $error_key ] ) ) ? $wp_error->errors[ $error_key ][0] : '';

		return $message;
	}

	/**
	 * Get Time In Millisecond
	 *
	 * This function is only available on operating
	 * systems that support the gettimeofday() system call.
	 * @link https://www.php.net/manual/en/function.microtime.php
	 *
	 * @return int
	 */
	public static function getTimeInMillisecond() {
		try {
			return ( int ) ( microtime( true ) * 1000 );
		} catch ( Exception $e ) {
			return 0;
		}
	}

	/**
	 * Maybe JSON
	 *
	 * Converts input to an array if contains valid json string
	 *
	 * If input contains base64 encoded json string, then it
	 * can decode it as well
	 *
	 * @param $input_data
	 * @param $return_first_item
	 *
	 * Returns first item of the array if $return_first_item is set to true
	 * Returns original input if it is not decodable
	 *
	 * @return mixed
	 */
	public static function maybe_json( $input_data = '', $return_first_item = false ) {
		if ( ! is_string( $input_data ) ) {
			return $input_data;
		}

		$output_data = $input_data;

		// JSON Docode
		$decode_json = json_decode( $input_data, true );

		if ( ! is_null( $decode_json ) ) {
			return ( $return_first_item && is_array( $decode_json ) && isset( $decode_json[0] ) ) ? $decode_json[0] : $decode_json;
		}

		// JSON Decode from Base64
		$decode_base64 = base64_decode( $input_data );
		$decode_base64_json = json_decode( $decode_base64, true );

		if ( ! is_null( $decode_base64_json ) ) {
			return ( $return_first_item && is_array( $decode_base64_json ) && isset( $decode_base64_json[0] ) ) ? $decode_base64_json[0] : $decode_base64_json;
		}

		return $output_data;
	}

	// get_widget_value
	public static function get_widget_value( $post_id = 0, $widget = [] ) {
		$value = '';

		// directorist_console_log( $widget );

		if ( ! is_array( $widget ) ) { return ''; }

		if ( isset( $widget['field_key'] ) ) {
			$value = get_post_meta( $post_id, '_'.$widget['field_key'], true );

			if ( empty( $value ) ) {
				$value = get_post_meta( $post_id, $widget['field_key'], true );
			}
		}

		if ( isset( $widget['original_data'] ) && isset( $widget['original_data']['field_key'] ) ) {
			$value = get_post_meta( $post_id, '_' . $widget['original_data']['field_key'], true );

			if ( empty( $value ) ) {
				$value = get_post_meta( $post_id, $widget['original_data']['field_key'], true );
			}
		}

		return $value;
	}

	// add_listings_review_meta
	public static function add_listings_review_meta( array $args = [] ) {

		if ( empty( $args['post_id'] ) ) { return false; }

		$reviews = get_post_meta( $args['post_id'], '_directorist_reviews', true );

		if ( ! is_array( $reviews ) ) { $reviews = []; }

		if ( empty( $args['reviewer_id'] ) ) { return false; }
		if ( empty( $args['status'] ) ) { return false; }
		if ( empty( $args['rating'] ) ) { return false; }
		if ( ! is_numeric( $args['rating'] ) ) { return false; }

		$reviews[ $args['reviewer_id'] ] = $args;

		update_post_meta( $args['post_id'], '__directorist_reviews', $reviews );

		return self::update_listings_ratings_meta( $args['post_id'] );
	}

	// update_listings_review_meta
	public static function update_listings_review_meta( array $args = [] ) {

		if ( empty( $args['post_id'] ) ) { return false; }

		$reviews = get_post_meta( $args['post_id'], '_directorist_reviews', true );

		if ( ! is_array( $reviews ) ) { return false; }

		if ( empty( $args['field_key'] ) ) { return false; }
		if ( empty( $args['value'] ) ) { return false; }
		if ( empty( $args['reviewer_id'] ) ) { return false; }


		if (  'rating' === $args['field_key'] && ! is_numeric( $args['value'] ) ) {
			return false;
		}

		if ( empty( $reviews[ $args['reviewer_id'] ] ) ) { return false; }
		if ( empty( $reviews[ $args['reviewer_id'] ][ $args['field_key'] ] ) ) { return false; }


		$reviewer_id = $args['reviewer_id'];
		$field_key   = $args['field_key'];
		$value       = $args['value'];

		$reviews[ $reviewer_id ][ $field_key ] = $value;

		update_post_meta( $args['post_id'], '__directorist_reviews', $reviews );

		return self::update_listings_ratings_meta( $args['post_id'] );
	}

	// update_listings_ratings_meta
	public static function update_listings_ratings_meta( $post_id = 0 ) {

		if ( empty( $post_id ) ) { return false; }

		$reviews = get_post_meta( $post_id, '_directorist_reviews', true );

		if ( empty( $reviews ) ) { return  false; }
		if ( ! is_array( $reviews ) ) { return  false; }

		$total_ratings = 0;

		foreach ( $reviews as $id => $review ) {

			if ( empty( $review[ 'rating' ] ) ) { continue; }
			if ( ! is_numeric( $review[ 'rating' ] ) ) { continue; }
			if ( empty( $review[ 'status' ] ) ) { continue; }
			if ( 'published' !== $review[ 'status' ] ) { continue; }

			$total_ratings = $total_ratings + ( float ) $review[ 'rating' ];
		}

		$avg_ratings = $total_ratings / count( $reviews );
		update_post_meta( $post_id, '_directorist_ratings', $avg_ratings );

		return true;
	}

	public static function listing_price( $id='' ) {
		if ( !$id ) {
			$id = get_the_ID();
		}

		if ( !self::has_price_range( $id ) && !self::has_price( $id ) ) {
			return;
		}

		if ( 'range' == Helper::pricing_type( $id ) ) {
			self::price_range_template( $id );
		}
		else {
			self::price_template( $id );
		}
	}

	public static function socials() {
		$socials = [
			'facebook'       => __('Facebook', 'directorist'),
			'twitter'        => __('Twitter', 'directorist'),
			'linkedin'       => __('LinkedIn', 'directorist'),
			'pinterest'      => __('Pinterest', 'directorist'),
			'instagram'      => __('Instagram', 'directorist'),
			'tumblr'         => __('Tumblr', 'directorist'),
			'flickr'         => __('Flickr', 'directorist'),
			'snapchat'       => __('Snapchat', 'directorist'),
			'reddit'         => __('Reddit', 'directorist'),
			'youtube'        => __('Youtube', 'directorist'),
			'vimeo'          => __('Vimeo', 'directorist'),
			'vine'           => __('Vine', 'directorist'),
			'github'         => __('Github', 'directorist'),
			'dribbble'       => __('Dribbble', 'directorist'),
			'behance'        => __('Behance', 'directorist'),
			'soundcloud'     => __('SoundCloud', 'directorist'),
			'stack-overflow' => __('StackOverFLow', 'directorist'),
		];

		asort( $socials );

		return $socials;
	}

	public static function pricing_type( $listing_id ) {
		$pricing_type = get_post_meta( $listing_id, '_atbd_listing_pricing', true );
		return $pricing_type;
	}

	public static function has_price( $listing_id ) {
		$price = get_post_meta( $listing_id, '_price', true );
		return $price;
	}

	public static function has_price_range( $listing_id ) {
		$price_range = get_post_meta( $listing_id, '_price_range', true );
		return $price_range;
	}

	public static function price_template( $listing_id ) {
		$price = get_post_meta( $listing_id, '_price', true );
		self::get_template( 'global/price', compact( 'price' ) );
	}

	public static function price_range_template( $listing_id ) {
		$price_range = get_post_meta( $listing_id, '_price_range', true );
		$currency = directorist_get_currency();
		$currency = atbdp_currency_symbol( $currency );

		switch ( $price_range ) {
			case 'skimming':
			$active_items = 4;
			$price_range_text = __( 'Skimming', 'directorist' );
			break;

			case 'moderate':
			$active_items = 3;
			$price_range_text = __( 'Moderate', 'directorist' );
			break;

			case 'economy':
			$active_items = 2;
			$price_range_text = __( 'Economy', 'directorist' );
			break;

			case 'bellow_economy':
			$active_items = 1;
			$price_range_text = __( 'Cheap', 'directorist' );
			break;

			default:
			$active_items = 4;
			$price_range_text = __( 'Skimming', 'directorist' );
			break;
		}

		self::get_template( 'global/price-range', compact( 'active_items', 'currency', 'price_range_text' ) );
	}

	public static function formatted_price( $price ) {
		$allow_decimal = get_directorist_option('allow_decimal', 1);
		$c_position    = directorist_get_currency_position();
		$currency      = directorist_get_currency();
		$symbol        = atbdp_currency_symbol($currency);
		$before        = '';
		$after         = '';

		if ('after' == $c_position) {
			$after = $symbol;
		}
		else {
			$before = $symbol;
		}

		$price = $before . atbdp_format_amount( $price, $allow_decimal ) . $after;
		return $price;
	}

	public static function formatted_tel( $tel_number = '', $echo = true ) {
		$tel_number = preg_replace( '/[^\d\+]/', '', $tel_number );

		if ( ! $echo ) {
			return $tel_number;
		}

		echo esc_html( $tel_number );
	}

	public static function phone_link( $args ) {
		$args = array_merge( array(
			'number'    => '',
			'whatsapp'  => false,
		), $args );

		$number = self::formatted_tel( $args['number'], false );

		if ( $args['whatsapp'] ) {
			return sprintf( 'https://wa.me/%s', $number );
		}

		return sprintf( 'tel:%s', $number );
	}

	public static function user_info( $user_id_or_obj, $meta ) {

		if ( is_integer( $user_id_or_obj ) ) {
			$user_id = $user_id_or_obj;
			$user = get_userdata( $user_id );
		}
		else {
			$user = $user_id_or_obj;
			$user_id = $user->data->ID;
		}

		$result = '';

		switch ( $meta ) {
			case 'name':
			$result = $user->data->display_name;
			break;

			case 'role':
			$result = $user->roles[0];
			break;

			case 'address':
			$result = get_user_meta($user_id, 'address', true);
			break;

			case 'phone':
			$result = get_user_meta($user_id, 'atbdp_phone', true);
			break;

			case 'email':
			$result = $user->data->user_email;
			break;

			case 'website':
			$result = $user->data->user_url;
			break;

			case 'description':
			$result = trim( get_user_meta( $user_id, 'description', true ) );
			//var_dump($result);
			break;

			case 'facebook':
			$result = get_user_meta($user_id, 'atbdp_facebook', true);
			break;

			case 'twitter':
			$result = get_user_meta($user_id, 'atbdp_twitter', true);
			break;

			case 'linkedin':
			$result = get_user_meta($user_id, 'atbdp_linkedin', true);
			break;

			case 'youtube':
			$result = get_user_meta($user_id, 'atbdp_youtube', true);
			break;
		}

		return $result;
	}

	public static function parse_video( $url ) {
		$embeddable_url = '';

		$is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url) || preg_match('/youtube\.com\/shorts/i', $url);
        if ($is_youtube) {
			$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(shorts\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
			preg_match($pattern, $url, $matches);
			if (count($matches) && strlen($matches[8]) == 11) {
				$embeddable_url = 'https://www.youtube.com/embed/' . $matches[8];
			}
		}

		$is_vimeo = preg_match('/vimeo\.com/i', $url);
		if ($is_vimeo) {
			$pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
			preg_match($pattern, $url, $matches);
			if (count($matches)) {
				$embeddable_url = 'https://player.vimeo.com/video/' . $matches[2];
			}
		}

		return $embeddable_url;
	}

	public static function is_popular( $listing_id ) {
		$listing_popular_by         = get_directorist_option( 'listing_popular_by' );
		$average                    = directorist_get_listing_rating( $listing_id );
		$average_review_for_popular = (int) get_directorist_option( 'average_review_for_popular', 4 );
		$view_count                 = directorist_get_listing_views_count( $listing_id );
		$view_to_popular            = (int) get_directorist_option( 'views_for_popular' );

		if ( 'average_rating' === $listing_popular_by && $average_review_for_popular <= $average ) {
			return true;
		} elseif ( 'view_count' === $listing_popular_by && $view_count >= $view_to_popular ) {
			return true;
		} elseif ( $average_review_for_popular <= $average && $view_count >= $view_to_popular ) {
			return true;
		}

		return false;
	}

	public static function badge_exists( $listing_id ) {
		// @cache @kowsar
		if ( self::is_new( $listing_id ) || self::is_featured( $listing_id ) || self::is_popular( $listing_id ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function is_new( $listing_id ) {
		$post = get_post( $listing_id ); // @cache @kowsar
		$new_listing_time = get_directorist_option('new_listing_day');
		$each_hours = 60 * 60 * 24;
		$s_date1 = strtotime(current_time('mysql'));
		$s_date2 = strtotime($post->post_date);
		$s_date_diff = abs($s_date1 - $s_date2);
		$days = round($s_date_diff / $each_hours);

		if ($days <= (int)$new_listing_time) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function multi_directory_enabled() {
		return directorist_is_multi_directory_enabled();
	}

	public static function default_preview_image_src( $directory_id ) {
		if ( directorist_is_multi_directory_enabled() ) {
			$settings = directorist_get_directory_general_settings( $directory_id );

			if ( ! empty( $settings['preview_image'] ) ) {
				$default_preview = $settings['preview_image'];
			} else {
				$default_img = get_directorist_option( 'default_preview_image' );
				$default_preview = $default_img ? $default_img : DIRECTORIST_ASSETS . 'images/grid.jpg';
			}
		} else {
			$default_img = get_directorist_option( 'default_preview_image' );
			$default_preview = $default_img ? $default_img : DIRECTORIST_ASSETS . 'images/grid.jpg';
		}

		return $default_preview;
	}

	public static function is_review_enabled() {
		return directorist_is_review_enabled();
	}

	public static function is_featured( $listing_id ) {
		return get_post_meta( $listing_id, '_featured', true );
	}

	public static function new_badge_text() {
		return get_directorist_option('new_badge_text', 'New');
	}

	public static function popular_badge_text() {
		return get_directorist_option('popular_badge_text', 'Popular');
	}

	public static function featured_badge_text() {
		return get_directorist_option('feature_badge_text', 'Featured');
	}

	public static function single_listing_dummy_shortcode( $shortcode, $atts = [] ) {
		$atts_string = '';

		if ( $atts ) {
			foreach ( $atts as $key => $value ) {
				$atts_string .= sprintf( ' %s="%s"', $key, $value );
			}
		}

		return sprintf( '<div class="directorist-single-dummy-shortcode">%s%s</div>', $shortcode, $atts_string );
	}

	/**
	 * Get a list of directories that has custom single listing page enabled and set.
	 *
	 * @todo remove this unused method
	 *
	 * @param  int|null $page_id Optional page id.
	 *
	 * @return array
	 */
	public static function get_directory_types_with_custom_single_page( $page_id = null ) {
		$args = array(
			'meta_query' => array(
				'page_enabled' => array(
					'key'     => 'enable_single_listing_page',
					'compare' => '=',
					'value'   => 1,
				),
			),
		);

		$directory_types = directorist_get_directories( $args );
		if ( empty( $directory_types ) || is_wp_error( $directory_types ) ) {
			return [];
		}

		$directory_types = array_filter( $directory_types, static function( $directory_type ) use ( $page_id ) {
			$selected_page_id = (int) get_term_meta( $directory_type->term_id, 'single_listing_page', true );

			if ( is_null( $page_id ) ) {
				return $selected_page_id;
			}

			return ( $selected_page_id === (int) $page_id );
		} );

		return $directory_types;
	}

	public static function builder_selected_single_pages() {
		// @cache @kowsar
		$pages = [];

		$types = get_terms( array(
			'taxonomy'   => 'atbdp_listing_types',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'     => 'single_listing_page',
					'compare' => 'EXISTS',
				),
			),
		) );

		foreach ( $types as $type ) {
			$page_id   = get_directorist_type_option( $type->term_id, 'single_listing_page' );
			$single_listing_enabled = get_directorist_type_option( $type->term_id, 'enable_single_listing_page' );
			if ( $single_listing_enabled && $page_id ) {
				$pages[$page_id] = $type->name;
			}
		}

		return $pages;
	}

	public static function get_listing_payment_status( $listing_id = '' ) {

		$order_id = get_post_meta( $listing_id, '_listing_order_id', true );

		if ( empty( $order_id ) ) {
			$order_id = self::get_listing_order_id( $listing_id );
			update_post_meta( $listing_id, '_listing_order_id', $order_id );
		}

		$payment_status = get_post_meta( $order_id, '_payment_status', true );

		return $payment_status;
	}

	// get_listing_order_id
	public static function get_listing_order_id( $listing_id = '' ) {
		$args = [
			'post_type' => 'atbdp_orders',
			'post_status' => 'publish',
			'meta_query' => [
				[
					'key' => '_listing_id',
					'value' => $listing_id,
				]
			]
		];

		$orders = new \WP_Query( $args );
		$order_id = ( $orders->have_posts() ) ? $orders->post->ID : '';

		return $order_id;
	}

	public static function add_hidden_data_to_dom( string $data_key = '', array $data = [] ) {

		if ( empty( $data ) ) { return; }

		$data_value = base64_encode( json_encode( $data ) );
		?>
		<span
			style="display: none;"
			class="directorist-dom-data directorist-dom-data-<?php echo esc_attr( $data_key ); ?>"
			data-value="<?php echo esc_attr( $data_value ); ?>"
		>
		</span>
		<?php
	}

	public static function add_shortcode_comment( string $shortcode = '' ) {
		echo "<!-- directorist-shortcode:: [ " . esc_attr( $shortcode ) . "] -->";
	}

	public static function sanitize_query_strings( $url = '' ) {
		$matches = [];
		$qs_pattern = '/[?].+/';

		$qs = preg_match( $qs_pattern, $url, $matches );
		$qs = ( ! empty( $matches ) ) ? ltrim( $matches[0], '?' ) : '';
		$qs = ( ! empty( $qs ) ) ? '?' . str_replace( '?', '&', $qs ) : '';

		$sanitized_url = preg_replace( $qs_pattern, $qs, $url );

		return $sanitized_url;
	}

	/**
	 * Is Rank Math Active
	 *
	 * Determines whether Rank Math is active
	 *
	 * @return bool True, if in the active plugins list. False, not in the list.
	 * @since 7.0.8
	 */
	public static function is_rankmath_active() {
		return self::is_plugin_active( 'seo-by-rank-math/rank-math.php' );
	}

	/**
	 * Is Yoast Active
	 *
	 * Determines whether Yoast is active
	 *
	 * @return bool True, if in the active plugins list. False, not in the list.
	 * @since 7.0.8
	 */
	public static function is_yoast_active() {
		$yoast_free_is_active    = self::is_plugin_active( 'wordpress-seo/wp-seo.php' );
    	$yoast_premium_is_active = self::is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' );

		return ( $yoast_free_is_active || $yoast_premium_is_active );
	}

	/**
	 * Is Plugin Active
	 *
	 * Determines whether a plugin is active
	 *
	 * @param string $plugin — Path to the plugin file relative to the plugins directory.
	 * @return bool True, if in the active plugins list. False, not in the list.
	 * @since 7.0.8
	 */
	public static function is_plugin_active( string $plugin = '' ) {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			return false;
		}

		return is_plugin_active( $plugin );
	}

	/**
	 * Validate Date Format
	 *
	 * @param string $date Date
	 * @param string $format Date Format
	 * @return bool
	 */
	public static function validate_date_format( $date, $format = 'Y-m-d h:i:s' ) {

		$d = \DateTime::createFromFormat( $format, $date );

		return $d && $d->format($format) === $date;
	}

	/**
	 * Escape Query Strings From URL
	 *
	 * @param string $url URL
	 * @return string URL
	 */
	public static function escape_query_strings_from_url( $url = '' ) {
		$matches = [];
		$qs_pattern = '/[?].+/';

		$qs = preg_match( $qs_pattern, $url, $matches );
		$qs = ( ! empty( $matches ) ) ? ltrim( $matches[0], '?' ) : '';
		$qs = ( ! empty( $qs ) ) ? '?' . str_replace( '?', '&', $qs ) : '';

		$sanitized_url = preg_replace( $qs_pattern, $qs, $url );

		return $sanitized_url;
	}

	/**
	 * Get Query String Pattern
	 *
	 * @return string String Pattern
	 */
	public static function get_query_string_pattern() {
		return '/\/?[?].+\/?/';
	}

	/**
	 * Join Slug To Url
	 *
	 * @param string $url
	 * @param string $slug
	 *
	 * @return string URL
	 */
	public static function join_slug_to_url( $url = '', $slug = '' ) {
		if ( empty( $url ) ) {
			return $url;
		}

		$query_string = self::get_query_strings_from_url( $url );
		$query_string = trim( $query_string, '/' );

		$url = preg_replace( self::get_query_string_pattern(), '', $url );
		$url = rtrim( $url, '/' );
		$url = "{$url}/{$slug}/{$query_string}";

		return $url;
	}

	/**
	 * Extracts Query Strings From URL
	 *
	 * @param string $url
	 *
	 * @return string Query Strings
	 */
	public static function get_query_strings_from_url( $url = '' ) {
		if ( empty( $url ) ) {
			return $url;
		}

		$qs_pattern = self::get_query_string_pattern();
		$matches = [];

		preg_match( $qs_pattern, $url, $matches );

		$query_strings = ( ! empty( $matches ) ) ? $matches[0] : '';

		return $query_strings;
	}

}
