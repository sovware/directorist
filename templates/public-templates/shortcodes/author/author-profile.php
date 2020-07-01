<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="<?php echo esc_attr($container_fluid); ?>">
        <?php
        /**
         * @since 7.0
         * @hooked Directorist_Listing_Author > header_template - 10
         * @hooked Directorist_Listing_Author > about_template - 15
         * @hooked Directorist_Listing_Author > author_listings_template - 20
         */
        do_action( 'directorist_author_profile_content' );
        ?>
    </div>
</div>
