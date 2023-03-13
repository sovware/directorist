<?php
/**
 * @author wpWax
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ATBDP_Metabox {

	/**
	 * Add meta boxes for ATBDP_POST_TYPE and ATBDP_SHORT_CODE_POST_TYPE
	 * and Save the meta data
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action('add_meta_boxes_'.ATBDP_POST_TYPE, array($this, 'listing_metabox'));
			add_action('transition_post_status',	array($this, 'publish_atbdp_listings'), 10, 3);
			add_action( 'edit_post', array($this, 'save_post_meta'), 10, 2);
			add_action('post_submitbox_misc_actions', array($this, 'post_submitbox_meta'));
			// load dynamic fields
			add_action( 'wp_ajax_atbdp_dynamic_admin_listing_form', array( $this, 'atbdp_dynamic_admin_listing_form' ) );
		}
	}

	public function atbdp_dynamic_admin_listing_form() {

		if ( ! directorist_verify_nonce() ) {
			wp_send_json( array(
				'error' => esc_html__( 'Invalid nonce!', 'directorist' ),
			) );
		}

		$term_id 		= ! empty( $_POST['directory_type'] ) ? (int) directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : '';
		$listing_id    	= ! empty( $_POST['listing_id'] ) ? directorist_clean( wp_unslash( $_POST['listing_id'] ) ) : '';

		// listing meta fields
		ob_start();
		$this->render_listing_meta_fields( $term_id, $listing_id );
		$listing_meta_fields =  ob_get_clean();

		ob_start();
		$this->render_listing_taxonomies( $listing_id, $term_id, ATBDP_CATEGORY );
		$listing_categories =  ob_get_clean();

		ob_start();
		$this->render_listing_taxonomies( $listing_id, $term_id, ATBDP_LOCATION );
		$listing_locations =  ob_get_clean();

		ob_start();
		$this->render_listing_pop_taxonomies( $listing_id, $term_id, ATBDP_CATEGORY );
		$listing_pop_categories =  ob_get_clean();

		ob_start();
		$this->render_listing_pop_taxonomies( $listing_id, $term_id, ATBDP_LOCATION );
		$listing_pop_locations =  ob_get_clean();

		ob_start();
		$this->render_expire_date( $listing_id, $term_id );
		$listing_expiration = ob_get_clean();


		$required_script_src = [];

		$map_type = get_directorist_option('select_listing_map', 'openstreet');
		$script_name = ( 'openstreet' === $map_type ) ? 'openstreet-map' : 'google-map';

		$is_enabled_script_debugging = get_directorist_option( 'script_debugging', false, true );
		$ext = $is_enabled_script_debugging ? '.js' : '.min.js';
		$required_script_src[ 'map-custom-script' ] = DIRECTORIST_JS . $script_name . $ext;

		wp_send_json_success( array(
			'listing_meta_fields' 		=> $listing_meta_fields,
			'listing_categories'  		=> $listing_categories,
			'listing_pop_categories'  	=> $listing_pop_categories,
			'listing_locations'   		=> $listing_locations,
			'listing_pop_locations'   	=> $listing_pop_locations,
			'required_js_scripts' 		=> $required_script_src,
			'listing_expiration'		=> $listing_expiration
		) );

	}

	public function render_listing_taxonomies( $listing_id, $term_id, $taxonomy_id, $parent_id = 0 ) {
		$args = array(
			'hide_empty'   => 0,
			'hierarchical' => true,
			'parent'       => $parent_id
		);
		$saving_terms  = get_the_terms( $listing_id, $taxonomy_id );
		$saving_values = array();
		if( $saving_terms ) {
			foreach( $saving_terms as $saving_term ) {
				$saving_values[] = $saving_term->term_id;
			}
		}
		$terms = get_terms( $taxonomy_id, $args);
	
		if( $terms ) {
			foreach( $terms as $term ) {
				$directory_type = get_term_meta( $term->term_id, '_directory_type', true );
				$directory_type = ! empty ( $directory_type ) ? $directory_type : array();
				$checked        = in_array( $term->term_id, $saving_values, true ) ? 'checked' : '';
				if( in_array( $term_id, $directory_type, true ) ) { ?>
					<li id="<?php echo esc_attr( $taxonomy_id ); ?>-<?php echo esc_attr( $term->term_id ); ?>">
						<label class="selectit">
							<input value="<?php echo esc_attr( $term->term_id ); ?>" type="checkbox" name="tax_input[<?php echo esc_attr( $taxonomy_id ); ?>][]" id="in-<?php echo esc_attr( $taxonomy_id ); ?>-<?php echo esc_attr( $term->term_id ); ?>" <?php echo ! empty( $checked ) ? esc_attr( $checked ) : ''; ?>>
							<?php echo esc_html( $term->name ); ?>
						</label>
						<?php
						$child_terms = get_term_children( $term->term_id, $taxonomy_id );
						if ( $child_terms ) {
							echo '<ul>';
								$this->render_listing_taxonomies( $listing_id, (int) $term_id, $taxonomy_id, $term->term_id );
							echo '</ul>';
						}
						?>
					</li>
				<?php
				}
			}
		}
	}

	public function render_listing_pop_taxonomies( $listing_id, $term_id, $taxonomy_id ) {
		$args = array(
			'hide_empty' => 0,
			'hierarchical' => false
		);
		$saving_terms   = get_the_terms( $listing_id, $taxonomy_id );
		$saving_values    = array();
		if( $saving_terms ) {
			foreach( $saving_terms as $saving_term ) {
				$saving_values[] = $saving_term->term_id;
			}
		}
		$terms = get_terms( $taxonomy_id, $args);

		if( $terms ) {
			foreach( $terms as $term ) {
				$directory_type = get_term_meta( $term->term_id, '_directory_type', true );
				$directory_type = ! empty ( $directory_type ) ? $directory_type : array();
				$checked		= in_array( $term->term_id, $saving_values ) ? 'checked' : '';
				if( in_array( $term_id, $directory_type) ) { ?>
					<li id="popular-<?php echo esc_attr( $taxonomy_id ); ?>-<?php echo esc_attr( $term->term_id ); ?>" class="popular-category"><label class="selectit"><input value="<?php echo esc_attr( $term->term_id ); ?>" type="checkbox" id="in-popular-<?php echo esc_attr( $taxonomy_id ); ?>-<?php echo esc_attr( $term->term_id ); ?>" <?php echo ! empty( $checked ) ? esc_attr( $checked ) : ''; ?>> <?php echo esc_html( $term->name ); ?></label></li>

				<?php
				}
			}
		}
	}

	public function listing_metabox( $post ) {
		add_meta_box('listing_form_info', __('Listing Information', 'directorist'), array($this, 'listing_form_info_meta'), ATBDP_POST_TYPE, 'normal', 'high');
	}

	public function render_listing_meta_fields( $type, $id ) {
		$form_data = $this->build_form_data( $type );
		foreach ( $form_data as $section ) {
			\Directorist\Directorist_Listing_Form::instance($id)->section_template( $section );
		}
	}

	public function render_expire_date( $listing_id, $term_id )
	{
		// show expiration date and featured listing.
		$directory_type         = isset( $term_id ) ? $term_id : default_directory_type();
		$expiration				= get_term_meta( $directory_type, 'default_expiration', true );
		$expire_in_days         = ! empty( $expiration ) ? $expiration : '90';
		$f_active               = get_directorist_option('enable_featured_listing');
		$never_expire           = get_post_meta( $listing_id, '_never_expire', true );
		$never_expire           = !empty( $never_expire ) ? (int) $never_expire : '';

		$e_d                    = get_post_meta( $listing_id, '_expiry_date', true );
		$e_d                    = !empty( $e_d ) ? $e_d : calc_listing_expiry_date( '', $expire_in_days, $directory_type );
		$expiry_date            = atbdp_parse_mysql_date( $e_d );

		$featured               = get_post_meta( $listing_id, '_featured', true);
		$listing_type           = get_post_meta( $listing_id, '_listing_type', true);
		$listing_status         = get_post_meta( $listing_id, '_listing_status', true);
		$default_expire_in_days = !empty( $default_expire_in_days ) ? $default_expire_in_days : '';
		// load the meta fields
		$data = compact('f_active', 'never_expire', 'expiry_date', 'featured', 'listing_type', 'listing_status', 'default_expire_in_days');

		if( empty($never_expire) && isset( $expiry_date ) ) : ?>
				<span id="atbdp-timestamp">
					<strong><?php esc_html_e( "Expiration", 'directorist' ); ?></strong>
					<?php esc_html_e( "Date & Time", 'directorist' ); ?>
				</span>
				<div id="atbdp-timestamp-wrap" class="atbdp-timestamp-wrap">
					<label>
						<select id="atbdp-mm" name="exp_date[mm]">
							<?php
							$months = atbdp_get_months();// get an array of translatable month names
							foreach( $months as $key => $month_name ) {
								$key += 1;
								printf( '<option value="%1$d" %2$s>%1$d-%3$s</option>', esc_attr( $key ), esc_attr( selected( $key, (int) $expiry_date['month'] ) ), esc_html( $month_name ) );
							}
							?>
						</select>
					</label>
					<label>
						<input type="text" id="atbdp-jj" placeholder="day" name="exp_date[jj]" value="<?php echo esc_attr( $expiry_date['day'] ); ?>" size="2" maxlength="2" />
					</label>,
					<label>
						<input type="text" id="atbdp-aa" placeholder="year" name="exp_date[aa]" value="<?php echo esc_attr( $expiry_date['year'] ); ?>" size="4" maxlength="4" />
					</label>@
					<label>
						<input type="text" id="atbdp-hh" placeholder="hour" name="exp_date[hh]" value="<?php echo esc_attr( $expiry_date['hour'] ); ?>" size="2" maxlength="2" />
					</label> :
					<label>
						<input type="text" id="atbdp-mn" placeholder="min" name="exp_date[mn]" value="<?php echo esc_attr( $expiry_date['min'] ); ?>" size="2" maxlength="2" />
					</label>
				</div>
		<?php endif;

	}

	public function listing_form_info_meta( $post ) {
		wp_enqueue_script( 'atbdp-google-map-front' );
        wp_enqueue_script( 'atbdp-markerclusterer' );
		$all_types     	= directory_types();
		$default     	= default_directory_type();
		$current_type   =  get_post_meta( $post->ID, '_directory_type', true );
		$value 			= $current_type ? $current_type : $default;
		wp_nonce_field( 'listing_info_action', 'listing_info_nonce' );
		$multi_directory = get_directorist_option( 'enable_multi_directory', false );


		$show_directory_type_nav = ! empty ( $multi_directory ) && ( count( $all_types ) > 1 );
		$show_directory_type_nav = apply_filters( 'directorist_show_admin_edit_listing_directory_type_nav', $show_directory_type_nav, $post->ID );

		if ( $show_directory_type_nav ) { ?>

		<label><?php esc_html_e( 'Listing Type', 'directorist' ); ?></label>
		<select name="directory_type">
			<option value=""><?php esc_attr_e( 'Select Listing Type', 'directorist' ); ?></option>
			<?php foreach ( $all_types as $type ):
				?>
				<option value="<?php echo esc_attr( $type->term_id ); ?>" <?php echo selected( $type->term_id, $value ); ; ?> ><?php echo esc_attr( $type->name ); ?></option>
			<?php endforeach;
			?>
		</select>
		<?php } else {?>
			<input type="hidden" name="directory_type" value="<?php echo esc_attr( $default ); ?>">
		<?php } ?>

		<div class="form-group atbd_content_module atbdp_category_custom_fields-wrapper diectorist-hide">
			<div class="atbdb_content_module_contents">
				<div class="form-group atbdp_category_custom_fields"></div>
			</div>
		</div>

		<div id="directiost-listing-fields_wrapper" data-id="<?php echo esc_attr( $post->ID )?>"><?php $this->render_listing_meta_fields( $value, $post->ID ); ?></div>
		<?php
	}

	public function build_form_data( $type ) {
		$form_data              = array();
		$submission_form_fields = get_term_meta( $type, 'submission_form_fields', true );
		$excluded_fields = array( 'title', 'description', 'location', 'category', 'tag', 'privacy_policy', 'terms_conditions' );

		if ( !empty( $submission_form_fields['groups'] ) ) {
			foreach ( $submission_form_fields['groups'] as $group ) {

				$section           = $group;
				$section['fields'] = array();

				foreach ( $group['fields'] as $field ) {

					if ( in_array( $field, $excluded_fields ) ) {
						continue;
					}

					$section['fields'][ $field ] = $submission_form_fields['fields'][ $field ];
				}

				$form_data[] = $section;
			}
		}

		return $form_data;
	}

	/**
	 * @since 5.4.0
	 */
	public function publish_atbdp_listings( $new_status, $old_status, $post ){
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? directorist_clean( wp_unslash( $_REQUEST['_wpnonce'] ) ) : null;
		if ( ($post->post_type == 'at_biz_dir') && ( $old_status == 'pending'  &&  $new_status == 'publish' ) && !wp_verify_nonce( $nonce, 'quick-publish-action' ) ){
			do_action('atbdp_listing_published', $post->ID);//for sending email notification
		}
	}

	/**
	 * It outputs expiration date and featured checkbox custom field on the submit box metabox.
	 * @param WP_Post $post
	 */
	public function post_submitbox_meta($post)
	{

		if(ATBDP_POST_TYPE !=$post->post_type) return; // vail if it is not our post type
		// show expiration date and featured listing.
		$directory_type         = default_directory_type();
		$expiration				= get_term_meta( $directory_type, 'default_expiration', true );
		$expire_in_days         = ! empty( $expiration ) ? $expiration : '90';
		$f_active               = get_directorist_option('enable_featured_listing');
		$never_expire           = get_post_meta($post->ID, '_never_expire', true);
		$never_expire           = !empty($never_expire) ? (int) $never_expire : '';

		$e_d                    = get_post_meta($post->ID, '_expiry_date', true);
		$e_d                    = !empty($e_d) ? $e_d : calc_listing_expiry_date( '', $expire_in_days );
		$expiry_date            = atbdp_parse_mysql_date($e_d);

		$featured               = get_post_meta($post->ID, '_featured', true);
		$listing_type           = get_post_meta($post->ID, '_listing_type', true);
		$listing_status         = get_post_meta($post->ID, '_listing_status', true);
		$default_expire_in_days = !empty($default_expire_in_days) ? $default_expire_in_days : '';
		// load the meta fields
		$data = compact('f_active', 'never_expire', 'expiry_date', 'featured', 'listing_type', 'listing_status', 'default_expire_in_days');

		if( apply_filters( 'directorist_before_featured_expire_metabox', true, $post ) ){
			ATBDP()->load_template('admin-templates/listing-form/expiration-featured-fields', array('data'=> $data));
		}
	}

	/**
	 * Save Meta Data of ATBDP_POST_TYPE
	 * @param int       $post_id    Post ID of the current post being saved
	 * @param object    $post       Current post object being saved
	 */
	public function save_post_meta( $post_id, $post ) {

		$nonce = !empty($_POST['listing_info_nonce']) ? directorist_clean( wp_unslash($_POST['listing_info_nonce'] ) ) : '';

		if( ! is_admin() ){
			return;
		}

		if( ! wp_verify_nonce( $nonce, 'listing_info_action' ) ) {
			return;
		}

		if ( ( ATBDP_POST_TYPE !== $post->post_type ) || wp_is_post_autosave( $post ) || wp_is_post_revision( $post ) || ! current_user_can( 'edit_'.ATBDP_POST_TYPE, $post_id ) ) {
			return;
		}

		$listing_type = !empty( $_POST['directory_type'] ) ? directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : '';
		$listing_categories = !empty( $_POST['tax_input']['at_biz_dir-category'] ) ?  directorist_clean( wp_unslash( $_POST['tax_input']['at_biz_dir-category'] ) ) : array();
		$listing_locations = !empty( $_POST['tax_input']['at_biz_dir-location'] ) ?  directorist_clean( wp_unslash( $_POST['tax_input']['at_biz_dir-location'] ) ) : array();
		$submission_form_fields = [];
		$metas = [];
		if( $listing_type ){
			$term = get_term_by( is_numeric( $listing_type ) ? 'id' : 'slug', $listing_type, ATBDP_TYPE );
			$submission_form = get_term_meta( $term->term_id, 'submission_form_fields', true );
			$expiration = get_term_meta( $term->term_id, 'default_expiration', true );
			$submission_form_fields = $submission_form['fields'];
		}

		if( ( ! empty( $listing_categories ) || ! empty( $listing_locations ) ) && ! empty( $listing_type ) ) {
			foreach( $listing_categories as $category ) {
				$directory_type = get_term_meta( $category, '_directory_type', true );
				if( empty( $directory_type ) ) {
					update_term_meta( $category, '_directory_type', array( $term->term_id ) );
				}
			}

			foreach( $listing_locations as $location ) {
				$directory_type = get_term_meta( $location, '_directory_type', true );
				if( empty( $directory_type ) ) {
					update_term_meta( $location, '_directory_type', array( $term->term_id ) );
				}
			}
		}

		foreach( $submission_form_fields as $key => $value ){
			$field_type = !empty( $value['field_type'] ) ? $value['field_type'] : '';
			if( 'image_upload' === $key ) {
				$metas['_listing_img']       = !empty($_POST['listing_img'])? directorist_clean( wp_unslash( $_POST['listing_img'] ) ) : array();
				$metas['_listing_prv_img']   = !empty($_POST['listing_prv_img'])? directorist_clean( wp_unslash( $_POST['listing_prv_img'] ) ) : '';
			}
			if( 'pricing' === $key ) {
				$metas[ '_atbd_listing_pricing' ] 	= !empty( $_POST['atbd_listing_pricing'] ) ? directorist_clean( wp_unslash( $_POST['atbd_listing_pricing'] ) ) : '';
				$metas[ '_price' ] 					= !empty( $_POST['price'] ) ? directorist_clean( wp_unslash( $_POST['price'] ) ) : '';
				$metas[ '_price_range' ] 			= !empty( $_POST['price_range'] ) ? directorist_clean( wp_unslash( $_POST['price_range'] ) ) : '';
			}
			if( 'map' === $key ) {
				$metas[ '_hide_map' ]   = !empty( $_POST['hide_map'] ) ? directorist_clean( wp_unslash( $_POST['hide_map'] ) ) : '';
				$metas[ '_manual_lat' ] = !empty( $_POST['manual_lat'] ) ? directorist_clean( wp_unslash( $_POST['manual_lat'] ) ) : '';
				$metas[ '_manual_lng' ] = !empty( $_POST['manual_lng'] ) ? directorist_clean( wp_unslash( $_POST['manual_lng'] ) ) : '';
			}
			$field_key = !empty( $value['field_key'] ) ? $value['field_key'] : '';

			if( ( $field_key !== 'listing_title' ) && ( $field_key !== 'listing_content' ) && ( $field_key !== 'tax_input' ) ){
				$key = '_'. $field_key;
				$metas[ $key ] = !empty( $_POST[ $field_key ] ) ? wp_unslash( $_POST[ $field_key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

		}

		$metas['_directory_type'] = $listing_type;
		$should_update_directory_type = apply_filters( 'directorist_should_update_directory_type', ! empty( $metas['_directory_type'] ) );

		if ( $should_update_directory_type ) {
			wp_set_object_terms( $post_id, (int) $listing_type, ATBDP_TYPE );
		}

		$metas['_featured'] = !empty( $_POST['featured'] ) ? directorist_clean( wp_unslash( $_POST['featured'] ) ) : '';

	   	$expiration_to_forever		 = ! $expiration ? 1 : '';
		$metas['_never_expire']      = !empty($_POST['never_expire']) ? (int) directorist_clean( wp_unslash( $_POST['never_expire'] ) ) : $expiration_to_forever;
		$exp_dt 					 = !empty($_POST['exp_date']) ? directorist_clean( wp_unslash( $_POST['exp_date'] ) ) : array(); // get expiry date from the $_POST and then later sanitize it.
		//prepare expiry date, if we receive complete expire date from the submitted post, then use it, else use the default data
		if (!is_empty_v($exp_dt) && !empty($exp_dt['aa'])){
			$exp_dt = array(
				'year'  => (int) $exp_dt['aa'],
				'month' => (int) $exp_dt['mm'],
				'day'   => (int) $exp_dt['jj'],
				'hour'  => (int) $exp_dt['hh'],
				'min'   => (int) $exp_dt['mn']
			);
			$exp_dt = get_date_in_mysql_format($exp_dt);
		}else{
			$exp_dt = calc_listing_expiry_date( '', $expiration, $directory_type ); // get the expiry date in mysql date format using the default expiration date.
		}

		$metas['_expiry_date']  = $exp_dt;
		$metas = apply_filters('atbdp_listing_meta_admin_submission', $metas, $_POST);
		// save the meta data to the database

		foreach ($metas as $meta_key => $meta_value) {
			update_post_meta($post_id, $meta_key, $meta_value); // array value will be serialize automatically by update post meta
		}

		if (!empty($_POST['listing_prv_img'])){
			set_post_thumbnail( $post_id, directorist_clean( wp_unslash( $_POST['listing_prv_img'] ) ) );
		}else{
			delete_post_thumbnail($post_id);
		}

		$listing_status = get_post_meta($post_id, '_listing_status', true);
		$post_status = get_post_status($post_id);
		$current_d = current_time('mysql');

		// let's check is listing need to update
		if ( empty( $listing_status ) || ('expired' === $listing_status) && ('private' === $post_status)){

			if ( ( $exp_dt > $current_d ) || !empty( $_POST['never_expire'] ) ) {
				wp_update_post( array(
					'ID'           => $post_id,
					'post_status' => $post_status, // update the status to private so that we do not run this func a second time
					'meta_input' => array(
						'_listing_status' => 'post_status',
					), // insert all meta data once to reduce update meta query
				) );
			}
		}


		if ( ! metadata_exists( 'post', $post_id, '_featured' ) ) {
			update_post_meta( $post_id, '_featured', false );
		}

		if ( ! metadata_exists( 'post', $post_id, '_listing_status' ) ) {
			update_post_meta( $post_id, '_listing_status', 'post_status' );
		}
	}
}