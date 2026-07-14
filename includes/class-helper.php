<?php
/**
 * @author wpWax
 */

namespace Directorist;

use Exception;
use Plugin_Upgrader;
use Automatic_Upgrader_Skin;

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
        $directory_type = directorist_get_listing_directory( $post_id );
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

        if ( ! is_array( $widget ) ) {
            return ''; }

        if ( isset( $widget['field_key'] ) ) {
            $value = get_post_meta( $post_id, '_' . $widget['field_key'], true );

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

        if ( empty( $args['post_id'] ) ) {
            return false; }

        $reviews = get_post_meta( $args['post_id'], '_directorist_reviews', true );

        if ( ! is_array( $reviews ) ) {
            $reviews = []; }

        if ( empty( $args['reviewer_id'] ) ) {
            return false; }
        if ( empty( $args['status'] ) ) {
            return false; }
        if ( empty( $args['rating'] ) ) {
            return false; }
        if ( ! is_numeric( $args['rating'] ) ) {
            return false; }

        $reviews[ $args['reviewer_id'] ] = $args;

        update_post_meta( $args['post_id'], '__directorist_reviews', $reviews );

        return self::update_listings_ratings_meta( $args['post_id'] );
    }

    // update_listings_review_meta
    public static function update_listings_review_meta( array $args = [] ) {

        if ( empty( $args['post_id'] ) ) {
            return false; }

        $reviews = get_post_meta( $args['post_id'], '_directorist_reviews', true );

        if ( ! is_array( $reviews ) ) {
            return false; }

        if ( empty( $args['field_key'] ) ) {
            return false; }
        if ( empty( $args['value'] ) ) {
            return false; }
        if ( empty( $args['reviewer_id'] ) ) {
            return false; }


        if ( 'rating' === $args['field_key'] && ! is_numeric( $args['value'] ) ) {
            return false;
        }

        if ( empty( $reviews[ $args['reviewer_id'] ] ) ) {
            return false; }
        if ( empty( $reviews[ $args['reviewer_id'] ][ $args['field_key'] ] ) ) {
            return false; }


        $reviewer_id = $args['reviewer_id'];
        $field_key   = $args['field_key'];
        $value       = $args['value'];

        $reviews[ $reviewer_id ][ $field_key ] = $value;

        update_post_meta( $args['post_id'], '__directorist_reviews', $reviews );

        return self::update_listings_ratings_meta( $args['post_id'] );
    }

    // update_listings_ratings_meta
    public static function update_listings_ratings_meta( $post_id = 0 ) {

        if ( empty( $post_id ) ) {
            return false; }

        $reviews = get_post_meta( $post_id, '_directorist_reviews', true );

        if ( empty( $reviews ) ) {
            return  false; }
        if ( ! is_array( $reviews ) ) {
            return  false; }

        $total_ratings = 0;

        foreach ( $reviews as $id => $review ) {

            if ( empty( $review[ 'rating' ] ) ) {
                continue; }
            if ( ! is_numeric( $review[ 'rating' ] ) ) {
                continue; }
            if ( empty( $review[ 'status' ] ) ) {
                continue; }
            if ( 'published' !== $review[ 'status' ] ) {
                continue; }

            $total_ratings = $total_ratings + ( float ) $review[ 'rating' ];
        }

        $avg_ratings = $total_ratings / count( $reviews );
        update_post_meta( $post_id, '_directorist_ratings', $avg_ratings );

        return true;
    }

    public static function listing_price( $id = '' ) {
        if ( ! $id ) {
            $id = get_the_ID();
        }

        if ( ! self::has_price_range( $id ) && ! self::has_price( $id ) ) {
            return;
        }

        if ( 'range' == Helper::pricing_type( $id ) ) {
            self::price_range_template( $id );
        } else {
            self::price_template( $id );
        }
    }

    public static function socials() {
        $socials = [
            'facebook'       => __( 'Facebook', 'directorist' ),
            'twitter'        => __( 'X', 'directorist' ),
            'linkedin'       => __( 'LinkedIn', 'directorist' ),
            'pinterest'      => __( 'Pinterest', 'directorist' ),
            'instagram'      => __( 'Instagram', 'directorist' ),
            'tumblr'         => __( 'Tumblr', 'directorist' ),
            'flickr'         => __( 'Flickr', 'directorist' ),
            'snapchat'       => __( 'Snapchat', 'directorist' ),
            'reddit'         => __( 'Reddit', 'directorist' ),
            'youtube'        => __( 'Youtube', 'directorist' ),
            'vimeo'          => __( 'Vimeo', 'directorist' ),
            'vine'           => __( 'Vine', 'directorist' ),
            'github'         => __( 'Github', 'directorist' ),
            'dribbble'       => __( 'Dribbble', 'directorist' ),
            'behance'        => __( 'Behance', 'directorist' ),
            'soundcloud'     => __( 'SoundCloud', 'directorist' ),
            'stack-overflow' => __( 'StackOverFLow', 'directorist' ),
        ];

        asort( $socials );

        return $socials;
    }

    public static function pricing_type( $listing_id ) {
        $pricing_type = get_post_meta( $listing_id, '_atbd_listing_pricing', true );
        if ( ! $pricing_type ) return self::default_pricing_type( $listing_id ); 
        return $pricing_type;
    }

    public static function default_pricing_type( $listing_id ) {
        $default_pricing_type = 'price';
        $directory_type = directorist_get_listing_directory( $listing_id );
        $directory_type = ( ! empty( $directory_type ) ) ? $directory_type : default_directory_type();
        $form_fields = get_term_meta( $directory_type, 'submission_form_fields', true );
        if ( isset( $form_fields['fields']['pricing']['pricing_type'] ) ) {
            if ( $form_fields['fields']['pricing']['pricing_type'] == 'price_range' ) {
                $default_pricing_type = 'range';
            }
        }
        return apply_filters( 'directorist_default_pricing_type', $default_pricing_type, $listing_id );
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
        $allow_decimal = get_directorist_option( 'allow_decimal', 1 );
        $c_position    = directorist_get_currency_position();
        $currency      = directorist_get_currency();
        $symbol        = atbdp_currency_symbol( $currency );
        $before        = '';
        $after         = '';

        if ( 'after' == $c_position ) {
            $after = $symbol;
        } else {
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
        $args = array_merge(
            [
                'number'    => '',
                'whatsapp'  => false,
            ], $args 
        );

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
        } else {
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
                $result = get_user_meta( $user_id, 'address', true );
            break;

            case 'phone':
                $result = get_user_meta( $user_id, 'atbdp_phone', true );
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
                $result = get_user_meta( $user_id, 'atbdp_facebook', true );
            break;

            case 'twitter':
                $result = get_user_meta( $user_id, 'atbdp_twitter', true );
            break;

            case 'linkedin':
                $result = get_user_meta( $user_id, 'atbdp_linkedin', true );
            break;

            case 'youtube':
                $result = get_user_meta( $user_id, 'atbdp_youtube', true );
            break;
        }

        return $result;
    }

    public static function parse_video( $url ) {
        $embeddable_url = '';

        $is_youtube = preg_match( '/youtu\.be/i', $url ) || preg_match( '/youtube\.com\/watch/i', $url ) || preg_match( '/youtube\.com\/shorts/i', $url );
        if ( $is_youtube ) {
            $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(shorts\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
            preg_match( $pattern, $url, $matches );
            if ( count( $matches ) && strlen( $matches[8] ) == 11 ) {
                $embeddable_url = 'https://www.youtube.com/embed/' . $matches[8];
            }
        }

        $is_vimeo = preg_match( '/vimeo\.com/i', $url );
        if ( $is_vimeo ) {
            $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
            preg_match( $pattern, $url, $matches );
            if ( count( $matches ) ) {
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
        return ! empty( self::matched_badges( $listing_id ) );
    }

    public static function display_badge( $listing_id, $badge_key ) {
        $badge_key = self::normalize_badge_key( $badge_key );

        if ( empty( $listing_id ) || empty( $badge_key ) ) {
            return false;
        }

        $rule = self::get_badge_rule( $badge_key );

        if ( ! empty( $rule ) ) {
            $rule_result = self::badge_rule_matches( $listing_id, $rule );

            if ( null !== $rule_result ) {
                return (bool) $rule_result;
            }
        }

        if ( self::badge_key_is_custom( $badge_key ) ) {
            return self::badge_rule_has_no_conditions( $rule );
        }

        return self::legacy_badge_matches( $listing_id, $badge_key );
    }

    public static function matched_badges( $listing_id, $badge_keys = [] ) {
        if ( empty( $listing_id ) ) {
            return [];
        }

        $definitions = self::badge_definitions();
        $badge_keys  = ! empty( $badge_keys ) && is_array( $badge_keys ) ? $badge_keys : array_keys( $definitions );
        $matched     = [];

        foreach ( $badge_keys as $badge_key ) {
            $badge_key = self::normalize_badge_key( $badge_key );

            if ( empty( $badge_key ) || empty( $definitions[ $badge_key ] ) ) {
                continue;
            }

            if ( self::display_badge( $listing_id, $badge_key ) ) {
                $matched[ $badge_key ] = $definitions[ $badge_key ];
            }
        }

        return $matched;
    }

    public static function is_new( $listing_id ) {
        $post = get_post( $listing_id ); // @cache @kowsar
        $new_listing_time = get_directorist_option( 'new_listing_day' );
        $each_hours = 60 * 60 * 24;
        $s_date1 = strtotime( current_time( 'mysql' ) );
        $s_date2 = strtotime( $post->post_date );
        $s_date_diff = abs( $s_date1 - $s_date2 );
        $days = round( $s_date_diff / $each_hours );

        if ( $days <= (int) $new_listing_time ) {
            return true;
        } else {
            return false;
        }
    }

    public static function multi_directory_enabled() {
        return directorist_is_multi_directory_enabled();
    }

    public static function default_preview_image_src( $directory_id ) {
        $settings = directorist_get_directory_general_settings( $directory_id );

        if ( ! empty( $settings['preview_image'] ) ) {
            $default_preview = $settings['preview_image'];
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

    public static function badge_definitions() {
        $rules       = self::badge_rules();
        $rule_badges = ! empty( $rules['badges'] ) && is_array( $rules['badges'] ) ? $rules['badges'] : [];
        $definitions = [];

        foreach ( self::core_badge_defaults() as $badge_key => $default ) {
            $definitions[ $badge_key ] = self::normalize_badge_definition(
                $badge_key,
                ! empty( $rule_badges[ $badge_key ] ) && is_array( $rule_badges[ $badge_key ] ) ? $rule_badges[ $badge_key ] : [],
                $default
            );
        }

        foreach ( $rule_badges as $badge_key => $rule ) {
            $badge_key = self::normalize_badge_key( $badge_key );

            if ( empty( $badge_key ) || ! self::badge_key_is_custom( $badge_key ) || ! is_array( $rule ) ) {
                continue;
            }

            $definitions[ $badge_key ] = self::normalize_badge_definition(
                $badge_key,
                $rule,
                [
                    'internalName' => __( 'Custom badge', 'directorist' ),
                    'label'        => __( 'Badge', 'directorist' ),
                    'type'         => self::default_badge_type(),
                    'icon'         => self::default_badge_icon(),
	                    'style'        => [
	                        'bg'     => '#3e62f5',
	                        'text'   => '#ffffff',
	                        'border' => '#3e62f5',
	                    ],
	                    'hover'        => [
	                        'text'      => '',
	                        'bg'        => '',
	                        'textColor' => '',
	                    ],
	                ]
	            );
	        }

        return apply_filters( 'directorist_badge_definitions', $definitions, $rules );
    }

	public static function custom_badge_definitions() {
		return array_filter(
			self::badge_definitions(),
			static function( $badge ) {
				return ! empty( $badge['key'] ) && self::badge_key_is_custom( $badge['key'] );
			}
		);
	}

	public static function normalize_badge_rules_setting_value( $rules ) {
		if ( empty( $rules ) ) {
			return $rules;
		}

		$raw_rules = $rules;
		$rules     = self::maybe_json( $rules, true );

		if ( empty( $rules['badges'] ) || ! is_array( $rules['badges'] ) ) {
			return $raw_rules;
		}

		foreach ( [ 'new', 'popular', 'featured' ] as $badge_key ) {
			if ( empty( $rules['badges'][ $badge_key ] ) || ! is_array( $rules['badges'][ $badge_key ] ) ) {
				continue;
			}

			if ( self::core_badge_uses_global_type( $badge_key, $rules['badges'][ $badge_key ] ) ) {
				$rules['badges'][ $badge_key ]['type'] = self::default_badge_type();
			}
		}

		foreach ( [ 'new', 'popular' ] as $badge_key ) {
			if ( empty( $rules['badges'][ $badge_key ] ) || ! is_array( $rules['badges'][ $badge_key ] ) ) {
				continue;
			}

			$icon = isset( $rules['badges'][ $badge_key ]['icon'] ) ? $rules['badges'][ $badge_key ]['icon'] : '';

			if ( self::core_badge_uses_unedited_star_fallback( $badge_key, $rules['badges'][ $badge_key ], $icon ) ) {
				$rules['badges'][ $badge_key ]['icon'] = self::default_badge_icon( $badge_key );
			}
		}

		return is_string( $raw_rules ) ? wp_json_encode( $rules ) : $rules;
	}

	public static function badge_definition( $badge_key ) {
        $badge_key   = self::normalize_badge_key( $badge_key );
        $definitions = self::badge_definitions();

        return ! empty( $definitions[ $badge_key ] ) ? $definitions[ $badge_key ] : [];
    }

    public static function badge_template_data( $badge_key, $field = [] ) {
        $definition = self::badge_definition( $badge_key );

        if ( empty( $definition ) ) {
            return [];
        }

        $field['badge_key']          = $definition['key'];
        $field['class']              = $definition['class'];
        $field['icon']               = $definition['icon'];
        $field['tooltip_class']      = 'directorist-badge-tooltip__' . $definition['class'];
        $field['tooltip_label']      = ! empty( $definition['hover']['text'] ) ? $definition['hover']['text'] : $definition['label'];
        $field['label']              = $definition['label'];
		$field['badge_display_type'] = 'icon' === $definition['type'] ? 'icon_badge' : 'text_badge';
		$field['badge_show_icon']    = self::badge_should_show_icon( $definition );
		$field['badge_show_dot']     = false;
		$field['badge_text_class']   = self::badge_template_classes( $field );
		$field['badge_style_attr']   = self::badge_style_attr( $definition );
		$field['badge_icon_color']   = ! empty( $definition['style']['text'] ) ? $definition['style']['text'] : '#ffffff';
		$field['badge_icon_html']    = $field['badge_show_icon'] ? self::badge_icon_markup( $definition['icon'], $definition ) : '';
		if ( ! empty( $field['badge_show_icon'] ) && empty( $field['badge_icon_html'] ) ) {
			$field['badge_show_icon'] = false;
			$field['badge_text_class'] = self::badge_template_classes( $field );
		}
		$field['badge_dot_html']     = '';
		$field['badge_marker_html']  = $field['badge_icon_html'];

        if ( 'featured' === $definition['key'] ) {
            $field['featured_badge_type'] = $field['badge_display_type'];
        }

        if ( 'new' === $definition['key'] ) {
            $field['new_badge_type']  = $field['badge_display_type'];
            $field['new_badge_class'] = $field['badge_text_class'];
        }

        return apply_filters( 'directorist_badge_template_data', $field, $definition );
    }

	private static function badge_should_show_icon( $badge ) {
		if ( ! empty( $badge['type'] ) && 'icon' === $badge['type'] ) {
			return true;
		}

		return ! empty( $badge['icon'] );
	}

	private static function badge_template_classes( $field ) {
		$classes = [];

		if ( ! empty( $field['badge_display_type'] ) && 'text_badge' === $field['badge_display_type'] ) {
			$classes[] = 'directorist-badge--only-text';
			if ( ! empty( $field['badge_show_icon'] ) ) {
				$classes[] = 'directorist-badge--has-icon';
			}
		} else {
			$classes[] = 'directorist-badge--icon-only';
			$classes[] = 'directorist-badge--has-icon';
		}

		return implode( ' ', $classes );
	}

	public static function badge_style_attr( $badge ) {
        if ( empty( $badge['style'] ) || ! is_array( $badge['style'] ) ) {
            return '';
        }

        $background = ! empty( $badge['style']['bg'] ) ? sanitize_hex_color( $badge['style']['bg'] ) : '';
        $text       = ! empty( $badge['style']['text'] ) ? sanitize_hex_color( $badge['style']['text'] ) : '';
        $border     = ! empty( $badge['style']['border'] ) ? sanitize_hex_color( $badge['style']['border'] ) : '';

        if ( empty( $background ) ) {
            return '';
        }

        $text   = ! empty( $text ) ? $text : '#ffffff';
        $border = ! empty( $border ) ? $border : $background;

		$hover_bg         = ! empty( $badge['hover']['bg'] ) ? sanitize_hex_color( $badge['hover']['bg'] ) : $background;
		$hover_text_color = ! empty( $badge['hover']['textColor'] ) ? sanitize_hex_color( $badge['hover']['textColor'] ) : $text;

		return sprintf(
			'--directorist-badge-bg:%1$s;--directorist-badge-border:%2$s;--directorist-badge-color:%3$s;--directorist-badge-icon-color:%3$s;--directorist-badge-tooltip-bg:%4$s;--directorist-badge-tooltip-color:%5$s;',
			esc_attr( $background ),
			esc_attr( $border ),
			esc_attr( $text ),
			esc_attr( $hover_bg ? $hover_bg : $background ),
			esc_attr( $hover_text_color ? $hover_text_color : $text )
		);
	}

	public static function badge_icon_markup( $icon, $badge = [] ) {
		$icon = self::sanitize_badge_icon( $icon );

		if ( empty( $icon ) ) {
			return '';
		}

		$icon_src = self::get_icon_src( $icon );

		if ( empty( $icon_src ) ) {
			return '';
		}

		$style = sprintf( '--directorist-icon:url(%1$s);', esc_url( $icon_src ) );
		$color = '';

		if ( ! empty( $badge['style']['text'] ) ) {
			$color = sanitize_hex_color( $badge['style']['text'] );
		} elseif ( ! empty( $badge['badge_icon_color'] ) ) {
			$color = sanitize_hex_color( $badge['badge_icon_color'] );
		}

		if ( $color ) {
			$style .= sprintf( '--directorist-badge-icon-color:%1$s;color:%1$s;', esc_attr( $color ) );
		}

		return sprintf(
			'<i class="directorist-icon-mask directorist-badge-icon-mask" aria-hidden="true" style="%1$s"></i>',
			esc_attr( $style )
		);
	}

    public static function badge_builder_widgets() {
        $widgets = [];

        foreach ( self::badge_definitions() as $badge_key => $badge ) {
            $widget_key = self::badge_widget_key( $badge_key );

            if ( empty( $widget_key ) ) {
                continue;
            }

            $widgets[ $widget_key ] = [
                'type'        => 'badge',
                'label'       => ! empty( $badge['internalName'] ) ? $badge['internalName'] : $badge['label'],
                'icon'        => ! empty( $badge['icon'] ) ? $badge['icon'] : '',
                'hook'        => 'atbdp_' . str_replace( '-', '_', $badge['class'] ) . '_badge',
                'widget_name' => $widget_key,
                'widget_key'  => $widget_key,
            ];
        }

        return apply_filters( 'directorist_badge_builder_widgets', $widgets );
    }

    public static function badge_widget_key( $badge_key ) {
        $badge_key = self::normalize_badge_key( $badge_key );

        switch ( $badge_key ) {
            case 'new':
                return 'new_badge';

            case 'popular':
                return 'popular_badge';

            case 'featured':
                return 'featured_badge';
        }

        return self::badge_key_is_custom( $badge_key ) ? $badge_key : '';
    }

    public static function badge_key_from_widget_key( $widget_key ) {
        return self::normalize_badge_key( $widget_key );
    }

    private static function get_badge_rule( $badge_key ) {
        $rules = self::badge_rules();

        if ( empty( $rules['badges'] ) || ! is_array( $rules['badges'] ) ) {
            return [];
        }

        if ( empty( $rules['badges'][ $badge_key ] ) || ! is_array( $rules['badges'][ $badge_key ] ) ) {
            return [];
        }

        return $rules['badges'][ $badge_key ];
    }

    private static function badge_rules() {
        $rules = get_directorist_option( 'directorist_badge_rules', [] );

        if ( empty( $rules ) ) {
            return [];
        }

        $rules = self::maybe_json( $rules, true );

        return is_array( $rules ) ? $rules : [];
    }

    private static function core_badge_defaults() {
        $type            = self::default_badge_type();
        $new_label       = self::new_badge_text();
        $popular_label   = self::popular_badge_text();
        $featured_label  = self::featured_badge_text();
        $new_color       = get_directorist_option( 'new_back_color', '#2C99FF' );
        $popular_color   = get_directorist_option( 'popular_back_color', '#f51957' );
        $featured_color  = get_directorist_option( 'featured_back_color', '#fa8b0c' );

        return [
            'new'      => [
                'internalName' => $new_label,
                'label'        => $new_label,
                'type'         => $type,
                'icon'         => self::default_badge_icon( 'new' ),
	                'style'        => [
	                    'bg'     => $new_color,
	                    'text'   => '#ffffff',
	                    'border' => $new_color,
	                ],
	                'hover'        => [
	                    'text'      => '',
	                    'bg'        => '',
	                    'textColor' => '',
	                ],
	            ],
            'popular'  => [
                'internalName' => $popular_label,
                'label'        => $popular_label,
                'type'         => $type,
                'icon'         => self::default_badge_icon( 'popular' ),
	                'style'        => [
	                    'bg'     => $popular_color,
	                    'text'   => '#ffffff',
	                    'border' => $popular_color,
	                ],
	                'hover'        => [
	                    'text'      => '',
	                    'bg'        => '',
	                    'textColor' => '',
	                ],
	            ],
            'featured' => [
                'internalName' => $featured_label,
                'label'        => $featured_label,
                'type'         => $type,
                'icon'         => self::default_badge_icon( 'featured' ),
	                'style'        => [
	                    'bg'     => $featured_color,
	                    'text'   => '#ffffff',
	                    'border' => $featured_color,
	                ],
	                'hover'        => [
	                    'text'      => '',
	                    'bg'        => '',
	                    'textColor' => '',
	                ],
	            ],
        ];
    }

    private static function default_badge_type() {
        return 'icon_badge' === get_directorist_option( 'badge_display_type', 'text_badge' ) ? 'icon' : 'text';
    }

    private static function normalize_badge_definition( $badge_key, $rule, $fallback ) {
        $fallback_style = ! empty( $fallback['style'] ) && is_array( $fallback['style'] ) ? $fallback['style'] : [];
        $rule_style     = ! empty( $rule['style'] ) && is_array( $rule['style'] ) ? $rule['style'] : [];
        $background     = self::normalize_badge_color(
            isset( $rule_style['bg'] ) ? $rule_style['bg'] : ( isset( $rule['color'] ) ? $rule['color'] : '' ),
            ! empty( $fallback_style['bg'] ) ? $fallback_style['bg'] : '#3e62f5'
        );
        $text_color     = self::normalize_badge_color(
            isset( $rule_style['text'] ) ? $rule_style['text'] : '',
            ! empty( $fallback_style['text'] ) ? $fallback_style['text'] : '#ffffff'
        );
        $border_color   = self::normalize_badge_color(
            isset( $rule_style['border'] ) ? $rule_style['border'] : '',
            ! empty( $fallback_style['border'] ) ? $fallback_style['border'] : $background
        );
        $label          = self::sanitize_badge_label(
            isset( $rule['label'] ) ? $rule['label'] : '',
            ! empty( $fallback['label'] ) ? $fallback['label'] : __( 'Badge', 'directorist' )
        );
        $internal_name  = self::sanitize_badge_label(
            isset( $rule['internalName'] ) ? $rule['internalName'] : ( isset( $rule['internal_name'] ) ? $rule['internal_name'] : '' ),
            ! empty( $fallback['internalName'] ) ? $fallback['internalName'] : $label
        );
		$type           = self::normalize_badge_definition_type( $badge_key, $rule, ! empty( $fallback['type'] ) ? $fallback['type'] : 'text' );

        $hover = self::normalize_badge_hover(
            isset( $rule['hover'] ) && is_array( $rule['hover'] ) ? $rule['hover'] : [],
            ! empty( $fallback['hover'] ) && is_array( $fallback['hover'] ) ? $fallback['hover'] : [],
            $label,
            $background,
            $text_color
        );

        return [
			'key'          => $badge_key,
			'class'        => self::badge_class_name( $badge_key ),
			'enabled'      => ! array_key_exists( 'enabled', $rule ) || self::badge_bool_value( $rule['enabled'] ),
			'internalName' => $internal_name,
			'label'        => $label,
			'type'         => $type,
			'icon'         => self::normalize_badge_definition_icon( $badge_key, $rule, ! empty( $fallback['icon'] ) ? $fallback['icon'] : self::default_badge_icon( $badge_key ), $type ),
			'style'        => [
				'bg'     => $background,
				'text'   => $text_color,
                'border' => $border_color ? $border_color : $background,
            ],
            'hover'        => $hover,
        ];
    }

    private static function normalize_badge_hover( $hover, $fallback_hover, $label, $background, $text_color ) {
        $fallback_text       = ! empty( $fallback_hover['text'] ) ? $fallback_hover['text'] : $label;
        $fallback_bg         = ! empty( $fallback_hover['bg'] ) ? $fallback_hover['bg'] : $background;
        $fallback_text_color = ! empty( $fallback_hover['textColor'] ) ? $fallback_hover['textColor'] : $text_color;

        return [
            'text'      => self::sanitize_badge_label(
                isset( $hover['text'] ) ? $hover['text'] : '',
                $fallback_text
            ),
            'bg'        => self::normalize_badge_color(
                isset( $hover['bg'] ) ? $hover['bg'] : '',
                $fallback_bg
            ),
            'textColor' => self::normalize_badge_color(
                isset( $hover['textColor'] ) ? $hover['textColor'] : ( isset( $hover['text_color'] ) ? $hover['text_color'] : '' ),
                $fallback_text_color
            ),
        ];
    }

    private static function normalize_badge_color( $color, $fallback ) {
        $color = sanitize_hex_color( $color );

        if ( $color ) {
            return $color;
        }

        $fallback = sanitize_hex_color( $fallback );

        return $fallback ? $fallback : '#3e62f5';
    }

    private static function sanitize_badge_label( $value, $fallback ) {
        $value = trim( wp_strip_all_tags( (string) $value ) );

        return '' !== $value ? $value : $fallback;
    }

    private static function sanitize_badge_icon( $icon ) {
        $icon = trim( wp_strip_all_tags( (string) $icon ) );

        return '' !== $icon ? $icon : '';
    }

	private static function normalize_badge_icon( $icon, $fallback = '' ) {
		$icon     = self::sanitize_badge_icon( $icon );
		$fallback = self::sanitize_badge_icon( $fallback );
		$default  = self::default_badge_icon();

        if ( self::badge_icon_is_supported( $icon ) ) {
            return $icon;
        }

        if ( self::badge_icon_is_supported( $fallback ) ) {
            return $fallback;
        }

		return $default;
	}

	private static function normalize_badge_definition_icon( $badge_key, $rule, $fallback, $type ) {
		$icon = isset( $rule['icon'] ) ? $rule['icon'] : '';

		if ( self::core_badge_uses_unedited_star_fallback( $badge_key, $rule, $icon ) ) {
			$icon = '';
		}

		$icon = self::sanitize_badge_icon( $icon );

		if ( empty( $icon ) && 'text' === self::normalize_badge_type( $type ) && self::badge_icon_was_edited( $rule ) ) {
			return '';
		}

		return self::normalize_badge_icon( $icon, $fallback );
	}

	private static function badge_icon_was_edited( $rule ) {
		return is_array( $rule ) && ( ! empty( $rule['iconEdited'] ) || ! empty( $rule['icon_edited'] ) );
	}

	private static function core_badge_uses_unedited_star_fallback( $badge_key, $rule, $icon ) {
		if ( ! in_array( $badge_key, [ 'new', 'popular' ], true ) || ! is_array( $rule ) || empty( $icon ) ) {
			return false;
		}

		if ( ! empty( $rule['iconEdited'] ) || ! empty( $rule['icon_edited'] ) ) {
			return false;
		}

		return in_array( self::sanitize_badge_icon( $icon ), self::legacy_generic_star_icons(), true );
	}

    private static function badge_icon_is_supported( $icon ) {
        if ( empty( $icon ) ) {
            return false;
        }

        $icon_src = self::get_icon_src( $icon );

        if ( empty( $icon_src ) ) {
            return false;
        }

        $icon_base_url = ATBDP_URL . 'assets/icons/';

        if ( 0 === strpos( $icon_src, $icon_base_url ) ) {
            $relative_icon_path = ltrim( substr( $icon_src, strlen( $icon_base_url ) ), '/' );

            return file_exists( DIRECTORIST_ICON_PATH . $relative_icon_path );
        }

        return true;
    }

	private static function default_badge_icons() {
		return [
			'new'      => 'la la-bolt',
			'popular'  => 'la la-fire',
			'featured' => 'la la-star-o',
		];
	}

	private static function legacy_generic_star_icons() {
		return [
			'la la-star-o',
			'las la-star',
			'far fa-star',
			'fas fa-star',
		];
	}

    private static function default_badge_icon( $badge_key = '' ) {
        $badge_key = self::normalize_badge_key( $badge_key );
        $icons     = self::default_badge_icons();

        return ! empty( $icons[ $badge_key ] ) ? $icons[ $badge_key ] : 'la la-certificate';
    }

	private static function normalize_badge_type( $type ) {
		return in_array( $type, [ 'icon', 'icon_badge' ], true ) ? 'icon' : 'text';
	}

	private static function normalize_badge_definition_type( $badge_key, $rule, $fallback ) {
		if ( self::core_badge_uses_global_type( $badge_key, $rule ) ) {
			return self::normalize_badge_type( $fallback );
		}

		return self::normalize_badge_type( isset( $rule['type'] ) ? $rule['type'] : $fallback );
	}

	private static function core_badge_uses_global_type( $badge_key, $rule ) {
		if ( ! in_array( $badge_key, [ 'new', 'popular', 'featured' ], true ) || ! is_array( $rule ) ) {
			return false;
		}

		return empty( $rule['typeEdited'] ) && empty( $rule['type_edited'] );
	}

    private static function badge_class_name( $badge_key ) {
        if ( in_array( $badge_key, [ 'new', 'popular', 'featured' ], true ) ) {
            return $badge_key;
        }

        $class = preg_replace( '/^custom_badge_/', 'custom-', $badge_key );
        $class = str_replace( '_', '-', $class );

        return sanitize_html_class( $class ? $class : 'custom-badge' );
    }

    private static function badge_rule_matches( $listing_id, $rule ) {
        if ( ! is_array( $rule ) ) {
            return null;
        }

        if ( array_key_exists( 'enabled', $rule ) && ! self::badge_bool_value( $rule['enabled'] ) ) {
            return false;
        }

        if ( empty( $rule['conditions'] ) || ! is_array( $rule['conditions'] ) ) {
            return null;
        }

        $match = ( ! empty( $rule['match'] ) && 'any' === $rule['match'] ) ? 'any' : 'all';
        $has_valid_condition = false;

        foreach ( $rule['conditions'] as $condition ) {
            $condition_result = self::badge_condition_matches( $listing_id, $condition );

            if ( null === $condition_result ) {
                return null;
            }

            $has_valid_condition = true;

            if ( 'any' === $match && $condition_result ) {
                return true;
            }

            if ( 'all' === $match && ! $condition_result ) {
                return false;
            }
        }

        return $has_valid_condition ? 'all' === $match : null;
    }

    private static function badge_rule_has_no_conditions( $rule ) {
        if ( ! is_array( $rule ) ) {
            return false;
        }

        if ( ! array_key_exists( 'conditions', $rule ) ) {
            return true;
        }

        return is_array( $rule['conditions'] ) && empty( $rule['conditions'] );
    }

    private static function badge_condition_matches( $listing_id, $condition ) {
        if ( ! is_array( $condition ) ) {
            return null;
        }

        $source = ! empty( $condition['source'] ) ? sanitize_key( $condition['source'] ) : '';

        $key = ! empty( $condition['key'] ) ? sanitize_key( $condition['key'] ) : '';
        $operator = ! empty( $condition['operator'] ) ? trim( (string) $condition['operator'] ) : '=';
        $expected = array_key_exists( 'value', $condition ) ? $condition['value'] : '';
        $actual = self::badge_condition_value( $listing_id, $source, $key );

        if ( null === $actual ) {
            return null;
        }

        return self::badge_compare_condition( $actual, $operator, $expected );
    }

    private static function badge_condition_value( $listing_id, $source, $key ) {
        switch ( $source ) {
            case 'general':
                return self::badge_general_condition_value( $listing_id, $key );

            case 'field':
                return self::badge_field_condition_value( $listing_id, $key );

            case 'pricing':
                return self::badge_pricing_condition_value( $listing_id, $key );
        }

        return null;
    }

    private static function badge_general_condition_value( $listing_id, $key ) {
        $value = null;

        switch ( $key ) {
            case 'age_days':
                $value = self::listing_age_days( $listing_id );
                break;

            case 'view_count':
                $value = (int) directorist_get_listing_views_count( $listing_id );
                break;

            case 'average_rating':
                $value = (float) directorist_get_listing_rating( $listing_id );
                break;

            case 'review_count':
                if ( function_exists( 'directorist_get_listing_review_count' ) ) {
                    $value = (int) directorist_get_listing_review_count( $listing_id );
                    break;
                }

                $value = 0;
                break;

            case 'is_featured':
                $value = (bool) get_post_meta( $listing_id, '_featured', true );
                break;

            case 'listing_status':
                $value = get_post_status( $listing_id );
                break;
        }

        return apply_filters( 'directorist_badge_rule_general_condition_value', $value, $listing_id, $key );
    }

    private static function badge_field_condition_value( $listing_id, $field_key ) {
        if ( empty( $field_key ) ) {
            return null;
        }

        $value = get_post_meta( $listing_id, '_' . $field_key, true );

        if ( '' === $value || null === $value ) {
            $value = get_post_meta( $listing_id, $field_key, true );
        }

        return apply_filters( 'directorist_badge_rule_field_value', $value, $listing_id, $field_key );
    }

    private static function badge_pricing_condition_value( $listing_id, $key ) {
        $plan_ids = self::listing_pricing_plan_ids( $listing_id );

        switch ( $key ) {
            case 'has_plan':
                return ! empty( $plan_ids );

            case 'plan_id':
                return $plan_ids;
        }

        return null;
    }

    private static function listing_pricing_plan_ids( $listing_id ) {
        $meta_keys = apply_filters(
            'directorist_badge_rule_pricing_plan_meta_keys',
            [ '_fm_plans' ],
            $listing_id
        );
        $plan_ids = [];

        foreach ( $meta_keys as $meta_key ) {
            $plan_ids = array_merge( $plan_ids, self::normalize_badge_rule_ids( get_post_meta( $listing_id, $meta_key, true ) ) );
        }

        $plan_ids = array_values( array_unique( array_filter( array_map( 'absint', $plan_ids ) ) ) );

        return apply_filters( 'directorist_badge_rule_pricing_plan_ids', $plan_ids, $listing_id );
    }

    private static function normalize_badge_rule_ids( $value ) {
        if ( empty( $value ) ) {
            return [];
        }

        if ( is_string( $value ) ) {
            $maybe_json = self::maybe_json( $value );

            if ( is_array( $maybe_json ) ) {
                $value = $maybe_json;
            } elseif ( false !== strpos( $value, ',' ) ) {
                $value = explode( ',', $value );
            }
        }

        if ( ! is_array( $value ) ) {
            $value = [ $value ];
        }

        return $value;
    }

    private static function badge_compare_condition( $actual, $operator, $expected ) {
        if ( is_array( $actual ) ) {
            return self::badge_compare_array_condition( $actual, $operator, $expected );
        }

        if ( is_array( $expected ) ) {
            return self::badge_compare_scalar_to_array_condition( $actual, $operator, $expected );
        }

        if ( is_bool( $actual ) || is_bool( $expected ) ) {
            $actual = self::badge_bool_value( $actual );
            $expected = self::badge_bool_value( $expected );
        }

        switch ( $operator ) {
            case 'is':
            case '=':
            case '==':
            case 'equals':
                return $actual == $expected;

            case '!=':
            case 'is_not':
            case 'not_equals':
                return $actual != $expected;

            case '>':
                return (float) $actual > (float) $expected;

            case '>=':
                return (float) $actual >= (float) $expected;

            case '<':
                return (float) $actual < (float) $expected;

            case '<=':
                return (float) $actual <= (float) $expected;

            case 'contains':
                if ( '' === (string) $expected ) {
                    return false;
                }

                return false !== strpos( (string) $actual, (string) $expected );

            case 'not_contains':
                if ( '' === (string) $expected ) {
                    return false;
                }

                return false === strpos( (string) $actual, (string) $expected );

            case 'is_empty':
                return '' === trim( (string) $actual );

            case 'is_not_empty':
                return '' !== trim( (string) $actual );
        }

        return null;
    }

    private static function badge_compare_scalar_to_array_condition( $actual, $operator, $expected ) {
        $actual = (string) $actual;
        $expected = array_values(
            array_filter(
                array_map( 'strval', $expected ),
                static function( $value ) {
                    return '' !== $value;
                }
            )
        );

        if ( empty( $expected ) ) {
            return false;
        }

        $matched = in_array( $actual, $expected, true );

        switch ( $operator ) {
            case 'is':
            case '=':
            case '==':
            case 'equals':
                return $matched;

            case '!=':
            case 'is_not':
            case 'not_equals':
                return ! $matched;

            case 'contains':
                foreach ( $expected as $value ) {
                    if ( false !== strpos( $actual, $value ) ) {
                        return true;
                    }
                }

                return false;

            case 'not_contains':
                foreach ( $expected as $value ) {
                    if ( false !== strpos( $actual, $value ) ) {
                        return false;
                    }
                }

                return true;

            case 'is_empty':
                return '' === trim( $actual );

            case 'is_not_empty':
                return '' !== trim( $actual );
        }

        return null;
    }

    private static function badge_compare_array_condition( $actual, $operator, $expected ) {
        $actual = array_map( 'strval', $actual );

        if ( is_array( $expected ) ) {
            $expected = array_map( 'strval', $expected );
        } else {
            $expected = [ (string) $expected ];
        }

        $expected = array_values(
            array_filter(
                $expected,
                static function( $value ) {
                    return '' !== $value;
                }
            )
        );

        if ( empty( $expected ) ) {
            return false;
        }

        $matched = (bool) array_intersect( $actual, $expected );

        switch ( $operator ) {
            case 'is':
            case '=':
            case '==':
            case 'equals':
            case 'contains':
                return $matched;

            case '!=':
            case 'is_not':
            case 'not_equals':
            case 'not_contains':
                return ! $matched;

            case 'is_empty':
                return empty( $actual );

            case 'is_not_empty':
                return ! empty( $actual );
        }

        return null;
    }

    private static function listing_age_days( $listing_id ) {
        $post = get_post( $listing_id ); // @cache @kowsar

        if ( ! $post ) {
            return null;
        }

        $each_hours = 60 * 60 * 24;
        $s_date1 = strtotime( current_time( 'mysql' ) );
        $s_date2 = strtotime( $post->post_date );
        $s_date_diff = abs( $s_date1 - $s_date2 );

        return (int) round( $s_date_diff / $each_hours );
    }

    private static function badge_bool_value( $value ) {
        if ( is_bool( $value ) ) {
            return $value;
        }

        if ( is_numeric( $value ) ) {
            return (bool) (int) $value;
        }

        return in_array( strtolower( trim( (string) $value ) ), [ '1', 'true', 'yes', 'on' ], true );
    }

    private static function normalize_badge_key( $badge_key ) {
        $badge_key = sanitize_key( $badge_key );

        if ( 'new_badge' === $badge_key ) {
            return 'new';
        }

        if ( 'popular_badge' === $badge_key ) {
            return 'popular';
        }

        if ( 'featured_badge' === $badge_key || 'feature_badge' === $badge_key ) {
            return 'featured';
        }

        if ( self::badge_key_is_custom( $badge_key ) ) {
            return $badge_key;
        }

        return in_array( $badge_key, [ 'new', 'popular', 'featured' ], true ) ? $badge_key : '';
    }

    private static function badge_key_is_custom( $badge_key ) {
        return (bool) preg_match( '/^custom_badge_[a-z0-9_]+$/', sanitize_key( $badge_key ) );
    }

    private static function legacy_badge_matches( $listing_id, $badge_key ) {
        switch ( $badge_key ) {
            case 'new':
                return self::is_new( $listing_id );

            case 'popular':
                return self::is_popular( $listing_id );

            case 'featured':
                return (bool) self::is_featured( $listing_id );
        }

        return false;
    }

    public static function new_badge_text() {
        return get_directorist_option( 'new_badge_text', 'New' );
    }

    public static function popular_badge_text() {
        return get_directorist_option( 'popular_badge_text', 'Popular' );
    }

    public static function featured_badge_text() {
        return get_directorist_option( 'feature_badge_text', 'Featured' );
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
        $args = [
            'meta_query' => [
                'page_enabled' => [
                    'key'     => 'enable_single_listing_page',
                    'compare' => '=',
                    'value'   => 1,
                ],
            ],
        ];

        $directory_types = directorist_get_directories( $args );
        if ( empty( $directory_types ) || is_wp_error( $directory_types ) ) {
            return [];
        }

        $directory_types = array_filter(
            $directory_types, static function( $directory_type ) use ( $page_id ) {
                $selected_page_id = (int) get_term_meta( $directory_type->term_id, 'single_listing_page', true );

                if ( is_null( $page_id ) ) {
                    return $selected_page_id;
                }

                return ( $selected_page_id === (int) $page_id );
            } 
        );

        return $directory_types;
    }

    public static function builder_selected_single_pages() {
        // @cache @kowsar
        $pages = [];

        $types = get_terms(
            [
                'taxonomy'   => 'atbdp_listing_types',
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'single_listing_page',
                        'compare' => 'EXISTS',
                    ],
                ],
            ] 
        );

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

        if ( empty( $data ) ) {
            return; }

        $data_value = base64_encode( wp_json_encode( $data ) );
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

        return $d && $d->format( $format ) === $date;
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

    public static function install_plugin( $slug ): bool {
        if ( self::is_plugin_installed( $slug ) ) {
            return true;
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        
        WP_Filesystem();
    
        $api = plugins_api( 'plugin_information', [ 'slug' => $slug, 'fields' => [ 'sections' => false ] ] );
        
        if ( is_wp_error( $api ) ) {
            throw new Exception( $api->get_error_message(), $api->get_error_code() );
        }
    
        $upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
        $result   = $upgrader->install( $api->download_link );
    
        if ( is_wp_error( $result ) ) {
            throw new Exception( $result->get_error_message(), $result->get_error_code() );
        }

        return true;
    }

    public static function activate_plugin( string $slug ): bool {
        if ( ! self::is_plugin_installed( $slug ) ) {
            throw new Exception( esc_html__( 'The plugin is not installed.', 'directorist' ), 404 );
        }
        
        if ( self::is_the_plugin_active( $slug ) ) {
            return true;
        }
        
        activate_plugin( "{$slug}/{$slug}.php" );
        return true;
    }

    public static function is_the_plugin_active( string $slug ): bool {
        if ( ! function_exists( '\is_plugin_active' ) ) {
            return false;
        }

        return \is_plugin_active( "{$slug}/{$slug}.php" );
    }

    public static function is_plugin_installed( $slug ): bool {
        return file_exists( WP_PLUGIN_DIR . "/{$slug}/{$slug}.php" );
    }
}
