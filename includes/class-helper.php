<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Helper {

	use URI_Helper;
	use Markup_Helper;

	public static function is_legacy_mode() {
		$legacy = get_directorist_option( 'atbdp_legacy_template', false );
		return $legacy;
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
		$currency = get_directorist_option( 'g_currency', 'USD' );
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
		$c_position    = get_directorist_option('g_currency_position');
		$currency      = get_directorist_option('g_currency', 'USD');
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

	public static function formatted_tel( $tel = '', $echo = true ) {
		$tel = preg_replace( '/\D/', '', $tel );

		if ( !$echo ) {
			return $tel;
		}
		else {
			echo $tel;
		}
	}

	public static function parse_video( $url ) {
		$embeddable_url = '';

		$is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url);
		if ($is_youtube) {
			$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
			preg_match($pattern, $url, $matches);
			if (count($matches) && strlen($matches[7]) == 11) {
				$embeddable_url = 'https://www.youtube.com/embed/' . $matches[7];
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
		$listing_popular_by = get_directorist_option('listing_popular_by');
		$average = ATBDP()->review->get_average($listing_id);
		$average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
		$view_count = get_post_meta($listing_id, '_atbdp_post_views_count', true);
		$view_to_popular = get_directorist_option('views_for_popular');

		if ('average_rating' === $listing_popular_by) {
			if ($average_review_for_popular <= $average) {
				return true;
			}
		}
		elseif ('view_count' === $listing_popular_by) {
			if ((int)$view_count >= (int)$view_to_popular) {
				return true;
			}
		}
		elseif (($average_review_for_popular <= $average) && ((int)$view_count >= (int)$view_to_popular)) {
			return true;
		}
		else {
			return false;
		}
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
		return get_directorist_option( 'enable_multi_directory', false );
	}

	public static function default_preview_image_src( $type ) {
		if ( self::multi_directory_enabled() ) {
			$type_general = get_term_meta( $type, 'general_config', true );

			if ( ! empty( $type_general['preview_image'] ) ) {
				$default_image_src = $type_general['preview_image'];
			}
			else {
				$default_img = get_directorist_option( 'default_preview_image' );
				$default_image_src = $default_img ? $default_img : DIRECTORIST_ASSETS . 'images/grid.jpg';
			}
		}
		else {
			$default_img = get_directorist_option( 'default_preview_image' );
			$default_image_src = $default_img ? $default_img : DIRECTORIST_ASSETS . 'images/grid.jpg';
		}
		
		return $default_image_src;
	}

	public static function is_review_enabled() {
		return get_directorist_option( 'enable_review', 1 );
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

	public static function add_hidden_data_to_dom( string $data_key = '', array $data = [] ) {

		if ( empty( $data ) ) { return; }
		
		$value = json_encode( $data );
		?>
		<!-- directorist-dom-data::<?php echo $data_key; ?> <?php echo $value; ?> -->
		<?php
	}

	public static function add_shortcode_comment( string $shortcode = '' ) {
		echo "<!-- directorist-shortcode:: [{$shortcode}] -->";
	}
	
}