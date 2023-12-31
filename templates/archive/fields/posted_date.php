<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$container_element = $listings->view === 'list' ? 'div' : 'li';
?>

<<?php echo esc_html( $container_element ); ?> class="directorist-listing-card-posted-on">
    <?php directorist_icon( $icon );?>
    <span>
        <?php echo esc_html( $listings->loop_get_published_date( $data ) );?>
    </span>
</<?php echo esc_html( $container_element ); ?>>