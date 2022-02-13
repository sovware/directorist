<?php
/**
 * Handles Listing archive page
 *
 * @package wpWax\Directorist\Model
 * @author  wpWax
 */

namespace wpWax\Directorist\Model;

use Directorist\Helper;
use ATBDP_Listings_Data_Store;
use ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Listings class
 *
 * @since 7.1.0
 */
class Listings {

	/**
	 * Load deprecated functions.
	 */
	use Deprecated_Listings;

	/**
	 * Singleton instance of the class.
	 *
	 * @var Listings|null
	 */
	protected static $instance = null;

	/**
	 * Current field inside loop
	 *
	 * @var string
	 */
	public static $current_field = '';

	/**
	 * Options from settings panel.
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	public $atts;

	/**
	 * Type of archive, possible values: listing, search_result.
	 *
	 * @var string
	 */
	public $type;

	/**
	 * WP_Query object for Listing post type.
	 *
	 * @var object
	 */
	public $query;

	/**
	 * Private Constructor of Singleton.
	 */
	private function __construct() {

	}

	/**
	 * Singleton instance.
	 *
	 * @return object Listings instance.
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initialize Listings
	 *
	 * @param  array   $atts     Shortcode attributes.
	 * @param  string  $type     Optional. Determines All listings page or Search result page.
	 *                           Accepts 'listing', 'search_result'. Defaults to ''.
	 * @param  bool $query_args  Optional. Custom args for listing query. Defaults to false.
	 */
	public function init( $atts = array(), $type = '', $query_args = false ) {
		$this->set_page_type( $type );
		$this->set_options();

		if ( 'search_result' == $this->type ) {
			$this->update_search_options();
		}

		$this->set_atts( $atts );
		$this->set_query( $query_args );
	}

	/**
	 * Set page type.
	 *
	 * @uses atbdp_is_page()
	 *
	 * @param string $type Accepts 'listing', 'search_result'.
	 */
	public function set_page_type( $type ) {
		if ( !$type ) {
			if ( atbdp_is_page('search_result') ) {
				$this->type = 'search_result';
			}
			else {
				$this->type = 'listing';
			}
		}
		else {
			$this->type = $type;
		}
	}

