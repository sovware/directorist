<?php
/**
 * This template displays the administration form of the widget.
 */
?>

<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Advance Search', ATBDP_TEXTDOMAIN ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>

<p>
    <input <?php checked( $instance['search_by_text_field'] ); ?> id="<?php echo $this->get_field_id( 'search_by_text_field' ); ?>" name="<?php echo $this->get_field_name( 'search_by_text_field' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_text_field' ); ?>"><?php _e( 'Search by Text Field', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_category'] ); ?> id="<?php echo $this->get_field_id( 'search_by_category' ); ?>" name="<?php echo $this->get_field_name( 'search_by_category' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_category' ); ?>"><?php _e( 'Search by Category', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_location'] ); ?> id="<?php echo $this->get_field_id( 'search_by_location' ); ?>" name="<?php echo $this->get_field_name( 'search_by_location' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_location' ); ?>"><?php _e( 'Search by Location', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_custom_fields'] ); ?> id="<?php echo $this->get_field_id( 'search_by_custom_fields' ); ?>" name="<?php echo $this->get_field_name( 'search_by_custom_fields' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_custom_fields' ); ?>"><?php _e( 'Search by Custom Fields', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_price'] ); ?> id="<?php echo $this->get_field_id( 'search_by_price' ); ?>" name="<?php echo $this->get_field_name( 'search_by_price' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_price' ); ?>"><?php _e( 'Search by Price', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_review'] ); ?> id="<?php echo $this->get_field_id( 'search_by_review' ); ?>" name="<?php echo $this->get_field_name( 'search_by_review' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_review' ); ?>"><?php _e( 'Search by Review', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_website'] ); ?> id="<?php echo $this->get_field_id( 'search_by_website' ); ?>" name="<?php echo $this->get_field_name( 'search_by_website' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_website' ); ?>"><?php _e( 'Search by Website', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_email'] ); ?> id="<?php echo $this->get_field_id( 'search_by_email' ); ?>" name="<?php echo $this->get_field_name( 'search_by_email' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_email' ); ?>"><?php _e( 'Search by Email', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_phone'] ); ?> id="<?php echo $this->get_field_id( 'search_by_phone' ); ?>" name="<?php echo $this->get_field_name( 'search_by_phone' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_phone' ); ?>"><?php _e( 'Search by Phone Number', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_address'] ); ?> id="<?php echo $this->get_field_id( 'search_by_address' ); ?>" name="<?php echo $this->get_field_name( 'search_by_address' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_address' ); ?>"><?php _e( 'Search by Address', ATBDP_TEXTDOMAIN ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_zip_code'] ); ?> id="<?php echo $this->get_field_id( 'search_by_zip_code' ); ?>" name="<?php echo $this->get_field_name( 'search_by_zip_code' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_zip_code' ); ?>"><?php _e( 'Search by Zip/Post Code', ATBDP_TEXTDOMAIN ); ?></label>
</p>
