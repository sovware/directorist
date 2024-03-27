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

<div class="atbdp">
    <iframe class="embed-responsive-item" src="<?php echo esc_url( $videourl ); ?>" allowfullscreen></iframe>
</div>

