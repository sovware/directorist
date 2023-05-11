<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$videourl   = ! empty( $videourl ) ? esc_attr( ATBDP()->atbdp_parse_videos( $videourl ) ) : '';
?>

<div class="directorist-card__body directorist-widget__video">
    <iframe class="directorist-embaded-item" src="<?php echo esc_url( $videourl ); ?>" allowfullscreen></iframe>
</div>

