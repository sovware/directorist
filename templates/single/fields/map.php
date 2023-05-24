<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div>
    <div class="directorist-single-map" data-map="<?php echo esc_attr( $listing->map_data() ); ?>"></div>

    <?php if( ! empty( $display_address_map ) && ! empty( $address) ) : ?>

        <address><?php echo $address ?></address>

    <?php endif; ?>

    <?php if( ! empty( $display_direction_map ) && ! empty( $manual_lat ) && ! empty( $manual_lng ) ) : ?>
        <div class='map_get_dir'>
            <a href='http://www.google.com/maps?daddr=<?php echo esc_attr( $manual_lat ); ?>, <?php echo esc_attr( $manual_lng ); ?>' target='_blank'><?php esc_html_e('Get Directions', 'directorist'); ?></a>
        </div><span class='iw-close-btn'><?php directorist_icon( 'las la-times' ); ?></span></div></div>
    <?php endif; ?>
</div>
