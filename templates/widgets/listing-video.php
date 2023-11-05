<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
$videourl   = ! empty( $videourl ) ? Helper::parse_video( $videourl ) : '';
?>

<div class="directorist-card__body directorist-widget__video">
    <iframe class="directorist-embaded-item" src="<?php echo esc_url( $videourl ); ?>" allowfullscreen></iframe>
</div>

