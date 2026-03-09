<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<<?php echo tag_escape( $args['before'] ? $args['before'] : 'li' ); ?> class="directorist-listing-card-posted-on">
    <?php directorist_icon( $icon );?>
    <span>
        <?php echo esc_html( $listings->loop_get_published_date( $data ) );?>
    </span>
</<?php echo tag_escape( $args['after'] ? $args['after'] : 'li' ); ?>>