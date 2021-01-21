<?php
/**
 * This template displays the administration form of the widget.
 */
?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Advance Search', 'directorist' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>

<p>
    <input <?php checked( $instance['search_by_text_field'] ); ?> id="<?php echo $this->get_field_id( 'search_by_text_field' ); ?>" name="<?php echo $this->get_field_name( 'search_by_text_field' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_text_field' ); ?>"><?php _e( 'Search by Text Field', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_location'] ); ?> id="<?php echo $this->get_field_id( 'search_by_location' ); ?>" name="<?php echo $this->get_field_name( 'search_by_location' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_location' ); ?>"><?php _e( 'Search by Location', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_category'] ); ?> id="<?php echo $this->get_field_id( 'search_by_category' ); ?>" name="<?php echo $this->get_field_name( 'search_by_category' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_category' ); ?>"><?php _e( 'Search by Category', 'directorist' ); ?></label>
</p>


<p>
    <input <?php checked( $instance['search_by_custom_fields'] ); ?> id="<?php echo $this->get_field_id( 'search_by_custom_fields' ); ?>" name="<?php echo $this->get_field_name( 'search_by_custom_fields' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_custom_fields' ); ?>"><?php _e( 'Search by Custom Fields', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_price'] ); ?> id="<?php echo $this->get_field_id( 'search_by_price' ); ?>" name="<?php echo $this->get_field_name( 'search_by_price' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_price' ); ?>"><?php _e( 'Search by Price', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_price_range'] ); ?> id="<?php echo $this->get_field_id( 'search_by_price_range' ); ?>" name="<?php echo $this->get_field_name( 'search_by_price_range' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_price_range' ); ?>"><?php _e( 'Search by Price Range', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_open_now'] ); ?> id="<?php echo $this->get_field_id( 'search_by_open_now' ); ?>" name="<?php echo $this->get_field_name( 'search_by_open_now' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_open_now' ); ?>"><?php _e( 'Search by Open Now (It requires Business Hour extension)', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_website'] ); ?> id="<?php echo $this->get_field_id( 'search_by_website' ); ?>" name="<?php echo $this->get_field_name( 'search_by_website' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_website' ); ?>"><?php _e( 'Search by Website', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_email'] ); ?> id="<?php echo $this->get_field_id( 'search_by_email' ); ?>" name="<?php echo $this->get_field_name( 'search_by_email' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_email' ); ?>"><?php _e( 'Search by Email', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_phone'] ); ?> id="<?php echo $this->get_field_id( 'search_by_phone' ); ?>" name="<?php echo $this->get_field_name( 'search_by_phone' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_phone' ); ?>"><?php _e( 'Search by Phone Number', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_zip_code'] ); ?> id="<?php echo $this->get_field_id( 'search_by_zip_code' ); ?>" name="<?php echo $this->get_field_name( 'search_by_zip_code' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_zip_code' ); ?>"><?php _e( 'Search by Zip/Post Code', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_tag'] ); ?> id="<?php echo $this->get_field_id( 'search_by_tag' ); ?>" name="<?php echo $this->get_field_name( 'search_by_tag' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_tag' ); ?>"><?php _e( 'Search by Tag', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_review'] ); ?> id="<?php echo $this->get_field_id( 'search_by_review' ); ?>" name="<?php echo $this->get_field_name( 'search_by_review' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_review' ); ?>"><?php _e( 'Search by Review', 'directorist' ); ?></label>
</p>

<p>
    <input <?php checked( $instance['search_by_radius'] ); ?> id="<?php echo $this->get_field_id( 'search_by_radius' ); ?>" name="<?php echo $this->get_field_name( 'search_by_radius' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'search_by_radius' ); ?>"><?php _e( 'Search by Radius', 'directorist' ); ?></label>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'location_source' ); ?>"><?php _e( 'Location Source for Search', 'directorist' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'location_source' ); ?>" name="<?php echo $this->get_field_name( 'location_source' ); ?>">
        <option value="map_api" <?php selected( $instance['location_source'], 'map_api' ); ?>><?php _e( 'Display from Map Api', 'directorist' ); ?></option>
        <option value="listing_location" <?php selected( $instance['location_source'], 'listing_location' ); ?>><?php _e( 'Display from Listing Location', 'directorist' ); ?></option>
    </select>
</p>