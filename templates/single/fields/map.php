<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-single-map" data-map="<?php echo esc_attr( $listing->map_data() ); ?>"></div>
<div class="directorist-single-map__location">
    <?php if( ! empty( $display_address_map ) && ! empty( $address) ) : ?>

        <div class="directorist-single-map__address"><?php echo directorist_icon('fas fa-map-marker-alt') ?> <?php echo $address ?></div>

    <?php endif; ?>

    <?php if( ! empty( $display_direction_map ) && ! empty( $manual_lat ) && ! empty( $manual_lng ) ) : ?>
        <div class='directorist-single-map__direction'>
            <a href='http://www.google.com/maps?daddr=<?php echo esc_attr( $manual_lat ); ?>, <?php echo esc_attr( $manual_lng ); ?>' target='_blank'><?php echo directorist_icon('fas fa-paper-plane')?> <?php esc_html_e('Get Directions', 'directorist'); ?></a>
        </div>
    <?php endif; ?>
</div>
