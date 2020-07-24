<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Directorist_Listing_Taxonomy {

	public $atts;
	public $type;
	public $tax;

	public $view;
	public $orderby;
	public $order;
	public $per_page;
	public $columns;
	public $slug;
	public $logged_in_user_only;
	public $redirect_page_url;

	public $show_count;
	public $hide_empty;
	public $depth;
	public $terms;

	public function __construct( $atts = array(), $type = 'category' ) {
		$atts = shortcode_atts(array(
			'view'                => get_directorist_option('display_categories_as', 'grid'),
			'orderby'             => get_directorist_option('order_category_by', 'id'),
			'order'               => get_directorist_option('sort_category_by', 'asc'),
			'cat_per_page'        => 100,
			'loc_per_page'        => 100,
			'columns'             => get_directorist_option('categories_column_number', 3),
			'slug'                => '',
			'logged_in_user_only' => '',
			'redirect_page_url'   => ''
		), $atts);

		$this->atts                = $atts;
		$this->type                = $type;
		$this->tax                 = ($type == 'category') ? ATBDP_CATEGORY : ATBDP_LOCATION;

		$this->view                = $atts['view'];
		$this->orderby             = $atts['orderby'];
		$this->order               = $atts['order'];
		$this->per_page            = ($type == 'category') ? $atts['cat_per_page'] : $atts['loc_per_page'];
		$this->columns             = $atts['columns'];
		$this->slug                = $atts['slug'];
		$this->logged_in_user_only = $atts['logged_in_user_only'];
		$this->redirect_page_url   = $atts['redirect_page_url'];

		$this->show_count = !empty( get_directorist_option('display_listing_count', 1 ) ) ? true : false;
		$this->hide_empty = !empty( get_directorist_option('hide_empty_categories') ) ? true : false;
		$this->depth      = ($type == 'category') ? get_directorist_option('categories_depth_number', 1) : get_directorist_option('locations_depth_number', 1);

		$this->set_terms();
	}

	public function set_terms(){
		$args = array(
			'orderby'      => $this->orderby,
			'order'        => $this->order,
			'hide_empty'   => $this->hide_empty,
			'parent'       => 0,
			'hierarchical' => $this->hide_empty,
			'slug'         => !empty($this->slug) ? explode(',', $this->slug) : '',
		);

		if ( $this->type == 'category' ) {
			$args = apply_filters('atbdp_all_categories_argument', $args);
		}
		else {
			$args = apply_filters('atbdp_all_locations_argument', $args);
		}

		$terms = get_terms($this->tax, $args);
		$terms = array_slice($terms, 0, $this->per_page);

		$this->terms = $terms;  
	}

	public function grid_count_html($term,$total) {
		$html = '';

		if ( $this->type == 'category' ) {
			if ($this->show_count) {
				$html = "<span>(" . $total . ")</span>";
			}
            /**
             * @since 5.0.0
             */
            return apply_filters('atbdp_all_categories_after_category_name', $html, $term);
        }

        else {
        	if ($this->show_count) {
        		$html = "<p>(" . $total . ")</p>";
        	}
            /**
             * @since 5.0.0
             */
            return apply_filters('atbdp_all_locations_after_location_name', $html, $term);
        }
    }

    public function list_count_html($term,$total) {
    	$html = '';
    	if ($this->show_count) {
    		$html = ' (' .  $total . ')';
    	}
    	return $html;
    }

    public function subterms_html($term){

    	if ($this->depth <= 0) {
    		return;
    	}

    	$args = array(
    		'orderby'      => $this->orderby,
    		'order'        => $this->order,
    		'hide_empty'   => $this->hide_empty,
    		'parent'       => $term->term_id,
    		'hierarchical' => false
    	);

    	$terms = get_terms($this->tax, $args);
    	$html = '';

    	if (count($terms) > 0) {

    		--$this->depth;

    		$html .= '<ul class="list-unstyled atbdp_child_category">';

    		foreach ($terms as $term) {
    			$child_category = get_term_children($term->term_id, $this->tax);
    			$plus_icon = !empty($child_category) ? '<span class="expander">+</span>' : '';
    			$count = 0;
    			if ($this->hide_empty || $this->show_count) {
    				$count = ( $this->type == 'category' ) ? atbdp_listings_count_by_category($term->term_id) : atbdp_listings_count_by_location($term->term_id);

    				if ($this->hide_empty && 0 == $count) continue;
    			}

    			$permalink = ( $this->type == 'category' ) ? ATBDP_Permalink::atbdp_get_category_page($term) : ATBDP_Permalink::atbdp_get_location_page($term);

    			$html .= '<li>';
    			$html .= '<a href=" ' . $permalink . ' ">';
    			$html .= $term->name;
    			if ($this->show_count) {
    				$html .= ' (' . $count . ')';
    			}
    			$html .= "</a>$plus_icon";
    			$html .= $this->subterms_html($term);
    			$html .= '</li>';
    		}

    		$html .= '</ul>';
    	}

    	return $html;
    }

    public function tax_data() {
    	$result = array();

    	foreach ($this->terms as $term) {
    		$count = 0;
    		if ($this->hide_empty || $this->show_count) {
    			$count = ( $this->type == 'category' ) ? atbdp_listings_count_by_category($term->term_id) : atbdp_listings_count_by_location($term->term_id);

    			if ($this->hide_empty && 0 == $count) {
    				continue;
    			}
    		}

    		$expired_listings = atbdp_get_expired_listings($this->tax, $term->term_id);
    		$number_of_expired = $expired_listings->post_count;
    		$number_of_expired = !empty($number_of_expired) ? $number_of_expired : '0';
    		$total = ($count) ? ($count - $number_of_expired) : $count;

    		$image = get_term_meta($term->term_id, 'image', true);
    		if ( $image ) {
    			$image = atbdp_get_image_source($image, apply_filters("atbdp_{$this->type}_image_size", array('350', '280')));
    			$image = !empty($image) ? $image : ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';
    		}

    		$child_terms = get_term_children($term->term_id, $this->tax);

    		$permalink = ( $this->type == 'category' ) ? ATBDP_Permalink::atbdp_get_category_page($term) : ATBDP_Permalink::atbdp_get_location_page($term);

    		$data = array(
    			'term'      => $term,
    			'has_child' => !empty($child_terms) ? true : false,
    			'name'      => $term->name,
    			'permalink' => $permalink,
    			'count'     => $total,
    			'grid_count_html' => $this->grid_count_html($term,$total),
    			'list_count_html' => $this->list_count_html($term,$total),
    			'img'        => $image,
    			'subterm_html' => ($this->view == 'list') ? $this->subterms_html($term) : '',
    		);

    		if ($this->type == 'category') {
    			$icon = get_term_meta($term->term_id, 'category_icon', true);
    			$icon_type = substr($icon, 0,2);
    			$data['has_icon']  = ('none' != $icon) ? true : false;
    			$data['icon_class'] = ('la' === $icon_type)? $icon_type.' '. $icon : 'fa '. $icon;
    		}

    		$result[] = $data;
    	}

    	return $result;
    }

    public function render_shortcode() {
    	if ( $this->logged_in_user_only && ! atbdp_logged_in_user() ) {
    		return ATBDP()->helper->guard( array('type' => 'auth') );
    	}

    	if ($this->redirect_page_url) {
    		$redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
    		return $redirect;
    	}

    	wp_enqueue_script('loc_cat_assets');

    	if ( $this->type == 'category' ) {
    		$args = array(
    			'taxonomy'   => $this,
    			'categories' => $this->tax_data(),
    			'grid_container' => apply_filters('atbdp_cat_container_fluid', 'container-fluid'),
    			'grid_col_class' => $this->columns == 5 ? 'atbdp_col-5' : 'col-md-' . floor(12 / $this->columns). ' col-sm-6',
    			'list_col_class' => 'col-md-' . floor(12 / $this->columns),
    		);
    		$template_file = 'taxonomies/categories-'. $this->view;
    	}

    	else {
    		$args = array(
    			'taxonomy'   => $this,
    			'locations' => $this->tax_data(),
    			'grid_col_class' => $this->columns == 5 ? 'atbdp_col-5' : 'col-md-' . floor(12 / $this->columns). ' col-sm-6',
    			'list_col_class' => 'col-md-' . floor(12 / $this->columns),
    		);
    		$template_file = 'taxonomies/locations-'. $this->view;
    	}

    	if ( !empty( $this->terms ) && !is_wp_error( $this->terms ) ) {
    		return atbdp_return_shortcode_template( $template_file, $args );
    	}
    	else {
    		return __('<p>No Results found!</p>', 'directorist');
    	}
    }
}