	/**
	 * Set shortcode attributes.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function set_atts( $atts ) {
		$defaults = array(
			'view'                     => get_directorist_option( 'default_listing_view', 'grid' ),
			'orderby'                  => $this->options['order_listing_by'],
			'order'                    => $this->options['sort_listing_by'],
			'listings_per_page'        => $this->options['listings_per_page'],
			'show_pagination'          => $this->options['paginate_listings'],
			'header'                   => $this->options['display_listings_header'],
			'header_title'             => get_directorist_option( 'all_listing_title', __( 'Items Found', 'directorist' ) ),
			'category'                 => '',
			'location'                 => '',
			'tag'                      => '',
			'ids'                      => '',
			'columns'                  => $this->options['listing_columns'],
			'featured_only'            => '',
			'popular_only'             => '',
			'display_preview_image'    => 'yes',
			'advanced_filter'          => $this->options['listing_filters_button'],
			'logged_in_user_only'      => '',
			'redirect_page_url'        => '',
			'map_height'               => get_directorist_option( 'listings_map_height', 350 ),
			'map_zoom_level'		   => get_directorist_option( 'map_view_zoom_level', 16 ),
			'directory_type'	       => '',
			'default_directory_type'   => ''
		);

		$defaults  = apply_filters( 'atbdp_all_listings_params', $defaults );
		$this->atts = shortcode_atts( $defaults, $atts );
	}

	/**
	 * Set options based on listing settings.
	 */
	public function set_options() {
		$this->options['order_listing_by']                = apply_filters( 'atbdp_default_listing_orderby', get_directorist_option( 'order_listing_by', 'date' ) );
		$this->options['sort_listing_by']                 = get_directorist_option( 'sort_listing_by', 'asc' );
		$this->options['listings_per_page']               = get_directorist_option( 'all_listing_page_items', 6 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_all_listings', 1 ) ) ? 'yes' : '';
		$this->options['listing_columns']                 = get_directorist_option( 'all_listing_columns', 3 );

		$this->options['display_listings_header']         = ! empty( get_directorist_option( 'display_listings_header', 1 ) ) ? 'yes' : '';
		$this->options['listing_filters_button']          = ! empty( get_directorist_option( 'listing_filters_button', 1 ) ) ? 'yes' : '';
		$this->options['listings_filter_button_text']     = get_directorist_option( 'listings_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->options['filter_open_method']              = get_directorist_option( 'home_display_filter', 'sliding' );
		$this->options['display_sort_by']                 = get_directorist_option( 'display_sort_by', 1 ) ? true : false;
		$this->options['sort_by_text']                    = get_directorist_option( 'sort_by_text', __( 'Sort By', 'directorist' ) );
		$this->options['listings_sort_by_items']          = get_directorist_option( 'listings_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
		$this->options['display_view_as']                 = get_directorist_option( 'display_view_as', 1 );
		$this->options['view_as_text']                    = get_directorist_option( 'view_as_text', __( 'View As', 'directorist' ) );
		$this->options['listings_view_as_items']          = get_directorist_option( 'listings_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
	}

	/**
	 * Update options based on search settings.
	 */
	public function update_search_options() {
		$this->options['order_listing_by']                = apply_filters( 'atbdp_default_listing_orderby', get_directorist_option( 'search_order_listing_by', 'date' ) );
		$this->options['sort_listing_by']                 = get_directorist_option( 'search_sort_listing_by', 'asc' );
		$this->options['listings_per_page']               = get_directorist_option( 'search_posts_num', 6 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_search_results', 1 ) ) ? 'yes' : '';
		$this->options['listing_columns']                 = get_directorist_option( 'search_listing_columns', 3 );
		$this->options['display_listings_header']         = ! empty( get_directorist_option( 'search_header', 1 ) ) ? 'yes' : '';
		$this->options['listing_filters_button']          = ! empty( get_directorist_option( 'search_result_filters_button_display', 1 ) ) ? 'yes' : '';
		$this->options['listings_filter_button_text']     = get_directorist_option( 'search_result_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->options['filter_open_method']              = get_directorist_option( 'search_result_display_filter', 'sliding' );
		$this->options['display_sort_by']                 = get_directorist_option( 'search_sort_by', 1 ) ? true : false;
		$this->options['sort_by_text']                    = get_directorist_option( 'search_sortby_text', __( 'Sort By', 'directorist' ) );
		$this->options['listings_sort_by_items']          = get_directorist_option( 'search_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
		$this->options['display_view_as']                 = get_directorist_option( 'search_view_as', 1 );
		$this->options['view_as_text']                    = get_directorist_option( 'search_viewas_text', __( 'View As', 'directorist' ) );
		$this->options['listings_view_as_items']          = get_directorist_option( 'search_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
	}

	/**
	 * Set query.
	 *
	 * @todo migration: remove transients for backword compatibility
	 *
	 * @param array $query_args
	 */
	public function set_query( $query_args ) {
		if ( ! $query_args ) {
			if ( $this->type == 'search_result' ) {
				$query_args = $this->parse_search_query_args();
			}
			else {
				$query_args = $this->parse_query_args();
			}
		}

		$this->query = $this->get_query_results( $query_args )->query;
	}

	/**
	 *
	 * @todo improve
	 *
	 * @param  array  $query_args
	 *
	 * @return object
	 */
	public function get_query_results( $query_args = [] ) {
		$caching_options = [];
		if ( ! empty( $query_args['orderby'] ) ) {
			if ( is_string( $query_args['orderby'] ) && preg_match( '/rand/', $query_args['orderby'] ) ) {
				$caching_options['cache'] = false;
			}

			if ( is_array( $query_args['orderby'] ) ) {
				foreach ( $query_args['orderby'] as $key => $value ) {
					if ( preg_match( '/rand/', $value ) ) {
						$caching_options['cache'] = false;
					}
				}
			}
		}

		return ATBDP_Listings_Data_Store::get_archive_listings_query( $query_args, $caching_options );
	}

	/**
	 * @return object
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * @return array
	 */
	public function post_ids() {
		return wp_parse_id_list( $this->query->posts );
	}

	/**
	 * @return int
	 */
	public function total_count() {
		return (int) $this->query->found_posts;
	}

	/**
	 * Renders directory type navigation template.
	 */
	public function directory_type_nav_template() {
		$count = count( $this->allowed_directory_types() );
		$enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );
		if ( $count > 1 && ! empty( $enable_multi_directory ) ) {
			Helper::get_template( 'archive/directory-type-nav' );
		}
	}

	/**
	 * Renders header template.
	 */
	public function header_bar_template() {
		if ( $this->atts['header'] == 'yes' ) {
			Helper::get_template( 'archive/header-bar' );
		}
	}

	/**
	 * Renders grid/list view template.
	 */
	public function archive_view_template() {
		$template_file = "archive/{$this->get_current_view()}-view";
		Helper::get_template( $template_file );
	}

	/**
	 * Renders sort-by dropdown template.
	 */
	public function sortby_dropdown_template() {
		Helper::get_template( 'archive/sortby-dropdown' );
	}

	/**
	 * Renders view-as dropdown template.
	 */
	public function viewas_dropdown_template() {
		Helper::get_template( 'archive/viewas-dropdown' );
	}

	/**
	 * Renders pagination template.
	 */
	public function pagination_template() {
		Helper::get_template( 'archive/pagination' );
	}

	/**
	 * Renders thumbnail template.
	 */
	public function loop_thumbnail_template() {
		Helper::get_template( 'archive/fields/thumbnail' );
	}

	/**
	 * Renders search form template.
	 */
	public function search_form_template() {
		$search_field_atts = array_filter( $this->atts, function( $key ) {
			return substr( $key, 0, 7 ) == 'filter_';
		}, ARRAY_FILTER_USE_KEY ); // only use atts with the prefix 'filter_'

		$args = array(
			'listings'   => $this,
			'searchform' => new Search_Form( $this->type, $this->current_directory_type_id(), $search_field_atts ),
		);

		Helper::get_template( 'archive/search-form', $args );
	}

	/**
	 * Renders the thumbnail image html inside loop.
	 *
	 * @todo improve default preview image fetching method, use attachment id.
	 *
	 * @param  string $class Css class for img tag.
	 *
	 * @return string Image HTML.
	 */
	public function loop_get_the_thumbnail( $class = '' ) {
		$attr             = ['class' => $class];
		$post_id          = get_the_ID();
		$size             = get_directorist_option( 'preview_image_quality', 'large' );
		$preview_img_id   = get_post_meta( $post_id, '_listing_prv_img', true );
		$image_ids        = get_post_meta( $post_id, '_listing_img', true );

		if ( $preview_img_id ) {
			$image = Helper::get_attachment_image( $preview_img_id, $size, false, $attr );
		} elseif ( is_array( $image_ids ) && ! empty( $image_ids[0] ) ) {
			$image = Helper::get_attachment_image( $image_ids[0], $size, false, $attr );
		} else {
			$image = '';
		}

		if ( !$image ) {
			$src   = Helper::default_preview_image_src( $this->current_directory_type_id() );
			$class = !empty( $attr['class'] ) ? $attr['class'] : '';
			$image = sprintf( '<img src="%s" alt="%s" class="%s" />', $src, get_the_title(), $class );
		}

		return $image;
	}

	/**
	 * Listing title.
	 *
	 * @return string
	 */
	public function loop_get_title() {
		return get_the_title();
	}

	/**
	 * Listing tagline.
	 *
	 * @return string
	 */
	public function loop_get_tagline() {
		return get_post_meta( get_the_ID(), '_tagline', true );
	}

	/**
	 * User already added listing in favourite list of not.
	 *
	 * @return bool
	 */
	public function loop_is_favourite() {
		$favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
		if ( in_array( get_the_id() , $favourites ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Listing permalink.
	 *
	 * @return string
	 */
	public function loop_get_permalink() {
		return get_permalink( get_the_ID() );
	}

	/**
	 * Listing categories.
	 *
	 * @return array Term list of Listing Category taxonomy.
	 */
	public function loop_get_categories() {
		return get_the_terms( get_the_ID(), ATBDP_CATEGORY );
	}

	/**
	 * @return string
	 */
	public function default_category_icon() {
		$icon = atbdp_icon_type(). '-folder-open';
		return apply_filters( 'default_category_icon', $icon );
	}

	/**
	 * @param object $term
	 *
	 * @return array
	 */
	public function category_icon( $term ) {
		$term_icon = get_term_meta( $term->term_id, 'category_icon', true );
		return $term_icon ? $term_icon: $this->default_category_icon();
	}

	/**
	 * @param object $term
	 *
	 * @return array
	 */
	public function category_link( $term ) {
		return get_term_link( $term->term_id, ATBDP_CATEGORY );
	}

	/**
	 * Featured listing or not.
	 *
	 * @return bool
	 */
	public function loop_is_featured() {
		return get_post_meta( get_the_ID(), '_featured', true );
	}

	/**
	 * Number of views of a listing.
	 *
	 * @return int|string
	 */
	public function loop_post_view_count() {
		$count = get_post_meta( get_the_ID(), '_atbdp_post_views_count', true );
		return $count ? $count : 0;
	}

	/**
	 * Listing author name.
	 *
	 * @return string
	 */
	public function loop_author_name() {
		$author_id   = get_the_author_meta( 'ID' );
		return get_the_author_meta( 'display_name', $author_id );
	}

	/**
	 * Listing author profile link.
	 *
	 * @return string
	 */
	public function loop_author_link() {
		$author_id   = get_the_author_meta( 'ID' );
		$get_directory_type = get_term_by( 'id', $this->current_directory_type_id(), ATBDP_TYPE );
		$directory_type = ! empty( $get_directory_type ) ? $get_directory_type->slug : '';
		return ATBDP_Permalink::get_user_profile_page_link( $author_id, $directory_type );
	}

	/**
	 * Listing author profile image URL.
	 *
	 * @return string
	 */
	public function loop_author_img_src() {
		$author_id = get_the_author_meta( 'ID' );
		$img_id = get_user_meta( $author_id, 'pro_pic', true );

		if ( ! empty( $img_id ) ) {
			$img_src = wp_get_attachment_image_src( $img_id, 'thumbnail' );
			if ( $img_src ) {
				return $img_src[0];
			}
		}

		return '';
	}

	/**
	 * Listing author avatar image.
	 *
	 * @return string Avatar img tag.
	 */
	public function loop_author_avatar() {
		$author_id = get_the_author_meta( 'ID' );
		return get_avatar( $author_id, apply_filters( 'atbdp_avatar_size', 32 ) );
	}

	/**
	 * Average rating of a listing.
	 *
	 * @uses ATBDP_Review_Rating::get_average()
	 *
	 * @return string
	 */
	public function loop_rating_average() {
		return ATBDP()->review->get_average(get_the_ID());
	}

	/**
	 * Review stars.
	 *
	 * @todo replce fontawesome code with dynamic icons.
	 *
	 * @return string Review star html.
	 */
	public function loop_review_star_html() {
		$average = $this->loop_rating_average();

		// Icons
		$icon_empty_star = '<i class="'. 'far fa-star'.'"></i>';
		$icon_half_star  = '<i class="'. 'fas fa-star-half-alt'.'"></i>';
		$icon_full_star  = '<i class="'. 'fas fa-star'.'"></i>';

		// Stars
		$star_1 = ( $average >= 0.5 && $average < 1) ? $icon_half_star : $icon_empty_star;
		$star_1 = ( $average >= 1) ? $icon_full_star : $star_1;

		$star_2 = ( $average >= 1.5 && $average < 2) ? $icon_half_star : $icon_empty_star;
		$star_2 = ( $average >= 2) ? $icon_full_star : $star_2;

		$star_3 = ( $average >= 2.5 && $average < 3) ? $icon_half_star : $icon_empty_star;
		$star_3 = ( $average >= 3) ? $icon_full_star : $star_3;

		$star_4 = ( $average >= 3.5 && $average < 4) ? $icon_half_star : $icon_empty_star;
		$star_4 = ( $average >= 4) ? $icon_full_star : $star_4;

		$star_5 = ( $average >= 4.5 && $average < 5 ) ? $icon_half_star : $icon_empty_star;
		$star_5 = ( $average >= 5 ) ? $icon_full_star : $star_5;

		$review_stars = "{$star_1}{$star_2}{$star_3}{$star_4}{$star_5}";

		return $review_stars;
	}

	/**
	 * Bootstrap like column number.
	 *
	 * @uses atbdp_calculate_column()
	 *
	 * @return string
	 */
	public function columns() {
		return (int) atbdp_calculate_column( $this->atts['columns'] );
	}

	/**
	 * Item found text.
	 *
	 * @todo Remove backward compatibility, execute migration for %COUNT%.
	 *
	 * @return string
	 */
	public function item_found_text() {
		$count = $this->total_count();
		$title = $this->atts['header_title'];

		if ( strpos( $title, '%COUNT%') !== false ) {
			$text = str_replace( '%COUNT%', $count, $title );
		}
		else {
			// Backward compatibility, in case of %COUNT% not found
			$text = $count . ' '. $title;
		}

		if ( $this->type == 'search_result' ) {
			$text = $this->item_found_text_for_search();
		}

		return apply_filters('directorist_listings_found_text', $text );
	}

	/**
	 * Item found text for search result page.
	 *
	 * @return string
	 */
	public function item_found_text_for_search() {
		$count = $this->total_count();
		$cat_name = $loc_name = '';

		if ( isset($_GET['in_cat'] ) ) {
			$cat_id = intval($_GET['in_cat']);
			$cat = get_term_by('id', $cat_id, ATBDP_CATEGORY);
			if ( $cat ) {
				$cat_name = $cat->name;
			}
		}

		if ( isset($_GET['in_loc'] ) ) {
			$loc_id = intval($_GET['in_cat']);
			$loc = get_term_by('id', $loc_id, ATBDP_LOCATION);
			if ( $loc ) {
				$loc_name = $loc->name;
			}
		} elseif ( isset($_GET['address'] ) ) {
			$loc_name = sanitize_text_field( $_GET['address'] );
		}

		if ( $cat_name && $loc_name ) {
			$title = sprintf( _nx( '%s result for %s in %s', '%s results for %s in %s', $count, 'search result header', 'directorist' ), $count, $cat_name, $loc_name );
		} elseif ( $cat_name ) {
			$title = sprintf( _nx( '%s result for %s', '%s results for %s', $count, 'search result header', 'directorist' ), $count, $cat_name );
		} elseif ( $loc_name ) {
			$title = sprintf( _nx( '%s result in %s', '%s results in %s', $count, 'search result header', 'directorist' ), $count, $loc_name );
		}
		else {
			$title = sprintf( _nx( '%s result', '%s results', $count, 'search result header', 'directorist' ), $count );
		}

		return $title;
	}

	/**
	 * Display search form or not.
	 *
	 * @return bool
	 */
	public function display_search_form() {
		return $this->atts['advanced_filter'] == 'yes' ? true : false;
	}

	/**
	 * Display search filter icon or not.
	 *
	 * @return bool
	 */
	public function display_search_filter_icon() {
		return get_directorist_option( 'listing_filters_icon', true );
	}

	/**
	 * Display pagination or not.
	 *
	 * @return bool
	 */
	public function display_pagination() {
		return $this->atts['show_pagination'] == 'yes' ? true : false;
	}

	/**
	 * Display blur background or not.
	 *
	 * @return bool
	 */
	public function display_blur_background() {
		$background_type = get_directorist_option( 'prv_background_type', 'blur' );
		return ( $background_type == 'blur' ) ? true : false;
	}

	/**
	 * @return string eg. cover, contain, full.
	 */
	public function thumbnail_display_type() {
		return get_directorist_option( 'way_to_show_preview', 'cover' );
	}

	/**
	 * @return string
	 */
	public function thumbnail_style_attr() {
		$container_px_or_ratio = get_directorist_option( 'prv_container_size_by', 'px' );
		$container_width       = (int) get_directorist_option( 'crop_width', 360 );
		$container_height      = (int) get_directorist_option( 'crop_height', 300) ;
		$custom_bgcolor        = get_directorist_option( 'prv_background_color', '#fff' );

		$style = '';

		if ( $this->thumbnail_display_type() !== 'full' && ! $this->display_blur_background() ) {
			$style .= "background-color:{$custom_bgcolor};";
		}

		if ( $container_px_or_ratio == 'ratio' ) {
			$padding_top = $container_height / $container_width * 100;
			$style .= "padding-top:{$padding_top}%;";
		} elseif ( $this->thumbnail_display_type() != 'full' ) {
			$style .= "height:{$container_height}px";
		}

		return $style;
	}

	/**
	 * Check if monetize by featured is enabled.
	 *
	 * @todo Remove is_fee_manager_active
	 *
	 * @return bool
	 */
	public function monetize_by_featued_enabled() {
		if ( is_fee_manager_active() ) {
			return true;
		}

		$enable_monetization = get_directorist_option( 'enable_monetization' );
		$enable_featured_listing = get_directorist_option( 'enable_featured_listing' );

		return ( $enable_monetization && $enable_featured_listing ) ? true : false;
	}

	/**
	 * Order by attribute for query.
	 *
	 * @return string
	 */
	public function query_orderby() {
		return $this->atts['orderby'];
	}

	/**
	 * Order attribute for query.
	 *
	 * @return string
	 */
	public function query_order() {
		return $this->atts['order'];
	}

	/**
	 * Number of listings per page.
	 *
	 * @return int
	 */
	public function listings_per_page() {
		return (int) $this->atts['listings_per_page'];
	}

	/**
	 * Display only featured listings or not.
	 *
	 * @return bool
	 */
	public function display_only_featured() {
		return $this->atts['featured_only'];
	}

	/**
	 * Display only popular listings or not.
	 *
	 * @return bool
	 */
	public function display_only_popular() {
		return $this->atts['popular_only'];
	}

	/**
	 * Display only for logged in users or not.
	 *
	 * @return bool
	 */
	public function display_only_for_logged_in() {
		return $this->atts['logged_in_user_only'] == 'yes' ? true : false;
	}

	/**
	 * Page redirection URL.
	 *
	 * @return string
	 */
	public function redirect_page_url() {
		return $this->atts['redirect_page_url'];
	}

	/**
	 * Map height.
	 *
	 * @return string
	 */
	public function map_height() {
		return (int) $this->atts['map_height'];
	}

	/**
	 * Map zoom level.
	 *
	 * @return string
	 */
	public function map_zoom_level() {
		return (int) $this->atts['map_zoom_level'];
	}

	/**
	 * Determines how popular listings are based on.
	 *
	 * @return string Possible values: view_count, average_rating, both_view_rating.
	 */
	public function popular_by() {
		return get_directorist_option( 'listing_popular_by' );
	}

	/**
	 * Minimum rating to determine popular listing.
	 *
	 * @return string|int
	 */
	public function average_review_for_popular() {
		return get_directorist_option( 'average_review_for_popular', 4 );
	}

	/**
	 * Minimum view count to determine popular listing.
	 *
	 * @return string|int
	 */
	public function view_to_popular() {
		return get_directorist_option( 'views_for_popular', 4 );
	}

	/**
	 * Unit for radius search.
	 *
	 * @return string Possible values: miles, kilometers.
	 */
	public function radius_search_unit() {
		return get_directorist_option( 'radius_search_unit', 'miles' );
	}

	/**
	 * Listing map type.
	 *
	 * @return string Possible values: google, openstreet.
	 */
	public function map_type() {
		return get_directorist_option( 'select_listing_map', 'google' );
	}

	/**
	 * Search Filter opening behaviour.
	 *
	 * @return string Possible values: overlapping, sliding, always_open.
	 */
	public function filter_open_method() {
		return $this->options['filter_open_method'];
	}

	/**
	 * Display sortby dropdown or not.
	 *
	 * @return bool
	 */
	public function display_sortby_dropdown() {
		return $this->options['display_sort_by'];
	}

	/**
	 * Display view-as dropdown or not.
	 *
	 * @return bool
	 */
	public function display_viewas_dropdown() {
		return $this->options['display_view_as'];
	}

	/**
	 * Label for Filter button.
	 *
	 * @return string
	 */
	public function filter_button_text() {
		return $this->options['listings_filter_button_text'];
	}

	/**
	 * Label for sortby dropdown.
	 *
	 * @return string
	 */
	public function sort_by_text() {
		return $this->options['sort_by_text'];
	}

	/**
	 * Label for view-as dropdown.
	 *
	 * @return string
	 */
	public function view_as_text() {
		return $this->options['view_as_text'];
	}

	/**
	 * List of view-as dropdown data.
	 *
	 * @return array eg. $data['grid'] = [
	 *                   	'label' => '',
	 *                   	'link' => '',
	 *                   ]
	 */
	public function view_as_dropdown_data() {
		$key_convert_list = [
			'grid'  => 'listings_grid',
			'list'  => 'listings_list',
			'map'   => 'listings_map',
		];

		$items = array_intersect( $key_convert_list, $this->options['listings_view_as_items'] );
		$items = array_keys( $items );

		$data = [];

		foreach ( $items as $item ) {
			$data[$item] = [
				'label' => $this->view_as_dropdown_label( $item ),
				'link' => $this->view_as_dropdown_link( $item ),

			];
		}

		return $data;
	}

	/**
	 * List of sort-as dropdown data.
	 *
	 * @return array eg. $data['title-asc'] = [
	 *                   	'label' => '',
	 *                   	'link' => '',
	 *                   ]
	 */
	public function sort_by_dropdown_data() {
		$key_convert_list = [
			'title-asc'    => 'a_z',
			'title-desc'   => 'z_a',
			'date-desc'    => 'latest',
			'date-asc'     => 'oldest',
			'views-desc'   => 'popular',
			'price-asc'    => 'price_low_high',
			'price-desc'   => 'price_high_low',
			'rand'         => 'random',
		];

		$items = array_intersect( $key_convert_list, $this->options['listings_sort_by_items'] );
		$items = array_keys( $items );

		$data = [];

		foreach ( $items as $item ) {
			$data[$item] = [
				'label' => $this->sort_by_dropdown_label( $item ),
				'link' => $this->sort_by_dropdown_link( $item ),

			];
		}

		return $data;
	}

	/**
	 * View-as dropdown label for an item.
	 *
	 * @return string
	 */
	public function view_as_dropdown_label( $item ) {
		$labels = array(
			'grid'   => __( 'Grid', 'directorist' ),
			'list'   => __( 'List', 'directorist' ),
			'map'    => __( 'Map', 'directorist' ),
		);

		return $labels[$item];
	}

	/**
	 * Sort-by dropdown label for an item.
	 *
	 * @return string
	 */
	public function sort_by_dropdown_label( $item ) {
		$labels = array(
			'title-asc'    => __( 'A to Z (title)', 'directorist' ),
			'title-desc'   => __( 'Z to A (title)', 'directorist' ),
			'date-desc'    => __( 'Latest listings', 'directorist' ),
			'date-asc'     => __( 'Oldest listings', 'directorist' ),
			'views-desc'   => __( 'Popular listings', 'directorist' ),
			'price-asc'    => __( 'Price (low to high)', 'directorist' ),
			'price-desc'   => __( 'Price (high to low)', 'directorist' ),
			'rand'         => __( 'Random listings', 'directorist' ),
		);

		return $labels[$item];
	}

	/**
	 * View-as dropdown link for an item.
	 *
	 * @return string
	 */
	public function view_as_dropdown_link( $item ) {
		return add_query_arg( 'view', $item );
	}

	/**
	 * Sort-by dropdown link for an item.
	 *
	 * @return string
	 */
	public function sort_by_dropdown_link( $item ) {
		return add_query_arg( 'sort', $item );
	}

	/**
	 * Display each info in single line or not.
	 *
	 * @return bool
	 */
	public function display_info_in_single_line() {
		return get_directorist_option( 'info_display_in_single_line', false );
	}

	/**
	 * Single listing view disabled or not.
	 *
	 * @return bool
	 */
	public function disable_single_listing() {
		return get_directorist_option( 'disable_single_listing', false );
	}

	/**
	 * @return array
	 */
	public function allowed_directory_types() {
		$args = array(
			'taxonomy'   => ATBDP_TYPE,
			'hide_empty' => false
		);

		if( !empty( $this->atts['directory_type'] ) ) {
			$args['slug'] = explode( ',', $this->atts['directory_type'] );
		}

		return get_terms( $args );
	}

	/**
	 * @return int
	 */
	public function current_directory_type_id() {
		$types = $this->allowed_directory_types();

		$current = !empty( $types[0] ) ? $types[0]->term_id : '';

		if ( isset( $_GET['directory_type'] ) ) {
			$current = $_GET['directory_type'];
		}
		else if( !empty( $this->atts['default_directory_type'] ) ) {
			$current = $this->atts['default_directory_type'];
		}
		else {
			foreach ( $types as $term ) {
				$is_default = get_term_meta( $term->term_id, '_default', true );

				if ( $is_default ) {
					$current = $term->term_id;
					break;
				}
			}
		}

		if( ! is_numeric( $current ) ) {
			$term = get_term_by( 'slug', $current, ATBDP_TYPE );
			$current = $term->term_id;
		}

		return (int) $current;
	}

	/**
	 * @param  object $term
	 *
	 * @return string
	 */
	public function directory_type_name( $term ) {
		return $term->name;
	}

	/**
	 * @param  object $term
	 *
	 * @return string
	 */
	public function directory_type_icon( $term ) {
		return get_term_meta( $term->term_id, 'general_config', true )['icon'];
	}

	/**
	 * @param  object $term
	 *
	 * @return string
	 */
	public function directory_type_url( $term ) {
		$type = $term->slug;

		$base_url = remove_query_arg( [ 'page', 'paged' ] );
		$base_url = preg_replace( '~/page/(\d+)/?~', '', $base_url );
		$base_url = preg_replace( '~/paged/(\d+)/?~', '', $base_url );

		$url = add_query_arg( [ 'directory_type' => $type ], $base_url );

		return apply_filters( 'directorist_get_directory_type_nav_url', $url, $type, $base_url );
	}

	/**
	 * @return array
	 */
	public function get_locations() {
		return get_the_terms( get_the_ID(), ATBDP_LOCATION );
	}

	/**
	 * @return string
	 */
	public function get_location_html() {
		$loc_array = [];

		foreach ( $this->get_locations() as $term ) {
			$link = get_term_link( $term->term_id, ATBDP_LOCATION );
			$loc_array[] = sprintf( '<a href="%s">%s</a>', $link, $term->name );
		}

		return implode( ', ', $loc_array );
	}

	/**
	 * @todo remove 2nd parameter from filter
	 *
	 * @return string
	 */
	public function loop_wrapper_class() {
		$class  = [];

		if ( $this->loop_is_featured() ) {
			$class[] = 'directorist-featured';
		}

		if ( $this->display_info_in_single_line() ) {
			$class[] = 'directorist-single-line';
		}

		$class  = apply_filters( 'directorist_loop_wrapper_class', $class, $this->current_directory_type_id() );

		return implode( ' ' , $class );
	}

	/**
	 * @return void
	 */
	public function loop_template() {
		$view = $this->get_current_view();

		if ( $view == 'grid' ) {
			$template = $this->display_thumbnail() ? 'loop-grid' : 'loop-grid-nothumb';
			Helper::get_template( 'archive/' . $template );
		}
		elseif ( $view == 'list' ) {
			$template = $this->display_thumbnail() ? 'loop-list' : 'loop-list-nothumb';
			Helper::get_template( 'archive/' . $template );
		}
	}

	/**
	 * Listing current view type.
	 *
	 * @todo remove BD_Map_View dependency by using hooks.
	 *
	 * @return string Possible values: grid, list or map.
	 */
	public function get_current_view() {
		$allowed_views = [ 'grid', 'list', 'map' ];

		if ( class_exists( 'BD_Map_View' ) ) {
			array_push( $allowed_views, 'listings_with_map' );
		}

		if ( !empty( $_GET['view'] ) ) {
			$view = sanitize_text_field( $_GET['view'] );
		} else {
			$view = $this->atts['view'];
		}

		if ( !in_array( $view, $allowed_views ) ) {
			$view = 'grid';
		}

		return $view;
	}

	/**
	 * Display preview image or not.
	 *
	 * @return bool
	 */
	public function display_thumbnail() {
		$card_meta = $this->card_meta();

		if ( !$card_meta ) {
			return true;
		}

		$active_template = $card_meta['active_template'];

		if ( $active_template == 'grid_view_with_thumbnail' || $active_template == 'list_view_with_thumbnail' ) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * @param  string $position
	 * @param  string $before
	 * @param  string $after
	 */
	public function render_fields( $position, $before = '', $after = '' ) {
		$data = $this->card_data();

		switch ( $position ) {

			case 'avatar':
				$fields = $data['thumbnail']['avatar'] ?? false;
				break;

			case 'title':
				$fields = $data['body']['title'] ?? false;
				break;

			case 'quick-actions':
				$fields = $data['body']['quick_actions'];
				break;

			case 'quick-info':
				$fields = $data['body']['quick_info'] ?? false;
				break;

			case 'thumb-top-left':
				$fields = $data['thumbnail']['top_left'] ?? false;
				break;

			case 'thumb-top-right':
				$fields = $data['thumbnail']['top_right'] ?? false;
				break;

			case 'thumb-bottom-left':
				$fields = $data['thumbnail']['bottom_left'] ?? false;
				break;

			case 'thumb-bottom-right':
				$fields = $data['thumbnail']['bottom_right'] ?? false;
				break;

			case 'body-top':
				$fields = $data['body']['top'] ?? false;
				break;

			case 'body-right':
				$fields = $data['body']['right'] ?? false;
				break;

			case 'body-bottom':
				$fields = $data['body']['bottom'] ?? false;
				break;

			case 'body-excerpt':
				$fields = $data['body']['excerpt'] ?? false;
				break;

			case 'footer-left':
				$fields = $data['footer']['left'] ?? false;
				break;

			case 'footer-right':
				$fields = $data['footer']['right'] ?? false;
				break;
		}

		if ( $fields ) {
			$this->render_card_view( $fields, $before, $after );
		}
	}

	/**
	 * @return array
	 */
	public function card_data() {
		$view = $this->get_current_view();
		$meta = $this->card_meta();

		$data = [];

		if ( $meta ) {
			if ( $this->display_thumbnail() ) {
				if ( $view == 'grid' ) {
					$data = $meta['template_data']['grid_view_with_thumbnail'];
				} elseif ( $view == 'list' ) {
					$data = $meta['template_data']['list_view_with_thumbnail'];
				}
			}
			else {
				if ( $view == 'grid' ) {
					$data = $meta['template_data']['grid_view_without_thumbnail'];
				} elseif ( $view == 'list' ) {
					$data = $meta['template_data']['list_view_without_thumbnail'];
				}
			}
		}

		return apply_filters( 'directorist_card_data', $data );
	}

	/**
	 * @return array
	 */
	public function card_meta() {
		$listing_type = $this->current_directory_type_id();

		$view = $this->get_current_view();

		if ( $view == 'grid' ) {
			$meta = get_term_meta( $listing_type, 'listings_card_grid_view', true );
		} elseif ( $view == 'list' ) {
			$meta = get_term_meta( $listing_type, 'listings_card_list_view', true );
		} else {
			$meta = [];
		}

		return $meta;
	}

	/**
	 * @param  array $fields
	 * @param  string $before
	 * @param  string $after
	 */
	public function render_card_view( $fields, $before = '', $after = '' ) {
		if( !empty( $fields ) ) {
			foreach ( $fields as $field ) {
				self::$current_field = $field;

				echo $before;
				$this->card_field_html( $field );
				echo $after;

				self::$current_field = '';
			}
		}
	}

	/**
	 * @link https://gist.github.com/kowsar89/db7b3e5e5453c7c86a73b659c9607eb7 Data structure.
	 *
	 * @param  array $field
	 */
	public function card_field_html( $field ) {
		// For badges, load badge template
		if ( $field['type'] == 'badge' ) {
			$this->render_badge_template( $field );
		} else {
			$form_field = $this->get_form_field_data();

			// @todo will improve later
			if ( ! empty( $form_field ) ) {
				$field['original_field'] = $form_field;
			}

			$value = $this->field_value();

			$load_template = true;

			$id = get_the_id();

			if( ( $field['type'] === 'list-item' ) && !$value  &&  ( 'posted_date' !== $field['widget_name'] ) ) {
				$load_template = false;
			}

			$label = !empty( $field['show_label'] ) ? $field['label']: '';
			$args = array(
				'listings' => $this,
				'post_id'  => $id,
				'data'     => $field,
				'value'    => $value,
				'label'    => $label,
				'icon'     => !empty( $field['icon'] ) ? $field['icon'] : '',
				'original_field' => get_term_meta( $this->current_directory_type_id(), 'submission_form_fields', true ),
			);

			$widget_name = $field['widget_name'];

			if ( $this->is_custom_field() ) {
				$template = 'archive/custom-fields/' . $widget_name;
			} else {
				$template = 'archive/fields/' . $widget_name;
			}

			if( $load_template ) {
				Helper::get_template( $template, $args );
			}

		}
	}

	/**
	 * Form field data for listing field.
	 *
	 * @param  array $field
	 *
	 * @return array
	 */
	public function get_form_field_data( $field = [] ) {
		if ( empty( $field ) ) {
			$field = self::$current_field;
		}

		$form_field = [];

		// Form field data for listing field
		if ( isset( $field['original_widget_key'] ) ) {
			$submission_form_fields = get_term_meta( $this->current_directory_type_id(), 'submission_form_fields', true );
			$form_key = $field['original_widget_key']; // key for making relation with form field's key

			if ( isset( $submission_form_fields['fields'][$form_key] ) ) {
				$form_field = $submission_form_fields['fields'][$form_key];
			}
		}

		return $form_field;
	}

	/**
	 * @return string
	 */
	public function field_icon() {
		$field = self::$current_field;

		return !empty( $field['icon'] ) ? $field['icon'] : '';
	}

	/**
	 * @return string
	 */
	public function field_label() {
		$field = self::$current_field;

		return !empty( $field['show_label'] ) ? $field['label']: '';
	}

	/**
	 * @param  array  $field
	 *
	 * @return string
	 */
	public function field_value( $field = [] ) {
		if ( empty( $field ) ) {
			$field = self::$current_field;
		}

		$form_field = $this->get_form_field_data();
		$id = get_the_id();

		$value = get_post_meta( $id, '_'.$field['widget_key'], true );

		// Return value for checkbox field
		if ( $this->is_custom_field() ) {
			$field_type = !empty( $form_field['type'] ) ? $form_field['type'] : '';

			if( 'checkbox' === $field_type ) {
				$option_value = [];
				$value = is_array( $value ) ? join( ",",$value ) : $value;
				foreach( $form_field['options'] as $option ) {
					$key = $option['option_value'];
					if( in_array( $key, explode( ',', $value ) ) ) {
						$space = str_repeat(' ', 1);
						$option_value[] = $space . $option['option_label'];
					}
				}
				$output = join( ',', $option_value );
				$value = $output ? $output : $value;

				return $value;
			}
		}

		// Return value for location field
		if( 'listings_location' === $field['widget_name'] ) {
			$location = get_the_terms( $id, ATBDP_LOCATION );
			if( $location ) {
				$value = true;
				return $value;
			}
		}

		// Return value based on form field if exists, try with "_" first
		if ( isset( $form_field['field_key']  ) ) {
			$value = ! empty( get_post_meta( $id, '_'.$form_field['field_key'], true ) ) ? get_post_meta( $id, '_'.$form_field['field_key'], true ) : get_post_meta( $id, $form_field['field_key'], true );
			return $value;
		}

		return $value;
	}

	/**
	 * Render icon
	 */
	public function print_icon() {
		$icon = $this->field_icon();
		if ( $icon ) {
			echo apply_filters( 'directorist_loop_icon', directorist_icon( $icon, false ) );
		}
	}

	/**
	 * Render label
	 */
	public function print_label() {
		$label = $this->field_label();
		if ( $label ) {
			$label_text = $label . ': ';
			$html = '<span class="directorist-listing-single__info--list__label">'.$label_text.'</span>';
			echo apply_filters( 'directorist_loop_label', $html, $label );
		}
	}

	/**
	 * Render value
	 */
	public function print_value() {
		$value = $this->field_value();
		if ( $value ) {
			$html = '<span class="directorist-listing-single__info--list__value">'.esc_html( $value ).'</span>';
			echo apply_filters( 'directorist_loop_value', $html );
		}
	}

	/**
	 * @return bool
	 */
	public function is_custom_field() {
		$allowed_fields = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];

		$field = self::$current_field;
		$form_field = $this->get_form_field_data();

		$widget_name = ( !empty( $form_field['widget_name'] ) ) ? $form_field['widget_name'] : $field['widget_name'];

		return in_array( $widget_name, $allowed_fields ) ? true : false;
	}

	/**
	 * @return bool
	 */
	public function has_whatsapp() {
		$form_field = $this->get_form_field_data();

		if ( !empty( $form_field['whatsapp'] ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * @return int
	 */
	public function excerpt_word_limit() {
		$field = self::$current_field;
		return (int) $field['words_limit'];
	}

	/**
	 * @return bool
	 */
	public function display_excerpt_readmore() {
		$field = self::$current_field;
		return $field['show_readmore'] ?? false;
	}

	/**
	 * @return string
	 */
	public function excerpt_readmore_text() {
		$field = self::$current_field;
		return $field['show_readmore_text'];
	}

	/**
	 * @return bool
	 */
	public function display_tagline() {
		$field = self::$current_field;
		return $field['show_tagline'] ?? false;
	}

	/**
	 * @return bool
	 */
	public function posted_date_is_days_ago() {
		$field = self::$current_field;
		return ( $field['date_type'] == 'days_ago' ) ? true : false;
	}

	/**
	 * @return string
	 */
	public function avatar_alignment() {
		$field = self::$current_field;
		return !empty( $field['align'] ) ? $field['align'] : '' ;
	}

	public function render_badge_template( $field ) {
		$id = get_the_ID();

		do_action( 'atbdp_all_listings_badge_template', $field );

		switch ($field['widget_key']) {
			case 'popular_badge':
			$field['class'] = 'popular';
			$field['label'] = Helper::popular_badge_text();
			if ( Helper::is_popular( $id ) ) {
				Helper::get_template( 'archive/fields/badge', $field );
			}
			break;

			case 'featured_badge':
			$field['class'] = 'featured';
			$field['label'] = Helper::featured_badge_text();
			if ( Helper::is_featured( $id ) ) {
				Helper::get_template( 'archive/fields/badge', apply_filters( 'directorist_featured_badge_field_data', $field ) );
			}
			break;

			case 'new_badge':
			$field['class'] = 'new';
			$field['label'] = Helper::new_badge_text();
			if ( Helper::is_new( $id ) ) {
				Helper::get_template( 'archive/fields/badge', $field );
			}
			break;
		}
	}

	public function loop_wrap_permalink( $html ) {
		return sprintf( '<a href="%s">%s</a>', get_the_permalink(), $html );
	}

	public function loop_map_latitude() {
		return get_post_meta( get_the_ID(), '_manual_lat', true );
	}

	public function loop_map_longitude() {
		return get_post_meta( get_the_ID(), '_manual_lng', true );
	}

	public function map_base_lat_long() {
		$query = $this->get_query();

		if ( !empty( $query->posts ) ) {
			$id   = $query->posts[0];
			$lat_long = [
				'lat' => get_post_meta( $id, '_manual_lat', true ),
				'lon' => get_post_meta( $id, '_manual_lng', true ),
			];
		} else {
			$lat_long = [
				'lat' => get_directorist_option( 'default_latitude', 40.7127753 ),
				'lon' => get_directorist_option( 'default_longitude', -74.0059728 ),
			];
		}

		return $lat_long;
	}

	public function loop_map_cat_icon() {
		$cats = get_the_terms( get_the_ID(), ATBDP_CATEGORY );

		if ( !empty( $cats ) ) {
			$cat_icon = get_cat_icon( $cats[0]->term_id );
		}
	
		$cat_icon  = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
		$icon_type = substr($cat_icon, 0,2);
		$fa_or_la  = ('la' == $icon_type) ? "la " : "fa ";
		$cat_icon  = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;

		return $cat_icon;
	}

	public function loop_map_direction_url() {
		return sprintf( 'http://www.google.com/maps?daddr=%s,%s', $this->loop_map_latitude(), $this->loop_map_longitude() );
	}

	public function loop_map_address() {
		return get_post_meta( get_the_ID(), '_address', true );
	}

	public function display_map_card_window() {
		return get_directorist_option( 'display_map_info', true );
	}

	public function display_map_image() {
		return get_directorist_option( 'display_image_map', true );
	}

	public function display_map_title() {
		return get_directorist_option( 'display_title_map', true );
	}

	public function display_map_address() {
		return get_directorist_option( 'display_address_map', true );
	}

	public function display_map_direction() {
		return get_directorist_option( 'display_direction_map', true );
	}

	public function render_map() {
		if ( $this->map_type() == 'openstreet' ) {
			$this->load_openstreet_map_data();
		}
		elseif( $this->map_type() == 'google' ) {
			$this->load_google_map();
		}
	}

	public function load_openstreet_map_data() {
		$script_path = DIRECTORIST_VENDOR_JS . 'openstreet-map/subGroup-markercluster-controlLayers-realworld.388.js';
		$opt = $this->get_map_options();
		?>
		<div id="map" style="width: 100%; height: <?php echo esc_attr( $this->map_height() );?>px;"></div>
		<?php
		Helper::add_hidden_data_to_dom( 'loc_data', ['script_path'  => $script_path] );
		Helper::add_hidden_data_to_dom( 'atbdp_map', $opt );
		Helper::add_hidden_data_to_dom( 'atbdp_lat_lon', $this->map_base_lat_long() );
		Helper::add_hidden_data_to_dom( 'listings_data', $this->openstreet_map_card_data() );

		wp_enqueue_script('directorist-openstreet-load-scripts');
	}

	public function get_map_options() {
		$opt['select_listing_map']    		= $this->map_type();
		$opt['crop_width']            		= get_directorist_option( 'crop_width', 360 );
		$opt['crop_height']           		= get_directorist_option( 'crop_height', 360 );
		$opt['display_map_info']      		= get_directorist_option( 'display_map_info', true );
		$opt['display_image_map']     		= get_directorist_option( 'display_image_map', true );
		$opt['display_title_map']     		= get_directorist_option( 'display_title_map', true );
		$opt['display_address_map']   		= get_directorist_option( 'display_address_map', true );
		$opt['display_direction_map'] 		= get_directorist_option( 'display_direction_map', true );
		$opt['zoom']                  		= $this->map_zoom_level();
		$opt['default_image']         		= get_directorist_option( 'default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg' );
		$opt['default_lat']           		= get_directorist_option( 'default_latitude', 40.7127753 );
		$opt['default_long']          		= get_directorist_option( 'default_longitude', -74.0059728 );
		$opt['use_def_lat_long']   			= get_directorist_option( 'use_def_lat_long', true );
		$opt['font_type']                   = get_directorist_option( 'font_type','line' );

		$opt['disable_single_listing'] = $this->disable_single_listing();

		$map_is_disabled = ( empty($opt['display_map_info']) && (empty($opt['display_image_map']) || empty($opt['display_title_map']) || empty($opt['display_address_map']) || empty($opt['display_direction_map']))) ? true : false;
		$opt['map_is_disabled'] = $map_is_disabled;

		return apply_filters( 'atbdp_map_options', $opt );
	}

	public function openstreet_map_card_data() {

		if ( !$this->display_map_card_window() ) {
			return [];
		}
		
		if ( !$this->display_map_image() && !$this->display_map_title() && !$this->display_map_address() && !$this->display_map_direction() ) {
			return [];
		}

		$map_data = [];

		$query = $this->get_query();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$map_data[] = [
					'info_content' => Helper::get_template_contents( 'archive/openstreet-map-card' ),
					'manual_lat' => $this->loop_map_latitude(),
					'manual_lng' => $this->loop_map_longitude(),
					'cat_icon' => $this->loop_map_cat_icon(),
				];
			}
		}

		wp_reset_postdata();

		return $map_data;
	}

	public function get_osm_map_info_card_data() {
		$opt = $this->get_map_options();

		$listings_data = [];
		$lat_lon = [];

		if ( $this->query->have_posts() ) :
			// Prime caches to reduce future queries.
			if ( is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $this->post_ids() );
			}

			$original_post = $GLOBALS['post'];

			foreach ( $this->post_ids() as $listings_id ) :
				$GLOBALS['post'] = get_post( $listings_id );
				setup_postdata( $GLOBALS['post'] );
				$ls_data = [];

				$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
				$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
				$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
				$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
				$ls_data['address']         = get_post_meta($listings_id, '_address', true);

				$lat_lon = [
					'lat' => $ls_data['manual_lat'],
					'lon' => $ls_data['manual_lng']
				];

				$ls_data['lat_lon'] = $lat_lon;

				if ( ! empty( $ls_data['listing_prv_img']) ) {
					$ls_data['prv_image'] = atbdp_get_image_source( $ls_data['listing_prv_img'], 'large' );
				}

				$ls_data['default_image'] = $opt['default_image'];

				if ( ! empty( $ls_data['listing_img'][0] ) ) {
					$ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
				}

				$cats      = get_the_terms(get_the_ID(), ATBDP_CATEGORY);

				if ( !empty($cats) ) {
					$cat_icon = get_cat_icon($cats[0]->term_id);
				}

				$cat_icon  = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
				$icon_type = substr($cat_icon, 0,2);
				$fa_or_la  = ('la' == $icon_type) ? "la " : "fa ";
				$cat_icon  = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;

				$ls_data['cat_icon'] = $cat_icon;
				$opt['ls_data'] = $ls_data;

				ob_start();

				// if ( ! empty( $opt['display_map_info'] ) && ( ! empty( $opt['display_image_map'] ) || ! empty( $opt['display_title_map'] ) || ! empty( $opt['display_address_map'] ) || ! empty( $opt['display_direction_map'] ) ) ) {
				// 	Helper::get_template( 'archive/openstreet-map', $opt );
				// }

				Helper::get_template( 'archive/openstreet-map-card', $opt );

				$ls_data['info_content'] = ob_get_clean();

				$listings_data[] = $ls_data;
			endforeach;

			$GLOBALS['post'] = $original_post;
			wp_reset_postdata();
		endif;

		$cord = [
			'lat' => $opt['default_lat'],
			'lon' => $opt['default_long']
		];

		if ( ! empty( $listings_data ) ) {
			$cord = $listings_data[0]['lat_lon'];
		}

		return [
			'lat_lon'       => $cord,
			'listings_data' => $listings_data,
		];
	}

	public function load_google_map() {
		wp_enqueue_script('directorist-map-view');

		$opt = $this->get_map_options();
		$disable_info_window = 'no';

		if (empty($opt['display_map_info'])) {
			$disable_info_window = 'yes';
		}
		elseif (empty($opt['display_image_map'] || $opt['display_title_map'] || $opt['display_address_map'] || $opt['display_direction_map'])){
			$disable_info_window = 'yes';
		}

		$data = array(
			'plugin_url'          => ATBDP_URL,
			'disable_info_window' => $disable_info_window,
			'zoom'                => $opt['zoom'],
			'default_latitude'    => $opt['default_lat'],
			'default_longitude'   => $opt['default_long'],
			'use_def_lat_long'    => $opt['use_def_lat_long'],
		);

		wp_localize_script( 'directorist-map-view', 'atbdp_map', $data );
		Helper::add_hidden_data_to_dom( 'atbdp_map', $data );

		?>
		<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="markerclusterer" style="height: <?php echo !empty($this->map_height())?$this->map_height():'';?>px;">
			<?php

			if ( $this->query->have_posts() ) {
				// Prime caches to reduce future queries.
				if ( is_callable( '_prime_post_caches' ) ) {
					_prime_post_caches( $this->post_ids() );
				}

				$original_post = $GLOBALS['post'];

				foreach ( $this->post_ids() as $listings_id ) :
					$GLOBALS['post'] = get_post( $listings_id );
					setup_postdata( $GLOBALS['post'] );
					$ls_data = [];

					$ls_data['post_id']         = $listings_id;
					$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
					$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
					$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
					$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
					$ls_data['crop_width']      = $opt['crop_width'];
					$ls_data['crop_height']     = $opt['crop_height'];
					$ls_data['address']         = get_post_meta($listings_id, '_address', true);
					$ls_data['font_type']       = $opt['font_type'];
					$ls_data['fa_or_la']        = ('line' == $ls_data['font_type']) ? "la " : "fa ";
					$ls_data['cats']            = get_the_terms($listings_id, ATBDP_CATEGORY);

					if(!empty($ls_data['cats'])){
						$cat_icon = get_cat_icon($ls_data['cats'][0]->term_id);
					}

					$cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
					$icon_type = substr($cat_icon, 0,2);
					$fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
					$ls_data['cat_icon'] = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;
					$ls_data['default_img'] = atbdp_image_cropping(DIRECTORIST_ASSETS . 'images/grid.jpg', $ls_data['crop_width'], $ls_data['crop_height'], true, 100)['url'];

					if (!empty($ls_data['listing_prv_img'])) {
						$ls_data['prv_image']   = atbdp_get_image_source($ls_data['listing_prv_img'], 'large');
					}

					if (!empty($ls_data['listing_img'][0])) {
						$ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
					}

					if ( ! empty( $ls_data['manual_lat'] ) && ! empty( $ls_data['manual_lng'] ) ) {
						$opt['ls_data'] = $ls_data;
						Helper::get_template( 'archive/google-map', $opt );
					}

				endforeach;

				$GLOBALS['post'] = $original_post;
				wp_reset_postdata();
			}
		echo "</div>";
	}

	public function parse_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page(),
		);

		if ( $this->display_pagination() ) {
			$args['paged'] = Helper::pagi_current_page_num();
		}

		else {
			$args['no_found_rows'] = true;
		}

		$categories = !empty( $this->atts['category'] ) ? explode( ',', $this->atts['category'] ) : [];
		$locations = !empty( $this->atts['location'] ) ? explode( ',', $this->atts['location'] ) : [];
		$tags = !empty( $this->atts['tag'] ) ? explode( ',', $this->atts['tag'] ) : [];
		$listing_ids = !empty( $this->atts['ids'] ) ? explode( ',', $this->atts['ids'] ) : [];

		if ( ! empty( $listing_ids ) ) {
			$args['post__in'] = $listing_ids;
		}

		$tax_queries = array();

		if ( ! empty( $categories ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_CATEGORY,
				'field'            => 'slug',
				'terms'            => $categories,
				'include_children' => true,
			);
		}

		if ( ! empty( $locations ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_LOCATION,
				'field'            => 'slug',
				'terms'            => $locations,
				'include_children' => true,
			);
		}

		if ( ! empty( $tags ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_TAGS,
				'field'            => 'slug',
				'terms'            => $tags,
				'include_children' => true,
			);
		}

		if( ! empty( $tax_queries ) ) {
			$args['tax_query'] = $tax_queries;
		}

		$meta_queries = array();
		$this->execute_meta_query_args($args, $meta_queries);

		$meta_queries = apply_filters( 'atbdp_all_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );

		if ( $count_meta_queries ) {
			$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_queries );
		}

		return apply_filters( 'atbdp_all_listings_query_arguments', $args );
	}

	public function parse_search_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page(),
		);

		if ( $this->display_pagination() ) {
			$args['paged'] = Helper::pagi_current_page_num();
		}
		else {
			$args['no_found_rows'] = true;
		}

		if (!empty($_GET['q'])) {
			$args['s'] = sanitize_text_field($_GET['q']);
		}

		if ($this->monetize_by_featued_enabled()) {
			$args['meta_key'] = '_featured';
			$args['orderby'] = array(
				'meta_value_num' => 'DESC',
				'title' => 'ASC',
			);
		}
		else {
			$args['orderby'] = 'title';
			$args['order'] = 'ASC';
		}

		$tax_queries = array();

		if (isset($_GET['in_cat']) && (int)$_GET['in_cat'] > 0) {
			$tax_queries[] = array(
				'taxonomy' => ATBDP_CATEGORY,
				'field' => 'term_id',
				'terms' => (int)$_GET['in_cat'],
				'include_children' => true,
			);
		}

		if (isset($_GET['in_loc']) && (int)$_GET['in_loc'] > 0) {
			$tax_queries[] = array(
				'taxonomy' => ATBDP_LOCATION,
				'field' => 'term_id',
				'terms' => (int)$_GET['in_loc'],
				'include_children' => true,
			);
		}

		if (isset($_GET['in_tag']) && (int)$_GET['in_tag'] > 0) {
			$tag_value = $_GET['in_tag'];
			$tax_queries[] = array(
				'taxonomy' => ATBDP_TAGS,
				'field' => 'term_id',
				'terms' => $tag_value,
			);

		}
		$count_tax_queries = count($tax_queries);
		if ($count_tax_queries) {
			$args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_queries);
		}

		$meta_queries = array();

		$this->execute_meta_query_args($args, $meta_queries);

		if ( isset( $_GET['custom_field'] ) ) {
			$cf = array_filter($_GET['custom_field']);

			foreach ( $cf as $key => $values ) {
				if ( is_array( $values ) ) {
					if ( count( $values ) > 1 ) {
						$sub_meta_queries = array();
						foreach ( $values as $value ) {
							$sub_meta_queries[] = array(
								'key' => '_' . $key,
								'value' => sanitize_text_field( $value ),
								'compare' => 'LIKE'
							);
						}
						$meta_queries[] = array_merge( array('relation' => 'OR'), $sub_meta_queries );
					}
					else {

						$meta_queries[] = array(
							'key' => '_' . $key,
							'value' => sanitize_text_field( $values[0] ),
							'compare' => 'LIKE'
						);
					}
				}
				else {

					$field_type = get_post_meta( $key, 'type', true );
					$operator = ('text' == $field_type || 'textarea' == $field_type || 'url' == $field_type) ? 'LIKE' : '=';
					$meta_queries[] = array(
						'key' => '_' . $key,
						'value' => sanitize_text_field( $values ),
						'compare' => $operator
					);

				}
			}
		}

		if (isset($_GET['price'])) {
			$price = array_filter($_GET['price']);
			if ($n = count($price)) {
				if (2 == $n) {
					$meta_queries[] = array(
						'key' => '_price',
						'value' => array_map('intval', $price),
						'type' => 'NUMERIC',
						'compare' => 'BETWEEN'
					);
				} else {
					if (empty($price[0])) {
						$meta_queries[] = array(
							'key' => '_price',
							'value' => (int)$price[1],
							'type' => 'NUMERIC',
							'compare' => '<='
						);
					} else {
						$meta_queries[] = array(
							'key' => '_price',
							'value' => (int)$price[0],
							'type' => 'NUMERIC',
							'compare' => '>='
						);
					}
				}
			}
		}

		if (isset($_GET['price_range']) && 'none' != $_GET['price_range']) {
			$price_range = sanitize_text_field( $_GET['price_range'] );
			$meta_queries[] = array(
				'key' => '_price_range',
				'value' => $price_range,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['website'])) {
			$website = sanitize_text_field( $_GET['website'] );
			$meta_queries[] = array(
				'key' => '_website',
				'value' => $website,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['email'])) {
			$email = sanitize_text_field( $_GET['email'] );
			$meta_queries[] = array(
				'key' => '_email',
				'value' => $email,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['phone'])) {
			$phone = sanitize_text_field( $_GET['phone'] );
			$meta_queries[] = array(
				'relation' => 'OR',
				array(
					'key' => '_phone2',
					'value' => $phone,
					'compare' => 'LIKE'
				),
				array(
					'key' => '_phone',
					'value' => $phone,
					'compare' => 'LIKE'
				)
			);
		}

		if (isset($_GET['fax'])) {
			$fax = sanitize_text_field( $_GET['fax'] );
			$meta_queries[] = array(
				'key' => '_fax',
				'value' => $fax,
				'compare' => 'LIKE'
			);
		}

		if (!empty($_GET['miles']) && $_GET['miles'] > 0 && !empty($_GET['cityLat']) && !empty($_GET['cityLng'])) {
			$args['atbdp_geo_query'] = array(
				'lat_field' => '_manual_lat',
				'lng_field' => '_manual_lng',
				'latitude' => sanitize_text_field( $_GET['cityLat'] ),
				'longitude' => sanitize_text_field( $_GET['cityLng'] ),
				'distance' => sanitize_text_field( $_GET['miles'] ),
				'units' => $this->radius_search_unit()
			);
		}
		elseif ( ! empty($_GET['address']) ) {
			$address = sanitize_text_field( $_GET['address'] );
			$meta_queries[] = array(
				'key' => '_address',
				'value' => $address,
				'compare' => 'LIKE'
			);
		}

		if (!empty($_GET['zip'])) {
			$zip = sanitize_text_field( $_GET['zip'] );
			$meta_queries[] = array(
				'key' => '_zip',
				'value' => $zip,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['search_by_rating'])) {
			$q_rating = sanitize_text_field( $_GET['search_by_rating'] );
			$listings_ids = ATBDP_Listings_Data_Store::get_listings_ids();
			$rated = array();
			if ( ! empty( $listings_ids ) ) {
				foreach ( $listings_ids as $listings_id ) {
					$average = ATBDP()->review->get_average( $listings_id );
					if ($q_rating === '5') {
						if (($average == '5')) {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '4') {
						if ($average >= '4') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '3') {
						if ($average >= '3') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '2') {
						if ($average >= '2') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '1') {
						if ($average >= '1') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ('' === $q_rating) {
						if ($average === '') {
							$rated[] = $listings_id;
						}
					}
				}
				$rating_id = array(
					'post__in' => !empty($rated) ? $rated : array()
				);
				$args = array_merge($args, $rating_id);
			}
		}

		$meta_queries = apply_filters('atbdp_search_listings_meta_queries', $meta_queries);
		$count_meta_queries = count($meta_queries);
		if ($count_meta_queries) {
			$args['meta_query'] = array_merge(array('relation' => 'AND'), $meta_queries);
		}

		return apply_filters( 'atbdp_listing_search_query_argument', $args );
	}

	private function execute_meta_query_args(&$args, &$meta_queries) {
		if ( 'rand' == $this->query_orderby() ) {
			$current_order = atbdp_get_listings_current_order( $this->query_orderby() );
		}
		else {
			$current_order = atbdp_get_listings_current_order( $this->query_orderby() . '-' . $this->query_order() );
		}

		$meta_queries['directory_type'] = array(
				'key'     => '_directory_type',
				'value'   => $this->current_directory_type_id(),
				'compare' => '=',
			);

		$meta_queries['expired'] = array(
			'key'     => '_listing_status',
			'value'   => 'expired',
			'compare' => '!=',
		);

		if ( $this->monetize_by_featued_enabled() ) {
			$meta_queries['_featured'] = array(
				'key'     => '_featured',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);
		}

		if ( 'yes' == $this->display_only_featured() ) {
			$meta_queries['_featured'] = array(
				'key'     => '_featured',
				'value'   => 1,
				'compare' => '=',
			);
		}

		$listings_ids = ATBDP_Listings_Data_Store::get_listings_ids();
		$rated        = array();

		if (  ( 'yes' == $this->display_only_popular() ) || ( 'views-desc' === $current_order ) ) {
			if ( $this->monetize_by_featued_enabled() ) {
				if ( 'average_rating' === $this->popular_by() ) {
					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular() <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
				elseif ( 'view_count' === $this->popular_by() ) {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular(),
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);
				}
				else {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular(),
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);

					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular() <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
			}
			else {
				if ( 'average_rating' === $this->popular_by() ) {
					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average    = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular() <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
				elseif ( 'view_count' === $this->popular_by() ) {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular(),
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'views' => 'DESC',
					);
				}
				else {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => (int)$this->view_to_popular(),
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'views' => 'DESC',
					);

					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average    = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular() <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
			}
		}

		switch ( $current_order ) {
			case 'title-asc':
			if ( $this->monetize_by_featued_enabled() ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'title'          => 'ASC',
				);
			}
			else {
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
			}
			break;

			case 'title-desc':
			if ( $this->monetize_by_featued_enabled() ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'title'          => 'DESC',
				);
			}
			else {
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
			}
			break;

			case 'date-asc':
			if ( $this->monetize_by_featued_enabled() ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'date'           => 'ASC',
				);
			}
			else {
				$args['orderby'] = 'date';
				$args['order']   = 'ASC';
			}
			break;

			case 'date-desc':
			if ( $this->monetize_by_featued_enabled() ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'date'           => 'DESC',
				);
			}
			else {
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
			}
			break;

			case 'price-asc':
			if ( $this->monetize_by_featued_enabled() ) {
				$meta_queries['price'] = array(
					'key'     => '_price',
					'type'    => 'NUMERIC',
					'compare' => 'EXISTS',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'price'     => 'ASC',
				);
			}
			else {
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'ASC';
			}
			break;

			case 'price-desc':
			if ( $this->monetize_by_featued_enabled() ) {
				$meta_queries['price'] = array(
					'key'     => '_price',
					'type'    => 'NUMERIC',
					'compare' => 'EXISTS',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'price'     => 'DESC',
				);
			}
			else {
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
			}
			break;

			case 'rand':
			if ( $this->monetize_by_featued_enabled() ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = 'meta_value_num rand';
			}
			else {
				$args['orderby'] = 'rand';
			}
			break;
		}
	}
}